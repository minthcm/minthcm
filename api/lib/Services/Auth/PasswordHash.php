<?php

namespace MintHCM\Lib\Services\Auth;

/**
 * Password hash policy for the API login path.
 *
 * Passwords are stored as bcrypt (PASSWORD_DEFAULT) of the lowercase md5 of the plaintext - the same
 * format legacy User::getPasswordHashMD5() produces. Very old accounts may still hold a plain,
 * unsalted md5 (32 hex chars); those are accepted while $sugar_config['passwordsetting']['legacy_md5_allowed']
 * is not false and re-hashed on the next successful login.
 *
 * The legacy layer cannot autoload this class - User::checkPasswordMD5() implements the same policy
 * and both have to be changed together.
 */
class PasswordHash
{
    private const LEGACY_MD5_LENGTH = 32;

    public function verifyMd5(string $password_md5, ?string $hash): bool
    {
        if (empty($hash)) {
            return false;
        }

        $password_md5 = strtolower($password_md5);
        if ($this->isLegacyMd5($hash)) {
            return $this->isLegacyMd5Allowed() && hash_equals($hash, $password_md5);
        }

        return password_verify($password_md5, $hash);
    }

    public function needsRehash(?string $hash): bool
    {
        if (empty($hash)) {
            return false;
        }

        return $this->isLegacyMd5($hash) || password_needs_rehash($hash, PASSWORD_DEFAULT);
    }

    public function hashMd5(string $password_md5): string
    {
        return password_hash(strtolower($password_md5), PASSWORD_DEFAULT);
    }

    public function isLegacyMd5Allowed(): bool
    {
        global $sugar_config;

        $allowed = $sugar_config['passwordsetting']['legacy_md5_allowed'] ?? true;
        return !in_array($allowed, [false, 0, '0', 'false'], true);
    }

    private function isLegacyMd5(string $hash): bool
    {
        return $hash[0] !== '$' && strlen($hash) === self::LEGACY_MD5_LENGTH;
    }
}
