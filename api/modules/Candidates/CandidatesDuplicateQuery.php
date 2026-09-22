<?php

namespace MintHCM\Modules\Candidates;

use Doctrine\DBAL\Query\QueryBuilder;
use MintHCM\Lib\DuplicateDetection\DuplicateArrayQuery;
use MintHCM\Lib\DuplicateDetection\DuplicateQueryInterface;

class CandidatesDuplicateQuery implements DuplicateQueryInterface
{
    const QUERIES = [
        'candidates' => [
            'alias' => 'c',
            'table' => 'candidates',
            'module' => 'Candidates',
            'extra_joins' => [],
            'extra_wheres' => ['c.deleted = 0', 'c.id != :id'],
        ],
        'employees' => [
            'alias' => 'u',
            'table' => 'users',
            'module' => 'Employees',
            'extra_joins' => [],
            'extra_wheres' => [
                'u.show_on_employees = 1',
                'u.deleted = 0',
                'NOT EXISTS (SELECT 1 FROM candidates_employees ce WHERE ce.employee_id = u.id AND ce.candidate_id = :id)'
            ],
        ],
    ];

    public function buildQueries(QueryBuilder $query_builder, array $record_data): DuplicateArrayQuery
    {
        if (empty($record_data['first_name']) || empty($record_data['last_name'])) {
            return new DuplicateArrayQuery();
        }

        $duplicate_array_query = new DuplicateArrayQuery();
        foreach (static::QUERIES as $query_name => $config) {
            $duplicate_array_query->addQuery($query_name, $this->buildSingleQuery(clone $query_builder, $record_data, $config));
        }
        return $duplicate_array_query;
    }

    protected function buildSingleQuery(QueryBuilder $query_builder, array $record_data, array $config): QueryBuilder | null
    {
        $this->buildSelect($query_builder, $config);

        $conditions = $this->buildConditions($query_builder, $config['alias'], $record_data);
        if (empty($conditions)) {
            return null;
        }
        $query_builder->where($query_builder->expr()->or(...$conditions));

        foreach ($config['extra_wheres'] as $where) {
            $query_builder->andWhere($where);
        }

        $query_builder->setParameters($this->buildParameters($record_data));
        return $query_builder;
    }

    protected function buildSelect(QueryBuilder $query_builder, array $config): void
    {
        $alias = $config['alias'];

        $query_builder->select(
                "{$alias}.id",
                "{$alias}.first_name",
                "{$alias}.last_name",
                "{$alias}.phone_mobile",
                "ea.email_address AS email1",
                "'{$config['module']}' AS module"
            )
            ->from($config['table'], $alias)
            ->leftJoin($alias, 'email_addr_bean_rel', 'er', "er.bean_id = {$alias}.id AND er.deleted = 0")
            ->leftJoin('er', 'email_addresses', 'ea', 'er.email_address_id = ea.id AND ea.deleted = 0');

        foreach ($config['extra_joins'] as [$join_table, $join_alias, $join_condition, $join_from, $join_type]) {
            match($join_type ?? 'inner') {
                'left'  => $query_builder->leftJoin($join_from, $join_table, $join_alias, $join_condition),
                default => $query_builder->join($join_from, $join_table, $join_alias, $join_condition),
            };
        }
    }

    protected function buildConditions(QueryBuilder $query_builder, string $alias, array $record_data): array
    {
        $conditions = [];

        if (!empty($record_data['email1'])) {
            $conditions[] = $query_builder->expr()->and(
                $query_builder->expr()->eq("{$alias}.first_name", ':first_name'),
                $query_builder->expr()->eq("{$alias}.last_name", ':last_name'),
                $query_builder->expr()->eq('ea.email_address', ':email1')
            );
        }

        if (!empty($record_data['phone_mobile'])) {
            $conditions[] = $query_builder->expr()->and(
                $query_builder->expr()->eq("{$alias}.first_name", ':first_name'),
                $query_builder->expr()->eq("{$alias}.last_name", ':last_name'),
                $query_builder->expr()->like("REPLACE({$alias}.phone_mobile, ' ', '')", ':phone_number_short')
            );
        }

        return $conditions;
    }

    protected function buildParameters(array $record_data): array
    {
        $available = [
            'first_name' => $record_data['first_name'] ?? null,
            'last_name' => $record_data['last_name'] ?? null,
            'email1' => $record_data['email1'] ?? null,
            'phone_number_short' => !empty($record_data['phone_mobile']) ? '%' . str_replace(' ', '', $record_data['phone_mobile']) : null,
            'id' => $record_data['id'] ?? '',
        ];

        return array_filter($available, fn($value) => $value !== null);
    }
}
