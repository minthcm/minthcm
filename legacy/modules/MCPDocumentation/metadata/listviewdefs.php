<?php

if (!defined('sugarEntry') || !sugarEntry) {
    die('Not A Valid Entry Point');
}

$module_name = 'MCPDocumentation';
$listViewDefs[$module_name] = [
    'NAME' => [
        'width' => '40%',
        'label' => 'LBL_NAME',
        'default' => true,
        'link' => true,
    ],
    'CATEGORY_NAME' => [
        'width' => '30%',
        'label' => 'LBL_CATEGORY',
        'default' => true,
        'id' => 'CATEGORY_ID',
        'link' => true,
    ],
    'DATE_MODIFIED' => [
        'type' => 'datetime',
        'label' => 'LBL_DATE_MODIFIED',
        'width' => '15%',
        'default' => true,
    ],
    'DATE_ENTERED' => [
        'type' => 'datetime',
        'label' => 'LBL_DATE_ENTERED',
        'width' => '15%',
        'default' => false,
    ],
];
