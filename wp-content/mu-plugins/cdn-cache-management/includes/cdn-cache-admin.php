<?php

/**
 * CDN Cache Admin
 */
if (!class_exists('CDN_Cache_Admin')) {

    class CDN_Cache_Admin {

        public static $instance;
        public static $cdn_custom_purge_page = 'cdn_custompurge';

        public function __construct() {
            $this->purge_cache = false;
            $this->purge_cache_message = '';

            add_action( 'admin_enqueue_scripts', array($this, 'register_script') );
            // add admin menu below tools menu to custom purge cache
            add_action('admin_menu', array($this, 'wp_cdn_cache_admin_menu'));
            // add admin menu in toolbar to purge all cache and custom purge cache
            add_action('admin_bar_menu', array($this, 'wp_cdn_cache_toolbar_dropdown'), 100);
            // purge all cache
            add_action('wp_loaded', array($this, 'admin_actions'), 20);
            add_action( 'admin_notices', array($this, 'purge_all_cache_notices') );
        }
        
        public function register_script(){
            $current_screen = get_current_screen();
            if($current_screen && isset($current_screen->id) && $current_screen->id == 'tools_page_cdn_custompurge'){
                wp_enqueue_script('cdn-cache-admin-script', WP_CDN_CACHE_URL . 'assets/js/cdn-cache-admin.js', array('jquery'), false, true);
            }
        }

        /**
         * add admin menu below tools menu to custom purge cache
         */
        public function wp_cdn_cache_admin_menu() {
            add_submenu_page('tools.php', __('CDN Cache Purge', 'cdn-cache-wp'), __('CDN Cache Purge', 'cdn-cache-wp'), 'manage_options', self::$cdn_custom_purge_page, array($this, 'wp_cdn_cache_purge_page') );
        }

        /**
         * wp cdn cache purge page
         */
        public function wp_cdn_cache_purge_page() {
            // purge cdn cache from admin 
            $check_purge_all = (isset($_GET['cdn-action']) && $_GET['cdn-action'] === 'purge-all') ? true : false;
            $cdn_cache_page_url = ($check_purge_all) ? $this->get_cdn_custom_purge_url() : '';

            $this->purge_cdn_cache_admin();
            include WP_CDN_CACHE_FILE . 'templates/admin/cdn-purge-cache-form.php';
        }

        /**
         * add admin menu in toolbar to purge all cache and custom purge cache
         * 
         * @param object $admin_bar
         * @return object
         */
        public function wp_cdn_cache_toolbar_dropdown($admin_bar) {
            if (!current_user_can('manage_options')) {
                return;
            }
            $admin_bar->add_menu([
                'id' => 'cdn_menu',
                'title' => __('CDN Cache', 'cdn-cache-wp'),
                'href' => '#',
                'meta' => [
                    'title' => __('CDN Cache', 'cdn-cache-wp')
                ]
            ]);


            $current_url = $_SERVER['REQUEST_URI'];
            $url = parse_url($current_url);
            $current_url = $url['path'];
            if (isset($url['query'])) {
                $query = explode('&', $url['query']);
                $params = [];
                foreach ($query as $q) {
                    if (strpos($q, 'rocket-action') !== false) {
                        continue;
                    }
                    $p = explode('=', $q);
                    if (isset($p[0]) && isset($p[1]) && !empty($p[0]) && !empty($p[1])) {
                        $params[$p[0]] = isset($p[1]) ? $p[1] : '';
                    }
                }
                $current_url .= '?';
                if (!empty($params)) {
                    $current_url .= http_build_query($params);
                    $current_url .= '&';
                }
            } else {
                $current_url .= '?';
            }
            $admin_bar->add_menu([
                'id' => 'cdn_menu_purge_everything',
                'title' => __('Purge Everything', 'cdn-cache-wp'),
                'href' =>  $current_url.'cdn-action=purge',
                'parent' => 'cdn_menu',
            ]);

            $cdn_cache_page_url = $this->get_cdn_custom_purge_url();
            $admin_bar->add_menu([
                'id' => 'cdn_menu_purge_custom',
                'title' => __('Custom purge', 'cdn-cache-wp'),
                'href' => $cdn_cache_page_url,
                'parent' => 'cdn_menu',
            ]);
        }

        /**
         * get cdn custom purge cache url
         */
        public function get_cdn_custom_purge_url(){
            $cdn_cache_page_url = add_query_arg('page', self::$cdn_custom_purge_page, get_admin_url(NULL, 'tools.php?page='));
            return $cdn_cache_page_url;
        }

        /**
         * purge cdn cache (purge all | specific files ) from admin 
         * @return 
         */
        public function purge_cdn_cache_admin() {
            if (!current_user_can('manage_options'))
                return;

            // purge all cache
            if (isset($_GET['cdn-action']) && $_GET['cdn-action'] === 'purge-all') {
                $retval = CDN_Clear_Cache_Hooks::purge_cache();
                $this->purge_cache_display_message($retval);
            }
            // purge specific files/urls
            if(isset($_POST['cdn_purge_cache']) && $_POST['cdn_purge_cache'] == 'custom-files'){
                $urls = isset($_POST['urls']) ? $_POST['urls'] : '';
                if (!empty($urls)) {
                    $urls = preg_split("/\r\n|\n|\r/", $urls);

                    $retval = CDN_Clear_Cache_Api::cache_api_call($urls, 'purge');
                    $this->purge_cache_display_message($retval);
                } else {
                    $error = __('Please provide URLs/Files in the form below.', 'cdn-cache-wp');
                    include WP_CDN_CACHE_FILE . 'templates/admin/cdn-purge-cace-error-msg.php';
                }
            }
        }

        /**
         * purge cache display message
         */
        public function purge_cache_display_message($retval){
            if ( $retval instanceof stdClass && isset($retval->success) && $retval->success) {
                include WP_CDN_CACHE_FILE . 'templates/admin/cdn-purge-cace-success-msg.php';
            } else {
                $error = (isset($retval->messages) && $retval->messages) ? implode(',', $retval->messages) : __("Can't purge cache try again", 'cdn-cache-wp');
                include WP_CDN_CACHE_FILE . 'templates/admin/cdn-purge-cace-error-msg.php';
            }
        }


        /**
         * clear cache notice 
         */
        public function admin_actions() {
            if (!isset($_GET['cdn-action']) || !current_user_can('manage_options')) {
                return;
            }
            if ($_GET['cdn-action'] === 'purge') {
                $retval = CDN_Clear_Cache_Hooks::purge_cache();
                if(is_admin()){
                    ob_start();
                    $this->purge_cache = true;
                    $this->purge_cache_display_message($retval);
                    $this->purge_cache_message = ob_get_clean();
                }else{
                    $this->purge_cache_display_message($retval);
                }
            }
        }

        /**
         * print notices when purge cache from admin
         */
        public function purge_all_cache_notices(){
            if($this->purge_cache){
                echo $this->purge_cache_message;
            }
        }

        /**
         * CDN_Cache_Admin instance
         *
         * @return object
         */
        public static function get_instance() {
            if (!isset(self::$instance) || is_null(self::$instance))
                self::$instance = new self();

            return self::$instance;
        }

    }

    CDN_Cache_Admin::get_instance();
}