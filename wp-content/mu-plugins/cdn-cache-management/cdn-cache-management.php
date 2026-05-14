<?php

/*
 * Plugin Name: CDN Cache Wordpress
 * Description: Clear the cloud cache automatically
 * Version: 1.0.0
 * License: Proprietary (do not copy)
 * Author: wpexperts
 * Author URI: https://wpexperts.io/
 * Text Domain:  cdn-cache-wp
 *
 * Uses portion of code from Cloudflare-Wordpress plugin by Cloudflare:
 * https://github.com/cloudflare/Cloudflare-WordPress/
*/



defined( 'ABSPATH' ) || exit;

if (!defined('WP_CDN_CACHE_FILE')) {
    define('WP_CDN_CACHE_FILE', plugin_dir_path(__FILE__));
}

if (!defined('WP_CDN_CACHE_URL')) {
    define('WP_CDN_CACHE_URL', plugin_dir_url(__FILE__));
}



/**
 * The main wc cdn class.
 *
 * @since 1.0
 */
class WP_CDN_Cache_Plugin {
    /**
     * @var WP_CDN_Cache_Plugin
     */
    public static $instance;

    public function __construct()
    {
        
        require_once WP_CDN_CACHE_FILE . 'includes/index.php';

    }

   
    /**
     * WP_CDN_Cache_Plugin instance
     *
     * @return object
     */
    public static function get_instance()
    {
        if (!isset(self::$instance) || is_null(self::$instance))
            self::$instance = new self();

        return self::$instance;
    }

}

WP_CDN_Cache_Plugin::get_instance();