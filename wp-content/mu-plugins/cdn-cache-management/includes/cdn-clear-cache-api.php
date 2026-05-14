<?php

/**
 * CDN Clear Cache Api
 */
if (!class_exists('CDN_Clear_Cache_Api')) {

    class CDN_Clear_Cache_Api {

        /**
         * clear site cache cdn api request
         *
         * @param array $files
         * @param string $action
         * @return array
         */
        public static function cache_api_call($files, $action = 'purge') {
            $data = [];

            if ($action) {
                $curl_url = 'https://api.rocket.net/v1/sites/' . CDN_SITE_ID . '/cache/' . $action;
                // set clear cache files
                if (!empty($files))
                    $data['files'] = $files;
                // set staging parameter
                if (in_array($action, ['purge', 'purge_everything'], true) && strpos(get_site_url(), 'staging') !== false)
                    $curl_url = add_query_arg('staging', 'true', $curl_url);
                
                if( is_multisite() )
                    $curl_url = add_query_arg('domain', $_SERVER['SERVER_NAME'], $curl_url);
                
                // prepare request data
                $data = json_encode($data);

                if ($action == "purge_everything" && CDN_HTML_PURGE_EVERYTHING === true) {
                  $curl_url = 'https://api.rocket.net/v1/sites/' . CDN_SITE_ID . '/cache/purge';
                  $data = "{\"tags\": [\"html\"]}";
                }

                if ($action == "purge" && CDN_HTML_PURGE === true) {
                  $curl_url = 'https://api.rocket.net/v1/sites/' . CDN_SITE_ID . '/cache/purge';
                  $data = "{\"tags\": [\"html\"]}";
                }

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
                    CURLOPT_POSTFIELDS => $data,
                    CURLOPT_RETURNTRANSFER => true,
                    // Timeout the request after 5 seconds
                    CURLOPT_TIMEOUT => 5,
                ]);

                try {
                    $result = curl_exec($ch);

                    curl_close($ch);
                //     // log request and response
                //    $message = 'Request: ' . $data . ' Response ' . $result;
                //    self::cdn_cache_log($message);
                    // return result
                    $result = json_decode($result);
                    return $result;
                } catch (\Exception $e) {
                    // log request and exception
                    $message =' Exception: ' . $e->getMessage();
                    self::cdn_cache_log($message);
                    return [];
                }
            }

            return $data;
        }

        /**
         * wp cdn clear cache custom log
         *
         * @param string $message
         */
        public static function cdn_cache_log($message) {
            if (WP_DEBUG_LOG) {
                try {
                    error_log($message);
                } catch (\Exception $e) {

                }
            }
        }

    }

}
