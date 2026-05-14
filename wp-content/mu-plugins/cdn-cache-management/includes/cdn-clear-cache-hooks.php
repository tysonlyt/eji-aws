<?php

/**
 * CDN Clear Cache Hooks
 */
if (!class_exists('CDN_Clear_Cache_Hooks')) {

    class CDN_Clear_Cache_Hooks {

        /**
         * @var WC_Subscription_Box
         */
        public static $instance;

        public function __construct() {
            
            /**
             * purge single post cache 
             */
            // purge post cache when delete post
            add_action('deleted_post', array($this, 'purge_cache_queue'), PHP_INT_MAX);
            // purge attachment cache when delete attachment
            add_action('delete_attachment', array($this, 'purge_cache_queue'), PHP_INT_MAX);
            // Register action to account for post status changes
            add_action('transition_post_status', array($this, 'purge_cache_post_status_change'), PHP_INT_MAX, 3);
            // purge cache when comment status is change
            add_action('transition_comment_status', array($this, 'purge_cache_comment_status_change'), PHP_INT_MAX, 3);
            // purge cache when add new comment
            add_action('comment_post', array($this, 'purge_cache_new_comment'), PHP_INT_MAX, 3);
            // Fires podcast-importer-secondline post import purge cache
            add_action('secondline_after_post_import', array($this, 'purge_cache_secondline_post_imported'), PHP_INT_MAX, 3 );
            // Fires podcast-importer-secondline post scheduled import purge cache
            add_action('secondline_after_post_scheduled_import', array($this, 'purge_cache_secondline_post_imported'), PHP_INT_MAX, 3 );
            // Fires when purge mv_trellis_crit_css_success theme css cache
//            add_action( 'mv_trellis_pre_crit_css_req', array($this, 'purge_mv_trellis_crit_css_cache' ), PHP_INT_MAX, 2 );
//            add_action( 'mv_trellis_crit_css_success', array($this, 'purge_mv_trellis_crit_css_cache' ), PHP_INT_MAX, 2 );

            /**
             * purge site cache
             */
            // purge cache when active/deactive plugin
            add_action('activated_plugin', array($this, 'purge_everything_cache'), PHP_INT_MAX );
            add_action('deactivated_plugin', array($this, 'purge_everything_cache'), PHP_INT_MAX );
            // Fires when the upgrader process is complete.
            add_action('upgrader_process_complete', array($this, 'purge_everything_cache'), PHP_INT_MAX );
            // Fires after all automatic updates have run.
            add_action('automatic_updates_complete', array($this, 'purge_everything_cache'), PHP_INT_MAX );
            // Fires after switch theme
            add_action('switch_theme', array($this, 'purge_cache'), PHP_INT_MAX );
            // Fires after Customize settings have been saved.
            add_action('customize_save_after', array($this, 'purge_cache'), PHP_INT_MAX );
            // Fires wp rocket purge cache
            add_action('rocket_purge_cache', array($this, 'purge_wp_rocket_cache_clear'), PHP_INT_MAX );
            add_action('set_transient_rocket_preload_complete', array($this, 'purge_cache'), PHP_INT_MAX );
            // Fires w3 total cache purge cache
            add_action('w3tc_flush_all', array($this, 'purge_cache'), PHP_INT_MAX );
            // Fires autoptimize purge cache
            add_action('autoptimize_action_cachepurged', array($this, 'purge_cache'), PHP_INT_MAX );
            // purge cache when clear elementor file caches
            add_action('elementor/core/files/clear_cache', array($this, 'purge_cache'), PHP_INT_MAX);
            // purge cache after at the end of each import wp all import
            add_action('pmxi_before_xml_import', array($this, 'before_xml_import_purge_cache'), PHP_INT_MAX );
            add_action('pmxi_after_xml_import', array($this, 'after_xml_import_purge_cache'), PHP_INT_MAX );
            // on create order purge order products cache
            add_action( 'woocommerce_payment_complete', array($this, 'wc_purge_order_products_cache'), PHP_INT_MAX);
//            add_action( 'mv_trellis_crit_css_disable_flag', array($this, 'purge_cache' ), PHP_INT_MAX );
            if (class_exists('WP_CLI')) {
                WP_CLI::add_command('cdn purge', array($this, 'purge_cache') );
                WP_CLI::add_hook( 'after_invoke:cache flush', array($this, 'purge_cache') );
            }

        }

        /**
         * Register action to account for post status changes
         * This includes
         * - publish => publish transitions (editing a published post: no actual status change but the hook runs nevertheless)
         * - manually publishing/unpublishing a post
         */
        public function purge_cache_post_status_change($new_status, $old_status, $post) {
            if ('publish' === $new_status || 'publish' === $old_status) {
                $this->purge_cache_queue($post->ID);
            }
        }

        /**
         * purge cache when comment status is change
         * @param string $new_status
         * @param string $old_status
         * @param string $comment
         */
        public function purge_cache_comment_status_change( $new_status, $old_status, $comment ){
            if (!isset($comment->comment_post_ID) || empty($comment->comment_post_ID)) {
                return; // nothing to do
            }
    
            // in case the comment status changed, and either old or new status is "approved", we need to purge cache for the corresponding post
            if (($old_status != $new_status) && (($old_status === 'approved') || ($new_status === 'approved'))) {
                $this->purge_cache_queue($comment->comment_post_ID);
                return;
            }
        }
        
        /**
         * purge cache when add new comment
         * @param int $comment_id
         * @param string $comment_status
         * @param string $comment_data
         * 
         */
        public function purge_cache_new_comment($comment_id, $comment_status, $comment_data){
            if ($comment_status != 1) {
                return; // if comment is not approved, stop
            }
            if (!is_array($comment_data)) {
                return; // nothing to do
            }
            if (!array_key_exists('comment_post_ID', $comment_data)) {
                return; // nothing to do
            }
          // all clear, we ne need to purge cache related to this post id
            $this->purge_cache_queue($comment_data['comment_post_ID']);
        }

        public function purge_cache_secondline_post_imported($post_id, $secondline_rss_feed, $item ){
            $post = get_post($post_id);
            if ($post->post_status == 'publish') {
                $this->purge_cache_queue($post_id);
            }
        }

        /**
         *  Fires when purge mv_trellis_crit_css_success theme css cache
         *  @param Array $params
         *  @param String $type
         */
        public function purge_mv_trellis_crit_css_cache($params, $type){
            if ( ! empty( $params['extraInfo']['post_id'] ) ) {
				$post_id = $params['extraInfo']['post_id'];
                if ($post->post_status == 'publish') {
                    $this->purge_cache_queue($post_id);
                }
			}
        }

        /**
         * prevent purge post cache during wp all import run
         */
        public function before_xml_import_purge_cache(){
            $this->add_remove_wp_all_import_flag(true);
        }

        /**
         * purge cache after wp all import done 
         */
        public function after_xml_import_purge_cache(){
            $this->add_remove_wp_all_import_flag(false);
            $this->purge_everything_cache();
        }
 
        /**
         * on create order purge order products cache
         * 
         * @param int $order_id
         */
        public function wc_purge_order_products_cache($order_id){ 
            $order  = wc_get_order($order_id);
            $items  = $order->get_items();
 
            foreach ( $items as $item ) {
                    $post_id = absint( $item['product_id'] );
                    $this->purge_cache_queue($post_id, true);
            }
            
        }

        /**
         * purge cache when active/deactive plugin
         * purge cache when updates/upgrades plugin complete
         */
        public function purge_everything_cache(){
            static $everything_purge_counter;
            // If we've purged "enough" times, stop already.
            if ( isset($everything_purge_counter) && $everything_purge_counter >= 2 ) {
                return false;
            }
            // purge all site cache
            $this->purge_cache();

            if ( ! isset( $everything_purge_counter ) ) {
                $everything_purge_counter = 1;
            } else {
                $everything_purge_counter++;
            }

        }

        /**
         * purge rocket site cache when purge wp rocket cache
         * @param String $type
         */
        public function purge_wp_rocket_cache_clear($type){
            if($type == 'all'){
                $this->purge_cache();
            }
        }

        /**
         * purge post cache 
         */
        public function purge_cache_queue($post_id = null, $force = false){
            static $purge_counter;
            
            // Autosaving never updates published content.
            if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
                return false;
            }
            // If we've purged "enough" times, stop already.
            if ( isset($purge_counter) && $purge_counter >= 2 && ! $force ) {
                return false;
            }

            if($this->get_wp_all_import_flag())
                return false;


            if ( $post_id && $post_id > 1 && !! ( $post = get_post( $post_id ) ) ) {
                $list_of_urls = $this->get_post_related_links($post_id, $post);
                $list_of_urls = apply_filters('cdn_purge_cache_urls', $list_of_urls, $post_id);
                $retval = CDN_Clear_Cache_Api::cache_api_call($list_of_urls, 'purge');
            }
            // At this point, we know we're going to purge, so let's bump the purge counter. This will prevent us from
            // over-purging on a given request.
            // BWD: I'm not sure this is necessary and may actually be harmful, depending on how many updates to a given
            // post can happen within a single request.
            if ( ! isset( $purge_counter ) ) {
                $purge_counter = 1;
            } else {
                $purge_counter++;
            }
        }

        /**
         * get post related links
         * @param int $post_id 
         * @param object $post
         * 
         * @return 
         */
        private function get_post_related_links($post_id, $post) {

            // get post type
            $post_type = $post->post_type;
            // If purging everything, $paths and $abspaths are empty.
            $list_of_urls = array();       // path regular expressions to purge.

            // Always purge the post's own URI
            $list_of_urls[]    = $this->get_permalink_path_query( get_permalink( $post_id ), true );

            // Purge the v2 WP REST API endpoints
            if ( 'post' === $post_type ) {
                $list_of_urls[] = '/wp-json/wp/v2/posts';
            }
            else if ( 'page' === $post_type ) {
                $list_of_urls[] = '/wp-json/wp/v2/pages';
            }
    
            //Purge taxonomies terms and feeds URLs
            $post_taxonomies = get_object_taxonomies($post_type);
    
            foreach ($post_taxonomies as $taxonomy) {
                // Only if taxonomy is public
                $taxonomy_data = get_taxonomy($taxonomy);
                if ($taxonomy_data instanceof WP_Taxonomy && false === $taxonomy_data->public) {
                    continue;
                }
    
                $terms = get_the_terms($post_id, $taxonomy);
    
                if (empty($terms) || is_wp_error($terms)) {
                    continue;
                }
    
                foreach ($terms as $term) {
                    $term_link = $this->get_permalink_path_query( get_term_link( $term ) );
                    $term_feed_link = $this->get_permalink_path_query( get_term_feed_link($term->term_id, $term->taxonomy) );
                    if (!is_wp_error($term_link) && !is_wp_error($term_feed_link)) {
                        array_push($list_of_urls, $term_link);
                        array_push($list_of_urls, $term_feed_link);
                    }
                }
            }
    
            // Author URL
            array_push(
                $list_of_urls,
                $this->get_permalink_path_query( get_author_posts_url( get_post_field( 'post_author', $post_id ) ) ),
                $this->get_permalink_path_query( get_author_feed_link( get_post_field( 'post_author', $post_id ) ) )
            );
    
            // Archives and their feeds
            if ( get_post_type_archive_link( $post_type ) == true ) {
                array_push(
                    $list_of_urls,
                    $this->get_permalink_path_query( get_post_type_archive_link( $post_type ) ),
                    $this->get_permalink_path_query( get_post_type_archive_feed_link( $post_type ) )
                );
            }
    
            // Also clean URL for trashed post.
            if ($post->post_status == 'trash') {
                $trash_post = get_permalink($post_id);
                $trash_post = $this->get_permalink_path_query( str_replace('__trashed', '', $trash_post) );
                array_push($list_of_urls, $trash_post, $trash_post.'feed/');
            }
    
            // Feeds
            array_push(
                $list_of_urls,
                $this->get_permalink_path_query( get_bloginfo_rss('rdf_url') ),
                $this->get_permalink_path_query( get_bloginfo_rss('rss_url') ),
                $this->get_permalink_path_query( get_bloginfo_rss('rss2_url') ),
                $this->get_permalink_path_query( get_bloginfo_rss('rss2_url'), true ),
                $this->get_permalink_path_query( get_bloginfo_rss('atom_url') ),
                $this->get_permalink_path_query( get_bloginfo_rss('comments_rss2_url') ),
                $this->get_permalink_path_query( get_post_comments_feed_link($post_id) )
            );
    
            // Home Page and (if used) posts page
            array_push($list_of_urls, '/');
            $page_link = $this->get_permalink_path_query( get_permalink(get_option('page_for_posts')) );
            if (is_string($page_link) && !empty($page_link) && get_option('show_on_front') == 'page') {
                array_push($list_of_urls, $page_link);
            }
    
            // Refresh pagination
            $total_posts_count = wp_count_posts()->publish;
            $posts_per_page = get_option('posts_per_page');
            // Limit to up to 3 pages
            $page_number_max = min(3, ceil($total_posts_count / $posts_per_page));
    
            foreach (range(1, $page_number_max) as $page_number) {
                array_push($list_of_urls, sprintf('/page/%s/', $page_number));
            }
    
            // Attachments
            if ('attachment' == $post_type) {
                $attachment_urls = array();
                foreach (get_intermediate_image_sizes() as $size) {
                    $attachment_src = wp_get_attachment_image_src($post_id, $size);
                    if (is_array($attachment_src) && !empty($attachment_src)) {
                        $attachment_urls[] = $this->get_permalink_path_query( $attachment_src[0] );
                    }
                }
                $list_of_urls = array_merge(
                    $list_of_urls,
                    array_unique( array_filter( $attachment_urls ) )
                );
            }
            
            // Clean array if row empty
            $list_of_urls = array_values( array_filter( $list_of_urls ) );
    
            return $list_of_urls;
        }

        private static function get_path_trailing_slash( $path ) {
            if ( substr( $path, -1 ) != '/' )
                return $path . '/';
            return $path;
        }

        /**
         * get url path and query from permalink
         * 
         * @param string $link
         * @return $link_uri
         */
        private function get_permalink_path_query($link, $slash = false){
            $post_parts = parse_url( $link );
            $link_uri   = (isset($post_parts['path'])) ? rtrim($post_parts['path'],'/') : '';
            if ( ! empty( $post_parts['query'] ) ) {
                $link_uri .= "?" . $post_parts['query'];
            } else{
                if($slash)
                    $link_uri .= '/';
            }


            return $link_uri;
        }

        /**
         * Clear all cache for the website.
         */
        public static function purge_cache() {
            return CDN_Clear_Cache_Api::cache_api_call([], 'purge_everything');
        }

        /**
         * get wp all import flag
         */
        public function get_wp_all_import_flag(){
            return get_option('start_pmxi_import');
        }

        /**
         * add / remove wp all import flag
         */
        public function add_remove_wp_all_import_flag($add){
            if($add)
                update_option('start_pmxi_import', true);
            else
                delete_option('start_pmxi_import');
        }


        /**
         * CDN_Clear_Cache_Hooks instance
         *
         * @return object
         */
        public static function get_instance() {
            if (!isset(self::$instance) || is_null(self::$instance))
                self::$instance = new self();

            return self::$instance;
        }

    }

    CDN_Clear_Cache_Hooks::get_instance();
}
