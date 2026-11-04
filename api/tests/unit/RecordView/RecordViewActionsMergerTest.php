<?php

namespace MintHCM\Tests\Unit\RecordView;

use MintHCM\Api\RecordView\RecordViewActionsMerger;
use PHPUnit\Framework\TestCase;

final class RecordViewActionsMergerTest extends TestCase
{
    private const DEFAULT_ACTIONS = [
        'Audit',
        'Delete',
        'Duplicate',
    ];

    private RecordViewActionsMerger $merger;

    protected function setUp(): void
    {
        $this->merger = new RecordViewActionsMerger();
    }

    public function testReturnsDefaultActionsWhenNothingIsDeclared(): void
    {
        $result = $this->merger->merge([], true, self::DEFAULT_ACTIONS);

        self::assertSame(self::DEFAULT_ACTIONS, $result);
    }

    public function testEmptyDeclarationDoesNotDisableDefaultActions(): void
    {
        $result = $this->merger->merge([], true, self::DEFAULT_ACTIONS);

        self::assertSame(['Audit', 'Delete', 'Duplicate'], $result);
    }

    public function testAppendsNamedActionAfterDefaultActions(): void
    {
        $result = $this->merger->merge([['name' => 'Archive']], true, self::DEFAULT_ACTIONS);

        self::assertSame(['Audit', 'Delete', 'Duplicate', ['name' => 'Archive']], $result);
    }

    public function testAppendsGenericApiActionAfterDefaultActions(): void
    {
        $action = ['key' => 'MauticSync', 'api_route' => 'ProspectLists/syncWithMautic'];

        $result = $this->merger->merge([$action], true, self::DEFAULT_ACTIONS);

        self::assertSame(['Audit', 'Delete', 'Duplicate', $action], $result);
    }

    public function testDeclarationOverridingDefaultActionReplacesItInPlace(): void
    {
        $audit = ['name' => 'Audit', 'icon' => 'mdi-history'];

        $result = $this->merger->merge([$audit], true, self::DEFAULT_ACTIONS);

        self::assertSame([$audit, 'Delete', 'Duplicate'], $result);
    }

    public function testLastDeclarationWinsForRepeatedGenericActionKey(): void
    {
        $first = ['key' => 'Sync', 'api_route' => 'A/first'];
        $last = ['key' => 'Sync', 'api_route' => 'B/last'];

        $result = $this->merger->merge([$first, $last], true, self::DEFAULT_ACTIONS);

        self::assertSame(['Audit', 'Delete', 'Duplicate', $last], $result);
    }

    public function testExcludingDefaultActionsWithoutDeclarationGivesEmptyList(): void
    {
        $result = $this->merger->merge([], false, self::DEFAULT_ACTIONS);

        self::assertSame([], $result);
    }

    public function testExcludingDefaultActionsKeepsOnlyDeclaredActions(): void
    {
        $action = ['key' => 'Sync', 'api_route' => 'A/sync'];

        $result = $this->merger->merge([$action], false, self::DEFAULT_ACTIONS);

        self::assertSame([$action], $result);
    }

    public function testSkipsGenericApiActionWithoutKey(): void
    {
        $result = $this->merger->merge([['api_route' => 'A/sync']], true, self::DEFAULT_ACTIONS);

        self::assertSame(self::DEFAULT_ACTIONS, $result);
    }

    public function testTreatsDeclaredButNullApiRouteAsGenericAction(): void
    {
        // The frontend recognises a generic action with `'api_route' in action`, so a null value
        // still means "generic" -- and a generic action without a key is skipped.
        $result = $this->merger->merge([['api_route' => null, 'name' => 'Audit']], true, self::DEFAULT_ACTIONS);

        self::assertSame(self::DEFAULT_ACTIONS, $result);
    }

    public function testNullApiRouteWithKeyIsDeduplicatedByKey(): void
    {
        $last = ['api_route' => null, 'key' => 'Sync', 'label' => 'B'];

        $result = $this->merger->merge([['api_route' => null, 'key' => 'Sync', 'label' => 'A'], $last], true, self::DEFAULT_ACTIONS);

        self::assertSame(['Audit', 'Delete', 'Duplicate', $last], $result);
    }

    public function testSkipsActionWithNeitherNameNorApiRoute(): void
    {
        $result = $this->merger->merge([['icon' => 'mdi-sync']], true, self::DEFAULT_ACTIONS);

        self::assertSame(self::DEFAULT_ACTIONS, $result);
    }

    public function testDeclaredStringActionIsDeduplicatedAgainstDefaults(): void
    {
        $result = $this->merger->merge(['Delete', 'Archive'], true, self::DEFAULT_ACTIONS);

        self::assertSame(['Audit', 'Delete', 'Duplicate', 'Archive'], $result);
    }
}
