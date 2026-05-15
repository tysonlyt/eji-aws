<?php
/**
 * Template Handler Component
 * 
 * Handles all template related functionality
 *
 * @package Unique_Client_Page
 * @since 1.3.2
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Template Handler Class
 */
class UCP_Template_Handler {
    /**
     * Component instance
     *
     * @var UCP_Template_Handler
     */
    private static $instance = null;
    
    /**
     * Get singleton instance
     *
     * @return UCP_Template_Handler Component instance
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
        // Add custom page template hooks
        add_filter('theme_page_templates', array($this, 'add_page_template'));
        add_filter('template_include', array($this, 'load_page_template'), 20);
        
        // Add template support for Gutenberg editor
        add_action('init', array($this, 'register_block_template'));
        
        // Register block editor template categories
        if (function_exists('register_block_type')) {
            add_filter('block_categories_all', array($this, 'register_block_category'), 10, 2);
        }
    }
    
    /**
     * Add custom page template
     *
     * @param array $templates Existing templates
     * @return array Updated templates list
     */
    public function add_page_template($templates) {
        // Use full path as template key so WordPress can locate the template file correctly
        $plugin_path = 'plugins/unique-client-page/templates/';
        $template_file = 'unique-client-template.php';
        $full_template_path = $plugin_path . $template_file;
        
        // Log template path for debugging
        error_log('UCP Debug - Registering template path: ' . $full_template_path);
        
        // Keep the old template key for backward compatibility
        $templates[$template_file] = __('Unique Client Product Page', 'unique-client-page');
        $templates[$full_template_path] = __('Unique Client Product Page (Full Path)', 'unique-client-page');
        
        // Add alternative path formats for better compatibility
        $templates['plugins/templates/' . $template_file] = __('Unique Client Product Page (Alt Path)', 'unique-client-page');
        
        return $templates;
    }
    
    /**
     * Load custom page template
     * 
     * @param string $template Template path
     * @return string Modified template path
     */
    public function load_page_template($template) {
        global $post;
        
        // Try different methods to locate the template file
        $template_name = 'unique-client-template.php';
        
        // Fully qualified absolute path method - use correct path calculation
        $plugin_root = dirname(dirname(dirname(__FILE__))); // Go up 3 levels from frontend/classes/
        $plugin_template_path = $plugin_root . '/templates/' . $template_name;
        $theme_template_path = get_stylesheet_directory() . '/' . $template_name;
        
        // Log paths for debugging
        error_log('UCP Debug - Checking template paths:');
        error_log('UCP Debug - Plugin template path: ' . $plugin_template_path . ' (Exists: ' . (file_exists($plugin_template_path) ? 'Yes' : 'No') . ')');
        error_log('UCP Debug - Theme template path: ' . $theme_template_path . ' (Exists: ' . (file_exists($theme_template_path) ? 'Yes' : 'No') . ')');
        error_log('UCP Debug - Requested page ID: ' . (isset($post) ? $post->ID : 'Not set'));
        
        // If no page object exists, return the original template
        if (!isset($post) || !is_object($post)) {
            return $template;
        }
        
        // Check if current page uses our template
        if (is_page($post->ID)) {
            $current_template = get_post_meta($post->ID, '_wp_page_template', true);
            error_log('UCP Debug - Current page template set to: ' . $current_template);
            
            if ($template_name === $current_template) {
                // Always use plugin template (no longer copy to theme)
                if (file_exists($plugin_template_path)) {
                    error_log('UCP Debug - Using template from plugin directory: ' . $plugin_template_path);
                    return $plugin_template_path;
                }
                
                // Legacy: Check theme directory as fallback
                if (file_exists($theme_template_path)) {
                    error_log('UCP Debug - Using template from theme directory: ' . $theme_template_path);
                    return $theme_template_path;
                }
                
                // Fallback solution: Generate template content programmatically
                error_log('UCP Debug - Template file not found, using fallback solution');
                
                // Create a temporary template file
                $temp_template = get_stylesheet_directory() . '/ucp-temp-template-' . uniqid() . '.php';
                $template_content = '<?php
/**
 * Template Name: Unique Client Page Template (Generated)
 */

get_header(); ?>

<div class="ucp-content-wrapper">
    <div class="ucp-content">
        <?php while ( have_posts() ) : the_post(); ?>
            <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
                <header class="entry-header">
                    <h1 class="entry-title"><?php the_title(); ?></h1>
                </header>

                <div class="entry-content">
                    <?php the_content(); ?>
                </div>
            </article>
        <?php endwhile; ?>
    </div>
</div>

<?php get_footer(); ?>';
                
                // Attempting to write temporary template
                if (file_put_contents($temp_template, $template_content)) {
                    error_log('UCP Debug - Created temporary template: ' . $temp_template);
                    
                    // Register shutdown function to delete temporary template
                    register_shutdown_function(function() use ($temp_template) {
                        if (file_exists($temp_template)) {
                            @unlink($temp_template);
                            error_log('UCP Debug - Deleted temporary template: ' . $temp_template);
                        }
                    });
                    
                    return $temp_template;
                }
            }
        }
        
        return $template;
    }
    
    /**
     * Register block editor template category
     *
     * @param array $categories Existing categories
     * @param WP_Post $post Current post
     * @return array Modified categories list
     */
    public function register_block_category($categories, $post) {
        return array_merge(
            $categories,
            array(
                array(
                    'slug' => 'unique-client-page',
                    'title' => __('Unique Client Page', 'unique-client-page'),
                ),
            )
        );
    }
    
    /**
     * Register block template
     * Add template support for Gutenberg editor
     */
    public function register_block_template() {
        // Copy template file to theme directory (if it doesn't exist)
        $this->copy_template_file();
        
        // Support for FSE Themes, add appropriate template registration
        if (function_exists('register_block_pattern')) {
            register_block_pattern(
                'unique-client-page/product-selection-pattern',
                array(
                    'title'       => __('Product Selection Area', 'unique-client-page'),
                    'description' => __('Display product selection interface, supports filtering and loading more products', 'unique-client-page'),
                    'content'     => '<!-- wp:shortcode -->[unique_client_products]<!-- /wp:shortcode -->',
                    'categories'  => array('unique-client-page'),
                )
            );
        }
    }
    
    /**
     * Copy template file to theme directory
     * This is important for themes that look for templates in the theme directory
     * 
     * @return bool Whether the template file was copied successfully
     */
    public function copy_template_file() {
        try {
            // Use absolute path to ensure source file exists
            $source_path = dirname(plugin_dir_path(__FILE__)) . '/templates/unique-client-template.php';
            
            // Ensure source file exists
            if (!file_exists($source_path)) {
                error_log('UCP Error: Template source file not found: ' . $source_path);
                return false;
            }
            
            // Get theme directory
            $theme_dir = get_stylesheet_directory();
            if (!$theme_dir || !is_dir($theme_dir)) {
                error_log('UCP Error: Theme directory not found or not accessible');
                return false;
            }
            
            // Destination file path
            $destination = $theme_dir . '/unique-client-template.php';
            
            // If destination file exists with same content, skip copying
            if (file_exists($destination) && md5_file($source_path) === md5_file($destination)) {
                return true;
            }
            
            // Copy file
            $result = copy($source_path, $destination);
            
            // Log copy result
            if ($result) {
                error_log('UCP: Template file successfully copied to theme directory: ' . $destination);
            } else {
                error_log('UCP Error: Failed to copy template file to: ' . $destination);
            }
            
            return $result;
        } catch (Exception $e) {
            error_log('UCP Error: Exception while copying template file: ' . $e->getMessage());
            return false;
        }
    }
}
