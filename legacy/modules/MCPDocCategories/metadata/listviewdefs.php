<?php

if (!defined('sugarEntry') || !sugarEntry) {
    die('Not A Valid Entry Point');
}

$module_name = 'MCPDocCategories';
$listViewDefs[$module_name] = [
    'NAME' => [
        'width' => '50%',
        'label' => 'LBL_NAME',
        'default' => true,
        'link' => true,
    ],
    'DESCRIPTION' => [ // FIXME: Nie dodawaj do list view opisu kategorii, bo może być długi
        'width' => '50%',
        'label' => 'LBL_DESCRIPTION',
        'default' => true,
    ],
];
