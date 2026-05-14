<?php
/*
* Plugin Name: CDN Cache Plugin
* Description: Clear the cloud cache automatically
* Version: 1.1.3
* License: Proprietary (do not copy)
*
* Uses portion of code from Cloudflare-Wordpress plugin by Cloudflare:
* https://github.com/cloudflare/Cloudflare-WordPress/
*/

if (!defined('ABSPATH') || !is_dir(ABSPATH)) {
	return;
}
/**
* Define the constants in wp-config.php
*/
if (!defined('CDN_SITE_ID') || !defined('CDN_SITE_TOKEN')) {
	return;
}

if (!defined('CDN_HTML_PURGE')) {
	define('CDN_HTML_PURGE', false);
}

if (!defined('CDN_HTML_PURGE_EVERYTHING')) {
	define('CDN_HTML_PURGE_EVERYTHING', false);
}

require_once WPMU_PLUGIN_DIR . '/cdn-cache-management/cdn-cache-management.php';
