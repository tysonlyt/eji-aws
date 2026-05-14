<?php

if (!defined('ABSPATH'))
    exit; // Exit if accessed directly

if (!class_exists('Activity_Log_Hook_Widgets')) {

    class Activity_Log_Hook_Widgets {

        /**
         * @var Activity_Log_Hook_Widgets
         */
        public static $instance;

        public function __construct() { 
            add_filter( 'widget_update_callback', array( &$this, 'hooks_widget_update_callback_log' ), 9999, 4 );

            // delete widget in low wp versions :)
            add_filter( 'sidebar_admin_setup', array( &$this, 'hooks_widget_delete_log' ) ); // Widget delete.

            // delete widget in new wp versions :)
	        add_filter( 'rest_post_dispatch', [$this, 'hooks_new_wp_versions_widget_delete_log'], 10, 3 );

        }

        public function hooks_new_wp_versions_widget_delete_log($response, $server, $request){
            $data = $response->get_data();
            $is_widget_page = strpos($request->get_route(),"wp/v2/widgets");
            $status = $response->get_status();

            if($request->get_method() == "DELETE" 
                && $status == 200
                && isset($data['deleted']) 
                && $data['deleted'] 
                && isset($data['previous'])
                && isset($data['previous']['id'])
                && isset($data['previous']['sidebar'])
                && $is_widget_page
            ){
                $block_id = $data['previous']['id'];
                $widget_name = $data['previous']['sidebar'];
                $this->send_widget_log('deleted', $data['previous']['sidebar'] , "The $block_id block from $widget_name widget is deleted.");
            }
		    return $response;
        }

        public function hooks_widget_update_callback_log( $instance, $new_instance, $old_instance, WP_Widget $widget ) {
            if ( empty( $_REQUEST['sidebar'] ) ) {
                return $instance;
            }
            $this->send_widget_log('updated', $_REQUEST['sidebar'] , $_REQUEST['sidebar'] . ' is updated with id '. $widget->id);

            // We are need return the instance, for complete the filter.
            return $instance;
        }
    
        public function hooks_widget_delete_log() {
            // A reference: http://grinninggecko.com/hooking-into-widget-delete-action-in-wordpress/
            if ( 'post' == strtolower( $_SERVER['REQUEST_METHOD'] ) && ! empty( $_REQUEST['widget-id'] ) ) {
                if ( isset( $_REQUEST['delete_widget'] ) && 1 === (int) $_REQUEST['delete_widget'] && isset($_REQUEST['sidebar'])) {
                    $block_id = $_REQUEST['widget-id'];
                    $widget_name = $_REQUEST['sidebar'];
                    $this->send_widget_log('deleted', $widget_name , "The $block_id block from $widget_name widget is deleted");
                }
            }
        }

        public function send_widget_log($action, $label, $description){
            $params = array(
                'type' => 'Widget',
                'label' => $label,
                'action' => $action,
                'description' => $description
            );

            $user_params = CDN_User_General_Data::get_user_params_api();
            $params = array_merge($user_params, $params);

            CDN_Activity_Log_Api::activity_log_api_call($params);
        }

        /**
         * Activity_Log_Hook_Widgets instance
         *
         * @return object
         */
        public static function get_instance() {
            if (!isset(self::$instance) || is_null(self::$instance))
                self::$instance = new self();

            return self::$instance;
        }

    }
    
    Activity_Log_Hook_Widgets::get_instance();

}
