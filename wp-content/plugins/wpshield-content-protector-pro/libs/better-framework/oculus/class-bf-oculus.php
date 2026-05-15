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

require __DIR__ . '/functions.php';
require __DIR__ . '/exceptions.php';
require __DIR__ . '/includes/class-bf-oculus-logger.php';
require __DIR__ . '/includes/class-bf-oculus-message-manager.php';

/**
 * Class BS_API
 */
class BetterFramework_Oculus {

	/**
	 * self instance
	 *
	 * @var array
	 */
	protected static $instance;

	/**
	 * Oculus base slug
	 *
	 * Created to be flexible for future updates
	 *
	 * @var string
	 */
	public static $slug = 'oculus';

	/**
	 * Store Authentication params - array {
	 *
	 * @type string|int $item_id       the product unique id
	 * @type string     $purchase_code license code
	 * }
	 *
	 * @var array
	 */
	protected $auth = [];

	/**
	 * Oculus Version
	 */
	const VERSION = BS_OCULUS_VERSION;

	/**
	 * Initialize
	 */
	//phpcs:ignore
	public static function Run() {

		if ( ! self::$instance instanceof self ) {
			self::$instance = new self();
			self::$instance->init();
		}

		return self::$instance;
	}


	/**
	 * @param string $id
	 *
	 * @throws BF_API_Exception
	 * @return static
	 */
	public static function instance( string $id, $overrides = [] ): self {

		$instance = self::Run();
		$auth     = $instance->config( $id );

		if ( ! isset( $auth['item_id'], $auth['purchase_code'] ) ) {

			throw new BF_API_Exception( 'invalid authentication data', 'invalid-auth-data' );
		}

		$auth['id'] = $id;
		$instance->set_auth_params( array_merge( $auth, $overrides ) );

		return $instance;
	}

	/**
	 * apply filters/actions
	 */
	protected function init() {

		add_action( 'admin_init', [ $this, 'register_schedule' ] );
		add_action( 'better-framework/oculus/check-update/init', [ $this, 'check_for_update' ] );
	}

	public function check_for_update( $id = '15801051' ) {

		$status       = [
			'is-rtl'    => is_rtl() ? '1' : '0',
			'languages' => bf_get_all_languages(),
			'item_id'   => $id,
		];
		$slug         = self::$slug;
		$data         = apply_filters( "better-framework/$slug/check-update/data", $status );
		$use_wp_error = false;
		$response     = self::request( 'check-update', compact( 'data', 'use_wp_error', 'id' ) );

		if ( $response && ! empty( $response->success ) ) {
			do_action( 'better-framework/oculus/check-update/done', $response, $data );
		}
	}


	/**
	 * Callback: register sync cron job
	 * action  : admin_init
	 */
	public function register_schedule() {

		if ( wp_next_scheduled( 'better-framework/oculus/check-update/init' ) ) {

			return;
		}

		$ids = apply_filters( 'better-framework/oculus/update-schedule', [] );

		foreach ( $ids as $id ) {

			wp_schedule_event( time(), 'daily', 'better-framework/oculus/check-update/init', [ $id ] );
		}
	}

	/**
	 * Connect Better Studio API and Retrieve Data From Server
	 *
	 * @param string $action       {@see handle_request}
	 * @param array  $args         {
	 *
	 * @type array   $auth         authentication info {@see $auth}
	 * @type array   $data         array of data to send
	 * @type string  $group        API group name
	 * @type bool    $use_wp_error use wp_error object on failure or always return false
	 * }
	 *
	 * @return bool|WP_Error|array|object bool|WP_Error on failure.
	 */
	public static function request( $action, $args = [] ) {

		try {
			$args = bf_merge_args(
				$args,
				[
					'data'         => [],
					'auth'         => [],
					'group'        => 'default',
					'version'      => 1,
					'assoc'        => $args['json_assoc'] ?? false,
					'use_wp_error' => true,
					'id'           => $args['auth']['id'] ?? '15801051', // backward compatibility
				]
			);

			$instance = self::instance( $args['id'], $args['auth'] );
			$response = $instance->handle_request( $action, $args['data'], $args );

			// auto clean product registration info if purchase-code was not valid!
			if ( isset( $response->result, $response->{'error-code'} ) && 'error' === $response->result && 'invalid-purchase-code' === $response->{'error-code'} ) {

				if ( function_exists( 'bf_register_product_clear_info' ) ) {

					bf_register_product_clear_info( $args['id'] );
				}
			}

			return $response;

		} catch ( Exception $e ) {

			if ( $args['use_wp_error'] ) {

				return new WP_Error( 'error-' . $e->getCode(), $e->getMessage() );
			}

			return false;
		}
	}

	/**
	 * Fetch a remove url
	 *
	 * @param string $url
	 * @param array  $args wp_remote_get() $args
	 *
	 * @throws Exception
	 * @return string|false string on success or false|Exception on failure.
	 */
	public function fetch_data( string $url, array $args = [] ) {

		global $wp_version;

		$defaults = [
			'timeout'    => 30,
			'user-agent' => 'BetterStudioApi Domain:' . home_url( '/' ) . '; WordPress/' . $wp_version . '; Oculus/' . self::VERSION . ';',
			'headers'    => [
				'better-studio-item-id'      => $this->auth['item_id'],
				'better-studio-item-version' => $this->auth['version'] ?? 0,
				'envato-purchase-code'       => $this->auth['purchase_code'],
				'panel-language'             => $this->auth['panel_lang'] ?? 'unavailable',
				'locale'                     => get_locale(),
			],
		];

		$args         = bf_merge_args( $args, $defaults );
		$raw_response = wp_remote_post( $url, $args );

		if ( is_wp_error( $raw_response ) ) {

			$error_message = $raw_response->get_error_message();

			if ( preg_match( '/^\s*cURL\s*error\s*(\d+)\s*\:?\s*$/i', $error_message, $match ) && function_exists( 'curl_strerror' ) ) {
				//phpcs:ignore
				$error_message .= curl_strerror( $match[1] );
			}

			throw new BF_API_Exception( $error_message, $raw_response->get_error_code() );
		}

		$response_code = wp_remote_retrieve_response_code( $raw_response );

		if ( 200 === $response_code ) {

			return wp_remote_retrieve_body( $raw_response );
		}

		if ( 403 === $response_code ) {

			$parse_url = wp_parse_url( $url );

			throw new BF_API_Exception( sprintf( __( 'Server cannot connect to %s', 'better-studio' ), $parse_url['host'] ), $response_code );
		}

		return false;
	}

	/**
	 * Handle API Remove Request
	 *
	 * @param string $action Api action. EX: register_product, check_update.
	 * @param array  $body   array of data
	 *
	 * @throws Exception
	 * @return array|false|object array or object on success, false|Exception on failure
	 */
	public function handle_request( string $action, array $body, array $options = [] ) {

		$options = wp_parse_args(
			$options,
			[
				'version' => 1,
				'assoc'   => false,
				'group'   => 'default',
			]
		);

		$url = str_replace(
			[ '%group%', '%action%', '%version%' ],
			[ $options['group'], $action, $options['version'] ],
			$this->the_base_url()
		);

		$received = $this->fetch_data( $url, compact( 'body' ) );

		if ( $received ) {

			return json_decode( $received, $options['assoc'] );
		}

		return false;
	}

	/**
	 * Set Authentication Params
	 *
	 * @see   $auth
	 *
	 * @param array $args
	 */
	public function set_auth_params( array $args ): void {

		$this->auth = $args;
	}

	/**
	 * Is given url accessible
	 *
	 * @param string $url
	 *
	 * @since 1.1.0
	 * @return bool true if it does
	 */
	public static function is_host_accessible( string $url ): bool {

		$request = wp_remote_head(
			$url,
			[
				'timeout'     => bf_is_localhost() ? 10 : 2,
				'sslverify'   => false,
				'redirection' => 5,
			]
		);

		return $request && ! is_wp_error( $request ) && 200 === wp_remote_retrieve_response_code( $request );
	}

	/**
	 * @param string      $id
	 * @param string|null $index
	 *
	 * @since 1.4.0
	 * @return mixed
	 */
	protected function config( string $id, string $index = null ) {

		$config = apply_filters( 'better-framework/oculus/' . $id . '/auth', [] );

		if ( ! isset( $index ) ) {

			return $config;
		}

		return $config[ $index ] ?? null;
	}

	/**
	 * Choose an accessible core url from available servers
	 *
	 * @since 1.1.0
	 * @return string|null the base url on success or null otherwise
	 */
	protected function the_base_url(): ?string {

		[ $url, $is_expired ] = bf_get_transient( 'bf-oculus-url-' . $this->auth['id'], null );

		if ( ! isset( $url ) || $is_expired ) {

			$url = $this->find_base_url();

			bf_set_transient(
				'bf-oculus-url-' . $this->auth['id'],
				(string) $url,
				isset( $url ) ? DAY_IN_SECONDS : MINUTE_IN_SECONDS * 20
			);

		}

		return ! empty( $url ) ? $url : null;
	}

	/**
	 * @since 1.4.0
	 * @return string|null the url on success or null on error.
	 */
	protected function find_base_url(): ?string {

		if ( empty( $this->auth['urls'] ) ) {

			return null;
		}

		foreach ( $this->auth['urls'] as $base_url ) {

			$pos = strpos( $base_url, '%' );

			if ( $pos ) {

				$test_url = substr( $base_url, 0, $pos );

			} else {

				$parsed_url = parse_url( $base_url );
				$test_url   = sprintf( '%s://%s', $parsed_url['scheme'], $parsed_url['host'] );
			}

			if ( self::is_host_accessible( $test_url ) ) {

				$found = $base_url;

				break;
			}
		}

		return $found ?? null;
	}
}
