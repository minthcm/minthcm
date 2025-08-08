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
 * Filename: Server/Transport/Http/HttpMessage.php
 */

declare(strict_types=1);

namespace Mcp\Server\Transport\Http;

/**
 * Represents an HTTP message (request or response).
 * 
 * This class provides a simple abstraction for HTTP messages, supporting
 * both request and response scenarios for the MCP HTTP transport.
 */
class HttpMessage
{
    /**
     * HTTP headers.
     *
     * @var array<string, string>
     */
    private array $headers = [];
    
    /**
     * HTTP status code (for responses).
     *
     * @var int
     */
    private int $statusCode = 200;
    
    /**
     * Request/response body.
     *
     * @var string|null
     */
    private ?string $body;
    
    /**
     * HTTP method (for requests).
     *
     * @var string|null
     */
    private ?string $method = null;
    
    /**
     * Request URI (for requests).
     *
     * @var string|null
     */
    private ?string $uri = null;
    
    /**
     * Request query parameters.
     *
     * @var array<string, string>
     */
    private array $queryParams = [];
    
    /**
     * Constructor.
     *
     * @param string|null $body Request/response body
     */
    public function __construct(?string $body = null)
    {
        $this->body = $body;
    }
    
    /**
     * Set an HTTP header.
     *
     * @param string $name Header name
     * @param string $value Header value
     * @return self
     */
    public function setHeader(string $name, string $value): self
    {
        $this->headers[strtolower($name)] = $value;
        return $this;
    }
    
    /**
     * Get an HTTP header.
     *
     * @param string $name Header name
     * @return string|null Header value or null if not found
     */
    public function getHeader(string $name): ?string
    {
        return $this->headers[strtolower($name)] ?? null;
    }
    
    /**
     * Get all HTTP headers.
     *
     * @return array<string, string> All headers
     */
    public function getHeaders(): array
    {
        return $this->headers;
    }
    
    /**
     * Set the HTTP status code.
     *
     * @param int $code HTTP status code
     * @return self
     */
    public function setStatusCode(int $code): self
    {
        $this->statusCode = $code;
        return $this;
    }
    
    /**
     * Get the HTTP status code.
     *
     * @return int HTTP status code
     */
    public function getStatusCode(): int
    {
        return $this->statusCode;
    }
    
    /**
     * Set the body.
     *
     * @param string|null $body Body content
     * @return self
     */
    public function setBody(?string $body): self
    {
        $this->body = $body;
        return $this;
    }
    
    /**
     * Get the body.
     *
     * @return string|null Body content
     */
    public function getBody(): ?string
    {
        return $this->body;
    }
    
    /**
     * Set the HTTP method.
     *
     * @param string $method HTTP method
     * @return self
     */
    public function setMethod(string $method): self
    {
        $this->method = strtoupper($method);
        return $this;
    }
    
    /**
     * Get the HTTP method.
     *
     * @return string|null HTTP method
     */
    public function getMethod(): ?string
    {
        return $this->method;
    }
    
    /**
     * Set the request URI.
     *
     * @param string $uri Request URI
     * @return self
     */
    public function setUri(string $uri): self
    {
        $this->uri = $uri;
        return $this;
    }
    
    /**
     * Get the request URI.
     *
     * @return string|null Request URI
     */
    public function getUri(): ?string
    {
        return $this->uri;
    }
    
    /**
     * Set query parameters.
     *
     * @param array<string, string> $params Query parameters
     * @return self
     */
    public function setQueryParams(array $params): self
    {
        $this->queryParams = $params;
        return $this;
    }
    
    /**
     * Get query parameters.
     *
     * @return array<string, string> Query parameters
     */
    public function getQueryParams(): array
    {
        return $this->queryParams;
    }
    
    /**
     * Get a query parameter.
     *
     * @param string $name Parameter name
     * @param string|null $default Default value if parameter not found
     * @return string|null Parameter value or default if not found
     */
    public function getQueryParam(string $name, ?string $default = null): ?string
    {
        return $this->queryParams[$name] ?? $default;
    }
    
    /**
     * Convert to an array representation.
     *
     * @return array Message representation
     */
    public function toArray(): array
    {
        return [
            'headers' => $this->headers,
            'status_code' => $this->statusCode,
            'body' => $this->body,
            'method' => $this->method,
            'uri' => $this->uri,
            'query_params' => $this->queryParams,
        ];
    }
    
    /**
     * Create an HttpMessage from PHP globals.
     *
     * @return self
     */
    public static function fromGlobals(): self
    {
        $message = new self();
        
        // Set method
        $message->setMethod($_SERVER['REQUEST_METHOD'] ?? 'GET');
        
        // Set URI
        $uri = $_SERVER['REQUEST_URI'] ?? '/';
        $message->setUri($uri);
        
        // Set headers
        foreach ($_SERVER as $key => $value) {
            if (strpos($key, 'HTTP_') === 0) {
                $headerName = str_replace('_', '-', substr($key, 5));
                $message->setHeader($headerName, $value);
            } elseif (in_array($key, ['CONTENT_TYPE', 'CONTENT_LENGTH'])) {
                $headerName = str_replace('_', '-', $key);
                $message->setHeader($headerName, $value);
            }
        }
        
        // Set query parameters
        $message->setQueryParams($_GET);
        
        // Set body
        $body = file_get_contents('php://input');
        if ($body !== false) {
            $message->setBody($body);
        }
        
        return $message;
    }
    
    /**
     * Create a JSON response.
     *
     * @param mixed $data Response data
     * @param int $code HTTP status code
     * @return self
     */
    public static function createJsonResponse($data, int $code = 200): self
    {
        $response = new self();
        $response->setStatusCode($code);
        $response->setHeader('Content-Type', 'application/json');
        
        // Encode the data as JSON
        $body = json_encode($data, JSON_UNESCAPED_SLASHES);
        if ($body !== false) {
            $response->setBody($body);
        } else {
            // Fallback for encoding errors
            $response->setBody('{"error":"JSON encoding error"}');
            $response->setStatusCode(500);
        }
        
        return $response;
    }
    
    /**
     * Create a plain text response.
     *
     * @param string $text Response text
     * @param int $code HTTP status code
     * @return self
     */
    public static function createTextResponse(string $text, int $code = 200): self
    {
        $response = new self($text);
        $response->setStatusCode($code);
        $response->setHeader('Content-Type', 'text/plain');
        
        return $response;
    }
    
    /**
     * Create an empty response with the given status code.
     *
     * @param int $code HTTP status code
     * @return self
     */
    public static function createEmptyResponse(int $code = 204): self
    {
        $response = new self();
        $response->setStatusCode($code);
        
        return $response;
    }
}
