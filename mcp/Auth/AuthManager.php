<?php

namespace MintMCP\Auth;

use MintMCP\Handlers\Logger;
use BeanFactory;

/**
 * Authentication Manager for MintHCM MCP
 * 
 * Handles token validation and user authentication
 */
class AuthManager
{
    private static ?AuthManager $instance = null;
    private ?string $token;
    private ?array $tokenData;
    private ?string $userId;

    /**
     * Get singleton instance
     * 
     * @param string|null $token Authentication token
     * @return self Instance of AuthManager
     */
    public static function getInstance(?string $token = null): self
    {
        if (self::$instance === null) {
            self::$instance = new self($token);
        }
        return self::$instance;
    }
    
    /**
     * Constructor
     * 
     * @param string|null $token Authentication token
     */
    public function __construct(?string $token = null)
    {
        $this->token = $token;
        $this->tokenData = null;
        $this->userId = null;
    }
    
    /**
     * Validate token and set current user
     * 
     * @return bool True if token is valid, false otherwise
     */
    public function validate(): bool
    {
        if (empty($this->token)) {
            return false;
        }
        
        // Use the OAuth2Server to validate the token
        $oauth2Server = OAuth2Server::getInstance();
        $this->tokenData = $oauth2Server->introspectToken($this->token);
        
        if (!$this->isTokenValid()) {
            Logger::getLogger()->warning('Invalid token', [
                'token' => $this->getObfuscatedToken()
            ]);
            return false;
        }
        
        $this->userId = $this->tokenData['user_id'];
        
        // Set the global current_user
        $this->setCurrentUser();
        
        return true;
    }
    
    /**
     * Check if token data is valid
     */
    private function isTokenValid(): bool
    {
        return $this->tokenData && 
               isset($this->tokenData['active']) && 
               $this->tokenData['active'];
    }
    
    /**
     * Get obfuscated token for logging
     */
    private function getObfuscatedToken(): string
    {
        return substr($this->token, 0, 10) . '...';
    }
    
    /**
     * Get user ID from token
     * 
     * @return string|null User ID or null if not authenticated
     */
    public function getUserId(): ?string
    {
        return $this->userId;
    }
    
    /**
     * Get token data
     * 
     * @return array|null Token data or null if not authenticated
     */
    public function getTokenData(): ?array
    {
        return $this->tokenData;
    }
    
    /**
     * Set current user in global scope
     */
    private function setCurrentUser(): void
    {
        global $current_user;

        if (empty($this->userId)) {
            return;
        }
        
        chdir('../legacy');
        $user = BeanFactory::getBean('Users', $this->userId);
        chdir('../mcp');
        
        if ($user) {
            $current_user = $user;
        }
    }
}