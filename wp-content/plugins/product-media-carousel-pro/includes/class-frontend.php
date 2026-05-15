<?php
/**
 * Frontend Handler
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

class PMC_Frontend {
    
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
        // Shortcode
    }
    
    /**
     * Render carousel
     * 
     * @param array $atts Shortcode attributes
     */
    public function render_carousel($atts) {
        $product_id = isset($atts['product_id']) ? intval($atts['product_id']) : get_the_ID();
        
        if (!$product_id) {
            return '';
        }
        
        // Get product
        $product = wc_get_product($product_id);
        if (!$product) {
            return '';
        }
        
        // Default attributes for shortcode
        $default_atts = array(
            'product_id' => $product_id,
            'autoplay' => 'true',
            'autoplay_delay' => 5000,
            'loop' => 'true',
            'effect' => 'slide',
            'speed' => 300,
            'show_thumbnails' => 'true',
            'thumbnail_position' => 'bottom',
            'thumbnail_size' => 100,
            'thumbnail_gap' => 0,
            'thumbnails_per_view' => 4,
            'show_navigation' => 'true',
            'navigation_style' => 'circle',
            'navigation_size' => '',
            'navigation_color' => '',
            'navigation_bg_color' => '',
            'navigation_border_width' => '',
            'navigation_border_color' => '',
            'show_pagination' => 'false',
            'enable_lightbox' => 'true',
            'youtube_autoplay' => 'false',
            'youtube_loop' => 'false',
            'youtube_controls' => 'true',
            'youtube_mute' => 'false',
        );
        $atts = shortcode_atts($default_atts, $atts);
        
        // Get all media items
        $media_items = $this->get_all_media($product_id, $product, $atts);
        
        if (empty($media_items)) {
            return '';
        }
        
        // Generate unique ID for this carousel
        $carousel_id = 'pmc-carousel-' . uniqid();
        
        ob_start();
        include PMC_PLUGIN_DIR . 'templates/carousel.php';
        return ob_get_clean();
    }
    
    /**
     * Get all media items (images + videos)
     */
    public function get_all_media($product_id, $product, $atts = array()) {
        $all_media = array();
        
        // Get product images
        $image_ids = array();
        
        // Featured image
        if ($product->get_image_id()) {
            $image_ids[] = $product->get_image_id();
        }
        
        // Gallery images
        $gallery_ids = $product->get_gallery_image_ids();
        if (!empty($gallery_ids)) {
            $image_ids = array_merge($image_ids, $gallery_ids);
        }
        
        // Add images to media array (use negative order to ensure they come first by default)
        foreach ($image_ids as $index => $image_id) {
            $all_media[] = array(
                'type' => 'image',
                'value' => $image_id,
                'order' => -1000 + $index, // Negative order so images come first
                'url' => wp_get_attachment_url($image_id),
                'thumbnail' => wp_get_attachment_image_url($image_id, 'thumbnail')
            );
        }
        
        // Get custom media from database
        $custom_media = PMC_Database::get_product_media($product_id, 0);
        foreach ($custom_media as $item) {
            $media_data = array(
                'type' => $item->media_type,
                'value' => $item->media_value,
                'order' => intval($item->display_order) // Ensure it's an integer
            );
            
            if ($item->media_type === 'youtube') {
                // media_value is already the video ID (not full URL)
                $video_id = $item->media_value;
                $media_data['video_id'] = $video_id;
                $media_data['thumbnail'] = "https://img.youtube.com/vi/{$video_id}/mqdefault.jpg";
                $media_data['embed_url'] = "https://www.youtube.com/embed/{$video_id}";
            } elseif ($item->media_type === 'vimeo') {
                // Vimeo support
                $video_id = $item->media_value;
                $media_data['video_id'] = $video_id;
                $media_data['thumbnail'] = PMC_Video_Handler::get_vimeo_thumbnail($video_id);
                $media_data['embed_url'] = "https://player.vimeo.com/video/{$video_id}";
            } elseif ($item->media_type === 'self_hosted') {
                // Self-hosted video
                $media_data['video_id'] = $item->media_value;
                
                // Try to get video thumbnail
                $attachment_id = attachment_url_to_postid($item->media_value);
                $thumbnail = '';
                
                if ($attachment_id) {
                    // Try to get video thumbnail
                    $thumbnail = wp_get_attachment_image_url($attachment_id, 'medium');
                    
                    // If no thumbnail, try to get the first frame
                    if (!$thumbnail) {
                        $thumbnail = wp_get_attachment_image_url($attachment_id, 'thumbnail');
                    }
                }
                
                // Fallback: use video URL with #t=0.5 for first frame preview
                if (!$thumbnail) {
                    $thumbnail = $item->media_value . '#t=0.5';
                }
                
                $media_data['thumbnail'] = $thumbnail;
            }
            
            $all_media[] = $media_data;
        }
        
        // Sort by order
        usort($all_media, function($a, $b) {
            return $a['order'] - $b['order'];
        });
        
        return $all_media;
    }
    
    /**
     * Extract YouTube video ID from URL
     */
    private function extract_youtube_id($url) {
        $pattern = '/(?:youtube\.com\/watch\?v=|youtu\.be\/|youtube\.com\/embed\/)([a-zA-Z0-9_-]+)/';
        if (preg_match($pattern, $url, $matches)) {
            return $matches[1];
        }
        return false;
    }
}
