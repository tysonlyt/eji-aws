<?php

namespace GoDaddy\MWC\WordPress\Assistant;

use GoDaddy\WordPress\MWC\Common\Events\Events;
use GoDaddy\MWC\WordPress\Assistant\Events\AIPromptEvent;
use WP_Error;
use WP_REST_Request;

class API extends Assistant {

    public function __construct() {
        $this->load();
    }

    public function load(): void {
        add_action('rest_api_init', [$this, 'register_routes']);
    }

    /**
     * Registers the routes for the API.
     */
    public function register_routes(): void {
        register_rest_route('gd/v1', '/gd-assistant', [
            'methods' => 'POST',
            'callback' => [$this, 'handleRequest'],
            'permission_callback' => [$this, 'permissionCheck'],
        ]);
    }

    /**
     * Route handler. Calls the AI and returns the response.
     * @param WP_REST_Request $request
     * @return WP_Error|mixed|object
     */

    public function handleRequest(WP_REST_Request $request) {

        $response = $this->callAI($request);

        $prompts = isset($request->get_json_params()['options']['prompts']) ? $request->get_json_params()['options']['prompts'] : null;
        $prompt_content = $prompts ? end($prompts)['content'] : null;
        $path = isset($request->get_json_params()['path']) ? $request->get_json_params()['path'] : '';

        if (is_wp_error($response)) {
            $error_string = $response->get_error_message();
            Events::broadcast(new AIPromptEvent($prompt_content, null, $path, $error_string));
            return new WP_Error('rest_error', $error_string);
        }

        $body = wp_remote_retrieve_body($response);
        $json = json_decode($body);

        if (!is_object($json)) {
            return new WP_Error('rest_error', "The response does not contain a valid JSON object: {$body}");
        }

        if (isset($json->data)) {
            Events::broadcast(new AIPromptEvent($prompt_content, $json->data, $path));
            return $json->data;
        } else {
            $msg = isset($json->errors[0]) ? "Error: " . $json->errors[0]->message : "There was a problem";
            Events::broadcast(new AIPromptEvent($prompt_content, null, $path, $msg));
            return new WP_Error('rest_json_error', $msg, $body);
        }
    }



    /**
     * Calls the graphql API with the prompts. 
     * @param WP_REST_Request $request
     * @return array<string, mixed>|WP_Error
     */
    public function callAI($request) {

        $token = $this->getToken();

        if (is_wp_error($token)) {
            return $token;
        }

        $query = 'query($options: GocaasOptions!, $isWP: Boolean) {
            aiAssistant(options: $options, isWP: $isWP) {
              functionResponse {
                data
              }
              value {
                from
                content
                function {
                  data
                  name
                }
              }
              meta {
                id
                resolvedPrompt
              }
            }
          }';

        $response = wp_remote_request($this->getAssistantApiUrl(), [
            'method' => 'POST',
            'headers' => [
                'Content-Type' => 'application/json',
                'Authorization' => 'Bearer ' . $token
            ],
            'body' => (string) json_encode([
                'query' => $query,
                'variables' =>  [
                    'options' => $request->get_json_params()['options'],
                    'isWP' => true,
                ]
            ]),
            'timeout' => 60,
        ]);

        return $response;
    }


    protected function getAssistantApiUrl(): string {
        return defined('GD_ASSISTANT_API_URL') ? GD_ASSISTANT_API_URL : '';
    }

    /**
     * Checks if the user has permission to access the API.
     * @return bool
     */
    public function permissionCheck() {
        return $this->isLocal() ? true : current_user_can('manage_options');
    }
}

new API();
