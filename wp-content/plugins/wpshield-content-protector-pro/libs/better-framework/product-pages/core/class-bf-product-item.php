<?php
/***
 *  BetterFramework is BetterStudio framework for themes and plugins.
 *
 *  ______      _   _             ______                                           _
 *  | ___ \    | | | |            |  ___|                                         | |
 *  | |_/ / ___| |_| |_ ___ _ __  | |_ _ __ __ _ _ __ ___   _____      _____  _ __| | __
 *  | ___ \/ _ \ __| __/ _ \ '__| |  _| '__/ _` | '_ ` _ \ / _ \ \ /\ / / _ \| '__| |/ /
 *  | |_/ /  __/ |_| ||  __/ |    | | | | | (_| | | | | | |  __/\ V  V / (_) | |  |   <
 *  \____/ \___|\__|\__\___|_|    \_| |_|  \__,_|_| |_| |_|\___| \_/\_/ \___/|_|  |_|\_\
 *
 *  Copyright © 2017 Better Studio
 *
 *
 *  Our portfolio is here: https://betterstudio.com/
 *
 *  \--> BetterStudio, 2018 <--/
 */


/**
 * Class BF_Product_Item
 */
abstract class BF_Product_Item extends BF_Admin_Page {

	use BF_Product_Pages_Base;

	public $data;

	public $id;

	public function __construct( $args = [], $only_backend = true ) {

		$args = array_merge(
			$args ?? [],
			bf_get_product_item_params( self::get_config(), $this->id ),
			[
				'id'    => 'product-pages',
				'class' => 'bf-admin-panel',
			]
		);

		parent::__construct( $args, $only_backend );
	}

	abstract public function render_content( $item_data );

	protected function before_render() {
	}

	/**
	 * Retrieve the response data as array.
	 *
	 * @param $params
	 *
	 * @since 4.0.0
	 * @return array
	 */
	public function ajax_request( $params ) {

		return [];
	}


	/**
	 * Display module main content
	 */
	public function get_body() {

		ob_start();

		$this->template['id']        = $this->id;
		$this->template['css-class'] = [];

		$page_elements = apply_filters( 'better-framework/product-pages/page/' . $this->id . '/config', [] );

		echo '<div class="bs-product-item">';

		$this->before_render();

		// call render_content method of children class
		$this->render_content( $page_elements );

		$this->append_hidden_fields();
		$this->after_render();

		do_action( 'better-framework/product-pages/page/' . $this->id . '/loaded', $this->id );

		return ob_get_clean();
	}


	/**
	 * Page Title
	 *
	 * @since   2.0
	 * @return string
	 */
	protected function get_title() {

		return self::get_config()['panel-name'] ?? '';

	}


	/**
	 * Page header description
	 *
	 * @since   2.0
	 * @return string
	 */
	protected function get_desc() {

		return self::get_config()['panel-desc'] ?? '';
	}

	protected function after_render() {

	}

	/**
	 * append hidden fields for ajax request
	 */
	protected function append_hidden_fields() { ?>

		<form style="display: none;" id="bs-pages-hidden-params">

			<input type="hidden" name="active-page" id="bs-pages-current-id"
				   value="<?php echo esc_attr( $this->id ); ?>">

			<?php
			wp_nonce_field( 'bs-pages-' . $this->id, 'token', false );

			?>
			<input type="hidden" name="action" value="bs_pages_ajax">
		</form>

		<?php
	}

	protected function get_tabs(): array {

		global $plugin_page;

		$settings = $this->get_config();

		if ( ! isset( $settings['pages'] ) ) {

			return [];
		}

		foreach ( $settings['pages'] as $id => $menu ) {

			if ( empty( $menu['hide_tab'] ) ) {

				$page_slug = BF_Product_Pages::$menu_slug . "-$id";
				$active    = $page_slug === $plugin_page;

				if ( isset( $menu['type'] ) && 'tab_link' === $menu['type'] ) {
					$url = $menu['tab_link'] ?? '';
				} else {
					$url = admin_url( 'admin.php?page=' . $page_slug );
				}

				$results[ $id ] = [
					'url'     => $url,
					'active'  => $active,
					'label'   => $menu['tab']['label'] ?? $menu['name'],
					'classes' => $menu['tab']['classes'] ?? '',
					'header'  => $menu['tab']['header'] ?? '',
				];
			}
		}

		return $results ?? [];
	}


	public function is_current_admin_page(): bool {

		return $this->id && bf_is_product_page( $this->id );
	}

} // BF_Product_Pages_Base
