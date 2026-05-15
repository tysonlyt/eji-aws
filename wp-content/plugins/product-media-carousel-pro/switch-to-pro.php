<?php
/**
 * Switch to Pro Version
 * Run this file once to enable Pro features
 * 
 * Usage: 
 * 1. Access via browser: http://localhost/plugin-test/wp-content/plugins/product-media-carousel/switch-to-pro.php
 * 2. Or run via WP-CLI: wp eval-file switch-to-pro.php
 */

// Load WordPress
require_once('../../../../../wp-load.php');

// Check if user is admin
if (!current_user_can('manage_options')) {
    die('Error: You must be an administrator to run this script.');
}

// Enable Pro version
update_option('pmc_is_pro', true);

// Clear any caches
if (function_exists('wp_cache_flush')) {
    wp_cache_flush();
}

?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Switch to Pro - Product Media Carousel</title>
    <style>
        body {
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Oxygen-Sans, Ubuntu, Cantarell, "Helvetica Neue", sans-serif;
            background: #f0f0f1;
            margin: 0;
            padding: 50px 20px;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            background: #fff;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            padding: 40px;
        }
        .success {
            background: #d4edda;
            border-left: 4px solid #28a745;
            padding: 20px;
            border-radius: 4px;
            margin: 20px 0;
        }
        .success h2 {
            margin: 0 0 10px 0;
            color: #155724;
        }
        .success p {
            margin: 5px 0;
            color: #155724;
        }
        .features {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 4px;
            margin: 20px 0;
        }
        .features h3 {
            margin: 0 0 15px 0;
            color: #1d2327;
        }
        .features ul {
            margin: 0;
            padding-left: 20px;
        }
        .features li {
            margin: 8px 0;
            color: #50575e;
        }
        .features li:before {
            content: "✓ ";
            color: #28a745;
            font-weight: bold;
            margin-right: 5px;
        }
        .button {
            display: inline-block;
            background: #2271b1;
            color: #fff;
            padding: 12px 24px;
            text-decoration: none;
            border-radius: 4px;
            font-weight: 600;
            margin: 10px 10px 0 0;
        }
        .button:hover {
            background: #135e96;
        }
        .button-secondary {
            background: #50575e;
        }
        .button-secondary:hover {
            background: #3c434a;
        }
        h1 {
            color: #1d2327;
            margin: 0 0 20px 0;
        }
        .emoji {
            font-size: 48px;
            text-align: center;
            margin: 20px 0;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="emoji">🎉</div>
        <h1 style="text-align: center;">Pro Version Activated!</h1>
        
        <div class="success">
            <h2>✓ Successfully Switched to Pro</h2>
            <p><strong>Status:</strong> Pro features are now enabled</p>
            <p><strong>Version:</strong> <?php echo defined('PMC_VERSION') ? PMC_VERSION : '1.2.0'; ?></p>
        </div>
        
        <div class="features">
            <h3>🚀 Pro Features Now Available:</h3>
            <ul style="list-style: none; padding-left: 0;">
                <li>Unlimited YouTube videos per product</li>
                <li>Vimeo video support</li>
                <li>Self-hosted video uploads</li>
                <li>Bulk import via CSV</li>
                <li>Manual bulk input</li>
                <li>Product search functionality</li>
                <li>5 carousel effects (Slide, Fade, Cube, Coverflow, Flip)</li>
                <li>5 navigation styles</li>
                <li>Advanced customization options</li>
                <li>Priority support (24-hour response)</li>
            </ul>
        </div>
        
        <div style="text-align: center; margin-top: 30px;">
            <a href="<?php echo admin_url('admin.php?page=pmc-bulk-import'); ?>" class="button">
                Go to Bulk Import
            </a>
            <a href="<?php echo admin_url('plugins.php'); ?>" class="button button-secondary">
                View Plugins
            </a>
        </div>
        
        <div style="margin-top: 30px; padding-top: 20px; border-top: 1px solid #ddd; text-align: center; color: #646970; font-size: 14px;">
            <p><strong>Note:</strong> To switch back to Free version, run <code>switch-to-free.php</code></p>
        </div>
    </div>
</body>
</html>
