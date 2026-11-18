<?php

namespace {
    if (!defined('sugarEntry')) {
        define('sugarEntry', true);
    }
}

namespace MintHCM\Tests\Unit\Utils {

    use PHPUnit\Framework\Attributes\DataProvider;
    use PHPUnit\Framework\TestCase;
    use SuiteCRM\Utility\SiteUrl;

    require_once dirname(__DIR__, 4) . '/legacy/lib/Utility/SiteUrl.php';

    final class SiteUrlTest extends TestCase
    {
        private mixed $original_sugar_config = null;
        private bool $had_sugar_config = false;

        protected function setUp(): void
        {
            $this->had_sugar_config = array_key_exists('sugar_config', $GLOBALS);
            $this->original_sugar_config = $GLOBALS['sugar_config'] ?? null;
        }

        protected function tearDown(): void
        {
            if ($this->had_sugar_config) {
                $GLOBALS['sugar_config'] = $this->original_sugar_config;
            } else {
                unset($GLOBALS['sugar_config']);
            }
        }

        // --- Regression: password reset link with site_url ending in "/" ---

        public function testPasswordResetLinkHasSingleSlashWhenSiteUrlEndsWithSlash(): void
        {
            self::assertSame(
                'https://host/#/auth/reset?token=abc',
                SiteUrl::build('#/auth/reset?token=abc', 'https://host/')
            );
        }

        public function testPasswordResetLinkUnchangedWhenSiteUrlHasNoTrailingSlash(): void
        {
            self::assertSame(
                'https://host/#/auth/reset?token=abc',
                SiteUrl::build('#/auth/reset?token=abc', 'https://host')
            );
        }

        // --- build() ---

        #[DataProvider('buildProvider')]
        public function testBuildJoinsWithExactlyOneSlash(string $site_url, string $path, string $expected): void
        {
            self::assertSame($expected, SiteUrl::build($path, $site_url));
        }

        public static function buildProvider(): array
        {
            return [
                'no trailing, no leading'        => ['https://host', 'index.php', 'https://host/index.php'],
                'trailing slash'                 => ['https://host/', 'index.php', 'https://host/index.php'],
                'leading slash in path'          => ['https://host', '/index.php', 'https://host/index.php'],
                'both slashes'                   => ['https://host/', '/index.php', 'https://host/index.php'],
                'multiple trailing slashes'      => ['https://host///', 'index.php', 'https://host/index.php'],
                'multiple leading slashes'       => ['https://host', '//index.php', 'https://host/index.php'],
                'sub-path install'               => ['https://host/Mint4/', 'legacy/index.php?module=Users&action=Login', 'https://host/Mint4/legacy/index.php?module=Users&action=Login'],
                'sub-path install, no trailing'  => ['https://host/Mint4', 'legacy/index.php?module=Users&action=Login', 'https://host/Mint4/legacy/index.php?module=Users&action=Login'],
                'empty path, trailing slash'     => ['https://host/', '', 'https://host/'],
                'empty path, no trailing slash'  => ['https://host', '', 'https://host/'],
                'inner slashes in path kept'     => ['https://host/', 'oauth-handler/GoogleOauth2Redirect', 'https://host/oauth-handler/GoogleOauth2Redirect'],
            ];
        }

        // --- base() ---

        #[DataProvider('baseProvider')]
        public function testBaseStripsTrailingSlashes(string $site_url, string $expected): void
        {
            self::assertSame($expected, SiteUrl::base($site_url));
        }

        public static function baseProvider(): array
        {
            return [
                'no trailing slash'         => ['https://host', 'https://host'],
                'trailing slash'            => ['https://host/', 'https://host'],
                'multiple trailing slashes' => ['https://host//', 'https://host'],
                'sub-path install'          => ['https://host/Mint4/', 'https://host/Mint4'],
                'empty'                     => ['', ''],
            ];
        }

        // --- fallback to $sugar_config['site_url'] ---

        public function testUsesSugarConfigSiteUrlWhenNotGiven(): void
        {
            $GLOBALS['sugar_config'] = ['site_url' => 'https://configured/'];

            self::assertSame('https://configured', SiteUrl::base());
            self::assertSame('https://configured/index.php', SiteUrl::build('index.php'));
        }

        public function testReturnsEmptyBaseWhenSiteUrlNotConfigured(): void
        {
            $GLOBALS['sugar_config'] = [];

            self::assertSame('', SiteUrl::base());
            self::assertSame('/', SiteUrl::build());
        }

        public function testReturnsEmptyBaseWhenSugarConfigMissing(): void
        {
            unset($GLOBALS['sugar_config']);

            self::assertSame('', SiteUrl::base());
        }
    }
}
