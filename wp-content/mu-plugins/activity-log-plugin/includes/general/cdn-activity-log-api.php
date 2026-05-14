<?php

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * CDN Activity Log Api
 */
if (!class_exists('CDN_Activity_Log_Api')) {

    class CDN_Activity_Log_Api {

        /**
         * send site activity log api request
         * 
         * @param array $params 
         * @return array
         */
        public static function activity_log_api_call( $params ) { 
 
            if ($params) {
                $curl_url = 'https://api.rocket.net/v1/sites/' . CDN_SITE_ID . '/activity/events'; 
                // append ip to request params
                $params['ip'] = self::_get_ip_address();
                // terminate label with charcters more  than 40
                if(isset($params['label']) && strlen($params['label']) > 40)
                    $label = substr($params['label'], 0, 40) . '...';
                // prepare request data
                $params = json_encode($params);
                // init curl request
                $ch = curl_init();
                curl_setopt_array($ch, [
                    CURLOPT_URL => $curl_url,
                    CURLOPT_POST => true,
                    CURLOPT_HTTPHEADER => [
                        'Authorization: Bearer ' . CDN_SITE_TOKEN,
                        'Accept: application/json',
                        'Content-Type: application/json',
                    ],
                    CURLOPT_POSTFIELDS => $params,
                    CURLOPT_RETURNTRANSFER => true,
                    // Timeout the request after 5 seconds
                    CURLOPT_TIMEOUT => 5,
                ]);

                try {
                    $result = curl_exec($ch);
                    curl_close($ch);
                     // log request and response
                    $message = 'Activity log Request: ' . $params . ' Activity log Response ' . $result;
                    self::cdn_activity_log($message);
                    // return result
                    $result = json_decode($result);
                    
                    return $result;
                } catch (\Exception $e) {
                    // log request and exception
                    $message =' Activity log Exception: ' . $e->getMessage(); 
                    self::cdn_activity_log($message);
                    return [];
                }
            }

            return $params;
        }
        
        /**
	 * Get real address
	 * 
	 * @since 2.1.4
	 * 
	 * @return string real address IP
	 */
	public static function _get_ip_address() {
		$server_ip_keys = array(
			'HTTP_CF_CONNECTING_IP', // CloudFlare
			'HTTP_TRUE_CLIENT_IP', // CloudFlare Enterprise header
			'HTTP_CLIENT_IP',
			'HTTP_X_FORWARDED_FOR',
			'HTTP_X_FORWARDED',
			'HTTP_X_CLUSTER_CLIENT_IP',
			'HTTP_FORWARDED_FOR',
			'HTTP_FORWARDED',
			'REMOTE_ADDR',
		);
		
		foreach ( $server_ip_keys as $key ) {
			if ( isset( $_SERVER[ $key ] ) && filter_var( $_SERVER[ $key ], FILTER_VALIDATE_IP ) ) {
				return $_SERVER[ $key ];
			}
		}
		
		// Fallback local ip.
		return '127.0.0.1';
	}
        

        /**
         * wp cdn activity log custom log
         * 
         * @param string $message
         */
        public static function cdn_activity_log($message) {
            if (WP_DEBUG_LOG) {
                try {
                    error_log($message);
                } catch (\Exception $e) {
                    
                }
            }
        }

    }

}