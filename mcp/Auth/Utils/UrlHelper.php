<?php

namespace MintMCP\Auth\Utils;

/**
 * Helper methods for URL handling
 */
class UrlHelper
{
    /**
     * Base URL of the MCP server (protocol + host + mount path including /mcp).
     * Primary source: site_url from Sugar config (set by admin during install).
     * This handles protocol and subpath correctly in all server configurations,
     * including nginx SSL termination and non-root deployments.
     */
    public function getDomainUrl(): string
    {
        $siteUrl = $GLOBALS['sugar_config']['site_url'] ?? null;
        if (!empty($siteUrl)) {
            return rtrim($siteUrl, '/') . '/mcp';
        }

        // Fallback when Sugar config is not loaded (e.g. unit tests).
        $protocol = $this->detectProtocol();
        $host = $_SERVER['HTTP_HOST'] ?? 'localhost';
        $basePath = $this->detectBasePath();
        return "{$protocol}://{$host}{$basePath}";
    }

    private function detectBasePath(): string
    {
        // Use DOCUMENT_ROOT + SCRIPT_FILENAME instead of SCRIPT_NAME.
        // SCRIPT_NAME is distorted by RewriteBase in .htaccess; the other
        // two are set by the server directly and are always correct.
        $docRoot = rtrim($_SERVER['DOCUMENT_ROOT'] ?? '', '/');
        $scriptFile = $_SERVER['SCRIPT_FILENAME'] ?? '';
        if ($docRoot !== '' && str_starts_with($scriptFile, $docRoot)) {
            $dir = rtrim(dirname(substr($scriptFile, strlen($docRoot))), '/');
            // Strip the trailing /mcp segment — everything before it is the subpath.
            if ($dir === 'mcp' || $dir === '/mcp') {
                return '/mcp';
            }
            if (str_ends_with($dir, '/mcp')) {
                return substr($dir, 0, -4) . '/mcp';
            }
        }

        // Last-resort fallback via SCRIPT_NAME.
        $scriptName = $_SERVER['SCRIPT_NAME'] ?? '';
        $basePath = rtrim(dirname($scriptName), '/');
        return ($basePath === '' || $basePath === '.' || $basePath === '\\') ? '/mcp' : $basePath;
    }

    /**
     * Detect protocol. Checks forwarded headers for reverse-proxy setups.
     */
    public function detectProtocol(): string
    {
        $forwarded = $_SERVER['HTTP_X_FORWARDED_PROTO'] ?? $_SERVER['REQUEST_SCHEME'] ?? '';
        if ($forwarded === 'https') {
            return 'https';
        }
        if (!empty($_SERVER['HTTP_X_FORWARDED_SSL']) && $_SERVER['HTTP_X_FORWARDED_SSL'] === 'on') {
            return 'https';
        }
        if (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') {
            return 'https';
        }
        if (($_SERVER['SERVER_PORT'] ?? '') == 443) {
            return 'https';
        }
        return 'http';
    }
    
    /**
     * Get OAuth base URL
     */
    public function getOAuthBaseUrl(): string
    {
        return $this->getDomainUrl() . '/oauth';
    }
}