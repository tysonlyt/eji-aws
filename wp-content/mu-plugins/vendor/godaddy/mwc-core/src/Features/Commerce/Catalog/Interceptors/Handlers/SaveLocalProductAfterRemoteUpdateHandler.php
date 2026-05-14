<?php

namespace GoDaddy\WordPress\MWC\Core\Features\Commerce\Catalog\Interceptors\Handlers;

use Exception;
use GoDaddy\WordPress\MWC\Common\Exceptions\AdapterException;
use GoDaddy\WordPress\MWC\Common\Exceptions\SentryException;
use GoDaddy\WordPress\MWC\Common\Helpers\ArrayHelper;
use GoDaddy\WordPress\MWC\Common\Helpers\TypeHelper;
use GoDaddy\WordPress\MWC\Common\Register\Register;
use GoDaddy\WordPress\MWC\Core\Features\Commerce\Catalog\CatalogIntegration;
use GoDaddy\WordPress\MWC\Core\Features\Commerce\Catalog\Events\Subscribers\DispatchJobToSaveLocalProductSubscriber;
use GoDaddy\WordPress\MWC\Core\Features\Commerce\Catalog\Interceptors\SaveLocalProductAfterRemoteUpdateInterceptor;
use GoDaddy\WordPress\MWC\Core\Features\Commerce\Catalog\Operations\ReadProductOperation;
use GoDaddy\WordPress\MWC\Core\Features\Commerce\Catalog\Providers\DataObjects\ProductBase;
use GoDaddy\WordPress\MWC\Core\Features\Commerce\Catalog\Providers\DataSources\Adapters\ProductBaseAdapter;
use GoDaddy\WordPress\MWC\Core\Features\Commerce\Catalog\Services\Contracts\ProductsServiceContract;
use GoDaddy\WordPress\MWC\Core\Features\Commerce\Exceptions\GatewayRequest404Exception;
use GoDaddy\WordPress\MWC\Core\Features\Commerce\Exceptions\GatewayRequestException;
use GoDaddy\WordPress\MWC\Core\Features\Commerce\Exceptions\MissingProductLocalIdForParentException;
use GoDaddy\WordPress\MWC\Core\Features\Commerce\Exceptions\MissingProductRemoteIdException;
use GoDaddy\WordPress\MWC\Core\Features\Commerce\Exceptions\ProductMappingNotFoundException;
use GoDaddy\WordPress\MWC\Core\Interceptors\Handlers\AbstractInterceptorHandler;
use GoDaddy\WordPress\MWC\Core\WooCommerce\Adapters\ProductAdapter;
use GoDaddy\WordPress\MWC\Core\WooCommerce\Models\Products\Product;
use WC_Post_Data;
use WC_Product;

/**
 * Handler for {@see SaveLocalProductAfterRemoteUpdateInterceptor} async job callback.
 */
class SaveLocalProductAfterRemoteUpdateHandler extends AbstractInterceptorHandler
{
    protected ProductsServiceContract $productsService;
    protected ProductBaseAdapter $productBaseAdapter;

    public function __construct(ProductsServiceContract $productsService, ProductBaseAdapter $productBaseAdapter)
    {
        $this->productsService = $productsService;
        $this->productBaseAdapter = $productBaseAdapter;
    }

    /**
     * When a product is updated remotely, we re-save that product locally.
     * {@see DispatchJobToSaveLocalProductSubscriber}.
     *
     * Saving the local product when it's been updated remotely has these benefits:
     *
     * - Local caches will be purged. This means we won't be using outdated caches to serve product data; we'll get the latest
     *   changes from upstream. @link https://godaddy-corp.atlassian.net/browse/MWC-12725
     * - `woocommerce_update_product` hooks will fire when a product has been changed upstream. This creates a more expected
     *   and standard WooCommerce experience.
     *
     * @param ...$args
     * @return void
     */
    public function run(...$args)
    {
        try {
            /*
             * Gets a WC_Product instance from the supplied local product ID. This involves:
             *  - Find the corresponding remote UUID that matches the local ID.
             *  - Fetch the full product data from the platform.
             *  - Adapt that ProductBase DTO into a core Product object.
             *  - Adapt that core Product object into a WC_Product object.
             *
             * Note: we specifically want to run this through all the above adapters in order to ensure we have the
             * full set of remote data. If we just did `wc_get_product($id)->save()` and relied on our reads to take effect,
             * when fetching the product, we wouldn't get category associations saved, as we don't have hooks in place
             * to headlessly read those at this time.
             */
            $wcProduct = $this->makeWooProduct($this->getLocalId($args));

            $this->unhookDeferredProductSync();

            /*
             * Calling WC_Product::save() below triggers `jetpack_sync_save_post`.
             * Something in this action causes what we believe to be an infinite loop inside Jetpack's code.
             * Removing all callbacks from that action resolves the issue. However, we should spend some time to
             * investigate the root cause, to come up with a more thorough solution. Done in MWC-15139
             * {agibson 2024-01-08}
             */
            remove_all_actions('jetpack_sync_save_post');

            CatalogIntegration::withoutWrites(fn () => $wcProduct->save());
        } catch(Exception $e) {
            SentryException::getNewInstance($e->getMessage(), $e);
        }
    }

    /**
     * Gets the local ID from arguments passed to the handler.
     *
     * @param array<mixed> $args
     * @return int
     * @throws Exception
     */
    protected function getLocalId(array $args) : int
    {
        $localId = TypeHelper::int(ArrayHelper::get($args, 0), 0);

        if (empty($localId)) {
            throw new Exception('Missing local product ID in job arguments.');
        }

        return $localId;
    }

    /**
     * Fetches the remote {@see ProductBase} DTO from the platform.
     *
     * @param int $localProductId
     * @return ProductBase
     * @throws GatewayRequest404Exception|GatewayRequestException|MissingProductRemoteIdException|ProductMappingNotFoundException
     */
    protected function getRemoteProduct(int $localProductId) : ProductBase
    {
        return $this->productsService->readProduct(
            ReadProductOperation::getNewInstance()->setLocalId($localProductId)
        )->getProduct();
    }

    /**
     * Makes a core {@see Product} object from the supplied local product ID.
     *
     * @param int $localProductId
     * @return Product
     * @throws AdapterException|GatewayRequest404Exception|GatewayRequestException|MissingProductLocalIdForParentException|MissingProductRemoteIdException|ProductMappingNotFoundException
     */
    protected function makeCoreProduct(int $localProductId) : Product
    {
        return $this->productBaseAdapter
            ->convertFromSource($this->getRemoteProduct($localProductId))
            ->setId($localProductId); // set the local ID, as the adapter won't have set it; this ensure we end up updating the _existing_ product
    }

    /**
     * Makes an instance of {@see WC_Product} from the supplied local product ID.
     *
     * @param int $localProductId
     * @return WC_Product
     * @throws AdapterException|GatewayRequest404Exception|GatewayRequestException|MissingProductLocalIdForParentException|MissingProductRemoteIdException|ProductMappingNotFoundException|Exception
     */
    protected function makeWooProduct(int $localProductId) : WC_Product
    {
        return ProductAdapter::getNewInstance(new WC_Product())
            ->convertToSource($this->makeCoreProduct($localProductId));
    }

    /**
     * Unhooks the WooCommerce {@see WC_Post_Data::deferred_product_sync} action, because if we save a variant, WooCommerce
     * then queues up the parent product for a deferred sync. This causes the parent product to be saved locally and in
     * the platform. Saving the parent in the platform causes the variants' updatedAt values to change. This results in
     * a remote change being detected for the variants, which causes us to save them again... ultimately ending in an
     * infinite loop.
     *
     * @throws Exception
     * @see WC_Product::maybe_defer_product_sync()
     * @see WC_Post_Data::do_deferred_product_sync()
     */
    protected function unhookDeferredProductSync() : void
    {
        if (! class_exists('WC_Post_Data')) {
            return;
        }

        Register::action()
            ->setGroup('shutdown')
            ->setHandler([WC_Post_Data::class, 'do_deferred_product_sync'])
            ->setPriority(10)
            ->deregister();
    }
}
