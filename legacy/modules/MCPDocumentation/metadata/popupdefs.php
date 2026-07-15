<?php

if (!defined('sugarEntry') || !sugarEntry) {
    die('Not A Valid Entry Point');
}

$module_name = 'MCPDocumentation';
$object_name = 'MCPDocumentation';
$_module_name = 'mcp_documentation';
$popupMeta = [
    'moduleMain' => $module_name,
    'varName' => $object_name,
    'orderBy' => $_module_name . '.name',
    'whereClauses' => [
        'name' => $_module_name . '.name',
        'category_name' => 'mcp_doc_categories.name',
    ],
    'searchInputs' => ['name', 'category_name'],
    'whereStatement' => '',
    'searchdefs' => [
        'name' => [
            'name' => 'name',
            'width' => '10%',
        ],
        'category_name' => [
            'name' => 'category_name',
            'width' => '10%',
        ],
    ],
    'listviewdefs' => [
        'NAME' => [
            'width' => '40%',
            'label' => 'LBL_NAME',
            'default' => true,
            'link' => true,
            'name' => 'name',
        ],
        'CATEGORY_NAME' => [
            'width' => '40%',
            'label' => 'LBL_CATEGORY',
            'default' => true,
            'name' => 'category_name',
        ],
    ],
];
