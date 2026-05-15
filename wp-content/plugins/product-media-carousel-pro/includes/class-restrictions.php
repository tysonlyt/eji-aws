<?php
/**
 * Restrictions Manager
 * Manages free vs pro feature restrictions
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

class PMC_Restrictions {
    
    /**
     * Check if this is the Pro version
     */
    public static function is_pro() {
        // Check if Pro version is enabled via option
        // This can be controlled by test-pro.php or Freemius integration
        $is_pro = get_option('pmc_is_pro', false);
        
        // TODO: Integrate with Freemius later
        // return freemius()->is_premium();
        
        return (bool) $is_pro;
    }
    
    /**
     * Get maximum YouTube videos allowed per product
     */
    public static function get_max_youtube_videos() {
        return self::is_pro() ? -1 : 1; // -1 = unlimited
    }
    
    /**
     * Get allowed navigation styles
     */
    public static function get_allowed_navigation_styles() {
        if (self::is_pro()) {
            return array(
                'circle' => __('Circle', 'product-media-carousel'),
                'square' => __('Square', 'product-media-carousel'),
                'rounded' => __('Rounded Square', 'product-media-carousel'),
                'minimal' => __('Minimal', 'product-media-carousel'),
                'outline' => __('Outline Circle', 'product-media-carousel'),
            );
        }
        
        // Free version: only 3 styles
        return array(
            'circle' => __('Circle', 'product-media-carousel'),
            'square' => __('Square', 'product-media-carousel'),
            'minimal' => __('Minimal', 'product-media-carousel'),
        );
    }
    
    /**
     * Get allowed carousel effects
     */
    public static function get_allowed_effects() {
        if (self::is_pro()) {
            return array(
                'slide' => __('Slide', 'product-media-carousel'),
                'fade' => __('Fade', 'product-media-carousel'),
                'cube' => __('Cube', 'product-media-carousel'),
                'coverflow' => __('Coverflow', 'product-media-carousel'),
                'flip' => __('Flip', 'product-media-carousel'),
            );
        }
        
        // Free version: only 2 effects
        return array(
            'slide' => __('Slide', 'product-media-carousel'),
            'fade' => __('Fade', 'product-media-carousel'),
        );
    }
    
    /**
     * Check if a feature is available
     */
    public static function is_feature_available($feature) {
        if (self::is_pro()) {
            return true;
        }
        
        $free_features = array(
            'youtube_videos' => true,
            'basic_carousel' => true,
            'thumbnails' => true,
            'basic_navigation' => true,
            'lightbox' => true,
        );
        
        $pro_features = array(
            'vimeo_videos' => false,
            'self_hosted_videos' => false,
            'variation_videos' => false,
            'advanced_analytics' => false,
            'white_label' => false,
        );
        
        // Check if it's a free feature
        if (isset($free_features[$feature])) {
            return $free_features[$feature];
        }
        
        // Check if it's a pro feature
        if (isset($pro_features[$feature])) {
            return self::is_pro();
        }
        
        return false;
    }
    
    /**
     * Get pro-only features list
     */
    public static function get_pro_features() {
        return array(
            'unlimited_videos' => __('Unlimited YouTube Videos', 'product-media-carousel'),
            'all_navigation_styles' => __('All 5 Navigation Styles', 'product-media-carousel'),
            'all_effects' => __('All 5 Carousel Effects', 'product-media-carousel'),
            'vimeo_support' => __('Vimeo Video Support', 'product-media-carousel'),
            'self_hosted_video' => __('Self-Hosted Video (MP4/WebM)', 'product-media-carousel'),
            'variation_videos' => __('Product Variation Videos', 'product-media-carousel'),
            'priority_support' => __('Priority Support', 'product-media-carousel'),
        );
    }
    
    /**
     * Get upgrade URL
     */
    public static function get_upgrade_url() {
        // TODO: Replace with actual upgrade URL
        return 'https://everyideas.com/product-media-carousel-pro/';
    }
    
    /**
     * Display upgrade notice
     */
    public static function upgrade_notice($feature_name) {
        ?>
        <div class="pmc-upgrade-notice">
            <p>
                <span class="dashicons dashicons-lock"></span>
                <strong><?php echo esc_html($feature_name); ?></strong>
                <?php _e('is a Pro feature.', 'product-media-carousel'); ?>
                <a href="<?php echo esc_url(self::get_upgrade_url()); ?>" target="_blank" class="button button-primary button-small">
                    <?php _e('Upgrade to Pro', 'product-media-carousel'); ?>
                </a>
            </p>
        </div>
        <?php
    }
    
    /**
     * Check if user can add more YouTube videos
     */
    public static function can_add_youtube_video($product_id) {
        $max_videos = self::get_max_youtube_videos();
        
        // Unlimited in Pro
        if ($max_videos === -1) {
            return true;
        }
        
        // Count existing YouTube videos
        $media_items = PMC_Database::get_product_media($product_id);
        $youtube_count = 0;
        
        foreach ($media_items as $item) {
            if ($item->media_type === 'youtube') {
                $youtube_count++;
            }
        }
        
        return $youtube_count < $max_videos;
    }
    
    /**
     * Get remaining YouTube videos quota
     */
    public static function get_remaining_youtube_quota($product_id) {
        $max_videos = self::get_max_youtube_videos();
        
        if ($max_videos === -1) {
            return -1; // Unlimited
        }
        
        $media_items = PMC_Database::get_product_media($product_id);
        $youtube_count = 0;
        
        foreach ($media_items as $item) {
            if ($item->media_type === 'youtube') {
                $youtube_count++;
            }
        }
        
        return max(0, $max_videos - $youtube_count);
    }
    
    /**
     * Display quota notice
     */
    public static function display_quota_notice($product_id) {
        if (self::is_pro()) {
            return;
        }
        
        $remaining = self::get_remaining_youtube_quota($product_id);
        $max = self::get_max_youtube_videos();
        
        if ($remaining === 0) {
            ?>
            <div class="notice notice-warning inline">
                <p>
                    <span class="dashicons dashicons-info"></span>
                    <?php printf(
                        __('You have reached the limit of %d YouTube video(s) in the free version.', 'product-media-carousel'),
                        $max
                    ); ?>
                    <a href="<?php echo esc_url(self::get_upgrade_url()); ?>" target="_blank">
                        <?php _e('Upgrade to Pro for unlimited videos', 'product-media-carousel'); ?>
                    </a>
                </p>
            </div>
            <?php
        } else {
            ?>
            <div class="notice notice-info inline">
                <p>
                    <span class="dashicons dashicons-info"></span>
                    <?php printf(
                        __('Free version: %d of %d YouTube video(s) used.', 'product-media-carousel'),
                        $max - $remaining,
                        $max
                    ); ?>
                    <a href="<?php echo esc_url(self::get_upgrade_url()); ?>" target="_blank">
                        <?php _e('Upgrade to Pro', 'product-media-carousel'); ?>
                    </a>
                </p>
            </div>
            <?php
        }
    }
}
