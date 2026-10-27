<?php

namespace MintHCM\Tests\Unit\RecordView;

use PHPUnit\Framework\TestCase;

require_once dirname(__DIR__, 4) . '/legacy/include/RecordView/RecordViewDefsBuilder.php';

final class RecordViewDefsBuilderTest extends TestCase
{
    private string $temp_dir;

    protected function setUp(): void
    {
        $this->temp_dir = sys_get_temp_dir() . '/mint_recordviewdefs_' . uniqid();
        mkdir($this->temp_dir, 0777, true);
    }

    protected function tearDown(): void
    {
        $this->removeDirectory($this->temp_dir);
    }

    public function testReturnsSourceDefinitionWhenNoExtensionsGiven(): void
    {
        $source = $this->writeFile('source.php', "<?php \$viewdefs['Meetings'] = ['order' => ['basicInfo']];");

        $result = \RecordViewDefsBuilder::build('Meetings', $source, []);

        self::assertSame(['order' => ['basicInfo']], $result);
    }

    public function testReturnsEmptyArrayWhenSourceFileIsMissing(): void
    {
        $result = \RecordViewDefsBuilder::build('Meetings', $this->temp_dir . '/does-not-exist.php', []);

        self::assertSame([], $result);
    }

    public function testExtensionCanAddAnAction(): void
    {
        $source = $this->writeFile('source.php', "<?php \$viewdefs['Meetings'] = [
            'panels' => ['basicInfo' => ['data' => ['actions' => ['Delete']]]],
        ];");
        $extension = $this->writeFile('01-add-action.php', "<?php
            \$viewdefs['Meetings']['panels']['basicInfo']['data']['actions'][] = 'CloseMeeting';
        ");

        $result = \RecordViewDefsBuilder::build('Meetings', $source, [$extension]);

        self::assertSame(['Delete', 'CloseMeeting'], $result['panels']['basicInfo']['data']['actions']);
    }

    public function testExtensionCanOverwriteAnExistingPanel(): void
    {
        $source = $this->writeFile('source.php', "<?php \$viewdefs['Meetings'] = [
            'panels' => ['basicInfo' => ['component' => 'MintPanelRecordDetails']],
        ];");
        $extension = $this->writeFile('01-override-panel.php', "<?php
            \$viewdefs['Meetings']['panels']['basicInfo'] = ['component' => 'CustomPanel'];
        ");

        $result = \RecordViewDefsBuilder::build('Meetings', $source, [$extension]);

        self::assertSame(['component' => 'CustomPanel'], $result['panels']['basicInfo']);
    }

    public function testExtensionCanRemoveTheSidepanel(): void
    {
        $source = $this->writeFile('source.php', "<?php \$viewdefs['Meetings'] = [
            'sidepanel' => ['MintWidgetTenure'],
        ];");
        $extension = $this->writeFile('01-remove-sidepanel.php', "<?php
            unset(\$viewdefs['Meetings']['sidepanel']);
        ");

        $result = \RecordViewDefsBuilder::build('Meetings', $source, [$extension]);

        self::assertArrayNotHasKey('sidepanel', $result);
    }

    public function testMultipleExtensionsAreAppliedInGivenOrderWithoutOverwritingUnrelatedChanges(): void
    {
        $source = $this->writeFile('source.php', "<?php \$viewdefs['Meetings'] = [
            'panels' => ['basicInfo' => ['data' => ['actions' => []]]],
            'sidepanel' => [],
        ];");
        $first = $this->writeFile('01-add-action.php', "<?php
            \$viewdefs['Meetings']['panels']['basicInfo']['data']['actions'][] = 'CloseMeeting';
        ");
        $second = $this->writeFile('02-add-widget.php', "<?php
            \$viewdefs['Meetings']['sidepanel'][] = 'MintWidgetCalendar';
        ");

        $result = \RecordViewDefsBuilder::build('Meetings', $source, [$first, $second]);

        self::assertSame(['CloseMeeting'], $result['panels']['basicInfo']['data']['actions']);
        self::assertSame(['MintWidgetCalendar'], $result['sidepanel']);
    }

    public function testLaterExtensionWinsWhenTwoExtensionsTouchTheSameKey(): void
    {
        $source = $this->writeFile('source.php', "<?php \$viewdefs['Meetings'] = ['order' => ['basicInfo']];");
        $first = $this->writeFile('01-set-order.php', "<?php \$viewdefs['Meetings']['order'] = ['a'];");
        $second = $this->writeFile('02-set-order.php', "<?php \$viewdefs['Meetings']['order'] = ['b'];");

        $result = \RecordViewDefsBuilder::build('Meetings', $source, [$first, $second]);

        self::assertSame(['b'], $result['order']);
    }

    public function testOverwritingTheParentKeyDiscardsAnEarlierNestedChange(): void
    {
        $source = $this->writeFile('source.php', "<?php \$viewdefs['Meetings'] = [
            'panels' => ['basicInfo' => ['data' => ['actions' => ['Delete']]]],
        ];");
        $first = $this->writeFile('01-add-action.php', "<?php
            \$viewdefs['Meetings']['panels']['basicInfo']['data']['actions'][] = 'CloseMeeting';
        ");
        $second = $this->writeFile('02-override-panel.php', "<?php
            \$viewdefs['Meetings']['panels']['basicInfo'] = ['component' => 'CustomPanel'];
        ");

        $result = \RecordViewDefsBuilder::build('Meetings', $source, [$first, $second]);

        // Expected behavior: the second extension overwrites the whole 'basicInfo' key, so the
        // action added by the first extension is gone -- extension order matters and later wins,
        // even when an earlier extension only touched a nested fragment of what gets overwritten.
        self::assertSame(['component' => 'CustomPanel'], $result['panels']['basicInfo']);
    }

    public function testMissingExtensionFileIsSkippedInsteadOfFailing(): void
    {
        $source = $this->writeFile('source.php', "<?php \$viewdefs['Meetings'] = ['order' => ['basicInfo']];");

        $result = \RecordViewDefsBuilder::build('Meetings', $source, [$this->temp_dir . '/missing-extension.php']);

        self::assertSame(['order' => ['basicInfo']], $result);
    }

    public function testUnreadableExtensionFileIsSkippedInsteadOfFailing(): void
    {
        $extension = $this->writeFile('01-unreadable.php', "<?php \$viewdefs['Meetings']['order'][] = 'shouldNotApply';");
        chmod($extension, 0000);
        if (is_readable($extension)) {
            self::markTestSkipped('Cannot make file unreadable in this environment (e.g. running as root).');
        }
        $source = $this->writeFile('source.php', "<?php \$viewdefs['Meetings'] = ['order' => ['basicInfo']];");

        $result = \RecordViewDefsBuilder::build('Meetings', $source, [$extension]);

        self::assertSame(['order' => ['basicInfo']], $result);
    }

    private function writeFile(string $name, string $contents): string
    {
        $path = $this->temp_dir . '/' . $name;
        file_put_contents($path, $contents);
        return $path;
    }

    private function removeDirectory(string $dir): void
    {
        if (!is_dir($dir)) {
            return;
        }
        foreach (scandir($dir) ?: [] as $entry) {
            if ('.' === $entry || '..' === $entry) {
                continue;
            }
            unlink($dir . '/' . $entry);
        }
        rmdir($dir);
    }
}
