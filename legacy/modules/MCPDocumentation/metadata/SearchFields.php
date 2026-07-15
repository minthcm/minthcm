<?php

if (!defined('sugarEntry') || !sugarEntry) {
    die('Not A Valid Entry Point');
}

$module_name = 'MCPDocumentation';
$searchFields[$module_name] = [
    'name' => ['query_type' => 'default'],
    'category_name' => ['query_type' => 'default'],
    'range_date_entered' => ['query_type' => 'default', 'enable_range_search' => true, 'is_date_field' => true],
    'start_range_date_entered' => ['query_type' => 'default', 'enable_range_search' => true, 'is_date_field' => true],
    'end_range_date_entered' => ['query_type' => 'default', 'enable_range_search' => true, 'is_date_field' => true],
    'range_date_modified' => ['query_type' => 'default', 'enable_range_search' => true, 'is_date_field' => true],
    'start_range_date_modified' => ['query_type' => 'default', 'enable_range_search' => true, 'is_date_field' => true],
    'end_range_date_modified' => ['query_type' => 'default', 'enable_range_search' => true, 'is_date_field' => true],
];
