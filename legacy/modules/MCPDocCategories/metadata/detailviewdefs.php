<?php

$module_name = 'MCPDocCategories';
$viewdefs[$module_name]['DetailView'] = [
    'templateMeta' => [
        'form' => [
            'buttons' => ['EDIT', 'DUPLICATE', 'DELETE'],
        ],
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
    ],
    'panels' => [
        'default' => [
            ['name'],
            ['description'],
        ],
    ],
];
