<?php

namespace WPShield\Plugin\ContentProtectorPro\Core\WebServer;

/**
 * Class WPSHIELD_CPP_Web_Server_Configuration
 *
 * @since   1.0.0
 *
 * @package WPShield\Plugin\ContentProtectorPro\Core\WebServer
 */
abstract class WebServerConfig {

	/**
	 * Enable hotlink protection.
	 *
	 * @param array $file_types list of file types to protect.
	 * @param array $excluded_file_names list of file names to exclude of protect.
	 *
	 * @return bool true on success or false on failure.
	 */
	abstract public function hotlink_protection_enable( array $file_types, array $excluded_file_names ): bool;


	/**
	 * Drop hotlink protection configs.
	 *
	 * @param array $file_types list of file types to protect.
	 *
	 * @return bool true on success or false on failure.
	 */
	abstract public function hotlink_protection_disable( array $file_types ): bool;


	/**
	 * Rollback all changes.
	 *
	 * @return bool true on success or false on failure.
	 */
	abstract public function roll_back(): bool;
}
