<?php

namespace MintHCM\Tests\Unit\Lib\Services\Http;

use MintHCM\Lib\Services\Http\ClientIpResolver;
use PHPUnit\Framework\TestCase;

final class ClientIpResolverTest extends TestCase
{
    protected function tearDown(): void
    {
        unset($GLOBALS['sugar_config']);
    }

    public function testClientIpIsRemoteAddressWithoutTrustedProxies(): void
    {
        $ip = (new ClientIpResolver())->resolve(['REMOTE_ADDR' => '10.0.0.5', 'HTTP_X_FORWARDED_FOR' => '198.51.100.9']);

        self::assertSame('10.0.0.5', $ip);
    }

    public function testClientIpComesFromForwardedHeaderBehindTrustedProxy(): void
    {
        $this->trustProxies(['10.0.0.5']);

        $ip = (new ClientIpResolver())->resolve(['REMOTE_ADDR' => '10.0.0.5', 'HTTP_X_FORWARDED_FOR' => '198.51.100.9']);

        self::assertSame('198.51.100.9', $ip);
    }

    public function testClientIpIgnoresSpoofedLeftMostForwardedEntry(): void
    {
        $this->trustProxies(['10.0.0.5', '10.0.0.6']);

        // client sent 1.2.3.4 itself; the proxies appended the real address and their own
        $ip = (new ClientIpResolver())->resolve([
            'REMOTE_ADDR' => '10.0.0.5',
            'HTTP_X_FORWARDED_FOR' => '1.2.3.4, 198.51.100.9, 10.0.0.6',
        ]);

        self::assertSame('198.51.100.9', $ip);
    }

    public function testClientIpFallsBackToRemoteAddressForInvalidForwardedEntry(): void
    {
        $this->trustProxies(['10.0.0.5']);

        $ip = (new ClientIpResolver())->resolve(['REMOTE_ADDR' => '10.0.0.5', 'HTTP_X_FORWARDED_FOR' => 'not-an-ip']);

        self::assertSame('10.0.0.5', $ip);
    }

    public function testForwardedHeaderFromUntrustedHostIsIgnored(): void
    {
        $this->trustProxies(['10.0.0.5']);

        $ip = (new ClientIpResolver())->resolve(['REMOTE_ADDR' => '203.0.113.50', 'HTTP_X_FORWARDED_FOR' => '198.51.100.9']);

        self::assertSame('203.0.113.50', $ip);
    }

    public function testInvalidTrustedProxiesSettingIsIgnored(): void
    {
        $GLOBALS['sugar_config'] = ['trusted_proxies' => '10.0.0.5'];

        $ip = (new ClientIpResolver())->resolve(['REMOTE_ADDR' => '10.0.0.5', 'HTTP_X_FORWARDED_FOR' => '198.51.100.9']);

        self::assertSame('10.0.0.5', $ip);
    }

    /**
     * @param string[] $proxies
     */
    private function trustProxies(array $proxies): void
    {
        $GLOBALS['sugar_config'] = ['trusted_proxies' => $proxies];
    }
}
