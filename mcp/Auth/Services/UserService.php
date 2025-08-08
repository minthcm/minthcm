<?php

namespace MintMCP\Auth\Services;

use BeanFactory;
use MintMCP\Auth\Utils\LegacyBridge;
use MintMCP\Handlers\Logger;

/**
 * Handles user-related operations
 */
class UserService
{
    /**
     * Get authenticated user ID
     */
    public function getAuthenticatedUserId(): ?string
    {
        global $current_user;
        
        // Check session
        if (!empty($_SESSION['authenticated_user_id'])) {
            Logger::getLogger()->info('User found in session', [
                'user_id' => $_SESSION['authenticated_user_id']
            ]);
            return $_SESSION['authenticated_user_id'];
        }

        // Check if user is logged in
        if (!empty($current_user) && !empty($current_user->id)) {
            $_SESSION['authenticated_user_id'] = $current_user->id;
            return $current_user->id;
        }

        Logger::getLogger()->info('No authenticated user found');
        return null;
    }
    
    /**
     * Get user info
     */
    public function getUserInfo(string $userId): array
    {
        chdir('../legacy');
        $user = BeanFactory::getBean('Users', $userId);
        chdir('../mcp');

        if (!$user) {
            return [
                'sub' => $userId,
                'name' => 'Unknown User'
            ];
        }

        return [
            'sub' => $userId,
            'name' => $user->full_name,
            'given_name' => $user->first_name,
            'family_name' => $user->last_name,
            'email' => $user->email1,
            'preferred_username' => $user->user_name
        ];
    }
}