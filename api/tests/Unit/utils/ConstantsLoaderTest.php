<?php

namespace MintHCM\Tests\Unit\Utils;

use MintHCM\Utils\ConstantsLoader;
use PHPUnit\Framework\TestCase;

final class ConstantsLoaderTest extends TestCase
{
    private string $temp_dir;
    private string $original_dir;

    protected function setUp(): void
    {
        $this->temp_dir = sys_get_temp_dir() . '/mint_constants_' . uniqid();
        mkdir($this->temp_dir . '/constants', 0777, true);
        $this->original_dir = getcwd() ?: '';
        chdir($this->temp_dir);
    }

    protected function tearDown(): void
    {
        if ($this->original_dir !== '') {
            chdir($this->original_dir);
        }
        $this->removeDirectory($this->temp_dir);
    }

    public function testReturnsFalseWhenCoreFileDoesNotExist(): void
    {
        self::assertFalse(ConstantsLoader::getConstants('nonexistent'));
    }

    public function testReturnsCoreConstantsWhenNoCustomDirectoryExists(): void
    {
        file_put_contents($this->temp_dir . '/constants/config.php', '<?php return ["key" => "core_value"];');

        $result = ConstantsLoader::getConstants('config');

        self::assertSame(['key' => 'core_value'], $result);
    }

    public function testReturnsCoreConstantsWhenCustomDirectoryIsEmpty(): void
    {
        file_put_contents($this->temp_dir . '/constants/config.php', '<?php return ["key" => "core_value"];');
        mkdir($this->temp_dir . '/custom/constants/config', 0777, true);

        $result = ConstantsLoader::getConstants('config');

        self::assertSame(['key' => 'core_value'], $result);
    }

    public function testMergesCustomConstantsOverCoreByDefault(): void
    {
        file_put_contents($this->temp_dir . '/constants/config.php', '<?php return ["key" => "core", "other" => "core_other"];');
        mkdir($this->temp_dir . '/custom/constants/config', 0777, true);
        file_put_contents($this->temp_dir . '/custom/constants/config/override.php', '<?php return ["key" => "custom"];');

        $result = ConstantsLoader::getConstants('config');

        self::assertSame('custom', $result['key']);
        self::assertSame('core_other', $result['other']);
    }

    public function testReturnsOnlyCustomWhenOnlyCustomIfExistIsTrueAndCustomExists(): void
    {
        file_put_contents($this->temp_dir . '/constants/config.php', '<?php return ["key" => "core", "other" => "core_other"];');
        mkdir($this->temp_dir . '/custom/constants/config', 0777, true);
        file_put_contents($this->temp_dir . '/custom/constants/config/override.php', '<?php return ["key" => "custom"];');

        $result = ConstantsLoader::getConstants('config', only_custom_if_exist: true);

        self::assertSame(['key' => 'custom'], $result);
        self::assertArrayNotHasKey('other', $result);
    }

    public function testFallsBackToCoreWhenOnlyCustomIfExistIsTrueButNoCustumExists(): void
    {
        file_put_contents($this->temp_dir . '/constants/config.php', '<?php return ["key" => "core_value"];');

        $result = ConstantsLoader::getConstants('config', only_custom_if_exist: true);

        self::assertSame(['key' => 'core_value'], $result);
    }

    public function testMergesMultipleCustomFiles(): void
    {
        file_put_contents($this->temp_dir . '/constants/config.php', '<?php return ["a" => 1];');
        mkdir($this->temp_dir . '/custom/constants/config', 0777, true);
        file_put_contents($this->temp_dir . '/custom/constants/config/first.php',  '<?php return ["b" => 2];');
        file_put_contents($this->temp_dir . '/custom/constants/config/second.php', '<?php return ["c" => 3];');

        $result = ConstantsLoader::getConstants('config');

        self::assertSame(1, $result['a']);
        self::assertSame(2, $result['b']);
        self::assertSame(3, $result['c']);
    }

    public function testIgnoresNonPhpFilesInCustomDirectory(): void
    {
        file_put_contents($this->temp_dir . '/constants/config.php', '<?php return ["key" => "core"];');
        mkdir($this->temp_dir . '/custom/constants/config', 0777, true);
        file_put_contents($this->temp_dir . '/custom/constants/config/readme.txt', 'This should be ignored');
        file_put_contents($this->temp_dir . '/custom/constants/config/data.json', '{"key": "json"}');

        $result = ConstantsLoader::getConstants('config');

        self::assertSame(['key' => 'core'], $result);
    }

    private function removeDirectory(string $path): void
    {
        if (!is_dir($path)) {
            return;
        }
        foreach (scandir($path) as $entry) {
            if ($entry === '.' || $entry === '..') {
                continue;
            }
            $full = $path . '/' . $entry;
            is_dir($full) ? $this->removeDirectory($full) : unlink($full);
        }
        rmdir($path);
    }
}
