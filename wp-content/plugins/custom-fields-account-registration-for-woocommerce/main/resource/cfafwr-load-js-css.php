<?php      
	add_action('admin_enqueue_scripts','CFAFWR_load_admin_script_style');
	function CFAFWR_load_admin_script_style($hook_suffix)
	{
		if ( is_admin() && isset( $_GET['page'] ) && $_GET['page'] === 'custom-fields-registration-settings' || 
			isset($_GET['post_type']) && $_GET['post_type'] === 'wporg_custom_field' || 
			isset($_GET['post']) && get_post_type($_GET['post']) === 'wporg_custom_field' ||
			$hook_suffix === 'profile.php' || 
			$hook_suffix === 'user-edit.php' ) 
		{
		    wp_enqueue_script('jquery-ui-sortable');
		    wp_enqueue_style('wp-color-picker');
		    wp_enqueue_script('wp-color-picker');
		    wp_enqueue_style('cfafwr-backend-css', CFAFWR_PLUGIN_DIR . '/assets/css/cfafwr_backend_css.css', false, '1.1');
		    wp_enqueue_script('cfafwr-backend-js', CFAFWR_PLUGIN_DIR . '/assets/js/cfafwr_backend_js.js', array(
		        'jquery',
		        'select2',
		        'wp-color-picker',
		    ), false, '1.0', true);
		    wp_localize_script('ajaxloadpost', 'ajax_postajax', array(
		        'ajaxurl' => admin_url('admin-ajax.php')
		    ));
		    wp_enqueue_style('woocommerce_admin_styles-css', WP_PLUGIN_URL . '/woocommerce/assets/css/admin.css', false, '1.0', "all");
		    wp_localize_script('cfafwr-backend-js', 'remove_icon', array(
		        'icon' => CFAFWR_PLUGIN_DIR . '/assets/images/remove_icon.png',
		        'ajaxnonce' => wp_create_nonce( 'cfafwr_ajax_nonce' ),
		    ));
		}
	}

	add_action('wp_enqueue_scripts','CFAFWR_load_script_style');
	function CFAFWR_load_script_style()
	{
	    wp_enqueue_style('cfafwr-frontend-css', CFAFWR_PLUGIN_DIR . '/assets/css/cfafwr_frontend_css.css', false, '1.0');
	    wp_enqueue_script('cfafwr-frontend-js', CFAFWR_PLUGIN_DIR . '/assets/js/cfafwr_front_js.js', false, '1.0', true);
	    wp_localize_script('cfafwr-frontend-js', 'ajax_postajax', array(
	        'ajaxurl' => admin_url('admin-ajax.php'),
		    'ajaxnonce' => wp_create_nonce( 'cfafwr_ajax_nonce' ),
	    ));
	    wp_enqueue_style('color-spectrum-css', CFAFWR_PLUGIN_DIR . '/assets/css/cfafwr_color_spectrum.css', false, '1.0');
	    wp_enqueue_script('color-spectrum-js', CFAFWR_PLUGIN_DIR . '/assets/js/cfafwr_color_spectrum.js', false, '1.0', true);
	    
	    wp_enqueue_style('cfafwr_select2_css', CFAFWR_PLUGIN_DIR . '/assets/css/select2.css', false, '1.0.0');
	}