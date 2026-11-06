<?php

namespace MintHCM\Tests\Unit\Lib\Services\Auth;

use MintHCM\Lib\Services\Auth\PasswordHash;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

final class PasswordHashTest extends TestCase
{
    private PasswordHash $password_hash;

    protected function setUp(): void
    {
        $this->password_hash = new PasswordHash();
    }

    protected function tearDown(): void
    {
        unset($GLOBALS['sugar_config']);
    }

    public function testAcceptsLegacyMd5HashWhenSettingIsMissing(): void
    {
        self::assertTrue($this->password_hash->verifyMd5(md5('secret'), md5('secret')));
    }

    public function testAcceptsLegacyMd5HashRegardlessOfInputCase(): void
    {
        self::assertTrue($this->password_hash->verifyMd5(strtoupper(md5('secret')), md5('secret')));
    }

    public function testRejectsWrongPasswordAgainstLegacyMd5Hash(): void
    {
        self::assertFalse($this->password_hash->verifyMd5(md5('wrong'), md5('secret')));
    }

    #[DataProvider('disabledLegacyMd5Values')]
    public function testRejectsLegacyMd5HashWhenDisabled(mixed $value): void
    {
        $GLOBALS['sugar_config'] = ['passwordsetting' => ['legacy_md5_allowed' => $value]];

        self::assertFalse($this->password_hash->verifyMd5(md5('secret'), md5('secret')));
    }

    public static function disabledLegacyMd5Values(): array
    {
        return [
            'bool' => [false],
            'int' => [0],
            'string zero' => ['0'],
            'string false' => ['false'],
        ];
    }

    public function testBcryptHashStillWorksWhenLegacyMd5IsDisabled(): void
    {
        $GLOBALS['sugar_config'] = ['passwordsetting' => ['legacy_md5_allowed' => false]];
        $hash = $this->password_hash->hashMd5(md5('secret'));

        self::assertTrue($this->password_hash->verifyMd5(md5('secret'), $hash));
        self::assertFalse($this->password_hash->verifyMd5(md5('wrong'), $hash));
    }

    public function testRejectsEmptyHash(): void
    {
        self::assertFalse($this->password_hash->verifyMd5(md5(''), ''));
        self::assertFalse($this->password_hash->verifyMd5(md5(''), null));
    }

    public function testLegacyMd5HashNeedsRehash(): void
    {
        self::assertTrue($this->password_hash->needsRehash(md5('secret')));
    }

    public function testCurrentHashDoesNotNeedRehash(): void
    {
        self::assertFalse($this->password_hash->needsRehash($this->password_hash->hashMd5(md5('secret'))));
    }

    public function testEmptyHashDoesNotNeedRehash(): void
    {
        self::assertFalse($this->password_hash->needsRehash(null));
    }

    public function testHashMatchesLegacyUserFormat(): void
    {
        // legacy User::getPasswordHashMD5() hashes the lowercase md5 - both layers must verify each other's hashes
        $hash = $this->password_hash->hashMd5(strtoupper(md5('secret')));

        self::assertTrue(password_verify(strtolower(md5('secret')), $hash));
    }
}
