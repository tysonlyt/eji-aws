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
 * Class BF_Product_Demo_Factory
 *
 * Demo install & unInstaller handler
 */
class BF_Product_Demo_Installer {

	use BF_Product_Pages_Base;

	/**
	 * list of temporary IDs while insert data to database.
	 *
	 * @var array
	 */
	public $id_list = [];

	/**
	 * temporary rollback data.
	 *
	 * @var array
	 */
	public $rollback_data = [];

	/**
	 * active demo ID
	 *
	 * @var string
	 */
	public $demo_id;

	/**
	 * active demo context
	 *
	 * @see bf_get_demo_data
	 *
	 * @var string
	 */
	public $demo_context;

	/**
	 * active data type
	 *
	 * @var string
	 */
	public $active_data_type;

	/**
	 * active index step
	 *
	 * @var integer
	 */
	public $active_step_index;


	/**
	 * Pattern of ids of objects in string
	 *
	 * @var string
	 */
	public $id_pattern = '/%%(.*?)%%/';


	/**
	 * Pattern of custom functions in strings that supports params
	 *
	 * note: this should follow $id_pattern and should change it's inner value
	 *
	 * @var string
	 */
	public $function_pattern = '/%%(.*?)(:?\:.*?)%%/';


	/**
	 * store all demo data array
	 *
	 * @var array
	 */
	public $all_data = [];

	/**
	 * Store information about demo specifications.
	 *
	 * @var array
	 */
	protected $demo_info = [];


	/**
	 * Initialize
	 *
	 * @return self
	 */
	public static function Run(): self {

		global $bs_product_demo_factory;

		if ( ! $bs_product_demo_factory instanceof self ) {
			$bs_product_demo_factory = new self();
		}

		return $bs_product_demo_factory;
	}


	/**
	 * start import data to database.
	 *
	 * @param array   $import_data data to import
	 * @param string  $current_data_type
	 * @param integer $current_step
	 * @param string  $demo_id
	 * @param string  $demo_context
	 *
	 * @throws Throwable
	 * @return bool|WP_Error true on success or false on failure.
	 */
	public function import_start( $import_data, $current_data_type, $current_step, $demo_id, $page_builder, $language, $include_contents, $demo_context = 'content' ) {

		if ( ! is_array( $import_data ) ) {
			return false;
		}

		$this->set_demo_data( $demo_id, $page_builder, $language, $include_contents, $demo_context );
		$this->handle_event( 'before_import', $import_data );

		$have_data              = ! empty( $import_data );
		$custom_function_result = $this->handle_custom_function( $import_data, 'on_import' );

		if ( $have_data && empty( $import_data ) ) {
			// all of the $import_data variable was custom_function, so return custom_function result

			return (bool) $custom_function_result;
		}

		// increase memory and max_execution_time before import
		// to bypass timeout errors.
		$this->increase();

		// all import method have import_ prefix
		$callback_prefix = 'import_';
		$method          = strtolower( str_replace( '-', '_', $callback_prefix . $current_data_type ) );
		$callback        = [ $this, $method ];

		if ( ! is_callable( $callback ) || __FUNCTION__ === $method ) {
			return false;
		}

		$this->active_data_type  = $current_data_type;
		$this->active_step_index = $current_step;

		unset( $import_data['multi_steps'], $import_data['uninstall_multi_steps'] );

		$return = $callback( $import_data );
		$this->handle_event( 'after_import', $import_data );

		return $return;
	}


	/**
	 * Increase timeout and memory limit.
	 *
	 * @since 3.13.2
	 */
	protected function increase() {

		$timeout = 300;

		// Windows does not have support for this timeout function
		if ( strpos( PHP_OS, 'WIN' ) !== 0 ) {
			$max = (int) ini_get( 'max_execution_time' );

			// Don't bother if unlimited, or if set_time_limit is disabled
			if ( 0 !== $max && $timeout > $max && strpos( ini_get( 'disable_functions' ), 'set_time_limit' ) === false ) {
				//phpcs:ignore
				@set_time_limit( $timeout );
			}
		}

		wp_raise_memory_limit();
	}

	protected function set_demo_data( $demo_id, $page_builder, $language, $include_contents, $context ) {

		if ( $this->demo_id !== $demo_id ) {
			$this->demo_id      = $demo_id;
			$this->demo_context = $context;
			$this->demo_info    = compact( 'demo_id', 'page_builder', 'language', 'include_contents', 'context' );

			$this->all_data = bf_get_demo_data( $demo_id, $page_builder, $language, $include_contents );
		}
	}

	/**
	 * apply custom functions and remove it from $import_data
	 *
	 * @param array  $import_data array of data to import
	 * @param string $context     custom function context. call function on_import or on_rollback ?
	 *
	 * @return bool true on success or false otherwise
	 */
	protected function handle_custom_function( &$import_data, $context ) {

		$result = true;

		foreach ( $import_data as $index => $data ) {

			if ( isset( $data['type'] ) && 'custom_function' === $data['type'] ) {

				if ( ! empty( $data[ $context ] ) ) {

					if ( isset( $data['callback'] ) && is_callable( $data['callback'] ) ) {

						$params = isset( $data['params'] ) && is_array( $data['params'] ) ? $data['params'] : [];
						$result &= (bool) call_user_func_array( $data['callback'], $params );
					}
				}

				unset( $import_data[ $index ] );
			}
		}

		return $result;
	}


	/**
	 * receive absolute path to file in PS Product Pages Library
	 *
	 * @param string $path
	 *
	 * @return string
	 */
	protected function get_path( string $path ): string {

		return BF_Product_Pages::get_path( $path );
	}


	/**
	 * save ID and rollback information to database for future uses,
	 *
	 * fire this function after imported data.
	 *
	 * @return void
	 */
	public function import_stop(): void {

		// validate IDs
		if ( isset( $this->id_list[ $this->demo_id ] ) && is_array( $this->id_list[ $this->demo_id ] ) ) {

			$option_name  = sprintf( 'bs_demo_id_%s', $this->demo_id );
			$option_value = get_option( $option_name, [] );

			// save IDs
			update_option(
				$option_name,
				array_merge( $option_value, $this->id_list[ $this->demo_id ] ),
				'no'
			);
		}

		// validate rollback data
		if ( isset( $this->rollback_data[ $this->demo_id ] ) && is_array( $this->rollback_data[ $this->demo_id ] ) ) {

			$option_name  = sprintf( 'bs_demo_rollback_%s', $this->demo_id );
			$option_value = get_option( $option_name, [] );

			// save rollback data
			update_option(
				$option_name,
				array_merge( $option_value, $this->rollback_data[ $this->demo_id ] ),
				'no'
			);
		}
	}


	/**
	 * Import final step
	 *
	 * call import finished and clear BF css cache
	 */
	public function import_finished() {

		if ( is_callable( 'Better_Framework::factory' ) ) {
			// Clear CSS caches
			Better_Framework::factory( 'custom-css-fe' )->clear_cache( 'all' );
		}

		do_action( 'better-framework/product-pages/install-demo/import-finished', $this->demo_id, $this );
	}


	/**
	 * filter data array indexes
	 *
	 * @param array $params
	 */
	public static function data_params_filter( &$params ) {

		if ( is_array( $params ) ) {

			unset( $params['the_id'] );

			$params = array_filter( $params );
		}
	}


	/**
	 *
	 * @return array
	 */
	public static function import_data_sequence() {

		return [
			'taxonomy',
			'media',
			'posts',
			'options',
			'menus',
			'widgets',
			'styles',
			'assets',
		];
	}


	/**
	 * save imported data id or other information used to pass rollback data
	 *
	 * @param string $id
	 * @param mixed  $value
	 *
	 * @throws BF_Exception
	 * @return bool true on success or false on failure.
	 */
	protected function set_id( $id, $value ): bool {

		$this->throw_if_is_wp_error( $value );

		if ( ! isset( $this->id_list[ $this->demo_id ] ) ) {
			/**
			 * get previous data form database
			 *
			 * @see import_stop()
			 */
			$this->id_list[ $this->demo_id ] = get_option( sprintf( 'bs_demo_id_%s', $this->demo_id ) );
		}

		$id = trim( $id );

		$this->id_list[ $this->demo_id ][ $id ] = $value;

		return true;
	}


	/**
	 * save id of imported data if need
	 * this method will fire after each single import
	 *
	 * @param mixed $result
	 * @param array $data
	 *
	 * @throws BF_Exception
	 */
	protected function save_ID( $result, $data ) {

		if ( isset( $data['the_id'] ) ) {

			$this->set_id( $data['the_id'], $result );
		}
	}

	protected function prepare_item_string( &$value ): void {

		$values = array_map( [ $this, 'apply_pattern' ], explode( ',', $value ) );
		$value  = implode( ',', $values );
	}


	/**
	 * @param array $params
	 * @param array $id_list
	 */
	protected function prepare_params( &$params, $id_list = [] ) {

		if ( $id_list && is_array( $id_list ) ) {

			$id_keys = array_flip( $id_list );

			foreach ( $params as $key => $param ) {

				if ( isset( $id_keys[ $key ] ) ) {

					if ( is_array( $params[ $key ] ) ) {
						array_walk_recursive( $params[ $key ], [ $this, 'prepare_item_string' ] );
					} else {
						$this->prepare_item_string( $params[ $key ] );
					}
				}
			}
		}
	}


	/**
	 * receive new instance of class
	 *
	 * @param string $object_name name of object
	 *
	 * @return bool|object false on failure or object on success.
	 */
	protected function get_instance( string $object_name ) {

		switch ( $object_name ) {

			case 'taxonomy':
				$class_name = 'BF_Demo_Taxonomy_Manager';

				break;

			case 'media':
				$class_name = 'BF_Demo_Media_Manager';

				break;

			case 'post':
			case 'posts':
				$class_name = 'BF_Demo_Posts_Manager';

				break;

			case 'option':
			case 'options':
				$class_name = 'BF_Demo_Option_Manager';

				break;

			case 'widget':
			case 'widgets':
				$class_name = 'BF_Demo_Widget_Manager';

				break;

			case 'menu':
			case 'menus':
				$class_name = 'BF_Demo_Menu_Manager';

				break;
		}

		if ( ! empty( $class_name ) && class_exists( $class_name ) ) {
			return new $class_name();
		}

		return false;
	}

	/**
	 * Import the demo style.css.
	 *
	 * @param array $styles
	 *
	 * @return bool
	 */
	public function import_styles( $styles = [] ) {

		if ( empty( $styles ) ) {

			return false;
		}

		$style_urls = [];

		foreach ( $styles as $style ) {

			$url = $style['file'] ?? $style;

			if ( filter_var( $url, FILTER_VALIDATE_URL ) ) {

				$style_urls[] = $url;
			}
		}

		$this->update_option( 'bf-demo-styles-url', $style_urls );
		$prev_states = BF_Product_Assets_Sync::Run()->sync_styles( $style_urls );

		$this->save_states( $prev_states );

		return true;
	}

	public function import_assets( $assets ): bool {

		if ( empty( $assets ) ) {

			return false;
		}

		foreach ( $assets as $asset ) {

			if ( empty( $asset['id'] ) ) {

				continue;
			}

			$option_name = sprintf( 'bf-demo-asset-%s', $asset['id'] );

			if ( isset( $asset['file'] ) ) {

				$option_value = filter_var( $asset['file'], FILTER_VALIDATE_URL ) ? BF_Http_Util::remote_file_content( $asset['file'] ) : '';

			} else {

				$option_value = $asset['content'] ?? '';
			}

			if ( empty( $option_value ) ) {

				continue;
			}

			$this->update_option( $option_name, $option_value );
		}

		return true;
	}

	public function rollback_assets( $assets ) {

		return $this->rollback_options( $assets );
	}

	public function rollback_styles( $styles ) {

		return $this->rollback_options( $styles );
	}

	/**
	 * @param array $taxonomies
	 *
	 * @throws BF_Exception
	 * @return bool true on success or false on failure
	 */

	public function import_taxonomy( $taxonomies ): bool {

		/**
		 * @var $handler BF_Demo_Taxonomy_Manager
		 */
		if ( ! $handler = $this->get_instance( 'taxonomy' ) ) {

			return false;
		}

		foreach ( $taxonomies as $tax ) {

			$this->prepare_params( $tax, [ 'parent', 'term_id', 'meta_value' ] );

			$term_id = $handler->add_term( $tax );

			$this->throw_if_is_wp_error( $term_id, $tax );

			$this->save_ID( $term_id, $tax );

			$this->save_insert_state( $term_id, 'term' );

			if ( isset( $tax['term_meta'] ) && is_array( $tax['term_meta'] ) ) {

				foreach ( $tax['term_meta'] as $term_meta ) {

					$term_meta['term_id'] = $term_id;
					$this->prepare_params( $term_meta, [ 'meta_value' ] );

					if ( empty( $term_meta['update'] ) ) {
						$result = $handler->add_term_meta( $term_meta );
						$this->throw_if_is_wp_error( $result, $term_meta );

						$this->save_insert_state( $term_meta, 'term_meta' );
					} else {
						$term_meta['single'] = true;

						$prev_meta_data = $handler->get_term_meta( $term_meta );
						$result         = $handler->update_term_meta( $term_meta );
						$this->throw_if_is_wp_error( $result, $term_meta );

						$this->save_update_state( $term_meta, 'term_meta', $prev_meta_data );
					}
				}
			}
		}

		return true;
	}


	/**
	 * @param array $taxonomies
	 *
	 * @return bool true on success or false on failure
	 */
	public function rollback_taxonomy( $taxonomies ): bool {

		/**
		 * @var $handler BF_Demo_Taxonomy_Manager
		 */
		if ( ! $handler = $this->get_instance( 'taxonomy' ) ) {

			return false;
		}

		foreach ( $taxonomies as $tax ) {

			if ( ! isset( $tax['type'] ) ) {
				continue;
			}

			if ( 'term' === $tax['type'] ) {

				$handler->remove_term( $tax['id'] );

			} elseif ( 'term_meta' === $tax['type'] ) {

				if ( empty( $tax['prev_data'] ) ) {
					/**
					 * delete inserted data
					 *
					 * id index of $tax contain array of added meta array {
					 *
					 * @type string $meta_key
					 * @type mixed  $meta_value
					 * @type int    $term_id
					 * }
					 */
					$handler->remove_term_meta( $tax['id'] );
				} else {
					/**
					 * update term meta to previous value
					 *
					 * id index of $tax contain array of added meta array {
					 *
					 * @type string $meta_key
					 * @type mixed  $meta_value
					 * @type int    $term_id
					 * }
					 */
					$update_term               = $tax['id'];
					$update_term['prev_value'] = $tax['prev_data'];

					$handler->update_term_meta( $tax['id'], $update_term );
				}
			}
		}

		return true;
	}


	/**
	 * @param array $media_list
	 *
	 * @throws BF_Exception
	 * @return bool true on success or false on failure
	 */
	public function import_media( $media_list ): bool {

		/**
		 * @var $handler BF_Demo_Media_Manager
		 */
		if ( ! $handler = $this->get_instance( 'media' ) ) {

			return false;
		}

		/**
		 * post handler need to add items post meta
		 *
		 * @var $handler BF_Demo_Posts_Manager
		 */
		$post_handler = $this->get_instance( 'post' );

		foreach ( $media_list as $media ) {

			if ( isset( $media['file'] ) ) {

				$image_id = $handler->add_image( $media['file'], $media );
				$this->throw_if_is_wp_error( $image_id, $media );

				if ( $image_id ) {

					$this->save_ID( $image_id, $media );

					$this->save_insert_state( $image_id, 'media' );

					// add post meta
					if ( isset( $media['media_meta'] ) && is_array( $media['media_meta'] ) ) {
						$this->handle_post_meta( $image_id, $media['media_meta'], $post_handler );
					}
				} else {
					$this->throw_if_is_wp_error( new WP_Error( 'add_image_error', 'cannot insert image' ), $media );
				}
			}
		}

		return true;
	}


	/**
	 * @param array $media_list
	 *
	 * @return bool true on success or false on failure
	 */
	public function rollback_media( $media_list ): bool {

		/**
		 * @var $handler BF_Demo_Media_Manager
		 */
		if ( ! $handler = $this->get_instance( 'media' ) ) {

			return false;
		}

		foreach ( $media_list as $media ) {

			if ( isset( $media['type'] ) && 'media' === $media['type'] ) {

				$handler->remove_image( $media['id'] );
			}
		}

		return true;
	}


	/**
	 * insert/update post meta
	 *
	 * @param int                   $post_id        post id to add/update meta
	 * @param array                 $post_meta_list list of post meta
	 * @param BF_Demo_Posts_Manager $handler        object variable to prevent multiple instantiation
	 *
	 * @throws BF_Exception
	 */
	public function handle_post_meta( $post_id, $post_meta_list, &$handler ) {

		foreach ( $post_meta_list as $post_meta ) {

			$post_meta['post_id'] = $post_id;

			$this->prepare_params( $post_meta, [ 'meta_value' ] );

			if ( empty( $post_meta['update'] ) ) {

				$post_meta_id = $handler->add_post_meta( $post_meta );

				$this->save_insert_state( $post_meta_id, 'post_meta' );
			} else {

				$prev_meta_data = $handler->get_post_meta( $post_meta );
				$post_meta_id   = $handler->update_post_meta( $post_meta );

				$this->save_update_state( $post_meta_id, 'post_meta', $prev_meta_data );
			}

			$this->save_ID( $post_meta_id, $post_meta );
		}
	}


	/**
	 * @param array $posts_list
	 *
	 * @throws BF_Exception
	 * @return bool true on success or false on failure
	 */
	public function import_posts( $posts_list ): bool {

		static $remote_post_contents = []; // Cache storage for remote post contents.

		/**
		 * @var $handler BF_Demo_Posts_Manager
		 */
		if ( ! $handler = $this->get_instance( 'post' ) ) {

			return false;
		}

		$post_date = new DateTime( '-30 minutes' );

		foreach ( array_reverse( $posts_list ) as $post ) {

			if ( ! empty( $post['remote_content'] ) ) { // Fetch remote post content from better studio core

				if ( empty( $remote_post_contents[ $this->demo_id ] ) ) {

					$remote_params            = $post['remote_content'];
					$remote_params['demo-id'] = $this->demo_id;

					$remote_response = $this->api_request( 'demo-content', $remote_params );

					// Validate remote response
					if ( ! is_wp_error( $remote_response ) && ! empty( $remote_response->success ) && ! empty( $remote_response->content ) ) {
						$post['post_content'] = $remote_response->content;

						$remote_post_contents[ $this->demo_id ] = $post['post_content']; // Cache it!
					}
				} else {

					$post['post_content'] = $remote_post_contents[ $this->demo_id ];
				}

				unset( $post['remote_content'] ); // it's better to unset new array index
			}

			$this->prepare_params( $post, [ 'thumbnail_id', 'post_id', 'post_terms' ] );

			if ( ! empty( $post['post_date'] ) ) {
				$post['post_date_gmt'] = strtotime( $post['post_date'] );
			} else {
				$post_date->modify( '+1 second' );
				$post['post_date_gmt'] = $post_date->format( 'Y-m-d H:i:s' );
			}

			$post_id = $handler->add_post( $post );
			$this->throw_if_is_wp_error( $post_id, $post );

			$this->save_insert_state( $post_id, 'post' );

			$this->save_ID( $post_id, $post );

			if ( isset( $post['post_meta'] ) && is_array( $post['post_meta'] ) ) {

				$this->handle_post_meta( $post_id, $post['post_meta'], $handler );
			}
		}

		return true;
	}


	/**
	 * @param array $posts_list
	 *
	 * @return bool true on success or false on failure
	 */
	public function rollback_posts( $posts_list ): bool {

		/**
		 * @var $handler BF_Demo_Posts_Manager
		 */
		if ( ! $handler = $this->get_instance( 'post' ) ) {

			return false;
		}

		foreach ( $posts_list as $post ) {

			if ( 'post' === $post['type'] ) {

				$handler->remove_post( $post['id'] );

			} elseif ( 'post_meta' === $post['type'] ) {

				if ( empty( $post['prev_data'] ) ) {

					// delete inserted data
					$handler->remove_post_meta( $post['id'] );
				} else {

					// update meta & set previous data

					// TODO: test this method
					$handler->update_post_meta( $post['prev_data'] );
				}
			}
		}

		return true;
	}


	/**
	 * @param array $options_list
	 *
	 * @throws BF_Exception
	 * @return bool true on success or false on failure
	 */
	public function import_options( $options_list ): bool {

		/**
		 * @var $handler BF_Demo_Option_Manager
		 */
		if ( ! $handler = $this->get_instance( 'option' ) ) {

			return false;
		}

		foreach ( $options_list as $option ) {

			// replace recursively the_id in array of option value
			if ( isset( $option['option_value'] ) && is_array( $option['option_value'] ) ) {
				$this->prepare_params( $option, array_keys( $option['option_value'] ) );
			} else {
				$this->prepare_params( $option, [ 'option_value' ] );
			}

			if ( isset( $option['type'] ) && 'transient' === $option['type'] ) {
				// transient
				$transient = &$option;

				if ( ! isset( $transient['expiration'] ) ) {
					$transient['expiration'] = 0;
				}

				$prev_transient = $handler->get_transient( $transient );
				$handler->set_transient( $transient );

				$inserted_data = wp_array_slice_assoc(
					$transient,
					[
						'transient_name',
						'expiration',
					]
				);
				$this->save_update_state( $inserted_data, 'transient', $prev_transient );

			} else {
				// option

				if ( empty( $option['delete'] ) ) {

					$this->insert_update_option( $option, $handler );
				} else {

					$prev_option = $handler->get_option( $option );

					$handler->delete_option( $option['option_name'] );
					$this->save_delete_state(
						[
							'option_name'  => $handler->get_option_name( $option ),
							'option_value' => $prev_option,
						],
						'delete_option'
					);
				}
			}
		}

		return true;
	}


	/**
	 * @param array $options_list
	 *
	 * @return bool true on success or false on failure
	 */
	public function rollback_options( $options_list ): bool {

		/**
		 * @var $handler BF_Demo_Option_Manager
		 */
		if ( ! $handler = $this->get_instance( 'option' ) ) {

			return false;
		}

		foreach ( $options_list as $option ) {

			if ( ! isset( $option['type'] ) ) {
				continue;
			}

			switch ( $option['type'] ) {

				case 'merge_option':

					if ( isset( $option['prev_data'] ) ) {

						$handler->merge_and_update_option(
							[
								'option_name'  => $option['id'],
								'option_value' => $option['prev_data'],
							]
						);
					}
					break;

				case 'delete_option':
					$handler->add_option( $option['id'] );

					break;

				case 'add_option':
					$handler->delete_option( $option['id'] );

					break;

				case 'update_option':
					$handler->update_option(
						[
							'option_name'  => $option['id'],
							'option_value' => $option['prev_data'] ?? '',
						]
					);

					break;

				case 'transient':
					if ( empty( $option['prev_data'] ) ) {

						if ( isset( $option['id']['transient_name'] ) ) {
							$handler->remove_transient( $option['id']['transient_name'] );
						}
					} else {

						$option['id']['transient_value'] = $option['prev_data'];
						$handler->set_transient( $option['id'] );
					}

					break;
			}
		}

		return true;
	}


	/**
	 * @param array $widgets_list
	 *
	 * @throws BF_Exception
	 * @return bool true on success or false on failure
	 */

	public function import_widgets( $widgets_list ): bool {

		/**
		 * @var $handler BF_Demo_Widget_Manager
		 */

		if ( ! $handler = $this->get_instance( 'widget' ) ) {

			return false;
		}

		// save current widget status before start importing
		$this->save_widget_status();

		foreach ( $widgets_list as $sidebar_id => $sidebar_widgets ) {

			$handler->set_sidebar_id( $sidebar_id );

			if ( ! empty( $sidebar_widgets['remove_all_widgets'] ) ) {

				$handler->remove_all_widgets();

				unset( $sidebar_widgets['remove_all_widgets'] );

			} elseif ( ! empty( $sidebar_widgets['remove_widgets'] ) && is_array( $sidebar_widgets['remove_widgets'] ) ) {

				foreach ( $sidebar_widgets['remove_widgets'] as $widget_id_base ) {

					$handler->remove_widgets( $widget_id_base );
				}

				unset( $sidebar_widgets['remove_widgets'] );
			}

			foreach ( $sidebar_widgets as $widget_data ) {

				if ( ! isset( $widget_data['widget_id'] ) ) {

					continue;
				}

				$this->prepare_params( $widget_data, [ 'widget_settings' ] );

				$handler->add_widget(
					$widget_data['widget_id'],
					$widget_data['widget_settings'] ?? [],
					$handler->use_block_widget( $widget_data )
				);
			}
		}

		return true;
	}


	/**
	 * save sidebar widgets and widgets settings
	 *
	 * @see import_widgets
	 *
	 * @global wpdb $wpdb
	 * @throws BF_Exception
	 */
	protected function save_widget_status() {

		global $wpdb;

		// step 1) save widgets array
		$this->save_insert_state( get_option( 'sidebars_widgets' ), 'sidebars_widgets' );

		// step 2) save widget settings
		//phpcs:ignore
		$widgets_settings = $wpdb->get_results(
			'SELECT option_name,option_value FROM ' . $wpdb->options . ' WHERE option_name LIKE \'widget_%\'',
			ARRAY_A
		);

		if ( is_array( $widgets_settings ) ) {
			$widgets_settings = array_map( [ $this, 'unserialize_option_value' ], $widgets_settings );
			$this->save_insert_state( $widgets_settings, 'widgets_settings' );
		}
	}


	/**
	 * @see save_widget_status
	 *
	 * @param array $option_array
	 *
	 * @return mixed
	 */
	protected function unserialize_option_value( $option_array ) {

		if ( isset( $option_array['option_value'] ) ) {

			$option_array['option_value'] = maybe_unserialize( $option_array['option_value'] );
		}

		return $option_array;
	}


	/**
	 *
	 * TODO: add_option not working  on add_option( $option['option_name'],  $option['option_value'] );
	 *
	 * @param array $widgets_list
	 *
	 * @return bool true on success or false on failure
	 */
	public function rollback_widgets( $widgets_list ): bool {

		global $wpdb;
		/**
		 * @var $handler BF_Demo_Widget_Manager
		 */
		if ( ! $handler = $this->get_instance( 'widget' ) ) {

			return false;

		}

		$delete_options = false;

		foreach ( $widgets_list as $widget ) {

			if ( ! isset( $widget['type'], $widget['id'] ) ) {

				continue;
			}

			if ( 'sidebars_widgets' === $widget['type'] ) {

				update_option( 'sidebars_widgets', $widget['id'] );

			} elseif ( 'widgets_settings' === $widget['type'] ) {

				$settings_options = &$widget['id'];

				if ( is_array( $settings_options ) ) {

					if ( ! $delete_options ) {

						// remove all widgets settings and import previous data
						//phpcs:ignore
						$wpdb->query( 'DELETE FROM ' . $wpdb->options . ' WHERE option_name LIKE \'widget_%\'' );
						$delete_options = true;

						// options cache make problem while adding options
						wp_cache_delete( 'alloptions', 'options' );
						wp_cache_delete( 'notoptions', 'options' );
					}

					foreach ( $settings_options as $option ) {

						if ( isset( $option['option_name'] ) && isset( $option['option_value'] ) ) {

							wp_cache_delete( $option['option_name'], 'options' );
							add_option( $option['option_name'], $option['option_value'] );
						}
					}
				}
			}
		}

		return true;
	}


	/**
	 * import menu data
	 *
	 * @param array $menus_list
	 *
	 * @throws BF_Exception
	 * @return bool true on success or false on failure
	 */
	public function import_menus( $menus_list ): bool {

		/**
		 * @var $handler BF_Demo_Menu_Manager
		 */

		if ( ! $handler = $this->get_instance( 'menu' ) ) {

			return false;
		}

		// save menu locations before apply changes
		$this->save_update_state( '1', 'nav_menu_locations', get_theme_mod( 'nav_menu_locations' ) );

		/**
		 * post handler need to add items post meta
		 *
		 * @var $handler BF_Demo_Posts_Manager
		 */
		$post_handler = $this->get_instance( 'post' );

		$required_params = [
			'items'         => '',
			'menu-name'     => '',
			'menu-location' => '',
		];

		foreach ( $menus_list as $menu_list ) {

			if ( array_diff_key( $required_params, $menu_list ) ) {

				return false;
			}

			$created_menu = $handler->create_menu( $menu_list['menu-name'], $menu_list['menu-location'] );
			$this->throw_if_is_wp_error( $created_menu, $menu_list );

			list( $menu_id, $menu_exists ) = $created_menu;

			$this->save_ID( $menu_id, $menu_list );

			if ( ! $menu_exists && $menu_id ) {

				$this->save_insert_state( $menu_id, 'menu' );
			}

			if ( ! empty( $menu_list['recently-edit'] ) && $menu_id ) {
				update_user_meta( get_current_user_id(), 'nav_menu_recently_edited', $menu_id );
			}

			$id_list = [ 'parent-id', 'page_id', 'term_id', 'post_id' ];

			if ( ! is_array( $menu_list['items'] ) ) {

				continue;
			}

			foreach ( $menu_list['items'] as $menu_item ) {

				if ( ! isset( $menu_item['item_type'] ) ) {
					continue;
				}

				$item_type = $menu_item['item_type'];

				unset( $menu_item['item_type'] );

				$this->prepare_params( $menu_item, $id_list );

				$item_id = 0;

				switch ( $item_type ) {

					case 'custom':
						$item_id = $handler->append_link( $menu_item );

						break;

					case 'page':
						if ( isset( $menu_item['page_id'] ) ) {
							$item_id = $handler->append_page_link( $menu_item['page_id'], $menu_item );
						}

						break;

					case 'post':
						if ( isset( $menu_item['post_id'] ) ) {

							$post_type = $menu_item['post_type'] ?? 'post';

							$item_id = $handler->append_post_link( $menu_item['post_id'], $post_type, $menu_item );
						}

						break;

					case 'term':
						if ( isset( $menu_item['term_id'], $menu_item['taxonomy'] ) ) {
							$item_id = $handler->append_taxonomy_link( $menu_item['term_id'], $menu_item['taxonomy'], $menu_item );

						}

						break;
				}

				$this->throw_if_is_wp_error( $item_id, $menu_item );

				if ( ! $item_id ) {

					continue;
				}

				// save IDs
				$this->save_insert_state( $item_id, 'menu-item' );
				$this->save_ID( $item_id, $menu_item );

				// add post meta
				if ( isset( $menu_item['item_meta'] ) && is_array( $menu_item['item_meta'] ) ) {

					// Fix meta ids key
					$_item_meta = [];
					foreach ( $menu_item['item_meta'] as $meta ) {
						$_meta = $meta;
						//phpcs:ignore
						$_meta['meta_key'] = '_menu_item_' . $_meta['meta_key'];
						$_item_meta[]      = $_meta;
					}

					$this->handle_post_meta( $item_id, $_item_meta, $post_handler );
				}
			}
		}

		return true;
	}


	/**
	 * rollback menu to last status.
	 *
	 * @param array $menus_list
	 *
	 * @return bool true on success or false on failure
	 */

	public function rollback_menus( $menus_list ): bool {

		/**
		 * @var $handler BF_Demo_Menu_Manager
		 */

		if ( ! $handler = $this->get_instance( 'menu' ) ) {

			return false;
		}

		foreach ( $menus_list as $menu ) {

			if ( ! isset( $menu['type'] ) ) {
				continue;
			}

			if ( 'menu-item' === $menu['type'] ) {

				$handler->remove_item( $menu['id'] );

			} elseif ( 'menu' === $menu['type'] ) {

				$handler->remove_menu( $menu['id'] );

			} elseif ( 'nav_menu_locations' === $menu['type'] ) {

				if ( ! empty( $menu['prev_data'] ) && is_array( $menu['prev_data'] ) ) {

					set_theme_mod( 'nav_menu_locations', $menu['prev_data'] );
				}
			}
		}

		return true;
	}

	/***
	 * Demo Rollback Methods
	 */


	/**
	 * start rollback data.
	 *
	 * @param string  $current_data_type rollback data type
	 * @param integer $current_step      step index number
	 * @param string  $demo_id           demo name
	 * @param string  $demo_context      demo context {@see bf_get_demo_data}
	 *
	 * @throws Throwable
	 * @return bool true on success or false on failure
	 */

	public function rollback_start( $current_data_type, $current_step, $demo_id, $page_builder, $language, $include_contents, $demo_context = 'content' ) {

		$this->set_demo_data( $demo_id, $page_builder, $language, $include_contents, $demo_context );
		$data = $this->get_rollback_data();

		/**
		 * handle rollback final step
		 *
		 * {@see \BF_Product_Demo_Manager::calculate_install_steps} method
		 * with uninstall context will add clean step  for last step.
		 */
		if ( 'clean' === $current_data_type ) {

			return $this->rollback_finished();
		}

		if ( ! isset( $data[ $current_data_type ][ $current_step ] ) ) {

			return false;
		}

		$this->active_data_type  = $current_data_type;
		$this->active_step_index = $current_step;

		$callback_prefix = 'rollback_';
		$method          = strtolower( str_replace( '-', '_', $callback_prefix . $current_data_type ) );
		$callback        = [ $this, $method ];

		if ( ! is_callable( $callback ) || __FUNCTION__ === $method ) {

			return false;
		}

		/**
		 * data pass to function is array of data something like this
		 *
		 * array {
		 *
		 *   array {
		 *      id      =>  mixed (often string)  data saved while importing data.
		 *      type    =>  string data imported. arbitrary name, defined importer method
		 *   }
		 *
		 * }
		 */
		$this->handle_event( 'before_rollback', $data[ $current_data_type ][ $current_step ] );

		if ( isset( $this->all_data[ $current_data_type ][ $current_step ] ) ) {

			$this->handle_custom_function( $this->all_data[ $current_data_type ][ $current_step ], 'on_rollback' );
		}

		// pass all data to install if uninstall_multi_steps was not active
		if ( empty( $this->all_data[ $current_data_type ]['uninstall_multi_steps'] ) ) {
			$result = 1;
			foreach ( $data[ $current_data_type ] as $uninstall_data ) {
				$result &= $callback( $uninstall_data );
			}
		} else {
			$result = $callback( $data[ $current_data_type ][ $current_step ] );
		}

		$this->handle_event( 'after_rollback', $data[ $current_data_type ][ $current_step ] );

		return $result;
	}

	protected function handle_event( $event, $params = [] ) {

		if ( isset( $this->all_data['events'][ $event ] ) ) {

			$callback = &$this->all_data['events'][ $event ];
			if ( is_callable( $callback ) ) {

				return call_user_func_array( $callback, $params );
			}
		}
	}


	/**
	 * delete rollback data from database
	 *
	 * fire this function after rollback_start().
	 *
	 * @return bool true on success or false on failure.
	 */

	public function rollback_stop() {

		$data = $this->get_rollback_data();

		if ( ! is_array( $data ) ) {

			return false;
		}

		if ( ! isset( $data[ $this->active_data_type ][ $this->active_step_index ] ) ) {
			$this->delete_option_if_necessary( $data );

			return false;
		}

		unset( $data[ $this->active_data_type ][ $this->active_step_index ] );

		if ( $data ) {

			$option_name = sprintf( 'bs_demo_rollback_%s', $this->demo_id );

			return update_option( $option_name, $data, 'no' );

		} else {

			$this->delete_option_if_necessary( $data );

			return true;
		}
	}


	/**
	 * Rollback demo on single request
	 *
	 * @param string $demo_id
	 * @param string $demo_context
	 *
	 * @return bool true on success or false otherwise.
	 */
	public function rollback_force( $demo_id, $page_builder, $language, $include_contents, $demo_context = 'content' ): bool {

		$this->set_demo_data( $demo_id, $page_builder, $language, $include_contents, $demo_context );
		$all_data = $this->get_rollback_data();

		if ( ! $all_data || ! is_array( $all_data ) ) {
			return false;
		}

		foreach ( $all_data as $current_data_type => $data ) {

			if ( ! is_array( $data ) ) {
				continue;
			}

			$steps = array_keys( $data );

			if ( $steps ) {

				foreach ( $steps as $step ) {
					$this->rollback_start( $current_data_type, $step, $demo_id );
					$this->rollback_stop();
				}
			}
		}
		$this->rollback_finished();

		return true;
	}


	/**
	 * Rollback final step.
	 *
	 * delete all temporary data saved in options to make sure uninstalling process
	 * completed and prevent display uninstall demo button after demo uninstalled!
	 *
	 * @see \BF_Product_Demo_Manager::calculate_process_steps
	 *
	 * @return bool always return true.
	 */
	private function rollback_finished(): bool {

		/**
		 * pass empty array to function will delete temporary data saved in options table
		 */
		$data = [];
		$this->delete_option_if_necessary( $data );

		if ( is_callable( 'Better_Framework::factory' ) ) {
			// Clear CSS caches
			Better_Framework::factory( 'custom-css-fe' )->clear_cache( 'all' );
		}

		do_action( 'better-framework/product-pages/install-demo/rollback-finished', $this->demo_id, $this );

		return true;
	}


	/**
	 * Delete demo data saved in options if needed
	 *
	 * @see get_rollback_data
	 *
	 * @param array $data array of rollback data
	 *
	 * @return bool true if data was empty.
	 */
	private function delete_option_if_necessary( &$data ): bool {

		if ( ! is_array( $data ) ) {

			return false;
		}

		$data = array_filter( $data );

		if ( empty( $data ) ) {

			$option_name           = sprintf( 'bs_demo_rollback_%s', $this->demo_id );
			$temp_vars_option_name = sprintf( 'bs_demo_id_%s', $this->demo_id );

			delete_option( $option_name );
			delete_option( $temp_vars_option_name );

			return true;
		}

		return false;
	}


	/**
	 * Whether a demo is already installed
	 *
	 * @param string $demo_id
	 *
	 * @return bool
	 */
	public static function is_demo_installed( $demo_id ): bool {

		$options = get_option( sprintf( 'bs_demo_rollback_%s', $demo_id ) );

		return $options && is_array( $options );
	}

	/**
	 * @param string $demo_id
	 *
	 * @return array
	 */
	public static function demo_info( string $demo_id ): array {

		$options = get_option( sprintf( 'bs_demo_rollback_%s', $demo_id ) );

		return $options['info'] ?? [];
	}

	/**
	 * get rollback data for active demo id
	 *
	 * @return mixed
	 */

	protected function get_rollback_data() {

		if ( ! isset( $this->rollback_data[ $this->demo_id ] ) ) {
			$this->rollback_data[ $this->demo_id ] = get_option( sprintf( 'bs_demo_rollback_%s', $this->demo_id ) );
		}

		return $this->rollback_data[ $this->demo_id ] ?? null;
	}


	/**
	 * set rollback data for active demo
	 *
	 * @param mixed $data
	 *
	 * @throws BF_Exception
	 * @return bool true on success or false on failure.
	 */
	protected function set_rollback_data( $data ): bool {

		$this->throw_if_is_wp_error( $data );

		if ( ! isset( $this->rollback_data[ $this->demo_id ] ) ) {
			$this->rollback_data[ $this->demo_id ] = get_option( sprintf( 'bs_demo_rollback_%s', $this->demo_id ) );
		}

		// Fix: php>8 Deprecated: Automatic conversion of false to array is deprecated.
		if ( $this->rollback_data[ $this->demo_id ] === false ) {

			$this->rollback_data[ $this->demo_id ] = [];
		}

		$this->rollback_data
		[ $this->demo_id ]
		[ $this->active_data_type ]
		[ $this->active_step_index ][] = $data;

		if ( ! empty( $this->demo_info ) ) {
			$this->rollback_data[ $this->demo_id ]['info'] = $this->demo_info;
		}

		return true;
	}


	/**
	 * @param mixed  $inserted_data
	 * @param string $data_type
	 *
	 * @throws BF_Exception
	 * @return bool true on success or false on failure.
	 */
	protected function save_insert_state( $inserted_data, $data_type ) {

		$this->throw_if_is_wp_error( $inserted_data );

		if ( ! $inserted_data ) {
			return false;
		}

		return $this->set_rollback_data( [
			'id'   => $inserted_data,
			'type' => $data_type,
		] );
	}


	/**
	 * @param mixed  $inserted_data
	 * @param string $data_type
	 * @param mixed  $prev_data
	 *
	 * @throws BF_Exception
	 * @return bool true on success or false on failure.
	 */
	protected function save_update_state( $inserted_data, $data_type, $prev_data ) {

		$this->throw_if_is_wp_error( $inserted_data );

		if ( ! $inserted_data ) {
			return false;
		}

		if ( empty( $prev_data ) ) {

			return $this->save_insert_state( $inserted_data, $data_type );
		}

		return $this->set_rollback_data( [
			'id'        => $inserted_data,
			'type'      => $data_type,
			'prev_data' => $prev_data,
		] );
	}


	/**
	 * @param mixed  $deleted_data
	 * @param string $data_type
	 *
	 * @throws BF_Exception
	 * @return bool true on success or false on failure.
	 */
	protected function save_delete_state( $deleted_data, $data_type ) {

		return $this->set_rollback_data( [
			'id'   => $deleted_data,
			'type' => $data_type,
		] );
	}

	/***
	 *  Parse Input String
	 */


	/**
	 * change string, apply function or just replace ID with value
	 *
	 * @param string $string
	 *
	 * @return null|string string on success null otherwise.
	 */
	public function apply_pattern( $string ) {

		if ( ! is_string( $string ) ) {
			return $string;
		}

		preg_match_all( $this->id_pattern, $string, $matched );

		//phpcs:ignore
		foreach ( ( $matched[1] ?? [] ) as $index => $ID ) {

			// Advanced custom function pattern
			if ( preg_match( $this->function_pattern, $matched[0][ $index ], $_matched ) ) {

				$callback = &$_matched[1];
				$params   = &$_matched[2];

				// Prepare params
				$params = $this->quoted_explode( $params, ':' );
				$params = array_map( [ $this, 'filter_function_params' ], $params );

				$data_replace_keys[]   = $matched[0][ $index ];
				$data_replace_values[] = call_user_func_array( $callback, $params );
				//phpcs:ignore
			}
			//phpcs:ignore
			// Simple pattern
			else {
				$data_replace_keys[] = $matched[0][ $index ];
				//phpcs:ignore
				$data_replace_values[] = $this->get_id( $ID );
			}
		}// foreach

		return str_replace( $data_replace_keys ?? [], $data_replace_values ?? [], $string );
	}


	/**
	 * receive imported data information
	 *
	 * @see search_and_replace_id
	 *
	 * @param string $id
	 *
	 * @return mixed null on failure otherwise return saved data
	 */
	protected function get_id( $id ) {

		if ( ! isset( $this->id_list[ $this->demo_id ] ) ) {
			$this->id_list[ $this->demo_id ] = get_option( sprintf( 'bs_demo_id_%s', $this->demo_id ) );
		}

		$id = trim( $id );

		return $this->id_list[ $this->demo_id ][ $id ] ?? null;
	}


	/**
	 * escape special characters
	 *
	 * @param string $subject
	 *
	 * @return string
	 */
	public function regex_escape( $subject ) {

		return str_replace( [ '\\', '^', '-', ']' ], [ '\\\\', '\\^', '\\-', '\\]' ], $subject );
	}


	/**
	 * explode string using delimiter but skip content inside of quotes
	 *
	 * @param string $subject    string to explode
	 * @param string $delimiters explode delimiter
	 * @param string $quotes
	 *
	 * @return array
	 */
	public function quoted_explode( $subject, $delimiters = ':', $quotes = '\'' ) {

		$clauses[] = '[^' . $this->regex_escape( $delimiters . $quotes ) . ']';
		foreach ( str_split( $quotes ) as $quote ) {
			$quote     = $this->regex_escape( $quote );
			$clauses[] = "[$quote][^$quote]*[$quote]";
		}

		$regex = '(?:' . implode( '|', $clauses ) . ')+';
		preg_match_all( '/' . str_replace( '/', '\\/', $regex ) . '/', $subject, $matches );

		return $matches[0] ?? [];
	}


	/**
	 * Filter function parameters. Remove quotes inside around the parameter
	 *
	 * @param string $param
	 *
	 * @return string filtered string
	 */
	private function filter_function_params( $param ) {

		// replace params that are inside {the_id}
		if ( preg_match( '/^{(.*?)}$/', $param, $__matched ) ) {
			$param = $this->get_id( $__matched[1] );
		}

		return trim( $param ?? '', "'" );
	}


	/**
	 * update or insert option
	 * this method just used in import_option method
	 *
	 * @see import_options
	 *
	 * @param array                  $option
	 * @param BF_Demo_Option_Manager $handler
	 *
	 * @throws BF_Exception
	 */
	private function insert_update_option( $option, &$handler ) {

		$option_name = $handler->get_option_name( $option );

		if ( empty( $option['insert'] ) ) {

			$prev_option_data = $handler->get_option( $option );
			$update_option    = false;

			if ( ! empty( $option['merge_options'] ) ) {
				if ( $handler->merge_and_update_option( $option ) ) {

					// just save new option indexes, not all indexes
					if ( $prev_option_data && is_array( $prev_option_data ) ) {
						$new_option_data = array_intersect_key( $prev_option_data, $handler->get_option_value( $option ) );
						$this->save_update_state( $option_name, 'merge_option', $new_option_data );
					}
				} else {
					$update_option = true;
				}
			} else {
				$update_option = true;
			}


			if ( $update_option ) {
				$handler->update_option( $option );
				$this->save_update_state( $option_name, 'update_option', $prev_option_data );
			}
		} else {

			$handler->add_option( $option );
			$this->save_insert_state( $option_name, 'add_option' );
		}
	}

	/**
	 *
	 * @param string $option
	 * @param mixed  $value
	 *
	 * @return bool
	 */
	protected function update_option( $option, $value ) {

		$prev_option_data = get_option( $option );
		$updated          = update_option( $option, $value );
		$updated && $this->save_state( $option, $prev_option_data );

		return $updated;
	}

	protected function save_states( $options ) {

		foreach ( $options as $option_name => $prev_option_data ) {

			$this->save_state( $option_name, $prev_option_data );
		}
	}

	protected function save_state( $option_name, $prev_option_data ) {

		if ( false === $prev_option_data ) {

			$this->save_insert_state( $option_name, 'add_option' );

		} else {

			$this->save_update_state( $option_name, 'update_option', $prev_option_data );
		}
	}

}
