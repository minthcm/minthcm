<?php

namespace MintHCM\Tests\Unit\Repositories;

use Doctrine\ORM\AbstractQuery;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Mapping\ClassMetadata;
use Doctrine\ORM\QueryBuilder;
use MintHCM\Api\Entities\Currencies;
use MintHCM\Api\Repositories\CurrenciesRepository;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

final class CurrenciesRepositoryTest extends TestCase
{
    protected function setUp(): void
    {
        $GLOBALS['sugar_config'] = [
            'default_currency_name'   => 'US Dollar',
            'default_currency_symbol' => '$',
            'currency_on_right'       => false,
        ];
    }

    protected function tearDown(): void
    {
        unset($GLOBALS['sugar_config']);
    }

    public function testDefaultCurrencyIsAlwaysFirst(): void
    {
        $result = $this->createRepository([])->getAvailable();

        self::assertSame('-99', $result[0]['id']);
    }

    public function testDefaultCurrencyHasConversionRateOfOne(): void
    {
        $result = $this->createRepository([])->getAvailable();

        self::assertSame(1, $result[0]['conversion_rate']);
    }

    public function testDefaultCurrencyIsAlwaysActive(): void
    {
        $result = $this->createRepository([])->getAvailable();

        self::assertSame('Active', $result[0]['status']);
    }

    public function testDefaultCurrencyDataComesFromSugarConfig(): void
    {
        $result = $this->createRepository([])->getAvailable();

        self::assertSame('US Dollar', $result[0]['name']);
        self::assertSame('$', $result[0]['symbol']);
        self::assertFalse($result[0]['currency_on_right']);
    }

    public function testDbCurrenciesAreAppendedAfterDefault(): void
    {
        $db_currencies = [
            ['id' => '1', 'name' => 'Euro', 'symbol' => '€', 'status' => 'Active', 'currency_on_right' => false, 'conversion_rate' => 1.1],
            ['id' => '2', 'name' => 'GBP',  'symbol' => '£', 'status' => 'Active', 'currency_on_right' => false, 'conversion_rate' => 0.9],
        ];

        $result = $this->createRepository($db_currencies)->getAvailable();

        self::assertCount(3, $result);
        self::assertSame('-99', $result[0]['id']);
        self::assertSame('1',   $result[1]['id']);
        self::assertSame('2',   $result[2]['id']);
    }

    public function testReturnsOnlyDefaultWhenDbIsEmpty(): void
    {
        $result = $this->createRepository([])->getAvailable();

        self::assertCount(1, $result);
        self::assertSame('-99', $result[0]['id']);
    }

    private function createRepository(array $db_result): CurrenciesRepository&MockObject
    {
        $query = $this->createMock(AbstractQuery::class);
        $query->method('getArrayResult')->willReturn($db_result);

        $qb = $this->createMock(QueryBuilder::class);
        $qb->method('select')->willReturnSelf();
        $qb->method('where')->willReturnSelf();
        $qb->method('andWhere')->willReturnSelf();
        $qb->method('setParameter')->willReturnSelf();
        $qb->method('orderBy')->willReturnSelf();
        $qb->method('getQuery')->willReturn($query);

        $repository = $this->getMockBuilder(CurrenciesRepository::class)
            ->setConstructorArgs([
                $this->createMock(EntityManagerInterface::class),
                new ClassMetadata(Currencies::class),
            ])
            ->onlyMethods(['createQueryBuilder'])
            ->getMock();

        $repository->method('createQueryBuilder')->willReturn($qb);

        return $repository;
    }
}
