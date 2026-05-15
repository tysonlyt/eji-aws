<?php
/**
 * Video Handler
 * Handles different video types (YouTube, Vimeo, Self-hosted)
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

class PMC_Video_Handler {
    
    /**
     * Extract YouTube video ID from URL
     */
    public static function extract_youtube_id($url) {
        $pattern = '/(?:youtube\.com\/(?:[^\/]+\/.+\/|(?:v|e(?:mbed)?)\/|.*[?&]v=)|youtu\.be\/)([^"&?\/ ]{11})/i';
        
        if (preg_match($pattern, $url, $matches)) {
            return $matches[1];
        }
        
        return false;
    }
    
    /**
     * Extract Vimeo video ID from URL
     */
    public static function extract_vimeo_id($url) {
        $pattern = '/(?:vimeo\.com\/)(?:channels\/(?:\w+\/)?|groups\/(?:[^\/]*)\/videos\/|album\/(?:\d+)\/video\/|)(\d+)(?:|\/\?)/';
        
        if (preg_match($pattern, $url, $matches)) {
            return $matches[1];
        }
        
        return false;
    }
    
    /**
     * Get video type from URL
     */
    public static function get_video_type($url) {
        if (self::extract_youtube_id($url)) {
            return 'youtube';
        }
        
        if (self::extract_vimeo_id($url)) {
            return 'vimeo';
        }
        
        // Check if it's a self-hosted video
        $extension = pathinfo(parse_url($url, PHP_URL_PATH), PATHINFO_EXTENSION);
        if (in_array(strtolower($extension), array('mp4', 'webm', 'ogg'))) {
            return 'self_hosted';
        }
        
        return false;
    }
    
    /**
     * Get YouTube thumbnail URL
     */
    public static function get_youtube_thumbnail($video_id, $quality = 'hqdefault') {
        // Quality options: default, mqdefault, hqdefault, sddefault, maxresdefault
        return "https://img.youtube.com/vi/{$video_id}/{$quality}.jpg";
    }
    
    /**
     * Get Vimeo thumbnail URL
     * Note: Requires API call or oEmbed
     */
    public static function get_vimeo_thumbnail($video_id) {
        // Use oEmbed to get thumbnail
        $oembed_url = "https://vimeo.com/api/oembed.json?url=https://vimeo.com/{$video_id}";
        
        $response = wp_remote_get($oembed_url);
        
        if (is_wp_error($response)) {
            return false;
        }
        
        $body = wp_remote_retrieve_body($response);
        $data = json_decode($body, true);
        
        if (isset($data['thumbnail_url'])) {
            return $data['thumbnail_url'];
        }
        
        return false;
    }
    
    /**
     * Get video embed HTML
     */
    public static function get_embed_html($type, $video_id, $atts = array()) {
        $defaults = array(
            'autoplay' => 0,
            'loop' => 0,
            'mute' => 0,
            'controls' => 1,
            'width' => '100%',
            'height' => '100%',
        );
        
        $atts = wp_parse_args($atts, $defaults);
        
        switch ($type) {
            case 'youtube':
                return self::get_youtube_embed($video_id, $atts);
            
            case 'vimeo':
                return self::get_vimeo_embed($video_id, $atts);
            
            case 'self_hosted':
                return self::get_self_hosted_embed($video_id, $atts);
            
            default:
                return '';
        }
    }
    
    /**
     * Get YouTube embed HTML
     */
    private static function get_youtube_embed($video_id, $atts) {
        $params = array(
            'autoplay' => $atts['autoplay'],
            'loop' => $atts['loop'],
            'mute' => $atts['mute'],
            'controls' => $atts['controls'],
            'rel' => 0,
            'modestbranding' => 1,
        );
        
        if ($atts['loop']) {
            $params['playlist'] = $video_id;
        }
        
        $param_string = http_build_query($params);
        
        return sprintf(
            '<iframe src="https://www.youtube.com/embed/%s?%s" width="%s" height="%s" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>',
            esc_attr($video_id),
            $param_string,
            esc_attr($atts['width']),
            esc_attr($atts['height'])
        );
    }
    
    /**
     * Get Vimeo embed HTML
     */
    private static function get_vimeo_embed($video_id, $atts) {
        $params = array(
            'autoplay' => $atts['autoplay'],
            'loop' => $atts['loop'],
            'muted' => $atts['mute'],
            'controls' => $atts['controls'],
            'title' => 0,
            'byline' => 0,
            'portrait' => 0,
        );
        
        $param_string = http_build_query($params);
        
        return sprintf(
            '<iframe src="https://player.vimeo.com/video/%s?%s" width="%s" height="%s" frameborder="0" allow="autoplay; fullscreen; picture-in-picture" allowfullscreen></iframe>',
            esc_attr($video_id),
            $param_string,
            esc_attr($atts['width']),
            esc_attr($atts['height'])
        );
    }
    
    /**
     * Get self-hosted video embed HTML
     */
    private static function get_self_hosted_embed($video_url, $atts) {
        $autoplay = $atts['autoplay'] ? 'autoplay' : '';
        $loop = $atts['loop'] ? 'loop' : '';
        $muted = $atts['mute'] ? 'muted' : '';
        $controls = $atts['controls'] ? 'controls' : '';
        
        return sprintf(
            '<video width="%s" height="%s" %s %s %s %s><source src="%s" type="video/mp4">Your browser does not support the video tag.</video>',
            esc_attr($atts['width']),
            esc_attr($atts['height']),
            $autoplay,
            $loop,
            $muted,
            $controls,
            esc_url($video_url)
        );
    }
    
    /**
     * Validate video URL
     */
    public static function validate_video_url($url, $allowed_types = array('youtube', 'vimeo', 'self_hosted')) {
        $type = self::get_video_type($url);
        
        if (!$type) {
            return array(
                'valid' => false,
                'message' => __('Invalid video URL', 'product-media-carousel')
            );
        }
        
        if (!in_array($type, $allowed_types)) {
            return array(
                'valid' => false,
                'message' => sprintf(
                    __('%s videos are only available in Pro version', 'product-media-carousel'),
                    ucfirst($type)
                )
            );
        }
        
        return array(
            'valid' => true,
            'type' => $type,
            'video_id' => $type === 'youtube' ? self::extract_youtube_id($url) : 
                        ($type === 'vimeo' ? self::extract_vimeo_id($url) : $url)
        );
    }
}
