<?php

/**
 * MintHCM is a Human Capital Management software based on SuiteCRM developed by MintHCM,
 * Copyright (C) 2018-2025 MintHCM
 */

$dashletData['MCPDocumentationDashlet']['searchFields'] = [
    'name' => [
        'default' => '',
    ],
    'category_name' => [
        'default' => '',
    ],
    'date_modified' => [
        'default' => '',
    ],
    'date_entered' => [
        'default' => '',
    ],
    'created_by_name' => [
        'default' => '',
    ],
];

$dashletData['MCPDocumentationDashlet']['columns'] = [
    'name' => [
        'width' => '40%',
        'label' => 'LBL_NAME',
        'link' => true,
        'default' => true,
        'name' => 'name',
    ],
    'category_name' => [
        'width' => '25%',
        'label' => 'LBL_CATEGORY',
        'default' => true,
        'name' => 'category_name',
    ],
    'date_modified' => [
        'width' => '15%',
        'label' => 'LBL_DATE_MODIFIED',
        'default' => true,
        'name' => 'date_modified',
    ],
    'date_entered' => [
        'width' => '15%',
        'label' => 'LBL_DATE_ENTERED',
        'default' => false,
        'name' => 'date_entered',
    ],
    'created_by_name' => [
        'type' => 'relate',
        'link' => true,
        'label' => 'LBL_CREATED',
        'id' => 'CREATED_BY',
        'width' => '10%',
        'default' => false,
        'name' => 'created_by_name',
    ],
];
