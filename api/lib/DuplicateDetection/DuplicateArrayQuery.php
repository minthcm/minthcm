<?php

namespace MintHCM\Lib\DuplicateDetection;

use Doctrine\DBAL\Query\QueryBuilder;

class DuplicateArrayQuery
{
    protected array $queries;
    public function __construct()
    {
        $this->queries = [];
    }

    public function addQuery(string $query_name, $query_builder): void
    {
        if (empty($query_builder) || !$query_builder instanceof QueryBuilder) {
            return;
        }
        $this->queries[$query_name] = $query_builder;
    }

    public function getQueries(): array
    {
        return $this->queries;
    }
}
