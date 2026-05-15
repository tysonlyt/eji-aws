<?php
/**
 * Admin Handler
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

class PMC_Admin {
    
    /**
     * Instance of this class
     */
    private static $instance = null;
    
    /**
     * Get instance
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
        // Add meta box to product edit page with higher priority
        add_action('add_meta_boxes', array($this, 'add_meta_box'), 5);
        add_action('add_meta_boxes_product', array($this, 'add_meta_box'), 5);
        
        // Save meta box data
        add_action('save_post_product', array($this, 'save_meta_box'), 10, 2);
        
        // AJAX handlers
        add_action('wp_ajax_pmc_add_media', array($this, 'ajax_add_media'));
        add_action('wp_ajax_pmc_delete_media', array($this, 'ajax_delete_media'));
        add_action('wp_ajax_pmc_update_order', array($this, 'ajax_update_order'));
    }
    
    /**
     * Add meta box
     */
    public function add_meta_box() {
        // Add to product post type
        add_meta_box(
            'pmc_media_gallery',
            __('Product Media Gallery (Images + Videos)', 'product-media-carousel'),
            array($this, 'render_meta_box'),
            'product',
            'normal',
            'high'
        );
    }
    
    /**
     * Render meta box
     */
    public function render_meta_box($post) {
        // Get existing media
        $media_items = PMC_Database::get_product_media($post->ID);
        
        // Get product gallery images
        $product = wc_get_product($post->ID);
        $gallery_image_ids = array();
        
        if ($product) {
            $gallery_image_ids = $product->get_gallery_image_ids();
        }
        // Nonce field
        wp_nonce_field('pmc_save_meta_box', 'pmc_meta_box_nonce');
        ?>
        
        <div class="pmc-admin-wrapper">
            <!-- Version Badge -->
            <div class="pmc-version-badge">
                <?php if (PMC_Restrictions::is_pro()): ?>
                    <span class="pmc-badge pmc-badge-pro">✨ PRO VERSION</span>
                <?php else: ?>
                    <span class="pmc-badge pmc-badge-free">FREE VERSION</span>
                <?php endif; ?>
            </div>
            
            <!-- Instructions -->
            <div class="pmc-instructions">
                <p><?php _e('Add YouTube videos and manage your product gallery. Drag to reorder items.', 'product-media-carousel'); ?></p>
            <!-- Quota Notice -->
            <?php PMC_Restrictions::display_quota_notice($post->ID); ?>
            
            <!-- Add Media Section -->
            <div class="pmc-add-media-section">
                <h4><?php _e('Add Videos', 'product-media-carousel'); ?></h4>
                <!-- Video URL Input -->
                <div class="pmc-add-form">
                    <input type="text" 
                           id="pmc-video-url" 
                           class="regular-text" 
                           placeholder="<?php echo PMC_Restrictions::is_pro() ? 'YouTube, Vimeo, or video URL...' : 'YouTube URL...'; ?>" />
                    <button type="button" class="button button-primary" id="pmc-add-video">
                        <?php _e('Add Video URL', 'product-media-carousel'); ?>
                    </button>
                </div>
                
                <?php if (PMC_Restrictions::is_pro()) : ?>
                <!-- Upload Video File (Pro Only) -->
                <div class="pmc-upload-form" style="margin-top: 10px;">
                    <button type="button" class="button" id="pmc-upload-video">
                        <span class="dashicons dashicons-upload"></span>
                        <?php _e('Upload Video File (MP4/WebM)', 'product-media-carousel'); ?>
                    </button>
                    <input type="hidden" id="pmc-video-file-id" />
                </div>
                <?php endif; ?>
                
                <?php if (!PMC_Restrictions::is_pro()) : ?>
                <p class="description">
                    <?php _e('Free version: YouTube only.', 'product-media-carousel'); ?>
                    <a href="<?php echo esc_url(PMC_Restrictions::get_upgrade_url()); ?>" target="_blank">
                        <?php _e('Upgrade to Pro for Vimeo and self-hosted videos', 'product-media-carousel'); ?>
                    </a>
                </p>
                <?php endif; ?>
            </div>
            
            <!-- Media List Section -->
            <div class="pmc-media-list-section">
                <h4><?php _e('Media Gallery', 'product-media-carousel'); ?></h4>
                <p class="description"><?php _e('Drag to reorder. Product images from WooCommerce gallery are automatically included.', 'product-media-carousel'); ?></p>
                
                <ul id="pmc-media-list" class="pmc-media-list">
                    <?php
                    // Combine product images and custom media
                    $all_media = array();
                    
                    // Add product gallery images (use negative order to ensure they come first)
                    foreach ($gallery_image_ids as $index => $image_id) {
                        $all_media[] = array(
                            'id' => 'img_' . $image_id,
                            'type' => 'image',
                            'value' => $image_id,
                            'order' => -1000 + $index, // Negative order so images come first
                            'source' => 'woocommerce'
                        );
                    }
                    
                    // Add custom media from database
                    foreach ($media_items as $item) {
                        $all_media[] = array(
                            'id' => $item->id,
                            'type' => $item->media_type,
                            'value' => $item->media_value,
                            'order' => intval($item->display_order), // Ensure integer
                            'source' => 'custom'
                        );
                    }
                    
                    // Sort by order
                    usort($all_media, function($a, $b) {
                        return $a['order'] - $b['order'];
                    });
                    
                    // Render items
                    foreach ($all_media as $media) {
                        $this->render_media_item($media);
                    }
                    
                    if (empty($all_media)) {
                        echo '<li class="pmc-no-media">' . __('No media items yet. Add product images in the Product Gallery or add YouTube videos above.', 'product-media-carousel') . '</li>';
                    }
                    ?>
                </ul>
            </div>
            
            <p class="description" style="margin-top: 20px; padding: 15px; background: #f0f6fc; border-left: 4px solid #0073aa; border-radius: 0;">
                <strong><?php _e('Note:', 'product-media-carousel'); ?></strong> 
                <?php _e('Carousel display settings (autoplay, effects, thumbnails, etc.) are configured in the Elementor widget or shortcode parameters.', 'product-media-carousel'); ?>
            </p>
            
            <input type="hidden" name="pmc_product_id" value="<?php echo esc_attr($post->ID); ?>" />
        </div>
        
        <?php
    }
    
    /**
     * Render single media item
     */
    private function render_media_item($media) {
        $is_custom = ($media['source'] === 'custom');
        ?>
        <li class="pmc-media-item" data-id="<?php echo esc_attr($media['id']); ?>" data-type="<?php echo esc_attr($media['type']); ?>">
            <div class="pmc-media-preview">
                <?php if ($media['type'] === 'image'): ?>
                    <?php echo wp_get_attachment_image($media['value'], 'thumbnail'); ?>
                    <span class="pmc-media-label"><?php _e('Image', 'product-media-carousel'); ?></span>
                <?php elseif ($media['type'] === 'youtube'): ?>
                    <?php
                    $thumbnail = PMC_Video_Handler::get_youtube_thumbnail($media['value'], 'mqdefault');
                    ?>
                    <img src="<?php echo esc_url($thumbnail); ?>" alt="YouTube Video" />
                    <span class="pmc-media-label pmc-video-label">
                        <span class="dashicons dashicons-video-alt3"></span>
                        <?php _e('YouTube', 'product-media-carousel'); ?>
                    </span>
                <?php elseif ($media['type'] === 'vimeo'): ?>
                    <?php
                    $thumbnail = PMC_Video_Handler::get_vimeo_thumbnail($media['value']);
                    if (!$thumbnail) {
                        // Fallback: Use Vimeo's default thumbnail pattern
                        $thumbnail = "https://vumbnail.com/{$media['value']}.jpg";
                    }
                    ?>
                    <img src="<?php echo esc_url($thumbnail); ?>" alt="Vimeo Video" onerror="this.src='data:image/svg+xml,%3Csvg xmlns=%22http://www.w3.org/2000/svg%22 width=%22150%22 height=%22150%22%3E%3Crect fill=%22%231ab7ea%22 width=%22150%22 height=%22150%22/%3E%3Ctext fill=%22white%22 font-family=%22Arial%22 font-size=%2220%22 x=%2250%25%22 y=%2250%25%22 text-anchor=%22middle%22 dy=%22.3em%22%3EVimeo%3C/text%3E%3C/svg%3E';" />
                    <span class="pmc-media-label pmc-video-label">
                        <span class="dashicons dashicons-video-alt3"></span>
                        <?php _e('Vimeo', 'product-media-carousel'); ?>
                    </span>
                <?php elseif ($media['type'] === 'self_hosted'): ?>
                    <?php
                    // Try to get video thumbnail from attachment
                    $attachment_id = attachment_url_to_postid($media['value']);
                    $thumbnail = '';
                    
                    if ($attachment_id) {
                        // Get video thumbnail
                        $thumbnail = wp_get_attachment_image_url($attachment_id, 'thumbnail');
                    }
                    
                    if ($thumbnail): ?>
                        <img src="<?php echo esc_url($thumbnail); ?>" alt="Video" />
                    <?php else: ?>
                        <video width="150" height="150" style="object-fit: cover;" preload="metadata">
                            <source src="<?php echo esc_url($media['value']); ?>#t=0.5" type="video/mp4">
                        </video>
                    <?php endif; ?>
                    <span class="pmc-media-label pmc-video-label">
                        <span class="dashicons dashicons-media-video"></span>
                        <?php _e('Video File', 'product-media-carousel'); ?>
                    </span>
                <?php endif; ?>
            </div>
            
            <div class="pmc-media-info">
                <?php if ($media['type'] === 'youtube'): ?>
                    <div class="pmc-media-url"><?php echo esc_html($media['value']); ?></div>
                <?php endif; ?>
                <div class="pmc-media-source">
                    <?php echo $is_custom ? __('Custom', 'product-media-carousel') : __('WooCommerce Gallery', 'product-media-carousel'); ?>
                </div>
            </div>
            
            <div class="pmc-media-actions">
                <button type="button" class="button pmc-move-up" title="<?php _e('Move Up', 'product-media-carousel'); ?>">
                    <span class="dashicons dashicons-arrow-up-alt2"></span>
                </button>
                <button type="button" class="button pmc-move-down" title="<?php _e('Move Down', 'product-media-carousel'); ?>">
                    <span class="dashicons dashicons-arrow-down-alt2"></span>
                </button>
                <?php if ($is_custom): ?>
                    <button type="button" class="button pmc-delete-media" data-id="<?php echo esc_attr($media['id']); ?>">
                        <span class="dashicons dashicons-trash"></span>
                    </button>
                <?php endif; ?>
            </div>
        </li>
        <?php
    }
    
    
    /**
     * Save meta box
     */
    public function save_meta_box($post_id, $post) {
        // Verify nonce
        if (!isset($_POST['pmc_meta_box_nonce']) || !wp_verify_nonce($_POST['pmc_meta_box_nonce'], 'pmc_save_meta_box')) {
            return;
        }
        
        // Check autosave
        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
            return;
        }
        
        // Check permissions
        if (!current_user_can('edit_post', $post_id)) {
            return;
        }
        
        // Media items are saved via AJAX, no need to save anything here
        // Settings are now configured in Elementor widget
    }
    
    /**
     * AJAX: Add media
     */
    public function ajax_add_media() {
        try {
            // Security checks
            check_ajax_referer('pmc_admin_nonce', 'nonce');
            
            // Check user capabilities
            if (!current_user_can('edit_products')) {
                wp_send_json_error(array('message' => __('Permission denied', 'product-media-carousel')));
                return;
            }
            
            // Sanitize and validate input
            $product_id = isset($_POST['product_id']) ? intval($_POST['product_id']) : 0;
            $media_type = isset($_POST['media_type']) ? sanitize_text_field($_POST['media_type']) : '';
            $media_value = isset($_POST['media_value']) ? esc_url_raw($_POST['media_value']) : '';
            
            // Validate data
            if (!$product_id || !$media_type || !$media_value) {
                wp_send_json_error(array(
                    'message' => __('Invalid data', 'product-media-carousel'),
                    'debug' => array(
                        'product_id' => $product_id,
                        'media_type' => $media_type,
                        'media_value' => $media_value
                    )
                ));
                return;
            }
        } catch (Exception $e) {
            wp_send_json_error(array(
                'message' => 'Error: ' . $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ));
            return;
        }
        
        // Determine allowed video types based on version
        $allowed_video_types = array('youtube');
        if (PMC_Restrictions::is_pro()) {
            $allowed_video_types[] = 'vimeo';
            $allowed_video_types[] = 'self_hosted';
        }
        
        // Validate video URL using Video Handler
        if ($media_type === 'youtube' || $media_type === 'vimeo' || $media_type === 'video') {
            $validation = PMC_Video_Handler::validate_video_url($media_value, $allowed_video_types);
            
            if (!$validation['valid']) {
                wp_send_json_error(array(
                    'message' => $validation['message'],
                    'upgrade_url' => PMC_Restrictions::get_upgrade_url()
                ));
                return;
            }
            
            // Update media type to detected type
            $media_type = $validation['type'];
            $media_value = $validation['video_id'];
            
            // Check quota for YouTube videos in free version
            if ($media_type === 'youtube' && !PMC_Restrictions::can_add_youtube_video($product_id)) {
                wp_send_json_error(array(
                    'message' => sprintf(
                        __('You have reached the limit of %d YouTube video(s) in the free version. Upgrade to Pro for unlimited videos.', 'product-media-carousel'),
                        PMC_Restrictions::get_max_youtube_videos()
                    ),
                    'upgrade_url' => PMC_Restrictions::get_upgrade_url()
                ));
                return;
            }
        }
        
        // Validate media type
        $allowed_types = array('youtube', 'vimeo', 'self_hosted', 'image');
        if (!in_array($media_type, $allowed_types, true)) {
            wp_send_json_error(array('message' => __('Invalid media type', 'product-media-carousel')));
            return;
        }
        
        // Get current max order
        $media_items = PMC_Database::get_product_media($product_id, 0);
        $max_order = 0;
        foreach ($media_items as $item) {
            if ($item->display_order > $max_order) {
                $max_order = $item->display_order;
            }
        }
        
        // Add media (variation_id = 0 for main product)
        $result = PMC_Database::add_media($product_id, $media_type, $media_value, $max_order + 1, 0);
        
        if ($result) {
            $new_items = PMC_Database::get_product_media($product_id, 0);
            $new_item = end($new_items);
            
            wp_send_json_success(array(
                'message' => __('Media added successfully', 'product-media-carousel'),
                'item' => $new_item
            ));
        } else {
            global $wpdb;
            wp_send_json_error(array(
                'message' => __('Failed to add media', 'product-media-carousel'),
                'debug' => array(
                    'product_id' => $product_id,
                    'media_type' => $media_type,
                    'media_value' => $media_value,
                    'max_order' => $max_order,
                    'wpdb_error' => $wpdb->last_error,
                    'wpdb_query' => $wpdb->last_query
                )
            ));
        }
    }
    
    /**
     * AJAX: Delete media
     */
    public function ajax_delete_media() {
        // Security checks
        check_ajax_referer('pmc_admin_nonce', 'nonce');
        
        // Check user capabilities
        if (!current_user_can('edit_products')) {
            wp_send_json_error(array('message' => __('Permission denied', 'product-media-carousel')));
            return;
        }
        
        $media_id = isset($_POST['media_id']) ? intval($_POST['media_id']) : 0;
        
        if (!$media_id) {
            wp_send_json_error(array('message' => __('Invalid media ID', 'product-media-carousel')));
        }
        
        $result = PMC_Database::delete_media($media_id);
        
        if ($result) {
            wp_send_json_success(array('message' => __('Media deleted successfully', 'product-media-carousel')));
        } else {
            wp_send_json_error(array('message' => __('Failed to delete media', 'product-media-carousel')));
        }
    }
    
    /**
     * AJAX: Update order
     */
    public function ajax_update_order() {
        // Security checks
        check_ajax_referer('pmc_admin_nonce', 'nonce');
        
        // Check user capabilities
        if (!current_user_can('edit_products')) {
            wp_send_json_error(array('message' => __('Permission denied', 'product-media-carousel')));
            return;
        }
        
        $product_id = isset($_POST['product_id']) ? intval($_POST['product_id']) : 0;
        $order_data = isset($_POST['order']) ? array_map('intval', (array) $_POST['order']) : array();
        
        if (!$product_id || empty($order_data)) {
            wp_send_json_error(array('message' => __('Invalid data', 'product-media-carousel')));
        }
        $custom_order = array();
        foreach ($order_data as $index => $id) {
            if (strpos($id, 'img_') !== 0) {
                $custom_order[$index] = intval($id);
            }
        }
        
        $result = PMC_Database::update_display_order($product_id, $custom_order);
        
        if ($result) {
            wp_send_json_success(array('message' => __('Order updated successfully', 'product-media-carousel')));
        } else {
            wp_send_json_error(array('message' => __('Failed to update order', 'product-media-carousel')));
        }
    }
}
