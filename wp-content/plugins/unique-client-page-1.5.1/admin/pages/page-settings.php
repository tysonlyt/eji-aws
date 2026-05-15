<?php
/**
 * Page Settings for Unique Client Page
 * 
 * @package Unique_Client_Page
 * @since 1.0.0
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

class UCP_Page_Settings {
    /**
     * Constructor
     */
    public function __construct() {
        add_action('add_meta_boxes', array($this, 'add_meta_boxes'));
        add_action('save_post', array($this, 'save_meta_box_data'));
    }

    /**
     * Add meta boxes
     */
    public function add_meta_boxes() {
        add_meta_box(
            'ucp_page_settings',
            __('Page Settings', 'unique-client-page'),
            array($this, 'render_meta_box'),
            'page',
            'side',
            'default'
        );
    }

    /**
     * Render meta box
     */
    public function render_meta_box($post) {
        // Add nonce for security and authentication
        wp_nonce_field('ucp_save_page_settings', 'ucp_page_settings_nonce');
        
        // Get saved values
        $sales_person = get_post_meta($post->ID, '_ucp_sales_person', true);
        $sales_email = get_post_meta($post->ID, '_ucp_sales_email', true);
        ?>
        <div class="ucp-meta-box">
            <p>
                <label for="ucp_sales_person" style="display: block; margin-bottom: 5px; font-weight: 600;">
                    <?php _e('Salesperson Name', 'unique-client-page'); ?>
                </label>
                <input type="text" id="ucp_sales_person" name="ucp_sales_person" 
                       value="<?php echo esc_attr($sales_person); ?>" class="widefat">
            </p>
            <p>
                <label for="ucp_sales_email" style="display: block; margin-bottom: 5px; font-weight: 600;">
                    <?php _e('Sales Email', 'unique-client-page'); ?>
                </label>
                <input type="email" id="ucp_sales_email" name="ucp_sales_email" 
                       value="<?php echo esc_attr($sales_email); ?>" class="widefat">
            </p>
        </div>
        <?php
    }

    /**
     * Save meta box data
     */
    public function save_meta_box_data($post_id) {
        // Check if nonce is set
        if (!isset($_POST['ucp_page_settings_nonce'])) {
            return;
        }

        // Verify nonce
        if (!wp_verify_nonce($_POST['ucp_page_settings_nonce'], 'ucp_save_page_settings')) {
            return;
        }

        // Check if autosave
        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
            return;
        }

        // Check user capabilities
        if (!current_user_can('edit_page', $post_id)) {
            return;
        }

        // Save sales person
        if (isset($_POST['ucp_sales_person'])) {
            update_post_meta($post_id, '_ucp_sales_person', sanitize_text_field($_POST['ucp_sales_person']));
        }

        // Save sales email
        if (isset($_POST['ucp_sales_email'])) {
            update_post_meta($post_id, '_ucp_sales_email', sanitize_email($_POST['ucp_sales_email']));
        }
    }
}

// Initialize the class
new UCP_Page_Settings();
