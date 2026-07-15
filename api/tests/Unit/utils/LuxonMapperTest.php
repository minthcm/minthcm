<?php

namespace MintHCM\Tests\Unit\Utils;

use MintHCM\Utils\LuxonMapper;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

final class LuxonMapperTest extends TestCase
{
    #[DataProvider('formatCharProvider')]
    public function testConvertsIndividualFormatChars(string $php, string $expected): void
    {
        self::assertSame($expected, LuxonMapper::phpToLuxonFormat($php));
    }

    public static function formatCharProvider(): array
    {
        return [
            // Day
            ['d', 'dd'],
            ['D', 'EEE'],
            ['j', 'd'],
            ['l', 'EEEE'],
            ['N', 'E'],
            ['z', 'o'],
            // Week
            ['W', 'W'],
            // Month
            ['F', 'MMMM'],
            ['m', 'MM'],
            ['M', 'MMM'],
            ['n', 'M'],
            // Year
            ['Y', 'yyyy'],
            ['y', 'yy'],
            // Time
            ['a', 'a'],
            ['A', 'a'],
            ['g', 'h'],
            ['G', 'H'],
            ['h', 'hh'],
            ['H', 'HH'],
            ['i', 'mm'],
            ['s', 'ss'],
            ['v', 'SSS'],
            // Timezone
            ['e', 'z'],
            ['O', 'ZZZ'],
            ['P', 'ZZ'],
            // Full Date/Time
            ['c', "yyyy-MM-dd'T'HH:mm:ssZZ"],
            ['r', "EEE, dd MMM yyyy HH:mm:ss ZZZ"],
        ];
    }

    public function testPassesThroughUnknownChars(): void
    {
        self::assertSame('/', LuxonMapper::phpToLuxonFormat('/'));
        self::assertSame('-', LuxonMapper::phpToLuxonFormat('-'));
        self::assertSame(' ', LuxonMapper::phpToLuxonFormat(' '));
        self::assertSame(':', LuxonMapper::phpToLuxonFormat(':'));
    }

    public function testConvertsCommonDateFormat(): void
    {
        self::assertSame('yyyy-MM-dd', LuxonMapper::phpToLuxonFormat('Y-m-d'));
    }

    public function testConvertsCommonDateTimeFormat(): void
    {
        self::assertSame('yyyy-MM-dd HH:mm:ss', LuxonMapper::phpToLuxonFormat('Y-m-d H:i:s'));
    }

    public function testConvertsCommonEuropeanDateFormat(): void
    {
        self::assertSame('dd/MM/yyyy', LuxonMapper::phpToLuxonFormat('d/m/Y'));
    }

    public function testReturnsEmptyStringForEmptyInput(): void
    {
        self::assertSame('', LuxonMapper::phpToLuxonFormat(''));
    }
}
