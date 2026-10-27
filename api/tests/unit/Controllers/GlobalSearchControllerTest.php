<?php

namespace MintHCM\Tests\Unit\Controllers;

use Doctrine\ORM\EntityManagerInterface;
use MintHCM\Api\Controllers\GlobalSearchController;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

final class GlobalSearchControllerTest extends TestCase
{
    private ExposedGlobalSearchController $controller;

    protected function setUp(): void
    {
        $this->controller = new ExposedGlobalSearchController(
            $this->createMock(EntityManagerInterface::class)
        );
    }

    // --- International format (+prefix) ---

    public function testExpandsInternationalNumberIntoVariantsStrippingCountryCodes(): void
    {
        // +48555888666 → digits "48555888666", strip 1/2/3 leading chars
        $result = $this->controller->normalizePhone('+48555888666');
        self::assertSame('48555888666 OR 8555888666 OR 555888666 OR 55888666', $result);
    }

    public function testStripsWhitespaceAndDashesBeforeMatchingInternationalFormat(): void
    {
        $result = $this->controller->normalizePhone('+48 555-888-666');
        self::assertSame('48555888666 OR 8555888666 OR 555888666 OR 55888666', $result);
    }

    public function testStripsTrailingWildcardFromFrontendBeforeMatchingInternationalFormat(): void
    {
        $result = $this->controller->normalizePhone('+48555888666*');
        self::assertSame('48555888666 OR 8555888666 OR 555888666 OR 55888666', $result);
    }

    public function testDeduplicatesVariantsWhenCountryCodeStrippingProducesSameString(): void
    {
        // +1234567890 (10 digits) — all 3 stripped variants are distinct
        $result = $this->controller->normalizePhone('+1234567890');
        self::assertSame('1234567890 OR 234567890 OR 34567890 OR 4567890', $result);
    }

    public function testOmitsVariantShorterThan7DigitsFromInternationalExpansion(): void
    {
        // +4812345 (7 digits after +) — doesn't match international (needs 8–15)
        self::assertNull($this->controller->normalizePhone('+4812345'));
    }

    // --- Local format (digits only) ---

    public function testExpandsLocalNumberWithLeadingWildcard(): void
    {
        $result = $this->controller->normalizePhone('555888666');
        self::assertSame('555888666 OR *555888666*', $result);
    }

    public function testStripsFormattingCharsBeforeMatchingLocalFormat(): void
    {
        $result = $this->controller->normalizePhone('555-888-666');
        self::assertSame('555888666 OR *555888666*', $result);
    }

    public function testStripsTrailingWildcardBeforeMatchingLocalFormat(): void
    {
        $result = $this->controller->normalizePhone('555888666*');
        self::assertSame('555888666 OR *555888666*', $result);
    }

    // --- Non-phone queries → null ---

    #[DataProvider('nonPhoneQueryProvider')]
    public function testReturnsNullForNonPhoneQueries(string $query): void
    {
        self::assertNull($this->controller->normalizePhone($query));
    }

    public static function nonPhoneQueryProvider(): array
    {
        return [
            ['John Smith'],
            ['john@example.com'],
            ['123456'],       // too short (< 7 digits)
            ['1234567890123'], // too long for local (> 12 digits), no + for international
            ['abc123def'],
            [''],
        ];
    }
}

class ExposedGlobalSearchController extends GlobalSearchController
{
    public function normalizePhone(string $query): ?string
    {
        return $this->normalizePhoneQuery($query);
    }
}
