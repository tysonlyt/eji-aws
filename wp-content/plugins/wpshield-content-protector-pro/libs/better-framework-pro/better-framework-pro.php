<?php

use BetterStudio\Framework\Pro\{
	Booster,
	Updater
};

include BF_PRO_PATH . 'functions/other.php';
include BF_PRO_PATH . 'Compatibility/functions.php';

/**
 * Handy Function for accessing to BetterFramework
 *
 * @return Better_Framework_Pro
 */
function Better_Framework_Pro() {

	return Better_Framework_Pro::self();
}

// Fire Up BetterFramework
Better_Framework_Pro()->init();


class Better_Framework_Pro {

	const VERSION = BF_PRO_VERSION;

	/**
	 * Defines which sections should be include in BF
	 *
	 * @var array
	 * @since  1.0
	 * @access public
	 */
	public $sections = array(
		'booster' => false,   // Booster
	);

	/**
	 * Inner array of instances
	 *
	 * @var array
	 */
	protected static $instances = array();


	/**
	 * @param array $sections default features
	 *
	 * @since  1.0
	 * @access public
	 */
	public function init( array $sections = [] ): void {

		do_action( 'better-framework-pro/before-init' );

		// define features of BF
		$this->sections = array_merge( [
			'product-updater' => is_admin() || ( defined( 'DOING_CRON' ) && DOING_CRON )
		], $this->sections, $sections );

		$this->sections = apply_filters( 'better-framework/sections', $this->sections );

		if ( $this->sections['booster'] === true ) {

			self::factory( 'booster' );
		}

		if ( $this->sections['product-updater'] === true ) {

			self::factory( 'product-updater' );
		}
	}

	/**
	 * Build the required object instance
	 *
	 * @param string $object
	 * @param bool   $fresh
	 *
	 * @return object
	 */
	public static function factory( string $object = 'options', bool $fresh = false ) {

		if ( isset( self::$instances[ $object ] ) && ! $fresh ) {

			return self::$instances[ $object ];
		}

		switch ( $object ) {

			/**
			 * Main BetterFramework Class
			 */
			case 'self':
				$class = self::class;
				break;

			/**
			 * Booster
			 */
			case 'booster':

				if ( ! class_exists( '_WP_Dependency' ) ) {
					/** WordPress Dependency Class */
					require( ABSPATH . WPINC . '/class-wp-dependency.php' );
				}

				if ( ! class_exists( 'WP_Dependencies' ) ) {
					/** WordPress Dependencies Class */
					require( ABSPATH . WPINC . '/class.wp-dependencies.php' );
				}

				include BF_PRO_PATH . 'Booster/functions.php';

				$class = Booster\Booster::class;

				break;

			/**
			 * Products Manager
			 */
			case 'product-updater':

				include BF_PRO_PATH . 'Updater/functions.php';

				$class = Updater\ProductUpdater::class;

				break;

			default:
				return null;
		}

		if ( ! isset( $class ) ) {

			return null;
		}

		// don't cache fresh objects
		if ( $fresh ) {

			return new $class;
		}

		self::$instances[ $object ] = new $class;

		return self::$instances[ $object ];
	}

	/**
	 * Used for accessing alive instance of Better_Framework
	 *
	 * static
	 *
	 * @since 1.0
	 * @return self
	 */
	public static function self(): self {

		return self::factory( 'self' );
	}
}
