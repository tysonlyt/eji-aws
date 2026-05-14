<?php

namespace WPaaS;

if ( ! defined( 'ABSPATH' ) ) {

	exit;

}

final class RUM {

	/**
	 * Class constructor.
	 */
	public function __construct() {

		if ( ! self::is_enabled() ) {

			return;

		}

		add_action( 'wp_footer',    [ $this, 'print_inline_script' ], PHP_INT_MAX );
		add_action( 'admin_footer', [ $this, 'print_inline_script' ], PHP_INT_MAX );

	}

	/**
	 * Add the RUM code to the footer of all pages.
	 *
	 * @action wp_footer
	 * @action admin_footer
	 */
	public function print_inline_script() {

		global $wp_version, $post;

		$env  = Plugin::get_env();
		$host = in_array( $env, [ 'dev', 'test' ], true ) ? "{$env}-secureserver.net" : 'secureserver.net';

		?>
		<script>'undefined'=== typeof _trfq || (window._trfq = []);'undefined'=== typeof _trfd && (window._trfd=[]),_trfd.push({'tccl.baseHost':'<?php echo esc_js( $host ); ?>'}),_trfd.push({'ap':'wpaas'},{'server':'<?php echo esc_js( gethostname() ); ?>'},{'pod':'<?php echo esc_js( getenv('WPAAS_POD') ?: 'null' ); ?>'},{'storage':'<?php echo esc_js( getenv('WPAAS_STORAGE') ?: 'null' ); ?>'},{'xid':'<?php echo absint( Plugin::xid() ); ?>'},{'wp':'<?php echo esc_js( $wp_version ); ?>'},{'php':'<?php echo esc_js( PHP_VERSION ); ?>'},{'loggedin':'<?php echo is_user_logged_in() ? 1 : 0; ?>'},{'cdn':'<?php echo CDN::is_enabled() ? 1 : 0; ?>'},{'builder':'<?php echo esc_js( Plugin::get_page_builder( $post ) ); ?>'},{'theme':'<?php echo esc_js( sanitize_title( get_template() ) ); ?>'},{'wds':'<?php echo defined( 'GD_cORe_VERSION' ) ? 1 : 0; ?>'},{'wp_alloptions_count':'<?php echo count( wp_load_alloptions() ); ?>'},{'wp_alloptions_bytes':'<?php echo strlen( serialize( wp_load_alloptions() ) ); ?>'})</script>
		<script>window.addEventListener('click', function (elem) { var _elem$target, _elem$target$dataset, _window, _window$_trfq; return (elem === null || elem === void 0 ? void 0 : (_elem$target = elem.target) === null || _elem$target === void 0 ? void 0 : (_elem$target$dataset = _elem$target.dataset) === null || _elem$target$dataset === void 0 ? void 0 : _elem$target$dataset.eid) && ((_window = window) === null || _window === void 0 ? void 0 : (_window$_trfq = _window._trfq) === null || _window$_trfq === void 0 ? void 0 : _window$_trfq.push(["cmdLogEvent", "click", elem.target.dataset.eid]));});</script>
		<script src='https://img1.wsimg.com/tcc/tcc_l.combined.1.0.6.min.js'></script>
		<script src='https://img1.wsimg.com/traffic-assets/js/tccl-tti.min.js' onload="window.tti.calculateTTI()"></script>
		<?php

		if ( ! self::should_load_full_story_sessions() ) {

			return;

		}

		?>
		<script>
		window['_fs_host'] = 'fullstory.com';
		window['_fs_script'] = 'edge.fullstory.com/s/fs.js';
		window['_fs_org'] = 'YKBRC';
		window['_fs_namespace'] = 'FS';
		(function(m,n,e,t,l,o,g,y){
				if (e in m) {if(m.console && m.console.log) { m.console.log('FullStory namespace conflict. Please set window["_fs_namespace"].');} return;}
				g=m[e]=function(a,b,s){g.q?g.q.push([a,b,s]):g._api(a,b,s);};g.q=[];
				o=n.createElement(t);o.async=1;o.crossOrigin='anonymous';o.src='https://'+_fs_script;
				y=n.getElementsByTagName(t)[0];y.parentNode.insertBefore(o,y);
				g.identify=function(i,v,s){g(l,{uid:i},s);if(v)g(l,v,s)};g.setUserVars=function(v,s){g(l,v,s)};g.event=function(i,v,s){g('event',{n:i,p:v},s)};
				g.anonymize=function(){g.identify(!!0)};
				g.shutdown=function(){g("rec",!1)};g.restart=function(){g("rec",!0)};
				g.log = function(a,b){g("log",[a,b])};
				g.consent=function(a){g("consent",!arguments.length||a)};
				g.identifyAccount=function(i,v){o='account';v=v||{};v.acctId=i;g(o,v)};
				g.clearUserCookie=function(){};
				g.setVars=function(n, p){g('setVars',[n,p]);};
				g._w={};y='XMLHttpRequest';g._w[y]=m[y];y='fetch';g._w[y]=m[y];
				if(m[y])m[y]=function(){return g._w[y].apply(this,arguments)};
				g._v="1.3.0";
		})(window,document,window['_fs_namespace'],'script','user');
		FS.identify('<?php echo GD_CUSTOMER_ID; ?>');
		</script>
		<?php

	}

	/**
	 * Determine if the fullstory.com script should load.
	 *
	 * Conditions: Is an admin page, created less than 60 days ago, has a valid custom ID,
	 * still using the temporary domain as the primary domain, and the fullstory_wpadmin feature flag is true.
	 *
	 * @return bool True when the conditions are met, otherwise false.
	 */
	private static function should_load_full_story_sessions() {

		$is_admin                    = is_admin();
		$is_less_than_sixty_days_old = ( defined( 'GD_SITE_CREATED' ) && GD_SITE_CREATED >= strtotime( '-60 days' ) );
		$is_valid_customer_id        = ( defined( 'GD_CUSTOMER_ID' ) && wp_is_uuid( GD_CUSTOMER_ID ) );
		$is_using_temp_domain        = ( defined( 'GD_TEMP_DOMAIN' ) && GD_TEMP_DOMAIN === parse_url( home_url(), PHP_URL_HOST ) );
		$is_enabled                  = $GLOBALS['wpaas_feature_flag']->get_feature_flag_value( 'fullstory_wpadmin', false );

		return ( $is_admin && $is_less_than_sixty_days_old && $is_valid_customer_id && $is_using_temp_domain && $is_enabled );

	}

	/**
	 * Return whether RUM should be enabled on the current page load.
	 *
	 * @return bool
	 */
	public static function is_enabled() {

		$rum_enabled = Plugin::is_rum_enabled();
		$temp_domain = defined( 'GD_TEMP_DOMAIN' ) ? GD_TEMP_DOMAIN : null;
		$is_nocache  = (bool) filter_input( INPUT_GET, 'nocache' );
		$is_gddebug  = (bool) filter_input( INPUT_GET, 'gddebug' );
		$is_amp      = ( function_exists( 'is_amp_endpoint' ) && is_amp_endpoint() );

		return ( $rum_enabled && $temp_domain && ! $is_nocache && ! $is_gddebug && ! $is_amp && ! WP_DEBUG );

	}

}
