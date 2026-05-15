<?php
/**
 * Cache Manager Component
 *
 * Handles caching of queries, templates and metadata to improve performance
 *
 * @package Unique_Client_Page
 * @since 1.3.2
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

/**
 * Cache Manager Component
 */
class UCP_Cache_Manager {
    /**
     * Class instance
     *
     * @var UCP_Cache_Manager
     */
    private static $instance = null;
    
    /**
     * Cache group
     * 
     * @var string
     */
    private $cache_group = 'ucp_cache';
    
    /**
     * Debug manager reference
     * 
     * @var UCP_Debug_Manager
     */
    private $debug_manager = null;
    
    /**
     * Cache expiration in seconds
     * 
     * @var int
     */
    private $cache_expiration = 3600; // 1 hour default
    
    /**
     * Get the singleton instance
     *
     * @return UCP_Cache_Manager instance
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
        // Get reference to debug manager
        if (class_exists('UCP_Debug_Manager')) {
            $this->debug_manager = UCP_Debug_Manager::get_instance();
        }
        
        // Allow modifying cache expiration via filter
        $this->cache_expiration = apply_filters('ucp_cache_expiration', $this->cache_expiration);
    }
    
    /**
     * Register hooks
     */
    public function register_hooks() {
        // Clear caches when products or wishlist data are updated
        add_action('save_post_product', array($this, 'clear_product_cache'));
        add_action('woocommerce_update_product', array($this, 'clear_product_cache'));
        add_action('wp_ajax_ucp_wishlist_action', array($this, 'clear_wishlist_cache'), 5); // Before main handler
        add_action('wp_ajax_nopriv_ucp_wishlist_action', array($this, 'clear_wishlist_cache'), 5);
    }
    
    /**
     * Get cached data
     *
     * @param string $key Cache key
     * @param mixed $default Default value if cache is not found
     * @return mixed Cached data or default
     */
    public function get($key) {
        $cache_key = $this->build_key($key);
        $cached_data = wp_cache_get($cache_key, $this->cache_group);
        
        if ($cached_data !== false) {
            if ($this->debug_manager) {
                $this->debug_manager->log('Cache hit for key: ' . $key, 'debug', 'cache');
            }
            return $cached_data;
        }
        
        if ($this->debug_manager) {
            $this->debug_manager->log('Cache miss for key: ' . $key, 'debug', 'cache');
        }
        
        return false;
    }
    
    /**
     * Set data in cache
     *
     * @param string $key Cache key
     * @param mixed $data Data to cache
     * @param int $expiration Expiration time in seconds
     * @return bool True on success, false on failure
     */
    public function set($key, $data, $expiration = null) {
        if ($expiration === null) {
            $expiration = $this->cache_expiration;
        }
        
        $cache_key = $this->build_key($key);
        $result = wp_cache_set($cache_key, $data, $this->cache_group, $expiration);
        
        if ($this->debug_manager) {
            $this->debug_manager->log(
                'Cache set for key: ' . $key . ' (result: ' . ($result ? 'success' : 'fail') . ')',
                'debug',
                'cache'
            );
        }
        
        return $result;
    }
    
    /**
     * Delete a specific cache entry
     *
     * @param string $key Cache key
     * @return bool True on success, false on failure
     */
    public function delete($key) {
        $cache_key = $this->build_key($key);
        $result = wp_cache_delete($cache_key, $this->cache_group);
        
        if ($this->debug_manager) {
            $this->debug_manager->log(
                'Cache deleted for key: ' . $key . ' (result: ' . ($result ? 'success' : 'fail') . ')',
                'debug',
                'cache'
            );
        }
        
        return $result;
    }
    
    /**
     * Clear all caches for a specific type
     *
     * @param string $prefix Cache key prefix
     */
    public function clear_by_prefix($prefix) {
        // Unfortunately WordPress doesn't provide a native way to delete cache by prefix
        // This is a workaround using cache groups
        wp_cache_delete('ucp_keys_' . $prefix, $this->cache_group);
        
        if ($this->debug_manager) {
            $this->debug_manager->log('Cleared cache with prefix: ' . $prefix, 'info', 'cache');
        }
    }
    
    /**
     * Clear product cache when product is updated
     *
     * @param int $product_id Product ID
     */
    public function clear_product_cache($product_id) {
        $this->clear_by_prefix('product');
        $this->delete('product_' . $product_id);
        
        if ($this->debug_manager) {
            $this->debug_manager->log('Cleared cache for product #' . $product_id, 'info', 'cache');
        }
    }
    
    /**
     * Clear wishlist cache when wishlist is updated
     */
    public function clear_wishlist_cache() {
        if (!isset($_POST['wishlist_key'])) {
            return;
        }
        
        $wishlist_key = sanitize_text_field($_POST['wishlist_key']);
        $this->clear_by_prefix('wishlist');
        $this->delete('wishlist_' . $wishlist_key);
        
        if ($this->debug_manager) {
            $this->debug_manager->log('Cleared cache for wishlist: ' . $wishlist_key, 'info', 'cache');
        }
    }
    
    /**
     * Clear all UCP cache
     */
    public function clear_all_cache() {
        // Since we can't easily delete an entire cache group with wp_cache_*,
        // we'll signal that all cache entries are invalid
        $clear_token = wp_generate_password(10, false);
        update_option('ucp_cache_clear_token', $clear_token);
        
        if ($this->debug_manager) {
            $this->debug_manager->log('Cleared all UCP cache with token: ' . $clear_token, 'info', 'cache');
        }
        
        return true;
    }
    
    /**
     * Build a unique and consistent cache key
     *
     * @param string $key Base key
     * @return string Full cache key
     */
    private function build_key($key) {
        $clear_token = get_option('ucp_cache_clear_token', '');
        return 'ucp_' . md5($key . $clear_token);
    }
    
    /**
     * Get product data with caching
     *
     * @param int $product_id Product ID
     * @return WC_Product|false Product object or false
     */
    public function get_product($product_id) {
        $cache_key = 'product_' . $product_id;
        $product = $this->get($cache_key);
        
        if ($product === false) {
            $product = wc_get_product($product_id);
            
            if ($product) {
                $this->set($cache_key, $product);
            }
        }
        
        return $product;
    }
    
    /**
     * Get wishlist data with caching
     *
     * @param string $wishlist_key Wishlist key
     * @return array|false Wishlist data or false
     */
    public function get_wishlist($wishlist_key) {
        // Only proceed if wishlist manager is available
        if (!class_exists('UCP_Wishlist_Manager')) {
            return false;
        }
        
        $cache_key = 'wishlist_' . $wishlist_key;
        $wishlist_data = $this->get($cache_key);
        
        if ($wishlist_data === false) {
            $wishlist_manager = UCP_Wishlist_Manager::get_instance();
            $wishlist_data = $wishlist_manager->get_wishlist_data($wishlist_key);
            
            if ($wishlist_data) {
                $this->set($cache_key, $wishlist_data);
            }
        }
        
        return $wishlist_data;
    }
    
    /**
     * Get cache statistics for debug display
     * 
     * @return array Cache statistics
     */
    public function get_cache_stats() {
        global $wp_object_cache;
        
        $stats = array(
            'group_count' => 0,
            'key_count' => 0,
            'size_estimate' => 0
        );
        
        if (is_object($wp_object_cache) && isset($wp_object_cache->cache[$this->cache_group])) {
            $stats['group_count'] = 1;
            $stats['key_count'] = count($wp_object_cache->cache[$this->cache_group]);
            
            // Rough estimate of cache size
            $size = 0;
            foreach ($wp_object_cache->cache[$this->cache_group] as $key => $data) {
                $size += strlen(serialize($data));
            }
            $stats['size_estimate'] = $size;
        }
        
        return $stats;
    }
}
