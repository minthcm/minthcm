<?php

if (!defined('sugarEntry') || !sugarEntry) {
    die('Not A Valid Entry Point');
}

$module_name = 'MCPDocCategories';
$object_name = 'MCPDocCategories';
$_module_name = 'mcp_doc_categories';
$popupMeta = [
    'moduleMain' => $module_name,
    'varName' => $object_name,
    'orderBy' => $_module_name . '.name',
    'whereClauses' => [
        'name' => $_module_name . '.name',
    ],
    'searchInputs' => ['name'],
    'whereStatement' => '',
    'searchdefs' => [
        'name' => [
            'name' => 'name',
            'width' => '10%',
        ],
    ],
    'listviewdefs' => [
        'NAME' => [
            'width' => '50%',
            'label' => 'LBL_NAME',
            'default' => true,
            'link' => true,
            'name' => 'name',
        ],
        'DESCRIPTION' => [ // FIXME: Nie dodawaj do list view opisu kategorii, bo może być długi
            'width' => '50%',
            'label' => 'LBL_DESCRIPTION',
            'default' => true,
            'name' => 'description',
        ],
    ],
];
