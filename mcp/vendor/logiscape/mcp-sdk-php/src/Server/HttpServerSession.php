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
 * Filename: Server/HttpServerSession.php
 */

declare(strict_types=1);

namespace Mcp\Server;

use Mcp\Server\ServerSession;
use Mcp\Shared\BaseSession;
use Mcp\Types\Implementation;
use Mcp\Types\ClientCapabilities;
use Mcp\Types\ClientRootsCapability;
use Mcp\Server\InitializationState;
use Mcp\Types\InitializeRequestParams;

class HttpServerSession extends ServerSession
{
    protected function startMessageProcessing(): void
    {
        $this->isInitialized = true;

        if ($this->transport->getConfig()->isSseEnabled() && $this->transport->clientRequestedSse()) {
            // SSE path:
            // Keep open, periodically flush new events, until a break condition or client closes.
            while ($this->isInitialized) {
                $message = $this->readNextMessage();
                if ($message !== null) {
                    $this->handleIncomingMessage($message);
                }
                // Possibly sleep or do logic to push SSE events out
                // Then break if a certain time expires, or a "complete" message arrives, etc.
            }
        } else {
            // Normal HTTP path:
            // Process just one batch, then break.
            while ($this->isInitialized) {
                $message = $this->transport->readMessage();
                if ($message === null) {
                    break; 
                }
                $this->handleIncomingMessage($message);
            }
            $this->close();
        }
    }

    public function toArray(): array
    {
        return [
            // InitializationState is an enum; store its integer value
            'initializationState' => $this->initializationState->value,
            
            // $clientParams is an object (InitializeRequestParams)
            // so convert to an array or JSON
            'clientParams' => $this->clientParams
                ? $this->clientParams->jsonSerialize()
                : null,
            
            'negotiatedProtocolVersion' => $this->negotiatedProtocolVersion,
        ];
    }

    public static function fromArray(
        array $data,
        \Mcp\Server\Transport\Transport $transport,
        InitializationOptions $initOptions,
        \Psr\Log\LoggerInterface $logger
    ): self {
        // Build a new session object using existing constructor
        $session = new self($transport, $initOptions, $logger);

        // Restore the fields
        $session->initializationState = InitializationState::from($data['initializationState']);

        if (!empty($data['clientParams'])) {
            $clientParamsData = $data['clientParams'];
    
            // Reconstruct ClientRootsCapability if roots are present in the data
            $rootsData = $clientParamsData['capabilities']['roots'] ?? null;
            $roots = null;
    
            if (is_array($rootsData)) {
                // Instantiate ClientRootsCapability based on roots data
                $roots = new ClientRootsCapability(
                    listChanged: $rootsData['listChanged'] ?? false
                );
            }
    
            // Instantiate ClientCapabilities and pass the roots object
            $capabilities = new ClientCapabilities($roots);
    
            // Instantiate Implementation for clientInfo
            $clientInfo = new Implementation(
                name: $clientParamsData['clientInfo']['name'] ?? '',
                version: $clientParamsData['clientInfo']['version'] ?? ''
            );
    
            // Now instantiate InitializeRequestParams
            $initParams = new InitializeRequestParams(
                protocolVersion: $clientParamsData['protocolVersion'] ?? '',
                capabilities: $capabilities,
                clientInfo: $clientInfo
            );
    
            $session->clientParams = $initParams;
        }

        $session->negotiatedProtocolVersion =
            $data['negotiatedProtocolVersion'] ?? $session->negotiatedProtocolVersion;

        return $session;
    }
}
