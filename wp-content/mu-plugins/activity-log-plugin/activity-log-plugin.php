<?php

/*
 * Plugin Name: Activity Log Plugin
 * Description: Monitor and track your WordPress website activity
 * Version: 1.0.0
 * License: Proprietary (do not copy)
 * Author: wpexperts
 * Author URI: https://wpexperts.io/
 * Text Domain:  activity-log
 * 
 * Uses portion of code from Cloudflare-Wordpress plugin by Cloudflare:
 * https://github.com/onrocketdotcom/activity-log-plugin
*/

defined( 'ABSPATH' ) || exit;

if (!defined('WP_ACTIVITY_LOG_FILE')) {
    define('WP_ACTIVITY_LOG_FILE', plugin_dir_path(__FILE__));
}

if (!defined('WP_ACTIVITY_LOG_URL')) {
    define('WP_ACTIVITY_LOG_URL', plugin_dir_url(__FILE__));
}



/**
 * The main cdn activity log class.
 *
 * @since 1.0
 */
class WP_CDN_Activity_Log_Plugin {
    /**
     * @var WP_CDN_Activity_Log_Plugin
     */
    public static $instance;

    public function __construct()
    {
        
        require_once WP_ACTIVITY_LOG_FILE . 'includes/index.php';

    }

   
    /**
     * WP_CDN_Activity_Log_Plugin instance
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

WP_CDN_Activity_Log_Plugin::get_instance();