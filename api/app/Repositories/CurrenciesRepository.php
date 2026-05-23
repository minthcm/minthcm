<?php

namespace MintHCM\Api\Repositories;

use MintHCM\Data\ORM\Doctrine\MintRepository\MintEntityRepository;

class CurrenciesRepository extends MintEntityRepository
{
    public function getAvailable()
    {
        global $sugar_config;
        $currencies = [];
        $currencies[] = array(
            'id' => '-99',
            'name' => $sugar_config['default_currency_name'],
            'symbol' => $sugar_config['default_currency_symbol'],
            'status' => 'Active',
            'currency_on_right' => $sugar_config['currency_on_right'],
            'conversion_rate' => 1,
        );
        return array_merge($currencies, $this->createQueryBuilder('c')
            ->select('c.id, c.name, c.symbol, c.status, c.currency_on_right, c.conversion_rate')
            ->where('c.status = :status')
            ->andWhere('c.deleted = :deleted')
            ->setParameter('status', 'Active')
            ->setParameter('deleted', 0)
            ->orderBy('c.name', 'ASC')
            ->getQuery()
            ->getArrayResult());
    }
}
