<?php
/**
 * Wishlist Manager Component
 * 
 * Handles all wishlist related functionality
 *
 * @package Unique_Client_Page
 * @since 1.3.2
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Wishlist Manager Class
 */
class UCP_Wishlist_Manager {
    /**
     * Component instance
     *
     * @var UCP_Wishlist_Manager
     */
    private static $instance = null;
    
    /**
     * Get singleton instance
     *
     * @return UCP_Wishlist_Manager Component instance
     */
    public static function get_instance() {
        if (null === self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    /**
     * Constructor
     */
    private function __construct() {
        // Initialization code
    }
    
    /**
     * Register hooks
     */
    public function register_hooks() {
        // Register wishlist related hooks
        // NOTE: Duplicate AJAX registrations disabled to avoid conflicts.
        // Canonical handlers are centralized in `frontend/classes/class-ucp-ajax-handler.php` (UCP_AJAX_Handler)
        // and admin-side in `admin/modules/wishlist/class-wishlist-admin.php` (UCP_Wishlist_Admin).
        // The following add_action calls are intentionally commented out for de-duplication.
        //
        // add_action('wp_ajax_ucp_create_wishlist_version', array($this, 'create_wishlist_version')); // Deprecated: handled by UCP_AJAX_Handler
        // add_action('wp_ajax_ucp_get_wishlist_versions', array($this, 'get_wishlist_versions'));     // Deprecated: handled by UCP_AJAX_Handler
        // add_action('wp_ajax_ucp_get_wishlist_version', array($this, 'get_wishlist_version'));       // Deprecated: handled by UCP_AJAX_Handler
        // add_action('wp_ajax_ucp_set_current_wishlist_version', array($this, 'set_current_wishlist_version')); // Deprecated: handled by UCP_AJAX_Handler
        
        // Shortcode for wishlist pages list
        add_shortcode('ucp_wishlist_pages', array($this, 'render_wishlist_pages_list'));
    }
    
    /**
     * Check if product is in wishlist
     *
     * @param int $product_id Product ID
     * @return bool Whether product is in wishlist
     */
    public function is_product_in_wishlist($product_id) {
        if (class_exists('TInvWL_Public_AddToWishlist')) {
            $wishlist = new TInvWL_Public_AddToWishlist();
            return $wishlist->is_product_in_wishlist($product_id);
        }
        return false;
    }
    
    /**
     * Get user wishlist data
     *
     * @param int $user_id User ID
     * @param int $page_id Page ID
     * @return array|null Wishlist data or null if not found
     */
    private function get_user_wishlist_data($user_id, $page_id) {
        if (!$user_id || !$page_id) {
            return null;
        }
        
        // Get wishlist products from user meta
        $meta_key = 'ucp_wishlist_' . $page_id;
        $wishlist_data = get_user_meta($user_id, $meta_key, true);
        
        // If no data found, return null
        if (empty($wishlist_data)) {
            return null;
        }
        
        // Ensure data is in the correct format
        if (!is_array($wishlist_data)) {
            $wishlist_data = maybe_unserialize($wishlist_data);
            if (!is_array($wishlist_data)) {
                return array();
            }
        }
        
        return $wishlist_data;
    }
    
    /**
     * Create a new wishlist version
     * 
     * Handles AJAX request to create a new wishlist version
     */
    public function create_wishlist_version() {
        // Verify nonce
        if (!isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'ucp_ajax_nonce')) {
            wp_send_json_error(array('message' => __('Security check failed', 'unique-client-page')));
        }
        
        // Get parameters
        $user_id = isset($_POST['user_id']) ? intval($_POST['user_id']) : get_current_user_id();
        $page_id = isset($_POST['page_id']) ? intval($_POST['page_id']) : 0;
        $version_name = isset($_POST['version_name']) ? sanitize_text_field($_POST['version_name']) : '';
        $notes = isset($_POST['notes']) ? sanitize_textarea_field($_POST['notes']) : '';
        
        if (!$page_id) {
            wp_send_json_error(array('message' => __('Invalid page ID', 'unique-client-page')));
        }
        
        // Get current wishlist data
        $wishlist_data = $this->get_user_wishlist_data($user_id, $page_id);
        
        if (empty($wishlist_data)) {
            wp_send_json_error(array('message' => __('No wishlist data found', 'unique-client-page')));
        }
        
        global $wpdb;
        
        // Get current version number for this user and page
        $current_version = $wpdb->get_var($wpdb->prepare(
            "SELECT MAX(version_number) FROM {$wpdb->prefix}ucp_wishlist_versions WHERE user_id = %d AND page_id = %d",
            $user_id,
            $page_id
        ));
        
        // If no versions exist yet, start with version 1
        $new_version_number = $current_version ? $current_version + 1 : 1;
        
        // If version name is empty, create a default one
        if (empty($version_name)) {
            $version_name = sprintf(__('Version %d', 'unique-client-page'), $new_version_number);
        }
        
        // Reset 'is_current' flag for all versions
        $wpdb->update(
            $wpdb->prefix . 'ucp_wishlist_versions',
            array('is_current' => 0),
            array('user_id' => $user_id, 'page_id' => $page_id),
            array('%d'),
            array('%d', '%d')
        );
        
        // Insert new wishlist version
        $result = $wpdb->insert(
            $wpdb->prefix . 'ucp_wishlist_versions',
            array(
                'user_id' => $user_id,
                'page_id' => $page_id,
                'version_number' => $new_version_number,
                'version_name' => $version_name,
                'wishlist_data' => maybe_serialize($wishlist_data),
                'created_by' => get_current_user_id(),
                'created_at' => current_time('mysql'),
                'is_current' => 1,
                'notes' => $notes
            ),
            array('%d', '%d', '%d', '%s', '%s', '%d', '%s', '%d', '%s')
        );
        
        if ($result === false) {
            wp_send_json_error(array(
                'message' => __('Failed to create wishlist version', 'unique-client-page'),
                'debug' => $wpdb->last_error
            ));
        }
        
        $version_id = $wpdb->insert_id;
        
        wp_send_json_success(array(
            'message' => __('Wishlist version created successfully', 'unique-client-page'),
            'version_id' => $version_id,
            'version_number' => $new_version_number,
            'version_name' => $version_name
        ));
    }
    
    /**
     * Get all wishlist versions
     * 
     * Handles AJAX request to get all wishlist versions for a user and page
     */
    public function get_wishlist_versions() {
        // Verify nonce
        if (!isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'ucp_ajax_nonce')) {
            wp_send_json_error(array('message' => __('Security check failed', 'unique-client-page')));
        }
        
        // Get parameters
        $user_id = isset($_POST['user_id']) ? intval($_POST['user_id']) : get_current_user_id();
        $page_id = isset($_POST['page_id']) ? intval($_POST['page_id']) : 0;
        
        if (!$page_id) {
            wp_send_json_error(array('message' => __('Invalid page ID', 'unique-client-page')));
        }
        
        // Get versions from database
        global $wpdb;
        $versions = $wpdb->get_results($wpdb->prepare(
            "SELECT version_id, user_id, page_id, version_number, version_name, created_by, created_at, is_current, notes 
            FROM {$wpdb->prefix}ucp_wishlist_versions 
            WHERE user_id = %d AND page_id = %d 
            ORDER BY version_number DESC",
            $user_id,
            $page_id
        ));
        
        if ($versions === false) {
            wp_send_json_error(array(
                'message' => __('Failed to fetch wishlist versions', 'unique-client-page'),
                'debug' => $wpdb->last_error
            ));
        }
        
        // Format created_at date
        foreach ($versions as &$version) {
            $version->created_at = date('d/m/Y', strtotime($version->created_at));
            
            // Get creator info
            $creator = get_user_by('id', $version->created_by);
            $version->creator_name = $creator ? $creator->display_name : __('Unknown', 'unique-client-page');
        }
        
        wp_send_json_success(array(
            'versions' => $versions,
            'count' => count($versions)
        ));
    }
    
    /**
     * Get a specific wishlist version
     * 
     * Handles AJAX request to get a specific wishlist version by ID
     */
    public function get_wishlist_version() {
        // Verify nonce
        if (!isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'ucp_ajax_nonce')) {
            wp_send_json_error(array('message' => __('Security check failed', 'unique-client-page')));
        }
        
        // Get version ID
        $version_id = isset($_POST['version_id']) ? intval($_POST['version_id']) : 0;
        
        if (!$version_id) {
            wp_send_json_error(array('message' => __('Invalid version ID', 'unique-client-page')));
        }
        
        global $wpdb;
        
        // Get version data
        $version = $wpdb->get_row($wpdb->prepare(
            "SELECT * FROM {$wpdb->prefix}ucp_wishlist_versions WHERE version_id = %d",
            $version_id
        ));
        
        if (!$version) {
            wp_send_json_error(array('message' => __('Wishlist version not found', 'unique-client-page')));
        }
        
        // Unserialize the wishlist data
        $wishlist_data = maybe_unserialize($version->wishlist_data);
        
        // Format created_at date
        $version->created_at = date('d/m/Y', strtotime($version->created_at));
        
        // Get creator info
        $creator = get_user_by('id', $version->created_by);
        $creator_name = $creator ? $creator->display_name : __('Unknown', 'unique-client-page');
        
        wp_send_json_success(array(
            'version' => $version,
            'creator_name' => $creator_name,
            'wishlist_data' => $wishlist_data
        ));
    }
    
    /**
     * Set the current wishlist version
     * 
     * Handles AJAX request to set a specific wishlist version as current
     */
    public function set_current_wishlist_version() {
        // Verify nonce
        if (!isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'ucp_ajax_nonce')) {
            wp_send_json_error(array('message' => __('Security check failed', 'unique-client-page')));
        }
        
        // Get version ID
        $version_id = isset($_POST['version_id']) ? intval($_POST['version_id']) : 0;
        
        if (!$version_id) {
            wp_send_json_error(array('message' => __('Invalid version ID', 'unique-client-page')));
        }
        
        global $wpdb;
        
        // Get version data to verify it exists and to get user_id and page_id
        $version = $wpdb->get_row($wpdb->prepare(
            "SELECT * FROM {$wpdb->prefix}ucp_wishlist_versions WHERE version_id = %d",
            $version_id
        ));
        
        if (!$version) {
            wp_send_json_error(array('message' => __('Wishlist version not found', 'unique-client-page')));
        }
        
        // Start transaction
        $wpdb->query('START TRANSACTION');
        
        try {
            // Reset 'is_current' flag for all versions for this user and page
            $updated = $wpdb->update(
                $wpdb->prefix . 'ucp_wishlist_versions',
                array('is_current' => 0),
                array('user_id' => $version->user_id, 'page_id' => $version->page_id),
                array('%d'),
                array('%d', '%d')
            );
            
            if ($updated === false) {
                throw new Exception(__('Failed to update version status', 'unique-client-page'));
            }
            
            // Set the selected version as current
            $updated = $wpdb->update(
                $wpdb->prefix . 'ucp_wishlist_versions',
                array('is_current' => 1),
                array('version_id' => $version_id),
                array('%d'),
                array('%d')
            );
            
            if ($updated === false) {
                throw new Exception(__('Failed to set version as current', 'unique-client-page'));
            }
            
            // Update the user's current wishlist data
            $wishlist_data = maybe_unserialize($version->wishlist_data);
            $meta_key = 'ucp_wishlist_' . $version->page_id;
            $updated = update_user_meta($version->user_id, $meta_key, $wishlist_data);
            
            if (!$updated) {
                throw new Exception(__('Failed to update user wishlist data', 'unique-client-page'));
            }
            
            // Commit transaction
            $wpdb->query('COMMIT');
            
            wp_send_json_success(array(
                'message' => __('Current wishlist version updated successfully', 'unique-client-page'),
                'version_id' => $version_id,
                'version_number' => $version->version_number,
                'version_name' => $version->version_name
            ));
            
        } catch (Exception $e) {
            // Rollback transaction
            $wpdb->query('ROLLBACK');
            
            wp_send_json_error(array(
                'message' => $e->getMessage(),
                'debug' => $wpdb->last_error
            ));
        }
    }
    
    /**
     * Render the wishlist pages list
     * 
     * Shortcode callback for [ucp_wishlist_pages]
     * 
     * @param array $atts Shortcode attributes
     * @return string Rendered HTML
     */
    public function render_wishlist_pages_list($atts = []) {
        // Parse attributes
        $atts = shortcode_atts(array(
            'limit' => 10,
        ), $atts);
        
        // Check if user is logged in
        if (!is_user_logged_in()) {
            return '<div class="ucp-wishlist-pages">
                <p>' . __('Please log in to view your wishlist pages.', 'unique-client-page') . '</p>
            </div>';
        }
        
        global $wpdb;
        $user_id = get_current_user_id();
        
        // Get pages with wishlist data for this user
        $query = $wpdb->prepare(
            "SELECT DISTINCT page_id FROM {$wpdb->prefix}ucp_wishlist_versions WHERE user_id = %d ORDER BY created_at DESC LIMIT %d",
            $user_id,
            intval($atts['limit'])
        );
        
        $pages = $wpdb->get_col($query);
        
        if (empty($pages)) {
            return '<div class="ucp-wishlist-pages">
                <p>' . __('You have no wishlist pages yet.', 'unique-client-page') . '</p>
            </div>';
        }
        
        // Start output buffer
        ob_start();
        
        ?>        
        <div class="ucp-wishlist-pages">
            <h3><?php _e('Your Wishlist Pages', 'unique-client-page'); ?></h3>
            <ul class="ucp-wishlist-pages-list">
                <?php 
                foreach ($pages as $page_id) :
                    $page = get_post($page_id);
                    if (!$page) continue;
                    
                    // Get current version
                    $current_version = $wpdb->get_row($wpdb->prepare(
                        "SELECT * FROM {$wpdb->prefix}ucp_wishlist_versions WHERE user_id = %d AND page_id = %d AND is_current = 1 LIMIT 1",
                        $user_id,
                        $page_id
                    ));
                    
                    $has_version = !empty($current_version);
                    $version_name = $has_version ? $current_version->version_name : __('No version', 'unique-client-page');
                    $version_date = $has_version ? date('d/m/Y', strtotime($current_version->created_at)) : '';
                    
                    // Count products in current wishlist
                    $meta_key = 'ucp_wishlist_' . $page_id;
                    $wishlist_data = get_user_meta($user_id, $meta_key, true);
                    $product_count = 0;
                    
                    if (!empty($wishlist_data)) {
                        if (!is_array($wishlist_data)) {
                            $wishlist_data = maybe_unserialize($wishlist_data);
                        }
                        
                        if (is_array($wishlist_data)) {
                            $product_count = count($wishlist_data);
                        }
                    }
                    
                    $product_label = sprintf(
                        _n('%d product', '%d products', $product_count, 'unique-client-page'),
                        $product_count
                    );
                ?>
                <li class="ucp-wishlist-page-item">
                    <div class="ucp-wishlist-page-title">
                        <a href="<?php echo esc_url(get_permalink($page_id)); ?>">
                            <?php echo esc_html(get_the_title($page_id)); ?>
                        </a>
                    </div>
                    <div class="ucp-wishlist-page-meta">
                        <span class="ucp-wishlist-version">
                            <?php echo esc_html($version_name); ?>
                            <?php if ($version_date) : ?>
                                <span class="ucp-version-date">(<?php echo esc_html($version_date); ?>)</span>
                            <?php endif; ?>
                        </span>
                        <span class="ucp-wishlist-count">
                            <?php echo esc_html($product_label); ?>
                        </span>
                    </div>
                    <div class="ucp-wishlist-page-actions">
                        <a href="<?php echo esc_url(get_permalink($page_id)); ?>" class="ucp-view-page">
                            <?php _e('View Page', 'unique-client-page'); ?>
                        </a>
                        <?php if ($has_version) : ?>
                        <a href="<?php echo esc_url(add_query_arg('view_versions', '1', get_permalink($page_id))); ?>" class="ucp-view-versions">
                            <?php _e('View Versions', 'unique-client-page'); ?>
                        </a>
                        <?php endif; ?>
                    </div>
                </li>
                <?php endforeach; ?>
            </ul>
        </div>
        <?php
        
        return ob_get_clean();
    }
}
