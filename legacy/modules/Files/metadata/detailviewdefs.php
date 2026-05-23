<?php

if (!defined('sugarEntry') || !sugarEntry) {
    die('Not A Valid Entry Point');
}

$module_name = 'Files';
$_object_name = 'files';
$viewdefs[$module_name]['DetailView'] = array(
    'templateMeta' => array(
        'maxColumns' => '2',
        'form' => array(            
        ),
        'widths' => array(
            array(
                'label' => '10',
                'field' => '30'
            ),
            array(
                'label' => '10',
                'field' => '30'
            ),
        ),
        'useTabs' => true,
        'tabDefs' => array(
            'DEFAULT' => array(
                'newTab' => true,
                'panelDefault' => 'expanded',
            ),  
            'LBL_PANEL_ASSIGNMENT' => array(
                'newTab' => true,
                'panelDefault' => 'expanded',
            ),           
        ),
    ),
    'panels' => array(
        'default' => array(
            array(
                array(
                    'name' => 'document_name',
                    'label' => 'LBL_DOC_NAME',
                ),
                array(
                    'name' => 'uploadfile',
                    'displayParams' => array('link' => 'uploadfile', 'id' => 'id'),
                ),
            ),
            array(
                'status',
                'parent_name',
            ),
            array(
                'active_date',
                'exp_date',
            ),
            array(
                array(
                    'name' => 'description',
                    'label' => 'LBL_DOC_DESCRIPTION',
                ),
            ),
        ),
        'LBL_PANEL_ASSIGNMENT' => array(
            array(
                'assigned_user_name',
            ),
            array(
                array(
                    'name' => 'date_entered',
                    'customCode' => '{$fields.date_entered.value} {$APP.LBL_BY} {$fields.created_by_name.value}',
                    'label' => 'LBL_DATE_ENTERED',
                ),
                array(
                    'name' => 'date_modified',
                    'customCode' => '{$fields.date_modified.value} {$APP.LBL_BY} {$fields.modified_by_name.value}',
                    'label' => 'LBL_DATE_MODIFIED',
                ),
            ),
        ),
    ),
);
