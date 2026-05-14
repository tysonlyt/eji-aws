<?php
/*
* Plugin Name: Activity Log Plugin
* Description: Monitor and track your WordPress website activity
* Version: 1.0.0 
* License: Proprietary (do not copy)
* 
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


require_once WPMU_PLUGIN_DIR . '/activity-log-plugin/activity-log-plugin.php';
