<?php

namespace MintHCM\Lib\DuplicateDetection\Service;

use Doctrine\ORM\EntityManagerInterface;
use MintHCM\Lib\DuplicateDetection\DuplicateDetectionInterface;
use MintHCM\Lib\DuplicateDetection\Factory\DuplicateQueryFactory;

class DuplicateDetectionService implements DuplicateDetectionInterface
{
    protected $entity_manager;
    protected array $resolved_query_classes = [];

    public function __construct(EntityManagerInterface $entity_manager)
    {
        $this->entity_manager = $entity_manager;
    }

    public function getDuplicates(string $module, array $record_data): array
    {
        $query_class = $this->resolveQueryClass($module);
        if ($query_class === null) {
            return [];
        }
        $duplicate_query_instance = DuplicateQueryFactory::get($module, $query_class);
        $query_builder = $this->entity_manager->getConnection()->createQueryBuilder();
        $queries = $duplicate_query_instance->buildQueries($query_builder, $record_data)->getQueries();
        $duplicates = [];
        foreach ($queries as $query) {
            $duplicates = array_merge($duplicates, $query->executeQuery()->fetchAllAssociative());
        }
        return $duplicates;
    }

    public function shouldProcessModule($module): bool
    {
        return $this->resolveQueryClass($module) !== null;
    }

    protected function resolveQueryClass(string $module): ?string
    {
        if (array_key_exists($module, $this->resolved_query_classes)) {
            return $this->resolved_query_classes[$module];
        }

        $custom_class = "MintHCM\\Custom\\Modules\\{$module}\\Custom{$module}DuplicateQuery";
        $default_class = "MintHCM\\Modules\\{$module}\\{$module}DuplicateQuery";

        $resolved_class = match (true) {
            class_exists($custom_class) => $custom_class,
            class_exists($default_class) => $default_class,
            default => null,
        };

        return $this->resolved_query_classes[$module] = $resolved_class;
    }
}
