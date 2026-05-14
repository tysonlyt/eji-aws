<?php

namespace GoDaddy\WordPress\MWC\Core\Features\Commerce\Catalog\Services;

use GoDaddy\WordPress\MWC\Core\Features\Commerce\Catalog\Providers\DataObjects\ProductAssociation;
use GoDaddy\WordPress\MWC\Core\Features\Commerce\Catalog\Services\Contracts\ProductsServiceContract;

/**
 * Service class to aid in listing products by ID in batches.
 *
 * @method ProductAssociation[] batchListByLocalIds(array $localIds)
 */
class BatchListProductsByLocalIdService extends AbstractBatchListResourcesByLocalIdService
{
    protected ProductsServiceContract $productsService;

    /**
     * Constructor.
     *
     * @param ProductsServiceContract $productsService
     */
    public function __construct(ProductsServiceContract $productsService)
    {
        $this->productsService = $productsService;
    }

    /**
     * {@inheritDoc}
     * @return ProductAssociation[]
     */
    protected function listBatch(array $localIds) : array
    {
        return $this->productsService->listProductsByLocalIds($localIds)->getProducts();
    }
}
