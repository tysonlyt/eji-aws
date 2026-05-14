<?php

namespace GoDaddy\MWC\WordPress\Assistant;

use GoDaddy\WordPress\MWC\Common\Auth\AuthProviderFactory;
use GoDaddy\WordPress\MWC\Common\Auth\Exceptions\AuthProviderException;
use GoDaddy\WordPress\MWC\Common\Auth\Exceptions\CredentialsCreateFailedException;
use GoDaddy\WordPress\MWC\Common\Auth\Providers\Models\Token;
use WP_Error;

class Assistant {

    public function __construct() {
        $this->start();
    }

    public function start(): void {

        if (!defined('GD_ASSISTANT_URL')) {
            define('GD_ASSISTANT_URL', plugin_dir_url(__DIR__));
        }

        define('GD_ASSISTANT_DIR', plugin_dir_path(__DIR__));

        define('GD_ASSISTANT_VERSION', '0.2.1');
        define('GD_ASSISTANT_SCRIPT_VERSION', '0.1.1'); // scripts are loaded from aws

        if (!defined('GD_ASSISTANT_API_URL')) {
            define('GD_ASSISTANT_API_URL', 'https://ai-assistant.commerce.api.godaddy.com/graphqlexternal');
        }

        $this->loadFiles();
    }

    /**
     * Get MWC JWT, used for auth with the API backend.
     *
     * @return string|WP_Error
     */
    protected function getToken() {
        if ($this->isLocal()) {
            return '';
        }

        try {
            $credentials = AuthProviderFactory::getNewInstance()->getManagedWooCommerceAuthProvider()->getCredentials();
        } catch (AuthProviderException | CredentialsCreateFailedException $exception) {
            return new WP_Error('rest_error', "Cannot retrieve authentication token.");
        }

        $token = $credentials instanceof Token ? $credentials->getAccessToken() : '';

        if (!$token) {
            return new WP_Error('rest_error', "Cannot retrieve authentication token.");
        }

        return $token;
    }

    protected function isLocal(): string {
        return defined('GD_ASSISTANT_LOCAL') ? GD_ASSISTANT_LOCAL : false;
    }

    public function loadFiles(): void {
        require_once(dirname(__FILE__) . '/class-api.php');
        require_once(dirname(__FILE__) . '/class-admin.php');
        require_once(dirname(__FILE__) . '/class-ai-prompt-event.php');
    }
}
