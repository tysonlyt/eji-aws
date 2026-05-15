<?php

class BF_Product_Assets_Sync {

	/**
	 * Store self instance.
	 *
	 * @var self
	 * @since 3.16.0
	 */
	protected static $instance;

	/**
	 * Store the style files urls.
	 *
	 * @var array
	 * @since 3.16.0
	 */
	protected $styles = [];

	/**
	 * Initialize.
	 *
	 * @since 3.16.0
	 */
	public static function Run() {

		if ( ! self::$instance instanceof self ) {

			self::$instance = new self();
		}

		return self::$instance;
	}

	/**
	 * Trigger style sync functionally.
	 *
	 * @param array $style_urls
	 *
	 * @since 3.16.0
	 * @return array
	 */
	public function sync_styles( $style_urls ) {

		$this->styles = (array) $style_urls;

		if ( ! $this->can_update_styles() ) {

			return [];
		}

		return $this->update_styles();
	}

	/**
	 * Does the style file need to be updated.
	 *
	 * @since 3.16.0
	 * @return bool
	 */
	public function can_update_styles() {

		if ( empty( $this->styles ) ) {

			return false;
		}

		$timeout = $this->updated_time() + HOUR_IN_SECONDS;

		return $timeout < time();
	}

	/**
	 * Fetch and update style files.
	 *
	 * @since 3.16.0
	 * @return array prev data
	 */
	public function update_styles(): array {

		$prev_options = [
			'bf-demo-styles-info' => get_option( 'bf-demo-styles-info' ),
		];

		if ( ! $contents = BF_Http_Util::remote_files_content( $this->styles ) ) {

			$this->update_info(); // update time

			return $prev_options;
		}

		$prev_options['bf-demo-styles-content'] = get_option( 'bf-demo-styles-content' );
		update_option( 'bf-demo-styles-content', $contents );
		$this->update_info( [ 'version' => $this->product_version() ] );

		return $prev_options;
	}

	/**
	 * Get the registered product version number.
	 *
	 * @since 3.16.0
	 * @return string
	 */
	protected function product_version() {

		$info = bf_register_product_get_info();

		return $info['version'] ?? '';
	}

	/**
	 * Set css style information.
	 *
	 * @param array $data
	 *
	 * @since 3.16.0
	 * @return bool true on success
	 */
	protected function update_info( $data = [] ) {

		if ( empty( $data ) ) {

			return false;
		}

		if ( ! $info = get_option( 'bf-demo-styles-info' ) ) {

			$info = [];
		}

		$info = array_merge( $info, $data );
		//
		$info['updated_time'] = time();

		update_option( 'bf-demo-styles-info', $info );

		return true;
	}

	/**
	 * Get time of last style synced.
	 *
	 * @since 3.16.0
	 * @return int
	 */
	protected function updated_time() {

		$info = get_option( 'bf-demo-styles-info' );

		return $info['updated_time'] ?? 0;
	}

}
