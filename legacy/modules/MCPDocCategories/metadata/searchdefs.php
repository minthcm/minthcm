<?php

$module_name = 'MCPDocCategories';
$searchdefs[$module_name] = [
    'templateMeta' => [
        'maxColumns' => '3',
        'maxColumnsBasic' => '4',
        'widths' => ['label' => '10', 'field' => '30'],
    ],
    'layout' => [
        'basic_search' => [
            'name',
        ],
        'advanced_search' => [
            'name',
            'date_entered',
            'date_modified',
            [
                'name' => 'created_by',
                'label' => 'LBL_CREATED_BY',
                'type' => 'enum',
                'function' => ['name' => 'get_user_array', 'params' => [false]],
            ],
            [
                'name' => 'modified_user_id',
                'label' => 'LBL_MODIFIED_BY',
                'type' => 'enum',
                'function' => ['name' => 'get_user_array', 'params' => [false]],
            ],
        ],
    ],
];
