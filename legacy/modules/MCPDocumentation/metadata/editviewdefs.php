<?php

$module_name = 'MCPDocumentation';
$viewdefs[$module_name]['EditView'] = [
    'templateMeta' => [
        'maxColumns' => '2',
        'widths' => [
            ['label' => '10', 'field' => '30'],
            ['label' => '10', 'field' => '30'],
        ],
        'useTabs' => false,
        'tabDefs' => [
            'DEFAULT' => [
                'newTab' => false,
                'panelDefault' => 'expanded',
            ],
        ],
        'syncDetailEditViews' => false,
    ],
    'panels' => [
        'default' => [
            ['name', 'category_name'],
            ['content'],
        ],
    ],
];
