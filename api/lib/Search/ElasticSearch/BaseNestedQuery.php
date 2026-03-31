<?php

namespace MintHCM\Lib\Search\ElasticSearch;

class BaseNestedQuery
{
    protected $queries = [];

    final public function getProcessedQuery(string $query, array $params = [])
    {
        if (!method_exists($this, $query)) {
            return null;
        }
        return $this->$query($params);
    }

    final public function getNestedSecurityGroupQuery(array $params = []): array
    {
        return [
            "nested" => [
                "path" => 'security_groups',
                "query" => [
                    "simple_query_string" => [
                        "query" => $params['search'] ?? '',
                        "fields" => ["security_groups.name"],
                        'analyzer' => 'standard',
                        'default_operator' => 'AND',
                        'minimum_should_match' => '66%',
                    ],
                ],
            ],
        ];
    }

    final public function getQueries(): array
    {
        return $this->queries;
    }
}
