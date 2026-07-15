<?php

declare(strict_types=1);

namespace MintMCP\Auth\Internal;

/**
 * CORS allowlist for internal/session-token: Vite dev server + deployed app (site_url).
 */
final class TrustedOrigins
{
    /** Local Vue dev server */
    private const VITE_DEV = 'http://localhost:5173';

    public static function normalizeOrigin(?string $origin): ?string
    {
        if ($origin === null || $origin === '') {
            return null;
        }
        $parts = parse_url($origin);
        if (empty($parts['scheme']) || empty($parts['host'])) {
            return null;
        }
        $n = strtolower((string) $parts['scheme']) . '://' . strtolower((string) $parts['host']);
        if (!empty($parts['port'])) {
            $n .= ':' . $parts['port'];
        }
        return $n;
    }

    /** @return list<string> */
    public static function allAllowedOrigins(?string $siteUrl = null): array
    {
        if ($siteUrl === null) {
            global $sugar_config;
            $siteUrl = $sugar_config['site_url'] ?? null;
        }
        $origins = [self::VITE_DEV];
        $app = self::normalizeOrigin($siteUrl);
        if ($app !== null) {
            $origins[] = $app;
        }
        return array_values(array_unique($origins));
    }

    public static function isAllowed(?string $origin, ?string $siteUrl = null): bool
    {
        $n = self::normalizeOrigin($origin);
        return $n !== null && in_array($n, self::allAllowedOrigins($siteUrl), true);
    }
}
