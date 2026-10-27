<?php

namespace MintHCM\Tests\Unit\Middlewares\Params;

use MintHCM\Api\Middlewares\Params\ParamTypes\ArrayType;
use MintHCM\Api\Middlewares\Params\ParamTypes\BoolType;
use MintHCM\Api\Middlewares\Params\ParamTypes\EmailType;
use MintHCM\Api\Middlewares\Params\ParamTypes\IntType;
use MintHCM\Api\Middlewares\Params\ParamTypes\StringType;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Exception\HttpBadRequestException;

final class ParamTypesTest extends TestCase
{
    // --- BoolType ---

    #[DataProvider('validBoolProvider')]
    public function testBoolTypeAcceptsValidValues(mixed $value, bool $expected): void
    {
        $result = (new BoolType())($this->request(), $value);
        self::assertSame($expected, $result);
    }

    public static function validBoolProvider(): array
    {
        return [
            [true,    true],
            [false,   false],
            [1,       true],
            [0,       false],
            ['1',     true],
            ['0',     false],
            ['true',  true],
            ['false', false],
            ['TRUE',  true],
            ['FALSE', false],
        ];
    }

    #[DataProvider('invalidBoolProvider')]
    public function testBoolTypeRejectsInvalidValues(mixed $value): void
    {
        $this->expectException(HttpBadRequestException::class);
        (new BoolType())($this->request(), $value);
    }

    public static function invalidBoolProvider(): array
    {
        return [
            ['yes'],
            ['no'],
            ['enabled'],
            [2],
        ];
    }

    public function testBoolTypeReturnsNullForMissingOptionalValue(): void
    {
        self::assertNull((new BoolType())($this->request(), null, false));
    }

    public function testBoolTypeThrowsForMissingRequiredValue(): void
    {
        $this->expectException(HttpBadRequestException::class);
        (new BoolType())($this->request(), null, true);
    }

    // --- IntType ---

    #[DataProvider('validIntProvider')]
    public function testIntTypeAcceptsValidValues(mixed $value, int $expected): void
    {
        $result = (new IntType())($this->request(), $value);
        self::assertSame($expected, $result);
    }

    public static function validIntProvider(): array
    {
        return [
            [42,   42],
            ['42', 42],
            ['-5', -5],
            ['0',  0],
            [0,    0],
        ];
    }

    #[DataProvider('invalidIntProvider')]
    public function testIntTypeRejectsInvalidValues(mixed $value): void
    {
        $this->expectException(HttpBadRequestException::class);
        (new IntType())($this->request(), $value);
    }

    public static function invalidIntProvider(): array
    {
        return [
            ['3.14'],
            ['abc'],
            ['1e5'],
            ['1 2'],
            [''],
        ];
    }

    // --- EmailType ---

    #[DataProvider('validEmailProvider')]
    public function testEmailTypeAcceptsValidAddresses(string $email): void
    {
        $result = (new EmailType())($this->request(), $email);
        self::assertSame($email, $result);
    }

    public static function validEmailProvider(): array
    {
        return [
            ['user@example.com'],
            ['user.name@domain.co.uk'],
            ['user+tag@example.com'],
            ['user-name@example.com'],
        ];
    }

    #[DataProvider('invalidEmailProvider')]
    public function testEmailTypeRejectsInvalidAddresses(mixed $value): void
    {
        $this->expectException(HttpBadRequestException::class);
        (new EmailType())($this->request(), $value);
    }

    public static function invalidEmailProvider(): array
    {
        return [
            ['not-an-email'],
            ['@example.com'],
            ['user@'],
            ['plainaddress'],
            [42],
        ];
    }

    // --- ArrayType ---

    public function testArrayTypeConvertsCommaSeparatedStringToArray(): void
    {
        $result = (new ArrayType())($this->request(), 'a,b,c');
        self::assertSame(['a', 'b', 'c'], $result);
    }

    public function testArrayTypePassesThroughArray(): void
    {
        $result = (new ArrayType())($this->request(), ['a', 'b']);
        self::assertSame(['a', 'b'], $result);
    }

    public function testArrayTypeConvertsSingleStringToSingleElementArray(): void
    {
        $result = (new ArrayType())($this->request(), 'single');
        self::assertSame(['single'], $result);
    }

    #[DataProvider('invalidArrayProvider')]
    public function testArrayTypeRejectsNonStringNonArray(mixed $value): void
    {
        $this->expectException(HttpBadRequestException::class);
        (new ArrayType())($this->request(), $value);
    }

    public static function invalidArrayProvider(): array
    {
        return [
            [42],
            [true],
            [3.14],
        ];
    }

    // --- StringType ---

    public function testStringTypeAcceptsString(): void
    {
        $result = (new StringType())($this->request(), 'hello');
        self::assertSame('hello', $result);
    }

    public function testStringTypeAcceptsEmptyString(): void
    {
        $result = (new StringType())($this->request(), '');
        self::assertSame('', $result);
    }

    #[DataProvider('invalidStringProvider')]
    public function testStringTypeRejectsNonStringValues(mixed $value): void
    {
        $this->expectException(HttpBadRequestException::class);
        (new StringType())($this->request(), $value);
    }

    public static function invalidStringProvider(): array
    {
        return [
            [42],
            [true],
            [3.14],
            [[]],
        ];
    }

    private function request(): ServerRequestInterface
    {
        return $this->createMock(ServerRequestInterface::class);
    }
}
