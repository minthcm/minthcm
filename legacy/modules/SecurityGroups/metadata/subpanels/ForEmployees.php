<?php
if (!defined('sugarEntry') || !sugarEntry) {
    die('Not A Valid Entry Point');
}

$subpanel_layout = array(
    'top_buttons' => array(),
    'where' => '',
    'list_fields' => array(
        'name' => array(
            'vname' => 'LBL_NAME',
            'widget_class' => 'SubPanelDetailViewLink',
            'width' => '9999%',
        ),
        'description' => array(
            'vname' => 'LBL_DESCRIPTION',
            'width' => '9999%',
            'sortable' => false,
        ),
    ),
);
