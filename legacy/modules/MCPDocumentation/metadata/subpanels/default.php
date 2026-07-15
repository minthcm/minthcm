<?php

if (!defined('sugarEntry') || !sugarEntry) {
    die('Not A Valid Entry Point');
}

$subpanel_layout = [
    'top_buttons' => [
        ['widget_class' => 'SubPanelTopCreateButton'],
        ['widget_class' => 'SubPanelTopSelectButton', 'mode' => 'MultiSelect'],
    ],
    'where' => '',
    'list_fields' => [
        'name' => [
            'name' => 'name',
            'vname' => 'LBL_NAME',
            'widget_class' => 'SubPanelDetailViewLink',
            'width' => '50%',
            'default' => true,
        ],
        'date_modified' => [
            'type' => 'datetime',
            'vname' => 'LBL_DATE_MODIFIED',
            'width' => '20%',
            'default' => true,
        ],
        'edit_button' => [
            'vname' => 'LBL_EDIT_BUTTON',
            'widget_class' => 'SubPanelEditButton',
            'module' => 'MCPDocumentation',
            'width' => '4%',
            'default' => true,
        ],
        'remove_button' => [
            'vname' => 'LBL_REMOVE',
            'widget_class' => 'SubPanelRemoveButton',
            'module' => 'MCPDocumentation',
            'width' => '5%',
            'default' => true,
        ],
    ],
];
