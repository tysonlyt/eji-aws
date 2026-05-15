<?php

namespace WPShield\Plugin\ContentProtectorPro\Core\WebServer;

use WPShield\Plugin\ContentProtector\Core\Utils;

/**
 * Class WPSHIELD_CPP_Apache_Config_Manager
 *
 * @since   1.0.0
 *
 * @package WPShield\Plugin\ContentProtectorPro\Core\WebServer
 */
class ApacheConfigManager extends WebServerConfig {

	/**
	 * @var \BF_Htaccess_Editor
	 *
	 * @since 1.0.0
	 */
	protected $editor;

	/**
	 * WPSHIELD_CPP_Apache_Config_Manager constructor.
	 */
	public function __construct() {

		if ( ! class_exists( 'Better_Framework' ) ) {

			return;
		}

		$config_path = $this->htaccess_file_path();
		$file_system = bf_file_system_instance();

		$this->editor = \Better_Framework::factory( 'htaccess-editor' );

		$this->editor->init(
			$file_system->exists( $config_path ) ? $file_system->get_contents( $config_path ) : '',
			'# Content Protector START',
			'# Content Protector STOP'
		);
	}


	/**
	 * Rollback all changes.
	 *
	 * @since 1.0.0
	 * @return bool true on success or false on failure.
	 */
	public function roll_back(): bool {

		$this->editor->remove_context();

		return $this->save();
	}

	/**
	 * Enable hotlink protection.
	 *
	 * @param array $file_types          list of file types to protect.
	 * @param array $excluded_file_names list of file names to exclude of protect.
	 *
	 * @since 1.0.0
	 * @return bool true on success or false on failure.
	 */
	public function hotlink_protection_enable( array $file_types, array $excluded_file_names ): bool {

		$contents = '';

		if ( ! $this->editor->exists( 'RewriteEngine On' ) ) {

			$contents .= "RewriteEngine On\n";
		}

		if ( ! $this->editor->exists( 'RewriteCond %{HTTP_REFERER} !^$' ) ) {

			$contents .= "RewriteCond %{HTTP_REFERER} !^$\n";
		}

		$hosts = class_exists( Utils::class ) ? Utils::get_hosts() : [];

		$_contents = '';

		foreach ( $hosts as $host ) {

			$_contents .= "RewriteCond %{HTTP_REFERER} !^https?://(www\.)?$host/.*$ [NC]\n";
		}

		if ( $_contents && ! $this->editor->exists( $_contents ) ) {

			$contents .= $_contents;
		}

		if ( ! empty( $excluded_file_names ) ) {

			$_contents = 'RewriteRule ^(?!.*' . implode( '|.*', $excluded_file_names ) . ').*';
			$_contents .= '\.(' . implode( '|', $file_types ) . ')$ - [F]';

		} else {

			$_contents = 'RewriteRule \.(' . implode( '|', $file_types ) . ')$ - [F]';
		}

		$_contents .= "\n";

		if ( ! $this->editor->exists( $_contents ) ) {

			$contents .= $_contents;
		}

		if ( $contents ) {

			$this->editor->append_inside_condition( "\n$contents\n" );

			return $this->save();
		}

		return true;
	}


	/**
	 * Drop hotlink protection configs.
	 *
	 * @param array $file_types list of file types to protect.
	 *
	 * @since 1.0.0
	 * @return bool true on success or false on failure.
	 */
	public function hotlink_protection_disable( array $file_types ): bool {

		$this->editor->remove( 'RewriteCond %{HTTP_REFERER} !^$' );

		foreach ( Utils::get_hosts() as $host ) {

			$this->editor->remove( 'RewriteCond %{HTTP_REFERER} !^https?://(www\.)?' . $host . '/.*$ [NC]' );
		}

		$this->editor->remove( 'RewriteRule \.(' . implode( '|', $file_types ) . ')$ - [F]' );

		return $this->save();
	}

	/**
	 * @since 1.0.0
	 * @return bool|\WP_Error true on success or WP_Error|false on error.
	 */
	protected function save() {

		$content     = $this->editor->apply();
		$file_system = bf_file_system_instance();
		$config_path = $this->htaccess_file_path();

		if ( $file_system->exists( $config_path ) ) {

			if ( ! $file_system->is_writable( $config_path ) ) {
				return new \WP_Error(
					'write_error',
					__( 'Cannot update .htaccess file. please update .htaccess file with the following contents.', 'wpshield-content-protector' ),
					[
						'contents' => $content,
						'path'     => $config_path,
					]
				);
			}

			return $file_system->put_contents( $config_path, $content );
		}

		if ( ! $file_system->put_contents( $config_path, $content ) ) {

			return new \WP_Error(
				'write_error',
				__( 'Cannot create .htaccess file. please create .htaccess file and insert the following contents.', 'wpshield-content-protector' ),
				[
					'contents' => $content,
					'path'     => $config_path,
				]
			);
		}

		return true;
	}

	/**
	 * Get absolute path to the htaccess file.
	 *
	 * @since 3.9.1
	 * @return string
	 */
	public function htaccess_file_path(): string {

		if ( ! function_exists( 'get_home_path' ) ) {
			require ABSPATH . '/wp-admin/includes/file.php';
		}

		return get_home_path() . '.htaccess';
	}
}
