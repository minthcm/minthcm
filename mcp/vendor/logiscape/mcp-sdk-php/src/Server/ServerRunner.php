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
 * Filename: Server/ServerRunner.php
 */

declare(strict_types=1);

namespace Mcp\Server;

use Mcp\Server\Transport\StdioServerTransport;
use Mcp\Server\ServerSession;
use Mcp\Server\Server;
use Mcp\Server\InitializationOptions;
use Mcp\Types\ServerCapabilities;
use Psr\Log\LoggerInterface;
use Psr\Log\LogLevel;
use RuntimeException;

/**
 * Main entry point for running an MCP server synchronously using STDIO transport.
 *
 * This class emulates the behavior seen in the Python code, which uses async streams.
 * Here, we run a loop reading messages from STDIO, passing them to the Server for handling.
 */
class ServerRunner {
    protected LoggerInterface $logger;

    public function __construct(
        private readonly Server $server,
        private readonly InitializationOptions $initOptions,
        ?LoggerInterface $logger = null
    ) {
        $this->logger = $logger ?? $this->createDefaultLogger();
    }

    /**
     * Run the server using STDIO transport.
     *
     * This sets up a ServerSession with a StdioServerTransport and enters a loop to read messages.
     */
    public function run(): void {
        // Suppress warnings unless explicitly enabled (similar to Python code ignoring warnings)
        if (!getenv('MCP_ENABLE_WARNINGS')) {
            error_reporting(E_ERROR | E_PARSE);
        }

        try {
            $transport = StdioServerTransport::create();

            $session = new ServerSession(
                $transport,
                $this->initOptions,
                $this->logger
            );

            // Connect the server with the session
            $this->server->setSession($session);

            // Add handlers
            $session->registerHandlers($this->server->getHandlers());
            $session->registerNotificationHandlers($this->server->getNotificationHandlers());

            $session->start();

            $this->logger->info('Server started');

        } catch (\Exception $e) {
            $this->logger->error('Server error: ' . $e->getMessage());
            throw $e;
        } finally {
            if (isset($session)) {
                $session->stop();
            }
            if (isset($transport)) {
                $transport->stop();
            }
        }
    }

    /**
     * Creates a default PSR logger if none provided
     */
    private function createDefaultLogger(): LoggerInterface {
        return new class implements LoggerInterface {
            public function emergency($message, array $context = []): void {
                $this->log(LogLevel::EMERGENCY, $message, $context);
            }

            public function alert($message, array $context = []): void {
                $this->log(LogLevel::ALERT, $message, $context);
            }

            public function critical($message, array $context = []): void {
                $this->log(LogLevel::CRITICAL, $message, $context);
            }

            public function error($message, array $context = []): void {
                $this->log(LogLevel::ERROR, $message, $context);
            }

            public function warning($message, array $context = []): void {
                $this->log(LogLevel::WARNING, $message, $context);
            }

            public function notice($message, array $context = []): void {
                $this->log(LogLevel::NOTICE, $message, $context);
            }

            public function info($message, array $context = []): void {
                $this->log(LogLevel::INFO, $message, $context);
            }

            public function debug($message, array $context = []): void {
                $this->log(LogLevel::DEBUG, $message, $context);
            }

            public function log($level, $message, array $context = []): void {
                $timestamp = date('Y-m-d H:i:s');
                fprintf(
                    STDERR,
                    "[%s] %s: %s\n",
                    $timestamp,
                    strtoupper($level),
                    $this->interpolate($message, $context)
                );
            }

            private function interpolate($message, array $context = []): string {
                $replace = [];
                foreach ($context as $key => $val) {
                    $replace['{' . $key . '}'] = $val;
                }
                return strtr($message, $replace);
            }
        };
    }
}