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
 * Filename: Server/Transport/Http/Environment.php
 */

declare(strict_types=1);

namespace Mcp\Server\Transport\Http;

/**
 * Environment detection for HTTP transport configuration.
 * 
 * This class provides methods to detect the current PHP environment's capabilities
 * and limitations, especially regarding HTTP and SSE support.
 */
class Environment
{
    /**
     * Check if the current environment is likely a shared hosting environment.
     *
     * @return bool True if the environment appears to be shared hosting
     */
    public static function isSharedHosting(): bool
    {
        // Check for common shared hosting indicators
        $server = $_SERVER['SERVER_SOFTWARE'] ?? '';
        
        // Common shared hosting identifiers
        $sharedHostingIdentifiers = [
            'cpanel', 'plesk', 'directadmin', 'hostgator', 'bluehost', 
            'godaddy', 'cloudlinux', 'litespeed'
        ];
        
        foreach ($sharedHostingIdentifiers as $identifier) {
            if (stripos($server, $identifier) !== false) {
                return true;
            }
        }
        
        // Check for open_basedir restrictions (common in shared hosting)
        if (ini_get('open_basedir') !== '') {
            return true;
        }
        
        // Check for suPHP or other restrictive environments
        if (strpos(php_sapi_name() ?? '', 'cgi') !== false) {
            return true;
        }
        
        return false;
    }
    
    /**
     * Detect the maximum PHP execution time allowed by the environment.
     *
     * @return int The maximum execution time in seconds (0 means no limit)
     */
    public static function detectMaxExecutionTime(): int
    {
        $maxExecution = (int)ini_get('max_execution_time');
        
        // 0 means no time limit
        if ($maxExecution <= 0) {
            return 0;
        }
        
        return $maxExecution;
    }
    
    /**
     * Check if PHP is running in CLI mode.
     *
     * @return bool True if running in CLI mode
     */
    public static function isCliMode(): bool
    {
        return php_sapi_name() === 'cli';
    }
    
    /**
     * Check if the environment can support Server-Sent Events (SSE).
     *
     * @return bool True if SSE can be supported
     */
    public static function canSupportSse(): bool
    {
        // SSE requires:
        // 1. Long execution time
        // 2. Ability to flush output buffers
        // 3. No shared hosting limitations
        
        // If it's shared hosting, generally cannot support SSE
        if (self::isSharedHosting()) {
            return false;
        }
        
        // Need sufficient execution time for long-running connections
        $maxExecution = self::detectMaxExecutionTime();
        if ($maxExecution > 0 && $maxExecution < 60) {
            return false;
        }
        
        // Check if output buffering can be controlled
        if (ob_get_level() > 0 && !function_exists('ob_end_flush')) {
            return false;
        }
        
        // Check for presence of output compression
        if (ini_get('zlib.output_compression') == '1') {
            return false;
        }
        
        // Check for important functions that might be disabled
        $disabledFunctions = explode(',', ini_get('disable_functions'));
        $requiredFunctions = ['set_time_limit', 'ignore_user_abort', 'flush'];
        
        foreach ($requiredFunctions as $function) {
            if (in_array($function, $disabledFunctions)) {
                return false;
            }
        }
        
        return true;
    }
    
    /**
     * Get recommended configuration based on the detected environment.
     *
     * @return array Recommended configuration options
     */
    public static function getRecommendedConfig(): array
    {
        $config = [
            'session_timeout' => 3600, // 1 hour default
            'enable_sse' => false,     // Default disabled for compatibility
            'max_queue_size' => 1000,  // Maximum messages in queue
        ];
        
        // Adjust for CLI mode (development)
        if (self::isCliMode()) {
            $config['session_timeout'] = 86400; // 24 hours for development
            
            // CLI mode can potentially support SSE
            if (self::canSupportSse()) {
                $config['enable_sse'] = true;
            }
        }
        
        // Adjust for production environments
        if (!self::isCliMode()) {
            // Shared hosting needs more conservative settings
            if (self::isSharedHosting()) {
                $config['session_timeout'] = 1800; // 30 minutes
                $config['max_queue_size'] = 500;  // Smaller queue
            } else {
                // Non-shared hosting could potentially support SSE if environment checks pass
                $config['enable_sse'] = self::canSupportSse();
            }
        }
        
        // Determine maximum execution time and adjust accordingly
        $maxExecution = self::detectMaxExecutionTime();
        if ($maxExecution > 0) {
            // Ensure session timeout is not longer than max execution time
            // with some margin for safety
            $safeTimeout = (int)($maxExecution * 0.8);
            if ($safeTimeout < $config['session_timeout']) {
                $config['session_timeout'] = $safeTimeout;
            }
        }
        
        return $config;
    }
}
