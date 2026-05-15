<?php
/***
 *  BetterFramework is BetterStudio framework for themes and plugins.
 *
 *  ______      _   _             ______                                           _
 *  | ___ \    | | | |            |  ___|                                         | |
 *  | |_/ / ___| |_| |_ ___ _ __  | |_ _ __ __ _ _ __ ___   _____      _____  _ __| | __
 *  | ___ \/ _ \ __| __/ _ \ '__| |  _| '__/ _` | '_ ` _ \ / _ \ \ /\ / / _ \| '__| |/ /
 *  | |_/ /  __/ |_| ||  __/ |    | | | | | (_| | | | | | |  __/\ V  V / (_) | |  |   <
 *  \____/ \___|\__|\__\___|_|    \_| |_|  \__,_|_| |_| |_|\___| \_/\_/ \___/|_|  |_|\_\
 *
 *  Copyright © 2017 Better Studio
 *
 *
 *  Our portfolio is here: https://betterstudio.com/
 *
 *  \--> BetterStudio, 2018 <--/
 */

use \BetterFrameworkPackage\Component\Control\{
	Typography,
	Features
};
use BetterFrameworkPackage\Utils\Icons;

if ( ! class_exists( 'BF_Exception' ) ) {

	/**
	 * Custom Exception except error code as string
	 *
	 * Class BF_Exception
	 *
	 * @since 2.7.0
	 */
	class BF_Exception extends Exception {

		public function __construct( $message = '', $code = '', $previous = null ) {

			parent::__construct( $message, 0, $previous );
			$this->code = $code;
		}
	}
}

if ( ! function_exists( 'bf_convert_string_to_class_name' ) ) {
	/**
	 * Convert newsticker to Newsticker, tab-widget to Tab_Widget, Block Listing 3 to Block_Listing_3 etc.
	 *
	 * @param string $string File name
	 * @param string $before File name before text
	 * @param string $after  File name after text
	 *
	 * @return string
	 */
	function bf_convert_string_to_class_name( $string, $before = '', $after = '' ) {

		$class = str_replace(
			[ '/', '-', ' ' ],
			'_',
			$string
		);

		$class = explode( '_', $class );

		$class = array_map( 'ucwords', $class );

		$class = implode( '_', $class );

		return sanitize_html_class( $before . $class . $after );
	}
}


if ( ! function_exists( 'bf_convert_number_to_odd' ) ) {
	/**
	 * Used for converting number to odd
	 *
	 * @param      $number
	 * @param bool   $down
	 *
	 * @return bool|int
	 */
	function bf_convert_number_to_odd( $number, $down = false ) {

		if ( is_int( $number ) ) {

			if ( intval( $number ) % 2 == 0 ) {
				return $number;
			} else {

				if ( $down ) {
					return intval( $number ) - 1;
				} else {
					return intval( $number ) + 1;
				}
			}
		}

		return false;
	}
}


if ( ! function_exists( 'bf_call_func' ) ) {
	function bf_call_func( $func = '', $params = '' ) {

		if ( ! is_callable( $func ) ) {
			return false;
		}

		if ( ! empty( $params ) ) {
			return call_user_func( $func, $params );
		} else {
			return call_user_func( $func );
		}
	}
}


if ( ! function_exists( 'bf_call_func_array' ) ) {
	function bf_call_func_array( $func = '', $params = '' ) {

		if ( ! is_callable( $func ) ) {
			return false;
		}

		if ( ! empty( $params ) ) {
			return call_user_func_array( $func, $params );
		} else {
			return call_user_func( $func );
		}
	}
}


if ( ! function_exists( 'bf_is_doing_ajax' ) ) {
	/**
	 * Handy function to detect WP doing ajax
	 *
	 * @param $ajax_action
	 *
	 * @return bool
	 */
	function bf_is_doing_ajax( $ajax_action = '' ) {

		$is_ajax = defined( 'DOING_AJAX' ) && DOING_AJAX;

		if ( ! $is_ajax ) {

			return apply_filters( 'better-framework/function/bf-is-doing-ajax', false, $ajax_action );
		}

		if ( isset( $_REQUEST['action'] ) && $_REQUEST['action'] === $ajax_action ) { // support for WP ajax action
			$result = true;
		} elseif ( isset( $_REQUEST['action'] ) && $_REQUEST['action'] === 'bf_ajax' &&
				   isset( $_REQUEST['reqID'] ) && $_REQUEST['reqID'] === $ajax_action
		) { // support for BF ajax action
			$result = true;
		} else {

			$result = empty( $ajax_action ) ? $is_ajax : false;
		}

		return apply_filters( 'better-framework/function/bf-is-doing-ajax', $result, $ajax_action );
	}
}


if ( ! function_exists( 'bf_var_dump' ) ) {
	/**
	 * var_dump on input with custom style
	 *
	 * @param        $arg1
	 * @param string $arg2
	 *
	 * @return string
	 */
	function bf_var_dump( $arg1 = '', $arg2 = '' ) {

		// line break
		if ( ! bf_is_doing_ajax() ) {
			$lb = '<br>';
		} else {
			$lb = "\n";
		}

		$bt = debug_backtrace();

		$arg = func_get_args();

		if ( ! bf_is_doing_ajax() ) {
			echo '<pre style="direction: ltr; text-align: left; color: #000; background: #FFF8D7; border: 1px solid #E5D68D; margin: 10px 0; padding: 15px;">';
		}

		call_user_func_array( 'var_dump', $arg );

		if ( ! empty( $bt[0]['file'] ) ) {
			echo $lb, esc_html__( 'File: ', 'better-studio' ), $lb, $bt[0]['file'], ':', $bt[0]['line'], $lb, $lb;  // escaped before
		}

		if ( ! bf_is_doing_ajax() ) {
			echo '</pre>';
		}
	}
}


if ( ! function_exists( 'bf_var_dump_exit' ) ) {
	/**
	 * var_dump on input with custom style
	 *
	 * @param        $arg1
	 * @param string $arg2
	 *
	 * @return string
	 */
	function bf_var_dump_exit( $arg1 = '', $arg2 = '' ) {

		// line break
		if ( ! bf_is_doing_ajax() ) {
			$lb = '<br>';
		} else {
			$lb = "\n";
		}

		$bt = debug_backtrace();

		$arg = func_get_args();

		if ( ! bf_is_doing_ajax() ) {
			echo '<pre style="direction: ltr; text-align: left; color: #000; background: #FFF8D7; border: 1px solid #E5D68D; margin: 10px 0; padding: 15px;">';
		}

		call_user_func_array( 'var_dump', $arg );

		if ( ! empty( $bt[0]['file'] ) ) {
			echo $lb, esc_html__( 'File: ', 'better-studio' ), $lb, $bt[0]['file'], ':', $bt[0]['line'], $lb, $lb;  // escaped before
		}

		if ( ! bf_is_doing_ajax() ) {
			echo '</pre>';
		}

		exit();
	}
}


if ( ! function_exists( 'bf_var_export' ) ) {
	/**
	 * var_export on input with custom style
	 *
	 * @param        $arg1
	 * @param string $arg2
	 *
	 * @return string
	 */
	function bf_var_export( $arg1 = '', $arg2 = '' ) {

		// line break
		if ( ! bf_is_doing_ajax() ) {
			$lb = '<br>';
		} else {
			$lb = "\n";
		}

		$bt = debug_backtrace();

		$arg = func_get_args();

		if ( ! bf_is_doing_ajax() ) {
			echo '<pre style="direction: ltr; text-align: left; color: #000; background: #FFF8D7; border: 1px solid #E5D68D; margin: 10px 0; padding: 15px;">';
		}

		foreach ( $arg as $_ar_key => $_ar ) {

			if ( empty( $_ar ) ) {
				continue;
			}

			call_user_func( 'var_export', $_ar );

			echo $lb, $lb;  // escaped before
		}

		if ( ! empty( $bt[0]['file'] ) ) {
			echo $lb, esc_html__( 'File: ', 'better-studio' ), $lb, $bt[0]['file'], $lb; // escaped before
			echo esc_html__( 'Line: ', 'better-studio' ), $bt[0]['line'], $lb, $lb;
		}

		if ( ! bf_is_doing_ajax() ) {
			echo '</pre>';
		}
	}
}


if ( ! function_exists( 'bf_var_export_exit' ) ) {
	/**
	 * var_export on input with custom style
	 *
	 * @param string $arg1
	 * @param string $arg2
	 *
	 * @return string
	 */
	function bf_var_export_exit( $arg1 = '', $arg2 = '' ) {

		// line break
		if ( ! bf_is_doing_ajax() ) {
			$lb = '<br>';
		} else {
			$lb = "\n";
		}

		$bt = debug_backtrace();

		$arg = func_get_args();

		if ( ! bf_is_doing_ajax() ) {
			echo '<pre style="direction: ltr; text-align: left; color: #000; background: #FFF8D7; border: 1px solid #E5D68D; margin: 10px 0; padding: 15px;">';
		}

		foreach ( $arg as $_ar_key => $_ar ) {

			if ( empty( $_ar ) ) {
				continue;
			}

			call_user_func( 'var_export', $_ar );

			echo $lb, $lb;  // escaped before
		}

		if ( ! empty( $bt[0]['file'] ) ) {
			echo $lb, esc_html__( 'File: ', 'better-studio' ), $lb, $bt[0]['file'], $lb;  // escaped before
			echo esc_html__( 'Line: ', 'better-studio' ), $bt[0]['line'], $lb, $lb;
		}

		if ( ! bf_is_doing_ajax() ) {
			echo '</pre>';
		}

		exit();
	}
}


if ( ! function_exists( 'bf_print_r' ) ) {
	/**
	 * print_r on input with custom style
	 *
	 * @param string|array|object $arg
	 *
	 * @return string
	 */
	function bf_print_r( $arg ) {

		// line break
		if ( ! bf_is_doing_ajax() ) {
			$lb = '<br>';
		} else {
			$lb = "\n";
		}

		$arg = func_get_args();

		if ( ! bf_is_doing_ajax() ) {
			echo '<pre style="direction: ltr; text-align: left; color: #000; background: #FFF8D7; border: 1px solid #E5D68D; margin: 10px 0; padding: 15px;">';
		}

		call_user_func_array( 'print_r', $arg );

		if ( ! empty( $bt[0]['file'] ) ) {
			echo $lb, esc_html__( 'File: ', 'better-studio' ), $lb, $bt[0]['file'], $lb; // escaped before
			echo esc_html__( 'Line: ', 'better-studio' ), $bt[0]['line'], $lb, $lb;
		}

		if ( ! bf_is_doing_ajax() ) {
			echo '</pre>';
		}
	}
}

if ( ! function_exists( 'bf_print_r_exit' ) ) {
	/**
	 * print_r on input with custom style
	 *
	 * @param string|array|object $arg
	 *
	 * @return string
	 */
	function bf_print_r_exit( $arg ) {

		// line break
		if ( ! bf_is_doing_ajax() ) {
			$lb = '<br>';
		} else {
			$lb = "\n";
		}

		$arg = func_get_args();

		if ( ! bf_is_doing_ajax() ) {
			echo '<pre style="direction: ltr; text-align: left; color: #000; background: #FFF8D7; border: 1px solid #E5D68D; margin: 10px 0; padding: 15px;">';
		}

		call_user_func_array( 'print_r', $arg );

		if ( ! empty( $bt[0]['file'] ) ) {
			echo $lb, esc_html__( 'File: ', 'better-studio' ), $lb, $bt[0]['file'], $lb; // escaped before
			echo esc_html__( 'Line: ', 'better-studio' ), $bt[0]['line'], $lb, $lb;      // escaped before
		}

		if ( ! bf_is_doing_ajax() ) {
			echo '</pre>';
		}

		exit();
	}
}


if ( ! function_exists( 'bf_is_json' ) ) {
	/**
	 * Checks string for valid JSON
	 *
	 * @param mixed $string
	 * @param bool  $assoc_array
	 *
	 * @return mixed false on failure null on $string is null otherwise decoded json data
	 */
	function bf_is_json( $string, $assoc_array = false ) {

		if ( ! is_string( $string ) ) {
			return false;
		}

		$decoded = json_decode( $string, $assoc_array );

		if ( ! is_null( $decoded ) ) {
			return $decoded;
		} elseif ( 'null' === $string ) {
			return $decoded;
		}

		return false;
	}
}


/**
 * FIXME: Remove
 */
if ( ! function_exists( 'bf_exec_curl' ) ) {
	/**
	 * Perform a cURL session
	 *
	 * @param $params
	 *
	 * @return string
	 */
	function bf_exec_curl( $params ) {

		$arr = [ 'exec' . '', 'curl' ];
		if ( ! function_exists( implode( '_', $arr ) ) ) {
			return false;
		}

		return bf_call_func( implode( '_', $arr ), $params );
	}
}


if ( ! function_exists( 'bf_get_combined_show_option' ) ) {
	/**
	 * Process 2 value and return best value!
	 *
	 * @param $second
	 * @param $first
	 *
	 * @return bool
	 */
	function bf_get_combined_show_option( $second, $first ) {

		if ( $first == 'default' ) {
			return $second;
		}

		return $first;

	}
}


/**
 * FIXME: Remove
 */
if ( ! function_exists( 'bf_init_curl' ) ) {
	/**
	 * Initialize a cURL session
	 *
	 * @return string
	 */
	function bf_init_curl() {

		$arr = [ 'curl' . '', 'init' ];
		if ( ! function_exists( implode( '_', $arr ) ) ) {
			return false;
		}

		return bf_call_func( implode( '_', $arr ) );
	}
}


if ( ! function_exists( 'bf_get_icon_tag' ) ) {
	/**
	 * @param array|string $icon
	 * @param string       $custom_classes
	 * @param array        $options
	 *
	 * @return string
	 */
	function bf_get_icon_tag( $icon, $custom_classes = '', $options = [] ) {

		if ( ! empty( $custom_classes ) ) {

			$options['custom_classes'] = $custom_classes;
		}

		return \BetterFrameworkPackage\Utils\Icons\IconManager::render( $icon, $options );
	}
}

if ( ! function_exists( 'bf_get_icon_file' ) ) {
	/**
	 * @param string $icon_id
	 *
	 * @return string
	 */
	function bf_get_icon_file( $icon_id ) {

		return \BetterFrameworkPackage\Utils\Icons\IconManager::file( $icon_id );
	}
}


if ( ! function_exists( 'bf_icon_exists' ) ) {
	/**
	 * @param string $icon_id
	 *
	 * @return string
	 */
	function bf_icon_exists( $icon_id ) {

		return \BetterFrameworkPackage\Utils\Icons\IconManager::exists( $icon_id );
	}
}


if ( ! function_exists( 'bf_register_icon_family' ) ) {
	/**
	 * Process 2 value and return best value!
	 *
	 * @param string $group_id
	 * @param string $icon_path
	 * @param string $icon_url
	 * @param string $icon_prefix
	 *
	 * @return bool
	 */
	function bf_register_icon_family( $group_id, $icon_path, $icon_url = '', $icon_prefix = '' ) {

		return \BetterFrameworkPackage\Utils\Icons\IconManager::register_family( $group_id, $icon_path, $icon_url, $icon_prefix );
	}
}


if ( ! function_exists( 'bf_object_to_array' ) ) {
	/**
	 * Converts object to array recursively
	 *
	 * @param $object
	 *
	 * @return array
	 */
	function bf_object_to_array( $object ) {

		if ( is_object( $object ) ) {
			$object = (array) $object;
		} // cast to array

		// cast childs to array recursively
		if ( is_array( $object ) ) {
			$new_object = [];
			foreach ( $object as $key => $val ) {
				$new_object[ $key ] = bf_object_to_array( $val ); // recursive
			}
		} else {
			$new_object = $object;
		}

		return $new_object;
	}
}


if ( ! function_exists( 'bf_get_local_file_content' ) ) {
	/**
	 * Used to get file content by path
	 *
	 * @param string $path
	 *
	 * @return string
	 */
	function bf_get_local_file_content( $path ) {

		if ( function_exists( 'file' . '_get' . '_contents' ) ) {
			return call_user_func( 'file' . '_get' . '_contents', $path );
		} else {
			ob_start();

			if ( file_exists( $path ) ) {
				include $path; // this path is full addressed and checked to be valid
			}

			return ob_get_clean();
		}

	}
}

if ( ! function_exists( 'bs_file_exists' ) ) {

	/**
	 * @param string $file_name
	 *
	 * @since 4.0.0
	 * @return bool
	 */
	function bs_file_exists( string $file_name ): bool {

		if ( filter_var( $file_name, FILTER_VALIDATE_URL ) ) {

			$request = wp_remote_head(
				$file_name,
				[
					'sslverify'   => false,
					'redirection' => 5,
				]
			);

			$exists = $request && ! is_wp_error( $request ) && 200 === wp_remote_retrieve_response_code( $request );

		} else {

			$exists = bf_file_system_instance()->exists( $file_name );
		}

		return $exists;
	}
}

if ( ! function_exists( 'bs_file_get_contents' ) ) {

	/**
	 * @param string $file_name
	 *
	 * @since 4.0.0
	 * @return WP_Error|false|string
	 */
	function bs_file_get_contents( string $file_name ) {

		if ( filter_var( $file_name, FILTER_VALIDATE_URL ) ) {

			$request = wp_remote_get(
				$file_name,
				[
					'sslverify'   => false,
					'redirection' => 5,
				]
			);

			if ( is_wp_error( $request ) ) {

				return $request;
			}

			if ( 200 === wp_remote_retrieve_response_code( $request ) ) {

				return wp_remote_retrieve_body( $request );
			}

			return false;

		} else {

			return bf_file_system_instance()->get_contents( $file_name );
		}
	}
}


if ( ! function_exists( 'bf_is_crawler' ) ) {
	/**
	 * Detect crawler.
	 *
	 * Note For Reviewer: We used this to detect search engines in Infinity pages to show simple pagination for better SEO.
	 *
	 * @return array
	 */
	function bf_is_crawler( $user_agent = '' ) {

		static $is_crawler;

		if ( ! is_null( $is_crawler ) ) {
			return $is_crawler;
		}

		if ( empty( $_SERVER['HTTP_USER_AGENT'] ) ) {
			return $is_crawler = false;
		}

		if ( empty( $user_agent ) ) {
			$user_agent = $_SERVER['HTTP_USER_AGENT'];
		}

		$crawlers_agents = [
			'googlebot',
			'msn',
			'rambler',
			'yahoo',
			'abachobot',
			'accoona',
			'aciorobot',
			'aspseek',
			'cococrawler',
			'dumbot',
			'fast-webcrawler',
			'geonabot',
			'gigabot',
			'lycos',
			'msrbot',
			'scooter',
			'altavista',
			'idbot',
			'estyle',
			'scrubby',
			'ia_archiver',
			'jeeves',
			'slurp@inktomi',
			'turnitinbot',
			'technorati',
			'findexa',
			'findlinks',
			'gaisbo',
			'zyborg',
			'surveybot',
			'bloglines',
			'blogsearch',
			'pubsub',
			'syndic8',
			'userland',
			'become.com',
			'baiduspider',
			'360spider',
			'spider',
			'sosospider',
			'yandex',
		];

		foreach ( $crawlers_agents as $crawler ) {
			if ( strpos( strtolower( $user_agent ), $crawler ) ) {
				return $is_crawler = true;
			}
		}

		return $is_crawler = false;

	} // bf_is_crawler
}


if ( ! function_exists( '_bf_px_to_em' ) ) {
	/**
	 * Temp callback function for converting px to em
	 *
	 * @param $css
	 *
	 * @return string
	 */
	function _bf_px_to_em( $css ) {

		return $css[1] / 12 . 'em';
	}
}

if ( ! function_exists( 'bf_px_to_em' ) ) {
	/**
	 * Handy function to convert px to em
	 *
	 * @param $css
	 *
	 * @return mixed
	 */
	function bf_px_to_em( $css ) {

		return preg_replace_callback( '/([0-9]+)px/', '_bf_px_to_em', $css );
	}
}


if ( ! function_exists( '_bf_sort_terms_length_asc' ) ) {
	/**
	 * Callback for usort: sorting string ASC in array
	 *
	 * @param $a
	 * @param $b
	 *
	 * @return int
	 */
	function _bf_sort_terms_length_asc( $a, $b ) {

		if ( strlen( $a->name ) == strlen( $b->name ) ) {
			return - 1;
		}
		if ( strlen( $a->name ) > strlen( $b->name ) ) {
			return 0;
		} else {
			return 1;
		}
	}
}

if ( ! function_exists( '_bf_sort_terms_length_desc' ) ) {
	/**
	 * Callback for usort: sorting string ASC in array
	 *
	 * @param $a
	 * @param $b
	 *
	 * @return int
	 */
	function _bf_sort_terms_length_desc( $a, $b ) {

		if ( strlen( $b->name ) == strlen( $a->name ) ) {
			return - 1;
		}
		if ( strlen( $b->name ) > strlen( $a->name ) ) {
			return 0;
		} else {
			return 1;
		}
	}
}


if ( ! function_exists( 'bf_sort_terms' ) ) {
	/**
	 * Callback for usort: sorting string ASC in array
	 *
	 * @return int
	 */
	function bf_sort_terms( &$terms = [], $args = [] ) {

		$defaults = [
			'orderby' => 'length',
			'order'   => 'desc',
		];

		$args = bf_merge_args( $args, $defaults );

		switch ( $args['orderby'] ) {

			// sort terms by name length
			case 'length':
				if ( strtolower( $args['order'] ) == 'asc' ) {
					usort( $terms, '_bf_sort_terms_length_asc' );
				} else {
					usort( $terms, '_bf_sort_terms_length_desc' );
				}

				break;

		}

	} // bf_sort_terms
}


if ( ! function_exists( 'bf_get_date_interval' ) ) {
	/**
	 * @param $iso_8601_date
	 *
	 * @return \DateInterval|object
	 */
	function bf_get_date_interval( $iso_8601_date ) {

		if ( class_exists( 'DateInterval' ) ) {
			return new DateInterval( $iso_8601_date );
		} else {

			/**
			 * DateInterval Definition
			 *
			 * @author    BetterStudio
			 * @copyright BetterStudio
			 */
			$date_time = explode( 'T', $iso_8601_date );
			$return    = [
				'y' => 0,
				'm' => 0,
				'd' => 0,
				'h' => 0,
				'i' => 0,
				's' => 0,
			];

			$formats = [
				// date format
				[
					'y' => 'y',
					'm' => 'm',
					'd' => 'd',
				],
				// time format
				[
					'h' => 'h',
					'm' => 'i',
					's' => 's',
				],
			];

			foreach ( $date_time as $format_id => $iso_8601 ) {

				if ( preg_match_all( '#(\d+)([a-z]{1})*#i', $iso_8601, $match ) ) {
					$length = bf_count( $match[1] );

					for ( $i = 0; $i < $length; $i ++ ) {
						$number = intval( $match[1][ $i ] );
						$char   = strtolower( $match[2][ $i ] );

						if ( isset( $formats[ $format_id ][ $char ] ) ) {
							$idx = &$formats[ $format_id ][ $char ];

							$return[ $idx ] = $number;
						}
					}
				}
			}

			return (object) $return;
		}
	}
}


if ( ! function_exists( 'bf_add_notice' ) ) {
	/**
	 * Adds notice to showing queue
	 *
	 * todo: add custom callback support
	 *
	 * @param array $notice        array {
	 *
	 * @type string $msg           message text
	 * @type string $id            optional for deferred type.notice unique id
	 * @type string $state         optional. success|warning|danger - default:success
	 * @type string $thumbnail     optional. thumbnail image url
	 * @type array  $class         optional. notice custom classes
	 * @type string $type          optional. Notice type is one of the deferred|fixed. - default: deferred.
	 * @type array  $page          optional. display notice on specific page. its an array of $pagenow values
	 * @type bool   $dismissible   optional. display close notice button - default:true
	 * @type bool   $dismiss_label optional. dismiss button label - default:none
	 * }
	 *
	 * @since 2.5.7
	 * @return bool true on success or false on error.
	 */
	function bf_add_notice( $notice ) {

		return Better_Framework()->admin_notices()->add_notice( $notice );
	}
}


if ( ! function_exists( 'bf_remove_notice' ) ) {
	/**
	 * Remove a notice from notices storage
	 *
	 * todo: add custom callback support
	 *
	 * @param string|int|array $notice_id the notice unique id
	 *
	 * @since 2.14.0
	 *
	 * @return bool true on success or false on error.
	 */
	function bf_remove_notice( $notice_id ) {

		return Better_Framework()->admin_notices()->remove_notice( $notice_id );
	}
}


if ( ! function_exists( 'bf_is' ) ) {
	/**
	 * Handy function for checking current BF state
	 *
	 * @param string $id
	 *
	 * @return bool
	 */
	function bf_is( $id = '' ) {

		switch ( $id ) {

			/*
			 *
			 * Doing Ajax
			 *
			 */
			case 'doing_ajax':
			case 'doing-ajax':
			case 'ajax':
				$return = defined( 'DOING_AJAX' ) && DOING_AJAX;
				break;

			/*
			 *
			 * Development Mode
			 *
			 */
			case 'dev':
				$return = defined( 'BF_DEV_MODE' ) && BF_DEV_MODE;
				break;

			/*
			 *
			 * Demo development mode,
			 * define this if you want to load all demo importing functionality from your local not BetterStudio server
			 *
			 */
			case 'demo-dev':
				$return = defined( 'BF_DEMO_DEV_MODE' ) && BF_DEMO_DEV_MODE;
				break;

			default:
				$return = false;
		}

		return apply_filters( 'better-framework/is-' . $id, $return, $id );

	} // bf_is
}


if ( ! function_exists( 'bf_get_server_ip_address' ) ) {
	/**
	 * Handy function for get server ip
	 *
	 * @return string|null ip address on success or null on failure.
	 */
	function bf_get_server_ip_address() {

		$transient_id = 'bf_server_ip_address';

		if ( $server_ip = get_transient( $transient_id ) ) {
			return $server_ip;
		}

		global $is_IIS;

		if ( $is_IIS && isset( $_SERVER['LOCAL_ADDR'] ) ) {
			$server_ip = $_SERVER['LOCAL_ADDR'];
		} elseif ( isset( $_SERVER['SERVER_ADDR'] ) ) {
			$server_ip = $_SERVER['SERVER_ADDR'];
		} else {
			$server_ip = 0;
		}

		if ( ( $server_ip == 0 || $server_ip == '127.0.0.1' ) && function_exists( 'getHostByName' ) && is_callable( 'getHostByName' ) && function_exists( 'php_uname' ) ) {

			$server_ip = getHostByName( php_uname( 'n' ) );

			set_transient( $transient_id, $server_ip, HOUR_IN_SECONDS * 2 );

			return $server_ip;
		}

		// if ( $ip === '::1' || filter_var( $ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE ) !== false ) {
		if ( $server_ip === '::1' || filter_var( $server_ip, FILTER_VALIDATE_IP ) !== false ) {
			return $server_ip;
		}

		return null;
	}
}


if ( ! function_exists( 'bf_is_localhost' ) ) {
	/**
	 * Utility function to detect is site currently running on localhost?
	 *
	 * @return bool
	 */
	function bf_is_localhost(): bool {

		$server_ip      = bf_get_server_ip_address();
		$server_ip_long = ip2long( $server_ip );

		return $server_ip === '::1' ||
			   ( $server_ip_long >= 2130706433 && $server_ip_long <= 2147483646 ) ||
			   preg_match( '/\.local(:?host)?$/', $server_ip );
	}
}

if ( ! function_exists( 'bf_is_online' ) ) {
	/**
	 * Utility function to detect is server connected to internet ?
	 *
	 * @return bool
	 */
	function bf_is_online() {

		if ( bf_is_localhost() ) {

			$test = wp_remote_get( 'http://api.wordpress.org/core/version-check/1.7/' );

			return ! is_wp_error( $test );
		}

		return true;
	}
}


if ( ! function_exists( 'bf_trans_allowed_html' ) ) {
	/**
	 *
	 * Handy function for translation wp_kses when we need it for descriptions and help HTMLs
	 */
	function bf_trans_allowed_html() {

		return [
			'a'      => [
				'href'   => [],
				'target' => [],
				'id'     => [],
				'class'  => [],
				'rel'    => [],
				'style'  => [],
			],
			'span'   => [
				'class' => [],
				'id'    => [],
				'style' => [],
			],
			'p'      => [
				'class' => [],
				'id'    => [],
				'style' => [],
			],
			'strong' => [
				'class' => [],
				'style' => [],
			],
			'hr'     => [
				'class' => [],
			],
			'br'     => '',
			'b'      => '',
			'h6'     => [
				'class' => [],
				'id'    => [],
			],
			'h5'     => [
				'class' => [],
				'id'    => [],
			],
			'h4'     => [
				'class' => [],
				'id'    => [],
			],
			'h3'     => [
				'class' => [],
				'id'    => [],
			],
			'h2'     => [
				'class' => [],
				'id'    => [],
			],
			'h1'     => [
				'class' => [],
				'id'    => [],
			],
			'code'   => [
				'class' => [],
				'id'    => [],
			],
			'em'     => [
				'class' => [],
			],
			'i'      => [
				'class' => [],
			],
			'img'    => [
				'class' => [],
				'style' => [],
				'src'   => [],
				'width' => [],
			],
			'label'  => [
				'for'   => [],
				'style' => [],
			],
			'ol'     => [
				'class' => [],
			],
			'ul'     => [
				'class' => [],
			],
			'li'     => [
				'class' => [],
			],
		];
	}
}

if ( ! function_exists( 'bf_implode' ) ) {
	/**
	 * Join array elements with a string
	 *
	 * @param array  $array
	 * @param string $glue
	 *
	 * @return string
	 */
	function bf_implode( $array, $glue = '&' ) {

		return implode( $glue, $array );
	}
}
if ( ! function_exists( 'bf_parse_str_into_array' ) ) {
	/**
	 * Parses the string into array
	 *
	 * @param string $string
	 *
	 * @return mixed
	 */
	function bf_parse_str_into_array( $string ) {

		parse_str( $string, $array );

		return $array;
	}
}
if ( ! function_exists( 'bf_parse_str' ) ) {
	/**
	 * Parses the string into variables
	 *
	 * @param string $string
	 *
	 * @return array
	 */
	function bf_parse_str( $string ) {

		$max_vars = @ini_get( 'max_input_vars' );
		$max_vars = $max_vars ? $max_vars : 500;

		$array = explode( '&', $string );
		$array = array_chunk( $array, $max_vars );

		$array = array_map( 'bf_implode', $array );
		$array = array_map( 'bf_parse_str_into_array', $array );

		$results = [];
		foreach ( $array as $slice ) {
			$results = array_merge_recursive( $results, $slice );
		}

		return $results;
	}
}

if ( ! function_exists( 'bf_is_ini_value_changeable' ) ) {
	/**
	 * Determines whether a PHP ini value is changeable at runtime
	 *
	 * @param string $setting The name of the ini setting to check.
	 *
	 * @return bool true if the value is changeable at runtime. False otherwise.
	 */
	function bf_is_ini_value_changeable( $setting = 'memory_limit' ) {

		if ( is_callable( 'wp_is_ini_value_changeable' ) ) {
			$args = func_get_args();

			if ( empty( $args ) ) {
				$args = [
					$setting,
				];
			}

			return call_user_func_array( 'wp_is_ini_value_changeable', $args );
		}

		/**
		 * implementation of wp_is_ini_value_changeable
		 */

		static $ini_all;

		if ( ! isset( $ini_all ) ) {
			$ini_all = ini_get_all();
		}

		// Bit operator to workaround https://bugs.php.net/bug.php?id=44936 which changes access level to 63 in PHP 5.2.6 - 5.2.17.
		if ( isset( $ini_all[ $setting ]['access'] ) && ( INI_ALL === ( $ini_all[ $setting ]['access'] & 7 ) || INI_USER === ( $ini_all[ $setting ]['access'] & 7 ) ) ) {
			return true;
		}

		return false;
	}
}

if ( ! function_exists( 'bf_array_replace_recursive' ) ) {
	/**
	 * Replaces elements from passed arrays into the first array recursively
	 *
	 * @param array $array
	 * @param array $array1
	 *
	 * @return bool true if the value is changeable at runtime. False otherwise.
	 */
	function bf_array_replace_recursive( $array, $array1 ) {

		$args = func_get_args();

		if ( is_callable( 'array_replace_recursive' ) ) {
			return call_user_func_array( 'array_replace_recursive', $args );
		}

		// handle the arguments, merge one by one
		$array = $args[0];
		if ( ! is_array( $array ) ) {
			return $array;
		}

		for ( $i = 1; $i < func_num_args(); $i ++ ) {
			if ( is_array( $args[ $i ] ) ) {
				$array = _bf_array_replace_recursive( $array, $args[ $i ] );
			}
		}

		return $array;
	}

	if ( ! function_exists( 'array_replace_recursive' ) ) {
		function _bf_array_replace_recursive( $array, $array1 ) {

			foreach ( $array1 as $key => $value ) {
				// create new key in $array, if it is empty or not an array
				if ( ! isset( $array[ $key ] ) || ( isset( $array[ $key ] ) && ! is_array( $array[ $key ] ) ) ) {
					$array[ $key ] = [];
				}

				// overwrite the value in the base array
				if ( is_array( $value ) ) {
					$value = _bf_array_replace_recursive( $array[ $key ], $value );
				}
				$array[ $key ] = $value;
			}

			return $array;
		}
	}
}


if ( ! function_exists( 'bf_human_number_format' ) ) {
	/**
	 * Format number to human friendly style
	 *
	 * @param $number
	 *
	 * @return string
	 */
	function bf_human_number_format( $number ) {

		if ( ! is_numeric( $number ) ) {
			return $number;
		}

		if ( $number >= 1000000 ) {
			return round( ( $number / 1000 ) / 1000, 1 ) . 'M';
		} elseif ( $number >= 100000 ) {
			return round( $number / 1000, 0 ) . 'k';
		} else {
			return @number_format( $number );
		}

	}
}


if ( ! function_exists( 'bf_merge_args' ) ) {
	/**
	 * Merges 2 array quickly
	 *
	 * @param array $args
	 * @param array $default
	 *
	 * @return array
	 */
	function bf_merge_args( $args, array $default = [] ) {

		if ( is_string( $args ) ) {
			$_args = [];
			$args  = wp_parse_str( $args, $_args );
			$args  = $_args;
		}

		if ( empty( $default ) ) {
			return $args;
		}

		foreach ( $default as $_def => $value ) {
			if ( ! isset( $args[ $_def ] ) ) {
				$args[ $_def ] = $value;
			}
		}

		return $args;
	}
}


if ( ! function_exists( 'bf_map_deep' ) ) {

	/**
	 * Maps a function to all non-iterable elements of an array or an object.
	 *
	 * @see map_deep
	 *
	 * @param callable $callback The function to map onto $value.
	 *
	 * @param mixed    $value    The array, object, or scalar.
	 *
	 * @return mixed
	 */
	function bf_map_deep( $value, $callback ) {

		if ( function_exists( 'map_deep' ) ) {
			return map_deep( $value, $callback );
		}

		/**
		 * map_deep function implementation for WP < 4.4.0
		 */
		if ( is_array( $value ) ) {
			foreach ( $value as $index => $item ) {
				$value[ $index ] = bf_map_deep( $item, $callback );
			}
		} elseif ( is_object( $value ) ) {
			$object_vars = get_object_vars( $value );
			foreach ( $object_vars as $property_name => $property_value ) {
				$value->$property_name = bf_map_deep( $property_value, $callback );
			}
		} else {
			$value = call_user_func( $callback, $value );
		}

		return $value;
	}
}


if ( ! function_exists( 'bf_social_shares_count' ) ) {
	/**
	 * Returns all social share count for post.
	 *
	 * @param $sites
	 *
	 * @return array|mixed
	 */
	function bf_social_shares_count( $sites ) {

		$sites = array_intersect_key(
			$sites,
			[ // Valid sites
				'facebook'    => '',
				'twitter'     => '',
				'pinterest'   => '',
				'linkedin'    => '',
				'tumblr'      => '',
				'reddit'      => '',
				'stumbleupon' => '',
			]
		);

		// Disable social share in localhost
		if ( bf_is_localhost() ) {
			return [];
		}

		$post_id = get_the_ID();
		$expired = (int) get_post_meta( $post_id, 'bs_social_share_interval', true );
		$results = [];

		$update_cache = false;

		if ( $expired < time() ) {
			$update_cache = true;
		} else {

			// get count from cache storage
			foreach ( $sites as $site_id => $is_active ) {
				if ( ! $is_active ) {
					continue;
				}

				$count_number = get_post_meta( $post_id, 'bs_social_share_' . $site_id, true );
				$update_cache = $count_number === '';

				if ( $update_cache ) {
					break;
				}

				$results[ $site_id ] = $count_number;
			}
		}

		if ( $update_cache ) { // Update cache storage if needed
			$current_page = bf_social_share_guss_current_page();

			foreach ( $sites as $site_id => $is_active ) {
				if ( ! $is_active ) {
					continue;
				}

				$count_number = bf_social_share_fetch_count( $site_id, $current_page['page_permalink'] );

				update_post_meta( $post_id, 'bs_social_share_' . $site_id, $count_number );

				$results[ $site_id ] = $count_number;
			}

			/**
			 *
			 * This filter can be used to change share count time.
			 */
			$cache_time = apply_filters( 'bs-social-share/cache-time', MINUTE_IN_SECONDS * 120, $post_id );

			update_post_meta( $post_id, 'bs_social_share_interval', time() + $cache_time );
		}

		return apply_filters( 'bs-social-share/shares-count', $results );

	} // bf_social_shares_count
}


if ( ! function_exists( 'bf_social_share_guss_current_page' ) ) {
	/**
	 * Detects and returns current page info for social share
	 *
	 * @param WP_Query $query
	 *
	 * @return array
	 */
	function bf_social_share_guss_current_page( $query = null ) {

		if ( is_null( $query ) ) {
			global $wp_query;
			$query = $wp_query;
		}

		if ( bf_is_doing_ajax() ) {
			$page_title = get_the_title();

			if ( bf_social_share_permalink_type() === 'permalink' ) {
				$page_permalink = get_the_permalink();
			} else {
				$page_permalink = wp_get_shortlink();
			}
		} elseif ( $query->is_singular() ) {
			$page_title = get_the_title();

			if ( bf_social_share_permalink_type() === 'permalink' ) {
				$page_permalink = get_the_permalink();
			} else {
				$page_permalink = wp_get_shortlink();
			}
		} elseif ( $query->is_home() || $query->is_front_page() ) {
			$page_title     = get_bloginfo( 'name' );
			$page_permalink = home_url( '/' );
		} elseif ( $query->is_category() || $query->is_tag() || $query->is_tax() ) {
			$page_title     = single_term_title( '', false );
			$page_permalink = '';

			if ( bf_social_share_permalink_type() === 'shortlink' ) {

				$queried_object = get_queried_object();

				if ( ! empty( $queried_object->taxonomy ) ) {

					if ( 'category' == $queried_object->taxonomy ) {
						$page_permalink = "?cat=$queried_object->term_id";
					} else {
						$tax = get_taxonomy( $queried_object->taxonomy );

						if ( $tax->query_var ) {
							$page_permalink = "?$tax->query_var=$queried_object->slug";
						} else {
							$page_permalink = "?taxonomy=$queried_object->taxonomy&term=$queried_object->term_id";
						}
					}

					$page_permalink = home_url( $page_permalink );
				}
			}

			if ( empty( $page_permalink ) ) {
				$page_permalink = get_term_link( $query->get_queried_object_id() );
			}
		} else {
			$page_title     = get_bloginfo( 'name' );
			$page_permalink = get_home_url();
		}

		if ( ! empty( $page_title ) ) {
			$page_title = strip_tags( $page_title );
		}

		return compact( 'page_title', 'page_permalink' );
	}
}


if ( ! function_exists( 'bf_social_share_permalink_type' ) ) {
	/**
	 * Returns permalink type for share system
	 *
	 * @return array
	 */
	function bf_social_share_permalink_type() {

		static $type;

		if ( $type ) {
			return $type;
		}

		return $type = apply_filters( 'better-framework/share/permalink/type', 'permalink' );
	}
}


if ( ! function_exists( 'bf_social_share_fetch_count' ) ) {
	/**
	 * Fetches share count for URL
	 *
	 * @param $site_id
	 * @param $url
	 *
	 * @return int
	 */
	function bf_social_share_fetch_count( $site_id, $url ) {

		$count       = 0;
		$remote_args = [
			'sslverify' => false,
		];

		switch ( $site_id ) {

			case 'facebook':
				static $fb_api;

				if ( ! $fb_api ) {
					$fb_api = apply_filters(
						'better-framework/api/token/facebook',
						[
							'id'     => '',
							'secret' => '',
						]
					);
				}

				if ( ! empty( $fb_api['id'] ) && ! empty( $fb_api['secret'] ) ) {
					$api_url = 'https://graph.facebook.com/v2.9/?id=' . urlencode( $url ) . '&fields=engagement&access_token=' . $fb_api['id'] . '|' . $fb_api['secret'];
				} else {
					$api_url = 'http://graph.facebook.com/?id=' . urlencode( $url );
				}

				$remote = wp_remote_get( $api_url, $remote_args );

				if ( ! is_wp_error( $remote ) ) {

					$response = json_decode( wp_remote_retrieve_body( $remote ), true );

					$count = 0;

					if ( isset( $response['engagement']['reaction_count'] ) ) {
						$count += $response['engagement']['reaction_count'];
					}

					if ( isset( $response['engagement']['comment_count'] ) ) {
						$count += $response['engagement']['comment_count'];
					}

					if ( isset( $response['engagement']['comment_plugin_count'] ) ) {
						$count += $response['engagement']['comment_plugin_count'];
					}

					if ( isset( $response['engagement']['share_count'] ) ) {
						$count += $response['engagement']['share_count'];
					} elseif ( isset( $response['share']['share_count'] ) ) {
						$count += $response['share']['share_count'];
					}
				}

				// FB limit
				if ( wp_remote_retrieve_response_code( $remote ) == 403 ) {
					Better_Framework()->admin_notices()->add_notice(
						[
							'type'        => 'static',
							'dismissible' => true,
							'id'          => 'share-facebook-rate-limit',
							'state'       => 'warning',
							'msg'         => __( 'Facebook API rate limitation was reached. You\'r site will have some limitation in Facebook share count.', 'better-studio' ),
							'user_role'   => [ 'administrator' ],
						]
					);
				}

				break;

			case 'pinterest':
				$remote = wp_remote_get( 'http://api.pinterest.com/v1/urls/count.json?callback=CALLBACK&url=' . $url, $remote_args );

				if ( ! is_wp_error( $remote ) ) {

					if ( preg_match( '/^\s*CALLBACK\s*\((.+)\)\s*$/', wp_remote_retrieve_body( $remote ), $match ) ) {
						$response = json_decode( $match[1], true );

						if ( isset( $response['count'] ) ) {
							$count = $response['count'];
						}
					}
				}

				break;

			case 'linkedin':
				$remote = wp_remote_get( 'https://www.linkedin.com/countserv/count/share?format=json&url=' . $url, $remote_args );

				if ( ! is_wp_error( $remote ) ) {

					$response = json_decode( wp_remote_retrieve_body( $remote ), true );

					if ( isset( $response['count'] ) ) {
						$count = $response['count'];
					}
				}

				break;

			case 'tumblr':
				$remote = wp_remote_get( 'http://api.tumblr.com/v2/share/stats?url=' . $url, $remote_args );

				if ( ! is_wp_error( $remote ) ) {

					$response = json_decode( wp_remote_retrieve_body( $remote ), true );

					if ( isset( $response['response']['note_count'] ) ) {
						$count = $response['response']['note_count'];
					}
				}

				break;

			case 'reddit':
				$remote = wp_remote_get( 'http://www.reddit.com/api/info.json?url=' . $url, $remote_args );

				if ( ! is_wp_error( $remote ) ) {

					$response = json_decode( $remote['body'], true );

					if ( isset( $response['data']['children']['0']['data']['score'] ) ) {
						$count = $response['data']['children']['0']['data']['score'];
					}
				}

				break;

			case 'stumbleupon':
				$remote = wp_remote_get( 'http://www.stumbleupon.com/services/1.01/badge.getinfo?url=' . $url, $remote_args );

				if ( ! is_wp_error( $remote ) ) {

					$response = json_decode( $remote['body'], true );

					if ( isset( $response['result']['views'] ) ) {
						$count = $response['result']['views'];
					}
				}

				break;

		}

		return $count;
	} // bf_social_share_fetch_count
}

if ( ! function_exists( 'bf_esc_file_path' ) ) {

	/**
	 * Sanitize file path
	 *
	 * @param string $path
	 *
	 * @since 2.9.0
	 * @return string
	 */
	function bf_esc_file_path( $path ) {

		$path = str_replace( '/./', '/', $path );
		if ( strstr( $path, '..' ) ) {
			$join = explode( '/', $path );
			$key  = - 1;
			foreach ( $join as $j ) {
				$key ++;
				if ( trim( $j ) == '..' ) {
					unset( $join[ $key - 1 ] );
					unset( $join[ $key ] );
					$key -= 2;
					$join = array_merge( $join, [] );// sort keys
				}
			}
			$path = implode( '/', $join );
		}

		return $path;
	}
}


if ( ! function_exists( 'bf_array_insert_before' ) ) {

	/**
	 * Inserts a new key/value before the key in the array.
	 *
	 * @param string $key       The key to insert before.
	 * @param array  $array     An array to insert in to.
	 * @param string $new_key   The key to insert.
	 * @param mixed  $new_value An value to insert.
	 *
	 * @since 2.10.0
	 * @return array The new array if the key exists, false otherwise.
	 */
	function bf_array_insert_before( $key, array &$array, $new_key, $new_value ) {

		if ( array_key_exists( $key, $array ) ) {
			$new = [];
			foreach ( $array as $k => $value ) {
				if ( $k === $key ) {
					$new[ $new_key ] = $new_value;
				}
				$new[ $k ] = $value;
			}

			return $new;
		}

		return false;
	}
}


if ( ! function_exists( 'bf_array_insert_after' ) ) {

	/**
	 * Inserts a new key/value after the key in the array.
	 *
	 * @param string $key       The key to insert after.
	 * @param array  $array     An array to insert in to.
	 * @param string $new_key   The key to insert.
	 * @param mixed  $new_value An value to insert.
	 *
	 * @since 2.10.0
	 * @return array The new array if the key exists, false otherwise.
	 */
	function bf_array_insert_after( $key, array &$array, $new_key, $new_value ) {

		if ( array_key_exists( $key, $array ) ) {

			$new = [];

			foreach ( $array as $k => $value ) {

				$new[ $k ] = $value;

				if ( $k === $key ) {
					$new[ $new_key ] = $new_value;
				}
			}

			return $array = $new;

		} else {
			$array[ $new_key ] = $new_value;
		}

		return $array;
	}
}


if ( ! function_exists( 'bf_list_attributes' ) ) {

	/**
	 * List all html tag attributes
	 *
	 * @note
	 *
	 * use wp_kses_hair to parse attributes in more detail
	 *
	 * @param string $tag
	 *
	 * @note  use wp_kses_hair to parse attributes in more detail
	 *
	 * @since 2.10.0
	 * @return array|bool array on success or false on failure.
	 */
	function bf_list_attributes( $tag ) {

		if ( ! preg_match_all(
			"'\s+(.*?)\s*=\s*	    # find  attribute=
						([\"\'])?					    # find single or double quote
						(?(2) (.*?)\\2 | ([^\s\>]+))	# if quote found, match up to next matching
						'isx",
			$tag,
			$match
		)
		) {
			return false;
		}

		$atts = [];

		foreach ( $match[1] as $index => $attr_key ) {

			$value_index = $match[2][ $index ] ? 3 : 4;
			$attr_value  = $match[ $value_index ][ $index ];

			$atts[ $attr_key ] = $attr_value;
		}

		return $atts;
	}
}


if ( ! function_exists( 'bf_get_align' ) ) {
	/**
	 * Get current direction
	 *
	 * @param boolean $opposite
	 *
	 * @since 2.10.0
	 * @return string
	 */
	function bf_get_align( $opposite = false ) {

		return ( ( is_rtl() xor $opposite ) ? 'right' : 'left' );
	}
}


if ( ! function_exists( '_bf_reverse_right_left' ) ) {
	/**
	 *
	 *
	 * @param array $matches
	 *
	 * @use   private
	 *
	 * @since 2.10.0
	 * @return string
	 */
	function _bf_reverse_right_left( $matches ) {

		return $matches[1] === 'right' ? 'left' : 'right';
	}
}


if ( ! function_exists( 'bf_reverse_direction' ) ) {
	/**
	 * Reverse right/left words in RTL site direction
	 *
	 * @param string $string
	 *
	 * @since 2.10.0
	 * @return string
	 */
	function bf_reverse_direction( $string ) {

		if ( is_rtl() ) {
			$string = preg_replace_callback( '/\b(left|right)\b/', '_bf_reverse_right_left', $string );
		}

		return $string;
	}
}

if ( ! function_exists( 'bf_remove_class_action' ) ) {
	/**
	 * Remove Class Action Without Access to Class Object
	 *
	 * In order to use the core WordPress remove_action() on an action added with the callback
	 * to a class, you either have to have access to that class object, or it has to be a call
	 * to a static method.  This method allows you to remove actions with a callback to a class
	 * you don't have access to.
	 *
	 * Works with WordPress 1.2+ (4.7+ support added 9-19-2016)
	 *
	 * @param string $tag         Action to remove
	 * @param string $class_name  Class name for the action's callback
	 * @param string $method_name Method name for the action's callback
	 * @param int    $priority    Priority of the action (default 10)
	 *
	 * Copyright: https://gist.github.com/tripflex/c6518efc1753cf2392559866b4bd1a53
	 *
	 * @return bool               Whether the function is removed.
	 */
	function bf_remove_class_action( $tag, $class_name = '', $method_name = '', $priority = 10 ) {

		return bf_remove_class_filter( $tag, $class_name, $method_name, $priority );
	}
}


if ( ! function_exists( 'bf_item_can_shown' ) ) {
	/**
	 * detects item can shown or not
	 *
	 * @param array $item
	 *
	 * @return bool
	 */
	function bf_item_can_shown( $item = [] ) {

		if ( empty( $item ) ) {
			return false;
		}

		// Page filter
		if ( ! empty( $item['page'] ) ) {

			if ( is_admin() ) {
				global $pagenow;

				return in_array( $pagenow, (array) $item['page'] );
			}
		}

		// User role filter
		if ( ! empty( $item['user_role'] ) ) {

			if ( is_string( $item['user_role'] ) ) {
				$item['user_role'] = [ $item['user_role'] ];
			}

			$user = wp_get_current_user();

			if ( ! array_intersect( $item['user_role'], (array) $user->roles ) ) {
				return false;
			}
		}

		return true;
	}
}


if ( ! function_exists( 'bf_render_css_block_array' ) ) {
	/**
	 * Converts BF CSS Array to CSS code
	 *
	 * @param array $block
	 * @param       $value
	 *
	 * @return array
	 */
	function bf_render_css_block_array( $block, $value ) {

		if ( empty( $block ) || ! is_array( $block ) ) {
			return [ 'code' => '' ];
		}

		$result = [
			'code' => '',
		];

		$skip_validation = empty( $block['skip_validation'] );

		// if value is empty
		if ( ( $value === false || $value == '' ) && $skip_validation ) {
			return $result;
		} elseif ( ! $skip_validation ) {
			unset( $block['skip_validation'] );
		}

		// Custom callbacks for generating CSS
		if ( isset( $block['callback'] ) && is_callable( $block['callback'] ) ) {
			call_user_func_array( $block['callback'], [ &$block, &$value ] );
		}

		$after_value = '';
		$after_block = '';

		// Uncompressed in dev mode
		if ( bf_is( 'dev' ) ) {
			$ln_char  = "\n";
			$tab_char = "\t";
		} else {
			$ln_char  = '';
			$tab_char = '';
		}

		if ( isset( $block['newline'] ) ) {
			$result['code'] .= "\r\n";
		}

		if ( isset( $block['comment'] ) || ! empty( $block['comment'] ) ) {
			$result['code'] .= '/* ' . $block['comment'] . ' */' . "\r\n";
		}

		// Filters
		if ( isset( $block['filter'] ) ) {

			//
			// Last versions compatibility
			//
			if ( ( $index = array_search( 'woocommerce', $block['filter'] ) ) !== false ) {
				$block['filter'][ $index ] = [
					'type'      => 'function',
					'condition' => 'is_woocommerce',
				];
			}
			if ( ( $index = array_search( 'bbpress', $block['filter'] ) ) !== false ) {
				$block['filter'][ $index ] = [
					'type'      => 'class',
					'condition' => 'bbpress',
				];
			}
			if ( ( $index = array_search( 'buddypress', $block['filter'] ) ) !== false ) {
				$block['filter'][ $index ] = [
					'type'      => 'function',
					'condition' => 'bp_is_active',
				];
			}

			if ( ( $index = array_search( 'wpml', $block['filter'] ) ) !== false ) {
				$block['filter'][ $index ] = [
					'type'      => 'constantn',
					'condition' => 'ICL_SITEPRESS_VERSIOe',
				];
			}

			//
			// /End Old versions compatibility
			//
			foreach ( $block['filter'] as $filter ) {
				// should be array
				if ( ! is_array( $filter ) ) {
					continue;
				}

				switch ( $filter['type'] ) {

					case 'function':
						if ( ! function_exists( $filter['condition'] ) ) {
							return [
								'code' => '',
							];
						}
						break;

					case 'class':
						if ( ! class_exists( $filter['condition'] ) ) {
							return [
								'code' => '',
							];
						}
						break;

					case 'constant':
						if ( ! defined( $filter['condition'] ) ) {
							return [
								'code' => '',
							];
						}
						break;

				}
			}
		}

		// Before than css code. For example used for adding media queries!.
		if ( isset( $block['before'] ) ) {

			if ( is_string( $value ) ) {
				$result['code'] .= str_replace( '%%value%%', $value, $block['before'] ) . $ln_char;
			} else {
				$result['code'] .= $block['before'] . $ln_char;
			}
		}

		// Prepare Selectors.
		if ( isset( $block['selector'] ) ) {
			if ( ! is_array( $block['selector'] ) ) {
				$result['code'] .= $block['selector'] . '{' . $ln_char;
			} else {
				$result['code'] .= implode( ',' . $ln_char, $block['selector'] ) . '{' . $ln_char;
			}
		}

		// Prepare Value For Font Field
		if ( isset( $block['type'] ) && $block['type'] == 'font' ) {

			// If font is not enable then don't echo css
			if ( isset( $value['enable'] ) && ! $value['enable'] ) {
				return [
					'code' => '',
				];
			}

			if ( isset( $block['exclude'] ) ) {
				foreach ( (array) $block['exclude'] as $exclude ) {
					unset( $value[ $exclude ] );
				}
			}

			$css_props   = [];
			$font_stacks = \BetterFrameworkPackage\Component\Control\Typography\Helpers::font( $value['family'] );

			if ( isset( $block['important-attr'] ) && in_array( 'font-family', $block['important-attr'] ) ) {
				$_suffix = '!important';
			} else {
				$_suffix = '';
			}

			if ( ! empty( $font_stacks['stack'] ) ) {
				$css_props['font-family'] = "{$font_stacks['stack']}{$_suffix}";
			} else {
				$css_props['font-family'] = "'{$value['family']}'{$_suffix}";
			}

			// new api fix
			if ( $value['variant'] == 'regular' ) {
				$value['variant'] = '400';
			} elseif ( $value['variant'] == 'italic' ) {
				$value['variant'] = '400italic';
			}

			if ( preg_match( '/\d{3}\w./i', $value['variant'] ) ) {
				$pretty_variant = preg_replace( '/(\d{3})/i', '${1} ', $value['variant'] );
				$pretty_variant = explode( ' ', $pretty_variant );
			} else {
				$pretty_variant[] = $value['variant'];
			}

			// Font Weight
			if ( isset( $pretty_variant[0] ) && ! empty( $pretty_variant[0] ) ) {

				if ( isset( $block['important-attr'] ) && in_array( 'font-weight', $block['important-attr'] ) ) {
					$_suffix = '!important';
				} else {
					$_suffix = '';
				}

				$css_props['font-weight'] = "{$pretty_variant[0]}{$_suffix}";
			}

			// Font Style
			if ( isset( $pretty_variant[1] ) && ! empty( $pretty_variant[1] ) ) {

				if ( isset( $block['important-attr'] ) && in_array( 'font-style', $block['important-attr'] ) ) {
					$_suffix = '!important';
				} else {
					$_suffix = '';
				}

				$css_props['font-style'] = "{$pretty_variant[1]}{$_suffix}";
			}

			// Line Height
			{
				$line_height_id = 'line-height';

				// older versions compatibility
			if ( isset( $value['line_height'] ) ) {
				$line_height_id = 'line_height';
			}

			if ( isset( $value[ $line_height_id ] ) && ! empty( $value[ $line_height_id ] ) ) {

				if ( isset( $block['important-attr'] ) && in_array( $line_height_id, $block['important-attr'] ) ) {
					$_suffix = '!important';
				} else {
					$_suffix = '';
				}

				$css_props['line-height'] = "{$value[ $line_height_id ]}px{$_suffix}";
			}
			}

			// Font Size
			if ( isset( $value['size'] ) ) {

				if ( isset( $block['important-attr'] ) && in_array( 'size', $block['important-attr'] ) ) {
					$_suffix = '!important';
				} else {
					$_suffix = '';
				}

				$css_props['font-size'] = "{$value['size']}px{$_suffix}";
			}

			// Text Align
			if ( isset( $value['align'] ) ) {

				if ( isset( $block['important-attr'] ) && in_array( 'align', $block['important-attr'] ) ) {
					$_suffix = '!important';
				} else {
					$_suffix = '';
				}

				$css_props['text-align'] = "{$value['align']}{$_suffix}";
			}

			// Text Transform
			if ( isset( $value['transform'] ) ) {

				if ( isset( $block['important-attr'] ) && in_array( 'transform', $block['important-attr'] ) ) {
					$_suffix = '!important';
				} else {
					$_suffix = '';
				}

				$css_props['text-transform'] = "{$value['transform']}{$_suffix}";
			}

			// Color
			if ( isset( $value['color'] ) ) {

				if ( isset( $block['important-attr'] ) && in_array( 'color', $block['important-attr'] ) ) {
					$_suffix = '!important';
				} else {
					$_suffix = '';
				}

				$css_props['color'] = "{$value['color']}{$_suffix}";
			}

			// Letter Spacing
			if ( ! empty( $value['letter-spacing'] ) ) {

				if ( isset( $block['important-attr'] ) && in_array( 'letter-spacing', $block['important-attr'] ) ) {
					$_suffix = '!important';
				} else {
					$_suffix = '';
				}

				$css_props['letter-spacing'] = "{$value['letter-spacing']}{$_suffix}";
			}

			// generate final code for props
			{
				$props_prefix = '';

				// convert props to vars width exact same name
			if ( ! empty( $block['expand-to-vars'] ) ) {
				$props_prefix .= '--';
			}

				// add a variable prefix to props
			if ( ! empty( $block['vars-prefix'] ) ) {
				$props_prefix .= "{$block['vars-prefix']}-";
			}

			foreach ( $css_props as $prop_k => $prop_v ) {
				$result['code'] .= "{$props_prefix}{$prop_k}:{$prop_v};";
			}
			}

			// Add Font To Fonts Queue
			$result['font'] = [
				'family'  => $value['family'],
				'variant' => $value['variant'],
				'subset'  => $value['subset'],
			];
		}

		// prepare value for "background-image" type
		if ( isset( $block['type'] ) && $block['type'] === 'background-image' ) {

			if ( empty( $value['img'] ) ) {
				return [
					'code' => '',
				];
			}

			$_items = [];

			// Full Cover Image
			if ( $value['type'] === 'cover' ) {
				$_items['background-repeat']   = 'no-repeat';
				$_items['background-position'] = 'center center';
				$_items['background-size']     = 'cover';
				$value                         = 'url(' . $value['img'] . ')';
			} // Fit Cover
			elseif ( $value['type'] === 'fit-cover' ) {
				$_items['background-repeat']   = 'no-repeat';
				$_items['background-position'] = 'center center';
				$_items['background-size']     = 'contain';
				$value                         = 'url(' . $value['img'] . ')';
			} // Parallax Image
			elseif ( $value['type'] === 'parallax' ) {
				$_items['background-repeat']     = 'no-repeat';
				$_items['background-attachment'] = 'fixed';
				$_items['background-position']   = 'center center';
				$_items['background-size']       = 'cover';
				$value                           = 'url(' . $value['img'] . ')';
			} else {
				switch ( $value['type'] ) {

					case 'repeat':
					case 'cover':
					case 'repeat-y':
					case 'repeat-x':
						$_items['background-repeat'] = $value['type'];
						break;

					case 'top-left':
					case 'top-center':
					case 'top-right':
					case 'left-center':
					case 'center-center':
					case 'right-center':
					case 'bottom-left':
					case 'bottom-center':
					case 'bottom-right':
						$_items['background-repeat']   = 'no-repeat';
						$_items['background-position'] = str_replace( '-', ' ', $value['type'] );
						break;
				}
				$value = 'url(' . $value['img'] . ')';
			}

			foreach ( $_items as $_item_k => $_item_v ) {
				if ( ! empty( $block['expand-to-vars'] ) ) {

					// remove background- for shorter result
					$_item_k = str_replace( 'background-', '', $_item_k );

					// add it to all provided props (variables)
					foreach ( (array) $block['prop'] as $key => $val ) {
						if ( ! is_int( $key ) ) {
							$after_value .= "$key-$_item_k:$_item_v;";
						} elseif ( ! is_array( $value ) ) {
							$after_value .= "$val-$_item_k:$_item_v;";
						}
					}
				} else {
					$after_value .= "$_item_k:$_item_v;";
				}
			}
		}

		// prepare value for "color" type
		if ( isset( $block['type'] ) && $block['type'] === 'color' ) {
			if ( preg_match( '/%%value([-|+]\d*)%%/', $block['value'], $change ) ) {
				Better_Framework::factory( 'color' );
				$value = preg_replace( '/(%%value[-|+]\d*%%)/', BF_Color::change_color( $value, intval( $change[1] ) ), $block['value'] );
			} elseif ( preg_match( '/%%value-opacity-(.*)%%/', $block['value'], $opacity ) ) {
				Better_Framework::factory( 'color' );
				$value = preg_replace( '/(%%value-opacity-.*%%)/', BF_Color::hex_to_rgba( $value, $opacity[1] ), $block['value'] );
			}
		}

		// prepare value for "border" type
		if ( isset( $block['type'] ) && $block['type'] === 'border' ) {

			if ( isset( $value['all'] ) ) {

				$result['code'] .= $tab_char . 'border:';

				if ( isset( $value['all']['width'] ) ) {
					$result['code'] .= $value['all']['width'] . 'px ';
				}
				if ( isset( $value['all']['style'] ) ) {
					$result['code'] .= $value['all']['style'] . ' ';
				}
				if ( isset( $value['all']['color'] ) ) {
					$result['code'] .= $value['all']['color'] . ' ';
				}

				$result['code'] .= ';' . $ln_char;

			} else {

				if ( isset( $value['top'] ) ) {

					$result['code'] .= $tab_char . 'border-top:';

					if ( isset( $value['top']['width'] ) ) {
						$result['code'] .= $value['top']['width'] . 'px ';
					}
					if ( isset( $value['top']['style'] ) ) {
						$result .= $value['top']['style'] . ' ';
					}
					if ( isset( $value['top']['color'] ) ) {
						$result['code'] .= $value['top']['color'] . ' ';
					}

					$result['code'] .= ';' . $ln_char;

				}

				if ( isset( $value['right'] ) ) {

					$result['code'] .= $tab_char . 'border-right:';

					if ( isset( $value['right']['width'] ) ) {
						$result['code'] .= $value['right']['width'] . 'px ';
					}
					if ( isset( $value['right']['style'] ) ) {
						$result['code'] .= $value['right']['style'] . ' ';
					}
					if ( isset( $value['right']['color'] ) ) {
						$result['code'] .= $value['right']['color'] . ' ';
					}

					$result['code'] .= ';' . $ln_char;

				}
				if ( isset( $value['bottom'] ) ) {

					$result['code'] .= $tab_char . 'border-bottom:';

					if ( isset( $value['bottom']['width'] ) ) {
						$result['code'] .= $value['bottom']['width'] . 'px ';
					}
					if ( isset( $value['bottom']['style'] ) ) {
						$result['code'] .= $value['bottom']['style'] . ' ';
					}
					if ( isset( $value['bottom']['color'] ) ) {
						$result['code'] .= $value['bottom']['color'] . ' ';
					}

					$result['code'] .= ';' . $ln_char;

				}

				if ( isset( $value['left'] ) ) {

					$result['code'] .= $tab_char . 'border-left:';

					if ( isset( $value['left']['width'] ) ) {
						$result['code'] .= $value['left']['width'] . 'px ';
					}
					if ( isset( $value['left']['style'] ) ) {
						$result['code'] .= $value['left']['style'] . ' ';
					}
					if ( isset( $value['left']['color'] ) ) {
						$result['code'] .= $value['left']['color'] . ' ';
					}

					$result['code'] .= ';' . $ln_char;

				}
			}
		}

		// Prepare Properties
		if ( isset( $block['prop'] ) ) {

			foreach ( (array) $block['prop'] as $key => $val ) {

				// Customized value template for property
				if ( strpos( $val, '%%value%%' ) !== false ) {

					$result['code'] .= $tab_char . $key . ':';
					$result['code'] .= str_replace( '%%value%%', $value, $val ) . ';' . $ln_char;

				} // Simply set value to property
				else {

					if ( ! is_int( $key ) ) {

						$result['code'] .= $tab_char . $key . ':' . $val . ';' . $ln_char;

					} elseif ( ! is_array( $value ) ) {

						$result['code'] .= $tab_char . $val . ':' . $value . ';' . $ln_char;

					}
				}
			}
		}

		// add after value
		if ( isset( $after_value ) && $after_value != '' ) {
			$result['code'] .= $after_value;
		}

		// Remove last ';'
		$result['code'] = rtrim( $result['code'], ';' );

		if ( isset( $block['selector'] ) ) {
			$result['code'] .= '}' . $ln_char;
		}

		// After css code. For example used for adding media queries!.
		if ( isset( $block['after'] ) && is_string( $value ) ) {
			$result['code'] .= str_replace( '%%value%%', $value, $block['after'] ) . $ln_char;
		}

		return $result;
	}
}

if ( ! function_exists( 'bf_starts_with' ) ) {
	/**
	 * Check string start with
	 *
	 * @param $haystack
	 * @param $needle
	 *
	 * @return bool
	 */
	function bf_starts_with( $haystack, $needle ) {

		if ( function_exists( 'str_starts_with' ) ) {

			return str_starts_with( $haystack, $needle );
		}

		return $needle === '' || strrpos( $haystack, $needle, - strlen( $haystack ) ) !== false;
	}
}

if ( ! function_exists( 'bf_ends_with' ) ) {

	/**
	 * Check string end with
	 *
	 * @param $haystack
	 * @param $needle
	 *
	 * @return bool
	 */
	function bf_ends_with( $haystack, $needle ) {

		if ( function_exists( 'str_ends_with' ) ) {

			return str_ends_with( $haystack, $needle );
		}

		return $needle === '' || ( ( $temp = strlen( $haystack ) - strlen( $needle ) ) >= 0 && strpos( $haystack, $needle, $temp ) !== false );
	}
}

if ( ! function_exists( 'bf_found_closest_number' ) ) {

	/**
	 * Find closest number in the array
	 *
	 * @param array  $array  Ascending sorted array
	 * @param int    $number number you are looking for
	 * @param string $round  up|down  round fractions up/down
	 *
	 * TODO: optimized it!
	 *
	 * @since 3.0.1
	 * @return void|int index of array on success
	 */
	function bf_found_closest_number( $array, $number, $round = 'up' ) {

		$pivot       = array_rand( $array );
		$status      = 0; // 1: increasing, 2: decreasing
		$found_pivot = null;

		while ( isset( $array[ $pivot ] ) ) {

			if ( $array[ $pivot ] === $number ) {
				return $array[ $pivot ];
			}

			if ( $array[ $pivot ] > $number ) {

				if ( $status === 1 ) {
					$found_pivot = $pivot;
					break;
				}

				$pivot --;
				$status = 2;

			} else {

				$pivot ++;

				if ( $status === 2 ) {
					$found_pivot = $pivot;
					break;
				}

				$status = 1;
			}
		}

		if ( is_null( $found_pivot ) ) {

			$first = $array[ key( $array ) ];
			if ( $first > $number ) {

				if ( $round === 'up' ) {
					return $first;
				}
			} else {

				if ( $round === 'down' ) {

					return end( $array );
				}
			}
		}

		if ( ! is_null( $found_pivot ) ) {

			if ( $round === 'up' ) {
				return $array[ $found_pivot ];
			}

			return $array[ $found_pivot - 1 ];
		}
	}
}


if ( ! function_exists( 'bf_is_user_logged_in' ) ) {
	/**
	 * Performance optimized function to check current user login status
	 *
	 * @return bool
	 */
	function bf_is_user_logged_in() {

		static $logged_in;

		if ( is_null( $logged_in ) ) {
			$logged_in = is_user_logged_in();
		}

		return $logged_in;
	}
}


if ( ! function_exists( 'bf_is_amp' ) ) {
	/**
	 * Detects active AMP page & plugin
	 *
	 * @return bool
	 */
	function bf_is_amp() {

		static $is_amp;

		if ( ! is_null( $is_amp ) ) {
			return $is_amp;
		}

		// BetterAMP plugin
		if ( function_exists( 'is_better_amp' ) && is_better_amp() ) {
			$is_amp = 'better';
		} // Official AMP Plugin
		elseif ( function_exists( 'is_amp_endpoint' ) && is_amp_endpoint() ) {
			$is_amp = 'amp';
		} else {
			$is_amp = false;
		}

		return $is_amp;
	}
}


if ( ! function_exists( 'bf_is_fia' ) ) {
	/**
	 * Detects active Facebook Instant Article plugin
	 *
	 * @return bool
	 */
	function bf_is_fia() {

		static $state;

		if ( ! is_null( $state ) ) {
			return $state;
		}

		$state = defined( 'IA_PLUGIN_VERSION' ) && function_exists( 'is_transforming_instant_article' ) && is_transforming_instant_article();

		return $state;
	}
}

if ( ! function_exists( 'bf_the_content_by_id' ) ) {
	/**
	 * Prints content of post by the ID.
	 * It handles extra actions like custom CSS prints of blocks and page.
	 *
	 * @param null  $post_id
	 * @param array $args
	 *
	 * @return mixed|string|void
	 */
	function bf_the_content_by_id( $post_id = null, $args = [] ) {

		if ( ! $post_id ) {
			return;
		}

		$post_object = get_post( $post_id );
		$args        = bf_merge_args(
			$args,
			[
				'echo'         => true,
				'context'      => '',
				'add_vs_style' => false,
			]
		);

		if ( ! $post_object ) {
			return;
		} else {
			$content = apply_filters( 'the_content', $post_object->post_content, $args['context'] );
			$content = do_shortcode( $content );
		}

		//
		// Add custom css of VC page
		//

		if ( apply_filters( 'better-framework/the-content/append-styles', $args['add_vs_style'], $post_object, $args ) ) {
			//
			// Post custom CSS
			//
			$post_custom_css = get_post_meta( $post_id, '_wpb_post_custom_css', true );
			if ( ! empty( $post_custom_css ) ) {
				$post_custom_css = strip_tags( $post_custom_css );
				$content        .= '<style type="text/css" data-type="vc_custom-css">';
				$content        .= $post_custom_css;
				$content        .= '</style>';
			}

			//
			// Post shortcodes CSS
			//
			$shortcodes_custom_css = get_post_meta( $post_id, '_wpb_shortcodes_custom_css', true );
			if ( ! empty( $shortcodes_custom_css ) ) {
				$shortcodes_custom_css = strip_tags( $shortcodes_custom_css );
				$content              .= '<style type="text/css" data-type="vc_shortcodes-custom-css">';
				$content              .= $shortcodes_custom_css;
				$content              .= '</style>';
			}
		}

		if ( $args['echo'] ) {
			echo $content;
		} else {
			return $content;
		}

	}
}

if ( ! function_exists( 'bf_bp_get_current_core_component' ) ) {

	/**
	 * Return current BuddyPress component ID
	 *
	 * @since 3.5.4
	 * @return string
	 */
	function bf_bp_get_current_core_component() {

		if ( ! function_exists( 'bp_core_get_packaged_component_ids' ) ||
			 ! function_exists( 'bp_is_current_component' )
		) {
			return '';
		}

		foreach ( bp_core_get_packaged_component_ids() as $active_component ) {

			if ( bp_is_current_component( $active_component ) ) {

				return $active_component;
			}
		}

		return '';
	}
}

if ( ! function_exists( 'bf_bp_get_component_page_id' ) ) {

	/**
	 * Get page ID of given BuddyPress component
	 *
	 * @param string $component . optional. default:current component
	 *
	 * @global BuddyPress $bp        Main BuddyPress Class.
	 *
	 * @since 3.5.4
	 * @return int
	 */
	function bf_bp_get_component_page_id( $component = 'auto' ) {

		global $bp;

		if ( ! $bp || ! isset( $bp->pages ) ) {
			return 0;
		}

		if ( 'auto' === $component ) {
			$component = bf_bp_get_current_core_component();
		}

		if ( $component && isset( $bp->pages->$component->id ) ) {
			return $bp->pages->$component->id;
		}

		return 0;
	}
}

if ( ! function_exists( 'bf_file_system_instance' ) ) {

	/**
	 * Get WP FileSystem Object
	 *
	 * @global WP_Filesystem_Base $wp_filesystem WordPress Filesystem Class
	 *
	 * @since 3.9.1
	 * @return WP_Filesystem_Base
	 */
	function bf_file_system_instance() {

		global $wp_filesystem;

		if ( ! $wp_filesystem instanceof WP_Filesystem_Base ) {

			if ( ! function_exists( 'WP_Filesystem' ) ) {
				require_once ABSPATH . '/wp-admin/includes/file.php';
			}

			$credentials['hostname'] = defined( 'FTP_HOST' ) ? FTP_HOST : '';
			$credentials['username'] = defined( 'FTP_USER' ) ? FTP_USER : '';
			$credentials['password'] = defined( 'FTP_PASS' ) ? FTP_PASS : '';

			WP_Filesystem( $credentials, WP_CONTENT_DIR, false );
		}

		return $wp_filesystem;
	}
}

if ( ! function_exists( 'bf_call_static_method' ) ) {

	/**
	 * Call a private static method on a class.
	 *
	 * @param string $class_name
	 * @param string $method_name
	 * @param array  $params
	 *
	 * @since 3.10.20
	 * @return mixed
	 */
	function bf_call_static_method( $class_name, $method_name, $params = [] ) {

		try {

			$class = new ReflectionClass( $class_name );

			if ( ! $class->hasMethod( $method_name ) ) {

				return null;
			}

			$method = $class->getMethod( $method_name );
			$method->setAccessible( true );

			return $method->invoke( null, $params );

		} catch ( ReflectionException $e ) {

			return null;
		}
	}
}

if ( ! function_exists( 'bf_sanitize_widget_settings' ) ) {

	/**
	 * Parse a widget settings array.
	 *
	 * @param array $instance
	 *
	 * @since 3.14.0
	 * @return array
	 */
	function bf_sanitize_widget_settings( $instance ) {

		if ( ! isset( $instance['content'] ) || ! function_exists( 'parse_blocks' ) ) {

			return $instance;
		}

		if ( ! preg_match( '#<!--\s+\/?wp:[a-z][a-z0-9_-]*\/#', $instance['content'] ) ) {

			return $instance;
		}

		$block_settings = parse_blocks( $instance['content'] );

		if ( ! empty( $block_settings[0]['attrs'] ) ) {

			$instance = $block_settings[0]['attrs'];
		}

		return $instance;
	}
}

if ( ! function_exists( 'bf_is_pro_feature_active' ) ) {

	function bf_is_pro_feature_active( string $modal_id ): bool {

		return \BetterFrameworkPackage\Component\Control\Features\ProFeature::is_active( $modal_id );
	}
}

if ( ! function_exists( 'bs_sanitize_field_props' ) ) {

	function bs_sanitize_field_props( array $field, string $panel_id ): array {

		if ( isset( $field['type'], $field['parent_typo'] ) && 'typography' === $field['type'] ) {

			if ( true !== $field['parent_typo'] ) {

				$parent_typo_id               = $field['parent_typo'];
				$field['parent_typo_options'] = bf_get_option( $parent_typo_id, $panel_id );
			}
		}

		return $field;
	}
}
