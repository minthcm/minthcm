<?php

namespace MintMCP\Auth\Utils;

/**
 * Helper methods for URL handling
 */
class UrlHelper
{
    /**
     * Get domain URL (protocol + host)
     */
    public function getDomainUrl(): string
    {
        $protocol = $this->detectProtocol();
        $host = $_SERVER['HTTP_HOST'] ?? 'localhost';
        return "{$protocol}://{$host}";
    }

    /**
     * Detect protocol based on server variables
     */
    public function detectProtocol(): string
    {
        return (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ||
            $_SERVER['SERVER_PORT'] == 443 ||
            (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] === 'https')
            ? 'https' : 'http';
    }
    
    /**
     * Get OAuth base URL
     */
    public function getOAuthBaseUrl(): string
    {
        return $this->getDomainUrl() . '/oauth';
    }
}