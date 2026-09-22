<?php

namespace MintHCM\Lib\DuplicateDetection;

use Doctrine\DBAL\Query\QueryBuilder;

interface DuplicateQueryInterface
{
    public function buildQueries(QueryBuilder $query_builder, array $record_data): DuplicateArrayQuery;
}
