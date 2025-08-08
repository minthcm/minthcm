<?php

/**
 * Model Context Protocol SDK for PHP
 *
 * (c) 2025 Logiscape LLC <https://logiscape.com>
 *
 * Developed by:
 * - Josh Abbott
 * - Claude 3.7 Sonnet (Anthropic AI model)
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
 * Filename: Client/Transport/HttpConfiguration.php
 */

 declare(strict_types=1);

 namespace Mcp\Client\Transport;
 
 use InvalidArgumentException;
 
 /**
  * Configuration options for HTTP-based transports.
  * 
  * This class defines all the configurable parameters for connecting
  * to an MCP server over HTTP, including timeouts, retry options,
  * and authentication settings.
  */
 class HttpConfiguration {
     /**
      * @param string $endpoint The HTTP endpoint URL for the MCP server
      * @param array $headers Additional HTTP headers to include with all requests
      * @param float $connectionTimeout Timeout for establishing connections (seconds)
      * @param float $readTimeout Timeout for reading responses (seconds)
      * @param float $sseIdleTimeout Maximum idle time for SSE connections (seconds)
      * @param bool $enableSse Whether to enable SSE support when available
      * @param int $maxRetries Maximum number of retries for failed requests
      * @param float $retryDelay Base delay between retries (seconds)
      * @param bool $verifyTls Whether to verify TLS certificates
      * @param string|null $caFile Custom CA certificate file path
      * @param array $curlOptions Additional cURL options as key-value pairs
      */
     public function __construct(
         private string $endpoint,
         private array $headers = [],
         private float $connectionTimeout = 30.0,
         private float $readTimeout = 60.0,
         private float $sseIdleTimeout = 300.0,
         private bool $enableSse = true,
         private int $maxRetries = 3,
         private float $retryDelay = 0.5,
         private bool $verifyTls = true,
         private ?string $caFile = null,
         private array $curlOptions = []
     ) {
         $this->validateEndpoint($endpoint);
         $this->normalizeHeaders();
     }
 
     /**
      * Validate that the endpoint URL is properly formatted
      */
     private function validateEndpoint(string $endpoint): void {
         if (empty($endpoint)) {
             throw new InvalidArgumentException('MCP HTTP endpoint cannot be empty');
         }
 
         $urlParts = parse_url($endpoint);
         if (!$urlParts || !isset($urlParts['scheme']) || !isset($urlParts['host'])) {
             throw new InvalidArgumentException(
                 'Invalid MCP HTTP endpoint format. Expected format: http(s)://hostname/path'
             );
         }
 
         $scheme = strtolower($urlParts['scheme']);
         if ($scheme !== 'http' && $scheme !== 'https') {
             throw new InvalidArgumentException(
                 'MCP HTTP endpoint must use http or https scheme'
             );
         }
     }
 
     /**
      * Normalize HTTP headers to ensure consistent format
      */
     private function normalizeHeaders(): void {
         $normalized = [];
         foreach ($this->headers as $name => $value) {
             // Convert header names to standard format (e.g., 'Content-Type')
             $headerName = implode('-', array_map('ucfirst', explode('-', strtolower($name))));
             $normalized[$headerName] = $value;
         }
         $this->headers = $normalized;
     }
 
     /**
      * Get the HTTP endpoint URL
      */
     public function getEndpoint(): string {
         return $this->endpoint;
     }
 
     /**
      * Get all HTTP headers
      */
     public function getHeaders(): array {
         return $this->headers;
     }
 
     /**
      * Get the connection timeout in seconds
      */
     public function getConnectionTimeout(): float {
         return $this->connectionTimeout;
     }
 
     /**
      * Get the read timeout in seconds
      */
     public function getReadTimeout(): float {
         return $this->readTimeout;
     }
 
     /**
      * Get the SSE idle timeout in seconds
      */
     public function getSseIdleTimeout(): float {
         return $this->sseIdleTimeout;
     }
 
     /**
      * Check if SSE should be enabled
      */
     public function isSseEnabled(): bool {
         return $this->enableSse;
     }
 
     /**
      * Get the maximum number of retries
      */
     public function getMaxRetries(): int {
         return $this->maxRetries;
     }
 
     /**
      * Get the base delay between retries in seconds
      */
     public function getRetryDelay(): float {
         return $this->retryDelay;
     }
 
     /**
      * Check if TLS certificate verification is enabled
      */
     public function isVerifyTlsEnabled(): bool {
         return $this->verifyTls;
     }
 
     /**
      * Get the custom CA certificate file path
      */
     public function getCaFile(): ?string {
         return $this->caFile;
     }
 
     /**
      * Get additional cURL options
      */
     public function getCurlOptions(): array {
         return $this->curlOptions;
     }
 
     /**
      * Create a new configuration with modified properties
      * 
      * @param array $properties Properties to modify
      * @return self New configuration instance
      */
     public function with(array $properties): self {
         $config = clone $this;
         foreach ($properties as $property => $value) {
             if (property_exists($config, $property)) {
                 $config->$property = $value;
             }
         }
         if (isset($properties['endpoint'])) {
             $config->validateEndpoint($config->endpoint);
         }
         if (isset($properties['headers'])) {
             $config->normalizeHeaders();
         }
         return $config;
     }
 }
 