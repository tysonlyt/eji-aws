<?php
/**
 * Elementor Product Media Carousel Widget
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

class PMC_Elementor_Product_Media_Carousel_Widget extends \Elementor\Widget_Base {
    
    /**
     * Get widget name
     */
    public function get_name() {
        return 'product-media-carousel';
    }
    
    /**
     * Get widget title
     */
    public function get_title() {
        return __('Product Media Carousel', 'product-media-carousel');
    }
    
    /**
     * Get widget icon
     */
    public function get_icon() {
        return 'eicon-media-carousel';
    }
    
    /**
     * Get widget categories
     */
    public function get_categories() {
        return array('product-media-carousel', 'woocommerce-elements');
    }
    
    /**
     * Get widget keywords
     */
    public function get_keywords() {
        return array('product', 'media', 'carousel', 'slider', 'youtube', 'video', 'gallery', 'woocommerce');
    }
    
    /**
     * Register widget controls
     */
    protected function register_controls() {
        
        // Content Section
        $this->start_controls_section(
            'content_section',
            array(
                'label' => __('Content', 'product-media-carousel'),
                'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
            )
        );
        
        $this->add_control(
            'product_id',
            array(
                'label' => __('Product ID', 'product-media-carousel'),
                'type' => \Elementor\Controls_Manager::NUMBER,
                'default' => '',
                'description' => __('Leave empty to use current product', 'product-media-carousel'),
            )
        );
        
        $this->end_controls_section();
        
        // Carousel Settings
        $this->start_controls_section(
            'carousel_settings',
            array(
                'label' => __('Carousel Settings', 'product-media-carousel'),
                'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
            )
        );
        
        $this->add_control(
            'effect',
            array(
                'label' => __('Effect', 'product-media-carousel'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => 'slide',
                'options' => PMC_Restrictions::get_allowed_effects(),
                'description' => PMC_Restrictions::is_pro() ? '' : __('Free version: 2 effects. Upgrade to Pro for all 5 effects.', 'product-media-carousel'),
            )
        );
        
        $this->add_control(
            'autoplay',
            array(
                'label' => __('Autoplay', 'product-media-carousel'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => __('Yes', 'product-media-carousel'),
                'label_off' => __('No', 'product-media-carousel'),
                'return_value' => 'true',
                'default' => 'true',
            )
        );
        
        $this->add_control(
            'loop',
            array(
                'label' => __('Loop', 'product-media-carousel'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => __('Yes', 'product-media-carousel'),
                'label_off' => __('No', 'product-media-carousel'),
                'return_value' => 'true',
                'default' => 'true',
            )
        );
        
        $this->add_control(
            'show_navigation',
            array(
                'label' => __('Show Navigation', 'product-media-carousel'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => __('Yes', 'product-media-carousel'),
                'label_off' => __('No', 'product-media-carousel'),
                'return_value' => 'true',
                'default' => 'true',
            )
        );
        
        $this->add_control(
            'navigation_style',
            array(
                'label' => __('Navigation Style', 'product-media-carousel'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => 'circle',
                'options' => PMC_Restrictions::get_allowed_navigation_styles(),
                'condition' => array(
                    'show_navigation' => 'true',
                ),
                'description' => PMC_Restrictions::is_pro() ? '' : __('Free version: 3 styles. Upgrade to Pro for all 5 styles.', 'product-media-carousel'),
            )
        );
        
        $this->add_control(
            'navigation_size',
            array(
                'label' => __('Navigation Size (px)', 'product-media-carousel'),
                'type' => \Elementor\Controls_Manager::NUMBER,
                'default' => 44,
                'min' => 30,
                'max' => 80,
                'condition' => array(
                    'show_navigation' => 'true',
                ),
            )
        );
        
        $this->add_control(
            'navigation_color',
            array(
                'label' => __('Navigation Color', 'product-media-carousel'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'description' => __('Leave empty to use theme color', 'product-media-carousel'),
                'condition' => array(
                    'show_navigation' => 'true',
                ),
            )
        );
        
        $this->add_control(
            'navigation_bg_color',
            array(
                'label' => __('Navigation Background', 'product-media-carousel'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'description' => __('Leave empty to use theme color', 'product-media-carousel'),
                'condition' => array(
                    'show_navigation' => 'true',
                ),
            )
        );
        
        $this->add_control(
            'navigation_border_width',
            array(
                'label' => __('Border Width (px)', 'product-media-carousel'),
                'type' => \Elementor\Controls_Manager::NUMBER,
                'default' => 0,
                'min' => 0,
                'max' => 10,
                'condition' => array(
                    'show_navigation' => 'true',
                ),
            )
        );
        
        $this->add_control(
            'navigation_border_color',
            array(
                'label' => __('Border Color', 'product-media-carousel'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'description' => __('Leave empty to use theme color', 'product-media-carousel'),
                'condition' => array(
                    'show_navigation' => 'true',
                ),
            )
        );
        
        $this->add_control(
            'show_pagination',
            array(
                'label' => __('Show Pagination', 'product-media-carousel'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => __('Yes', 'product-media-carousel'),
                'label_off' => __('No', 'product-media-carousel'),
                'return_value' => 'true',
                'default' => 'false',
            )
        );
        
        $this->add_control(
            'enable_lightbox',
            array(
                'label' => __('Enable Lightbox (Fancybox)', 'product-media-carousel'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => __('Yes', 'product-media-carousel'),
                'label_off' => __('No', 'product-media-carousel'),
                'return_value' => 'true',
                'default' => 'true',
                'description' => __('Show zoom icon and enable lightbox for images and videos', 'product-media-carousel'),
            )
        );
        
        $this->add_control(
            'autoplay_delay',
            array(
                'label' => __('Autoplay Delay (ms)', 'product-media-carousel'),
                'type' => \Elementor\Controls_Manager::NUMBER,
                'default' => 5000,
                'min' => 1000,
                'step' => 100,
                'condition' => array(
                    'autoplay' => 'true',
                ),
            )
        );
        
        $this->add_control(
            'speed',
            array(
                'label' => __('Speed (ms)', 'product-media-carousel'),
                'type' => \Elementor\Controls_Manager::NUMBER,
                'default' => 300,
                'min' => 100,
                'step' => 50,
            )
        );
        
        $this->add_control(
            'show_thumbnails',
            array(
                'label' => __('Show Thumbnails', 'product-media-carousel'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => __('Yes', 'product-media-carousel'),
                'label_off' => __('No', 'product-media-carousel'),
                'return_value' => 'true',
                'default' => 'true',
            )
        );
        
        $this->end_controls_section();
        
        // Thumbnail Settings
        $this->start_controls_section(
            'thumbnail_settings',
            array(
                'label' => __('Thumbnail Settings', 'product-media-carousel'),
                'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
                'condition' => array(
                    'show_thumbnails' => 'true',
                ),
            )
        );
        
        $this->add_control(
            'thumbnail_position',
            array(
                'label' => __('Position', 'product-media-carousel'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => 'bottom',
                'options' => array(
                    'bottom' => __('Bottom', 'product-media-carousel'),
                    'top' => __('Top', 'product-media-carousel'),
                    'left' => __('Left', 'product-media-carousel'),
                    'right' => __('Right', 'product-media-carousel'),
                ),
            )
        );
        
        $this->add_control(
            'thumbnail_size',
            array(
                'label' => __('Size (px)', 'product-media-carousel'),
                'type' => \Elementor\Controls_Manager::NUMBER,
                'default' => 100,
                'min' => 50,
                'step' => 10,
            )
        );
        
        $this->add_control(
            'thumbnail_gap',
            array(
                'label' => __('Gap (px)', 'product-media-carousel'),
                'type' => \Elementor\Controls_Manager::NUMBER,
                'default' => 0,
                'min' => 0,
                'step' => 5,
            )
        );
        
        $this->add_control(
            'thumbnails_per_view',
            array(
                'label' => __('Thumbnails Per View', 'product-media-carousel'),
                'type' => \Elementor\Controls_Manager::NUMBER,
                'default' => 4,
                'min' => 2,
                'max' => 10,
            )
        );
        
        $this->end_controls_section();
        
        // YouTube Settings
        $this->start_controls_section(
            'youtube_settings',
            array(
                'label' => __('YouTube Settings', 'product-media-carousel'),
                'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
            )
        );
        
        $this->add_control(
            'youtube_autoplay',
            array(
                'label' => __('YouTube Autoplay', 'product-media-carousel'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => __('Yes', 'product-media-carousel'),
                'label_off' => __('No', 'product-media-carousel'),
                'return_value' => 'true',
                'default' => 'false',
                'description' => __('May not work on mobile devices', 'product-media-carousel'),
            )
        );
        
        $this->add_control(
            'youtube_loop',
            array(
                'label' => __('YouTube Loop', 'product-media-carousel'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => __('Yes', 'product-media-carousel'),
                'label_off' => __('No', 'product-media-carousel'),
                'return_value' => 'true',
                'default' => 'false',
            )
        );
        
        $this->add_control(
            'youtube_controls',
            array(
                'label' => __('Show Controls', 'product-media-carousel'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => __('Yes', 'product-media-carousel'),
                'label_off' => __('No', 'product-media-carousel'),
                'return_value' => 'true',
                'default' => 'true',
            )
        );
        
        $this->add_control(
            'youtube_mute',
            array(
                'label' => __('Mute', 'product-media-carousel'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => __('Yes', 'product-media-carousel'),
                'label_off' => __('No', 'product-media-carousel'),
                'return_value' => 'true',
                'default' => 'false',
            )
        );
        
        $this->end_controls_section();
        
        // Style Section
        $this->start_controls_section(
            'style_section',
            array(
                'label' => __('Style', 'product-media-carousel'),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            )
        );
        
        $this->add_responsive_control(
            'carousel_height',
            array(
                'label' => __('Height', 'product-media-carousel'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => array('px', 'vh'),
                'range' => array(
                    'px' => array(
                        'min' => 200,
                        'max' => 1000,
                    ),
                    'vh' => array(
                        'min' => 20,
                        'max' => 100,
                    ),
                ),
                'default' => array(
                    'unit' => 'px',
                    'size' => 500,
                ),
                'selectors' => array(
                    '{{WRAPPER}} .pmc-main-carousel' => 'height: {{SIZE}}{{UNIT}};',
                ),
            )
        );
        
        $this->end_controls_section();
    }
    
    /**
     * Get product ID with Elementor preview support
     */
    private function get_product_id_for_elementor($settings) {
        // Priority 1: Manual product ID from widget settings
        if (!empty($settings['product_id'])) {
            return intval($settings['product_id']);
        }
        
        // Priority 2: Elementor preview settings
        if (class_exists('\Elementor\Plugin')) {
            $elementor = \Elementor\Plugin::$instance;
            
            // Check if in editor mode
            if ($elementor->editor->is_edit_mode()) {
                $document = $elementor->documents->get_current();
                if ($document) {
                    // Get preview settings
                    $preview_id = $document->get_settings('preview_id');
                    if ($preview_id && get_post_type($preview_id) === 'product') {
                        return intval($preview_id);
                    }
                }
            }
            
            // Check preview mode
            if (isset($_GET['preview_id'])) {
                $preview_id = intval($_GET['preview_id']);
                if (get_post_type($preview_id) === 'product') {
                    return $preview_id;
                }
            }
            
            // Check elementor-preview parameter
            if (isset($_GET['elementor-preview'])) {
                $preview_id = intval($_GET['elementor-preview']);
                if (get_post_type($preview_id) === 'product') {
                    return $preview_id;
                }
            }
        }
        
        // Priority 3: Current post/page
        global $post, $product;
        
        // Try global product object
        if (is_object($product) && method_exists($product, 'get_id')) {
            return $product->get_id();
        }
        
        // Try global post object
        if (is_object($post) && $post->post_type === 'product') {
            return $post->ID;
        }
        
        // Try get_the_ID()
        $current_id = get_the_ID();
        if ($current_id && get_post_type($current_id) === 'product') {
            return $current_id;
        }
        
        // Priority 4: Check if on single product page
        if (is_singular('product')) {
            return get_the_ID();
        }
        
        return 0;
    }
    
    /**
     * Render widget output
     */
    protected function render() {
        $settings = $this->get_settings_for_display();
        
        // Get product ID with Elementor preview support
        $product_id = $this->get_product_id_for_elementor($settings);
        
        if (!$product_id) {
            if (\Elementor\Plugin::$instance->editor->is_edit_mode()) {
                echo '<div class="elementor-alert elementor-alert-warning">' . __('Please select a product in Preview Settings (bottom left) or specify a Product ID above.', 'product-media-carousel') . '</div>';
            }
            return;
        }
        
        $product = wc_get_product($product_id);
        if (!$product) {
            if (\Elementor\Plugin::$instance->editor->is_edit_mode()) {
                echo '<div class="elementor-alert elementor-alert-danger">' . __('Product not found.', 'product-media-carousel') . '</div>';
            }
            return;
        }
        
        // Get all media items
        $frontend = PMC_Frontend::get_instance();
        $media_items = $frontend->get_all_media($product_id, $product);
        
        if (empty($media_items)) {
            if (\Elementor\Plugin::$instance->editor->is_edit_mode()) {
                echo '<div class="elementor-alert elementor-alert-info">' . __('No media items found for this product.', 'product-media-carousel') . '</div>';
            }
            return;
        }
        
        // Generate unique ID
        $carousel_id = 'pmc-carousel-' . $this->get_id();
        
        // Prepare attributes from Elementor settings
        $atts = array(
            'autoplay' => $settings['autoplay'],
            'autoplay_delay' => isset($settings['autoplay_delay']) ? $settings['autoplay_delay'] : 5000,
            'loop' => $settings['loop'],
            'effect' => $settings['effect'],
            'speed' => isset($settings['speed']) ? $settings['speed'] : 300,
            'show_thumbnails' => $settings['show_thumbnails'],
            'thumbnail_position' => isset($settings['thumbnail_position']) ? $settings['thumbnail_position'] : 'bottom',
            'thumbnail_size' => isset($settings['thumbnail_size']) ? $settings['thumbnail_size'] : 100,
            'thumbnail_gap' => isset($settings['thumbnail_gap']) ? $settings['thumbnail_gap'] : 0,
            'thumbnails_per_view' => isset($settings['thumbnails_per_view']) ? $settings['thumbnails_per_view'] : 4,
            'show_navigation' => $settings['show_navigation'],
            'navigation_style' => isset($settings['navigation_style']) ? $settings['navigation_style'] : 'circle',
            'navigation_size' => isset($settings['navigation_size']) ? $settings['navigation_size'] : 44,
            'navigation_color' => isset($settings['navigation_color']) ? $settings['navigation_color'] : '',
            'navigation_bg_color' => isset($settings['navigation_bg_color']) ? $settings['navigation_bg_color'] : '',
            'navigation_border_width' => isset($settings['navigation_border_width']) ? $settings['navigation_border_width'] : 0,
            'navigation_border_color' => isset($settings['navigation_border_color']) ? $settings['navigation_border_color'] : '',
            'show_pagination' => $settings['show_pagination'],
            'enable_lightbox' => isset($settings['enable_lightbox']) ? $settings['enable_lightbox'] : 'true',
            'youtube_autoplay' => isset($settings['youtube_autoplay']) ? $settings['youtube_autoplay'] : 'false',
            'youtube_loop' => isset($settings['youtube_loop']) ? $settings['youtube_loop'] : 'false',
            'youtube_controls' => isset($settings['youtube_controls']) ? $settings['youtube_controls'] : 'true',
            'youtube_mute' => isset($settings['youtube_mute']) ? $settings['youtube_mute'] : 'false',
        );
        
        // Include template
        include PMC_PLUGIN_DIR . 'templates/carousel.php';
    }
}
