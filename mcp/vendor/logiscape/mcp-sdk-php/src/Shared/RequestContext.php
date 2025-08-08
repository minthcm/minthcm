<?php

/**
 * Model Context Protocol SDK for PHP
 *
 * (c) 2024 Logiscape LLC <https://logiscape.com>
 *
 * Based on the Python SDK for the Model Context Protocol
 * https://github.com/modelcontextprotocol/python-sdk
 *
 * PHP conversion developed by:
 * - Josh Abbott
 * - Claude 3.5 Sonnet (Anthropic AI model)
 * - ChatGPT o1 pro mode
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @package    logiscape/mcp-sdk-php 
 * @author     Josh Abbott <https://joshabbott.com>
 * @copyright  Logiscape LLC
 * @license    MIT License
 * @link       https://github.com/logiscape/mcp-sdk-php
 *
 * Filename: Shared/RequestContext.php
 */

declare(strict_types=1);

namespace Mcp\Shared;

use Mcp\Types\RequestId;
use Mcp\Types\Meta;

/**
 * Request context holding the current request's information.
 *
 * Similar to the Python RequestContext, which holds request_id, meta, and session.
 * Here, `meta` corresponds to params['_meta'] if present.
 */
class RequestContext {
    private ?Meta $meta = null;

    /**
     * @param RequestId $requestId The unique ID of the request.
     * @param array $params The request parameters (including optional '_meta').
     * @param BaseSession $session The active session.
     */
    public function __construct(
        private readonly RequestId $requestId,
        private readonly array $params,
        private readonly BaseSession $session,
    ) {
        $this->extractMeta();
    }

    public function getRequestId(): RequestId {
        return $this->requestId;
    }

    public function getParams(): array {
        return $this->params;
    }

    public function getSession(): BaseSession {
        return $this->session;
    }

    /**
     * Returns the Meta object if available, otherwise null.
     */
    public function getMeta(): ?Meta {
        return $this->meta;
    }

    /**
     * Extracts '_meta' from params if present and creates a Meta object.
     */
    private function extractMeta(): void {
        if (isset($this->params['_meta']) && is_array($this->params['_meta'])) {
            $this->meta = new Meta();
            // Populate Meta fields from _meta array
            foreach ($this->params['_meta'] as $key => $value) {
                $this->meta->$key = $value;
            }
        }
    }
}