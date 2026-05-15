<?php
/**
 * Email Settings Admin Page
 */

// Prevent direct file access
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Render email settings page
 */
function ucp_render_email_settings_page() {
    // Return if no permission
    if (!current_user_can('manage_options')) {
        return;
    }
    
    // Initial settings values
    $settings = get_option('ucp_email_settings', array(
        'smtp_enabled'      => 0,
        'smtp_host'         => '',
        'smtp_port'         => 587,
        'smtp_encryption'   => 'tls',
        'smtp_auth'         => 1,
        'smtp_username'     => '',
        'smtp_password'     => '',
        'from_email'        => '',
        'from_name'         => get_bloginfo('name'),
    ));
    
    // Handle form submission
    $message = '';
    if (isset($_POST['ucp_save_email_settings'])) {
        // Verify nonce
        check_admin_referer('ucp_email_settings_nonce');
        
        // Collect form data
        $settings['smtp_enabled']    = isset($_POST['smtp_enabled']) ? 1 : 0;
        $settings['smtp_host']       = sanitize_text_field($_POST['smtp_host']);
        $settings['smtp_port']       = absint($_POST['smtp_port']);
        $settings['smtp_encryption'] = sanitize_text_field($_POST['smtp_encryption']);
        $settings['smtp_auth']       = isset($_POST['smtp_auth']) ? 1 : 0;
        $settings['smtp_username']   = sanitize_text_field($_POST['smtp_username']);
        
        // Password handling - only update if new input
        if (!empty($_POST['smtp_password'])) {
            $settings['smtp_password'] = sanitize_text_field($_POST['smtp_password']);
        }
        
        $settings['from_email']      = sanitize_email($_POST['from_email']);
        $settings['from_name']       = sanitize_text_field($_POST['from_name']);
        
        // Save settings
        update_option('ucp_email_settings', $settings);
        $message = '<div class="notice notice-success"><p>' . __('Email settings saved.', 'unique-client-page') . '</p></div>';
    }
    
    // Test email sending
    if (isset($_POST['ucp_test_email'])) {
        // Verify nonce
        check_admin_referer('ucp_email_settings_nonce');
        
        // Get test email
        $test_email = sanitize_email($_POST['test_email']);
        
        if (empty($test_email)) {
            $message = '<div class="notice notice-error"><p>' . __('Please enter a valid test email address.', 'unique-client-page') . '</p></div>';
        } else {
            // Initialize mailer class
            $mailer = new UCP_Mailer();
            
            // Test email content
            $subject = sprintf(__('[Test Email] %s', 'unique-client-page'), get_bloginfo('name'));
            $body = sprintf(
                __('<p>This is a test email from %s website.</p><p>If you received this email, your SMTP settings are configured correctly.</p><p>Sent time: %s</p>', 'unique-client-page'),
                get_bloginfo('name'),
                current_time('mysql')
            );
            
            // Test SMTP connection if enabled
            $debug_info = '';
            $error_details = '';
            $error_hints = '';
            
            if ($settings['smtp_enabled'] && method_exists($mailer, 'test_smtp_connection')) {
                $connection_test = $mailer->test_smtp_connection();
                $debug_info .= '<br><strong>SMTP Connection Test:</strong> ' . 
                    ($connection_test['success'] ? 'Success' : 'Failed');
                
                if (!$connection_test['success']) {
                    $error_details = $connection_test['message'];
                }
            }
            
            // Send test email
            $result = $mailer->send($test_email, $subject, $body);
            
            // Get detailed error information if available
            if (!$result && method_exists($mailer, 'get_last_error')) {
                if (empty($error_details)) {
                    $error_details = $mailer->get_last_error();
                }
            }
            
            // Generate user-friendly error hints
            if (!$result && !empty($error_details)) {
                // Common error patterns and helpful advice
                if (strpos($error_details, 'connect() failed') !== false || 
                    strpos($error_details, 'Connection could not be established') !== false) {
                    $error_hints = __('<strong>Connection Failed:</strong> Please check that your SMTP host and port are correct. Your server may be blocking outgoing connections on this port.', 'unique-client-page');
                } 
                elseif (strpos($error_details, 'authentication failed') !== false || 
                       strpos($error_details, 'Incorrect authentication data') !== false || 
                       strpos($error_details, '535') !== false) {
                    $error_hints = __('<strong>Authentication Failed:</strong> Your username or password appears to be incorrect. Some providers require app-specific passwords instead of your regular account password.', 'unique-client-page');
                }
                elseif (strpos($error_details, 'certificate') !== false || 
                       strpos($error_details, 'SSL') !== false) {
                    $error_hints = __('<strong>SSL/TLS Error:</strong> There was a problem with the encryption. Try changing the encryption type or using a different port.', 'unique-client-page');
                }
                elseif (strpos($error_details, 'timed out') !== false) {
                    $error_hints = __('<strong>Connection Timeout:</strong> The server took too long to respond. Check your connection and server settings.', 'unique-client-page');
                }
            }
            
            // Generate appropriate message based on result
            if ($result) {
                $message = '<div class="notice notice-success"><p>' . 
                    __('Test email sent successfully! Please check the specified mailbox.', 'unique-client-page') . 
                    '</p></div>';
            } else {
                $error_message = __('Test email sending failed.', 'unique-client-page');
                
                if (!empty($error_details)) {
                    // Format for better readability
                    $formatted_error = '<div style="margin-top:10px;padding:10px;background:#f8f8f8;border-left:4px solid #dc3232;font-family:monospace;overflow-x:auto;">' . 
                        esc_html($error_details) . 
                        '</div>';
                } else {
                    $formatted_error = '';
                }
                
                $message = '<div class="notice notice-error"><p>' . $error_message . '</p>';
                
                if (!empty($error_hints)) {
                    $message .= '<p>' . $error_hints . '</p>';
                }
                
                if (!empty($debug_info)) {
                    $message .= '<p>' . $debug_info . '</p>';
                }
                
                if (!empty($formatted_error)) {
                    $message .= '<p><strong>' . __('Technical Details:', 'unique-client-page') . '</strong>' . $formatted_error . '</p>';
                    
                    $message .= '<p><a href="' . admin_url('admin.php?page=ucp-email-settings&show_debug=1') . '" class="button">' . 
                        __('View Debug Log', 'unique-client-page') . 
                        '</a></p>';
                }
                
                $message .= '</div>';
                
                // Log the error for debugging
                if (!empty($error_details)) {
                    $log_entry = '[' . current_time('mysql') . '] Test email failed: ' . $error_details . "\n";
                    error_log($log_entry, 3, WP_CONTENT_DIR . '/debug-smtp.log');
                }
            }
        }
    }
    
    // Display debug log if requested
    if (isset($_GET['show_debug']) && current_user_can('manage_options')) {
        $log_file = WP_CONTENT_DIR . '/debug-smtp.log';
        if (file_exists($log_file)) {
            $log_content = file_get_contents($log_file);
            $message .= '<div class="notice notice-info"><h3>' . __('Debug Log', 'unique-client-page') . '</h3>';
            $message .= '<pre style="background:#f8f8f8;padding:10px;max-height:400px;overflow-y:auto;">' . 
                esc_html($log_content) . 
                '</pre>';
            $message .= '<p><a href="' . admin_url('admin.php?page=ucp-email-settings&clear_log=1') . '" class="button">' . 
                __('Clear Log', 'unique-client-page') . 
                '</a></p>';
            $message .= '</div>';
        } else {
            $message .= '<div class="notice notice-info"><p>' . __('Debug log file not found.', 'unique-client-page') . '</p></div>';
        }
    }
    
    // Clear debug log if requested
    if (isset($_GET['clear_log']) && current_user_can('manage_options')) {
        $log_file = WP_CONTENT_DIR . '/debug-smtp.log';
        if (file_exists($log_file)) {
            @unlink($log_file);
            $message .= '<div class="notice notice-success"><p>' . __('Debug log cleared successfully.', 'unique-client-page') . '</p></div>';
        }
    }
    
    // Begin page output
    ?>
    <div class="wrap">
        <h1><?php echo esc_html__('Email Settings', 'unique-client-page'); ?></h1>
        <?php echo $message; ?>
        
        <form method="post" action="">
            <?php wp_nonce_field('ucp_email_settings_nonce'); ?>
            
            <h2><?php echo esc_html__('SMTP Settings', 'unique-client-page'); ?></h2>
            <p class="description"><?php echo esc_html__('Configure WordPress to send emails via SMTP to improve email delivery success rate.', 'unique-client-page'); ?></p>
            
            <table class="form-table">
                <tr>
                    <th scope="row"><?php echo esc_html__('Enable SMTP', 'unique-client-page'); ?></th>
                    <td>
                        <label for="smtp_enabled">
                            <input type="checkbox" id="smtp_enabled" name="smtp_enabled" value="1" <?php checked(1, $settings['smtp_enabled']); ?> />
                            <?php echo esc_html__('Use SMTP to send emails instead of the default PHP mail()', 'unique-client-page'); ?>
                        </label>
                    </td>
                </tr>
                
                <tr>
                    <th scope="row"><?php echo esc_html__('SMTP Host', 'unique-client-page'); ?></th>
                    <td>
                        <input type="text" id="smtp_host" name="smtp_host" class="regular-text" value="<?php echo esc_attr($settings['smtp_host']); ?>" />
                        <p class="description"><?php echo esc_html__('Example: smtp.gmail.com', 'unique-client-page'); ?></p>
                    </td>
                </tr>
                
                <tr>
                    <th scope="row"><?php echo esc_html__('SMTP Port', 'unique-client-page'); ?></th>
                    <td>
                        <input type="number" id="smtp_port" name="smtp_port" class="small-text" value="<?php echo esc_attr($settings['smtp_port']); ?>" />
                        <p class="description"><?php echo esc_html__('Common ports: 587 (for TLS), 465 (for SSL), or 25 (no encryption)', 'unique-client-page'); ?></p>
                    </td>
                </tr>
                
                <tr>
                    <th scope="row"><?php echo esc_html__('Encryption Type', 'unique-client-page'); ?></th>
                    <td>
                        <select id="smtp_encryption" name="smtp_encryption">
                            <option value="" <?php selected('', $settings['smtp_encryption']); ?>><?php echo esc_html__('None', 'unique-client-page'); ?></option>
                            <option value="tls" <?php selected('tls', $settings['smtp_encryption']); ?>><?php echo esc_html__('TLS', 'unique-client-page'); ?></option>
                            <option value="ssl" <?php selected('ssl', $settings['smtp_encryption']); ?>><?php echo esc_html__('SSL', 'unique-client-page'); ?></option>
                        </select>
                    </td>
                </tr>
                
                <tr>
                    <th scope="row"><?php echo esc_html__('Authentication', 'unique-client-page'); ?></th>
                    <td>
                        <label for="smtp_auth">
                            <input type="checkbox" id="smtp_auth" name="smtp_auth" value="1" <?php checked(1, $settings['smtp_auth']); ?> />
                            <?php echo esc_html__('Use authentication', 'unique-client-page'); ?>
                        </label>
                    </td>
                </tr>
                
                <tr>
                    <th scope="row"><?php echo esc_html__('Username', 'unique-client-page'); ?></th>
                    <td>
                        <input type="text" id="smtp_username" name="smtp_username" class="regular-text" value="<?php echo esc_attr($settings['smtp_username']); ?>" />
                    </td>
                </tr>
                
                <tr>
                    <th scope="row"><?php echo esc_html__('Password', 'unique-client-page'); ?></th>
                    <td>
                        <input type="password" id="smtp_password" name="smtp_password" class="regular-text" value="" autocomplete="new-password" />
                        <p class="description"><?php echo esc_html__('Leave empty if not changing password', 'unique-client-page'); ?></p>
                    </td>
                </tr>
            </table>
            
            <h2><?php echo esc_html__('Sender Information', 'unique-client-page'); ?></h2>
            <table class="form-table">
                <tr>
                    <th scope="row"><?php echo esc_html__('From Email', 'unique-client-page'); ?></th>
                    <td>
                        <input type="email" id="from_email" name="from_email" class="regular-text" value="<?php echo esc_attr($settings['from_email']); ?>" />
                    </td>
                </tr>
                
                <tr>
                    <th scope="row"><?php echo esc_html__('From Name', 'unique-client-page'); ?></th>
                    <td>
                        <input type="text" id="from_name" name="from_name" class="regular-text" value="<?php echo esc_attr($settings['from_name']); ?>" />
                    </td>
                </tr>
            </table>
            
            <p class="submit">
                <input type="submit" name="ucp_save_email_settings" class="button-primary" value="<?php echo esc_attr__('Save Settings', 'unique-client-page'); ?>" />
            </p>
        </form>
        
        <h2><?php echo esc_html__('Test Email', 'unique-client-page'); ?></h2>
        <p class="description"><?php echo esc_html__('Send a test email to verify your SMTP settings are correct.', 'unique-client-page'); ?></p>
        
        <form method="post" action="">
            <?php wp_nonce_field('ucp_email_settings_nonce'); ?>
            
            <table class="form-table">
                <tr>
                    <th scope="row"><?php echo esc_html__('Test Email Address', 'unique-client-page'); ?></th>
                    <td>
                        <input type="email" id="test_email" name="test_email" class="regular-text" value="" required />
                    </td>
                </tr>
            </table>
            
            <p class="submit">
                <input type="submit" name="ucp_test_email" class="button-secondary" value="<?php echo esc_attr__('Send Test Email', 'unique-client-page'); ?>" />
            </p>
        </form>
    </div>
    <?php
}
