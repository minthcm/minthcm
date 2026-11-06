<?php

namespace MintHCM\Api\Middlewares\Security;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;

/**
 * Security headers for every API response - the API counterpart of legacy send_security_headers().
 *
 *  - Cache-Control: no-store - API responses carry personal data and must not stay in browser or proxy
 *    caches. An endpoint that sets its own Cache-Control (e.g. a file download) keeps it.
 *  - Strict-Transport-Security - only when $sugar_config['hsts_max_age'] > 0 and the request came over
 *    HTTPS. Off by default: HSTS on an instance without a valid certificate locks users out.
 *
 * Registered as the outermost middleware, so error responses get the headers too.
 */
class SecurityHeadersMiddleware
{
    public function __invoke(Request $request, RequestHandler $handler): Response
    {
        $response = $handler->handle($request);

        if (!$response->hasHeader('Cache-Control')) {
            $response = $response->withHeader('Cache-Control', 'no-store');
        }
        if (!$response->hasHeader('X-Content-Type-Options')) {
            $response = $response->withHeader('X-Content-Type-Options', 'nosniff');
        }

        $hsts_max_age = $this->getHstsMaxAge();
        if ($hsts_max_age > 0 && $request->getUri()->getScheme() === 'https') {
            $response = $response->withHeader('Strict-Transport-Security', "max-age={$hsts_max_age}");
        }

        return $response;
    }

    protected function getHstsMaxAge(): int
    {
        global $sugar_config;

        $max_age = $sugar_config['hsts_max_age'] ?? 0;
        return is_numeric($max_age) ? max(0, (int) $max_age) : 0;
    }
}
