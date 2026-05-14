<?php

namespace GoDaddy\MWC\WordPress\Assistant\Events;

use GoDaddy\WordPress\MWC\Common\Events\Contracts\EventBridgeEventContract;
use GoDaddy\WordPress\MWC\Common\Traits\IsEventBridgeEventTrait;

/**
 * event class.
 */
class AIPromptEvent implements EventBridgeEventContract {
    use IsEventBridgeEventTrait;
    /** @var string */
    protected $prompt;
    protected string $errorMessage;
    protected string $path;

    /** @var mixed */
    protected $response;

    /**
     * event constructor.
     *
     * @param mixed $response
     */
    public function __construct(string $prompt, $response, string $path = "", string $errorMessage = "") {
        $this->resource = 'ai-assistant-prompt';
        $this->prompt = $prompt;
        $this->action = "create";
        $this->response = $response;
        $this->path = $path;
        $this->errorMessage = $errorMessage;
    }

    /**
     * Gets the data for the event.
     *
     * @return array<string, mixed>
     */
    protected function buildInitialData(): array {
        return [
            'resource' => [
                'prompt' => $this->prompt,
                'functionName' => is_object($this->response) && isset($this->response->aiAssistant->value->function->name) ? $this->response->aiAssistant->value->function->name : null,
                'responseContent' => is_object($this->response) && isset($this->response->aiAssistant->value->content) ? $this->response->aiAssistant->value->content : null,
                'surface' => 'mwcs',
                'path' => $this->path,
                'errorMessage' => $this->errorMessage,
            ]
        ];
    }
}
