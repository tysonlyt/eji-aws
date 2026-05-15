<?php


namespace WPShield\Plugin\ContentProtectorPro\Core\WebServer;

use BetterStudio\Core\Module\ModuleHandler;
use WPShield\Plugin\ContentProtector\ContentProtectorSetup;
use WPShield\Plugin\ContentProtector\Core\Component;

/**
 * Class WebServerConfigModifier
 *
 * @since   1.0.0
 *
 * @package WPShield\Plugin\ContentProtectorPro\Core\WebServer
 */
class WebServerConfigModifier extends ModuleHandler {

	/**
	 * Store web server config object.
	 *
	 * @var WebServerConfig
	 *
	 * @since 1.0.0
	 */
	protected $web_server;


	/**
	 * Initialize the library.
	 *
	 * @since 1.0.0
	 */
	public function init(): bool {

		// after saved panel event
		add_filter( 'better-framework/panel/reset/after', array( $this, 'panel_saved' ), 20, 2 );

		return true;
	}

	/**
	 * Apply changes after plugin options saved.
	 * Compatible with BF old code base!
	 *
	 * @hooked better-framework/panel/save/result
	 *
	 * @param array $output  contains result of save
	 * @param array $options contain options
	 *
	 * @since  1.0.0
	 * @return array
	 */
	public function panel_saved( $output, $options ) {

		if ( ! isset( $options['id'] ) ) {

			return $output;
		}

		if ( ! class_exists( ContentProtectorSetup::class ) || ContentProtectorSetup::instance()->product_id() !== $options['id'] ) {

			return $output;
		}

		if ( ! $this->find_web_server( new ApacheConfigManager() ) ) {

			return $output;
		}

		if ( ! $this->protection_config() ) {

			$this->web_server->roll_back();
		}

		return $output;
	}

	/**
	 * Retrieve the handler feature as instance of Component!
	 *
	 * @param string $protection_name
	 *
	 * @since 1.0.0
	 * @return Component|null
	 */
	protected function get_handler_feature( string $protection_name ): ?Component {

		$handler_class = sprintf(
			'\WPShield\Plugin\ContentProtectorPro\Features\%s\Handler',
			'image' === $protection_name ? ucfirst( $protection_name ) . 's' : ucfirst( $protection_name )
		);

		if ( ! class_exists( $handler_class ) ) {

			return null;
		}

		return new $handler_class();
	}

	/**
	 * Hotlink Protection for file types.
	 *
	 * @param bool $is_protected
	 *
	 * @since 1.0.0
	 *
	 * @return bool true on success, false when otherwise!
	 */
	public function protection_config( bool $is_protected = true ): bool {

		$formats = include __DIR__ . '/file-formats.php';

		$videos_option = [
			wpshield_cp_option( 'videos' ),
			wpshield_cp_option( 'videos/disable-hotlink' )
		];
		$images_option = [
			wpshield_cp_option( 'images' ),
			wpshield_cp_option( 'images/disable-hotlink' )
		];
		$audios_option = [
			wpshield_cp_option( 'audios' ),
			wpshield_cp_option( 'audios/disable-hotlink' )
		];

		if ( in_array( 'disable', $images_option, true ) ) {

			unset( $formats['images'] );
		}
		if ( in_array( 'disable', $videos_option, true ) ) {

			unset( $formats['videos'] );
		}
		if ( in_array( 'disable', $audios_option, true ) ) {

			unset( $formats['audios'] );
		}

		//Merge for convert to array flat!
		$formats = array_merge( ...array_values( $formats ) );

		if ( empty( $formats ) ) {

			return false;
		}

		//Before any changes into configuration cleanup wpshield-content-protector configuration!
		$this->cleanup_config();

		if ( $is_protected ) {

			$this->web_server->hotlink_protection_enable( $formats, $exclude_file_names ?? [] );
		}

		return $is_protected;
	}

	/**
	 * Cleanup all changes of configuration.
	 *
	 * Backward Compatible
	 *
	 * @return void
	 */
	private function cleanup_config(): void {

		$formats = include __DIR__ . '/file-formats.php';

		//to remove all types rule pattern => RewriteRule \.(mp4|flv|avi|mov|wmv|avchd|mkv|jpg|jpeg|gif|png|bmp|svg|ogg|mp3|wav)$ - [F]!
		$this->web_server->hotlink_protection_disable( array_merge( ...array_values( $formats ) ) );
		//to remove types without video types rule pattern => RewriteRule \.(jpg|jpeg|gif|png|bmp|svg|ogg|mp3|wav)$ - [F]!
		$this->web_server->hotlink_protection_disable(
			array_merge( $formats['images'], $formats['audios'] )
		);
		//to remove types without image types rule pattern => RewriteRule \.(mp4|flv|avi|mov|wmv|avchd|mkv|ogg|mp3|wav)$ - [F]!
		$this->web_server->hotlink_protection_disable(
			array_merge( $formats['videos'], $formats['audios'] )
		);
		//to remove types without audio types rule pattern => RewriteRule \.(mp4|flv|avi|mov|wmv|avchd|mkv|jpg|jpeg|gif|png|bmp|svg)$ - [F]!
		$this->web_server->hotlink_protection_disable(
			array_merge( $formats['videos'], $formats['images'] )
		);
		//to remove types without audio and video types rule pattern => RewriteRule \.(jpg|jpeg|gif|png|bmp|svg)$ - [F]!
		$this->web_server->hotlink_protection_disable( $formats['images'] );
		//to remove types without audio and image types rule pattern => RewriteRule \.(mp4|flv|avi|mov|wmv|avchd|mkv)$ - [F]!
		$this->web_server->hotlink_protection_disable( $formats['videos'] );
		//to remove types without video and image types rule pattern => RewriteRule \.(ogg|mp3|wav)$ - [F]!
		$this->web_server->hotlink_protection_disable( $formats['audios'] );
	}

	/**
	 * Detect web server.
	 *
	 * @param ApacheConfigManager $apache_config_manager
	 *
	 * @since 1.0.0
	 * @return self|false false on error
	 */
	public function find_web_server( ApacheConfigManager $apache_config_manager ) {

		global $is_apache;

		//FIXME: How to handling nginx webserver?
		if ( $is_apache ) {

			$this->set_web_server( $apache_config_manager );

			return $this;
		}

		return false;
	}

	/**
	 * Setup webserver instance.
	 *
	 * @param WebServerConfig $web_server
	 *
	 * @since 1.0.0
	 */
	public function set_web_server( WebServerConfig $web_server ): void {

		$this->web_server = $web_server;
	}

	/**
	 * @since 1.0.0
	 * @return WebServerConfig
	 */
	public function get_web_server(): WebServerConfig {

		return $this->web_server;
	}
}
