<?php

$layout_defs['MCPDocCategories']['subpanel_setup'] = [
    'mcp_documentation' => [
        'order' => 100,
        'module' => 'MCPDocumentation',
        'subpanel_name' => 'default',
        'sort_order' => 'asc',
        'sort_by' => 'name',
        'title_key' => 'LBL_MCPDOCUMENTATION_SUBPANEL_TITLE',
        'get_subpanel_data' => 'mcp_documentation',
        'top_buttons' => [
            ['widget_class' => 'SubPanelTopCreateButton'],
            ['widget_class' => 'SubPanelTopSelectButton', 'mode' => 'MultiSelect'],
        ],
    ],
    'securitygroups' => [
        'top_buttons' => [
            ['widget_class' => 'SubPanelTopSelectButton', 'popup_module' => 'SecurityGroups', 'mode' => 'MultiSelect'],
        ],
        'order' => 900,
        'sort_by' => 'name',
        'sort_order' => 'asc',
        'module' => 'SecurityGroups',
        'refresh_page' => 1,
        'subpanel_name' => 'default',
        'get_subpanel_data' => 'SecurityGroups',
        'add_subpanel_data' => 'securitygroup_id',
        'title_key' => 'LBL_SECURITYGROUPS_SUBPANEL_TITLE',
    ],
];
