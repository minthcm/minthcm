<?php

if (!defined('sugarEntry') || !sugarEntry) {
    die('Not A Valid Entry Point');
}

$module_name = 'Files';
$searchdefs[$module_name] = array(
    'templateMeta' => array(
        'maxColumns' => '3',
        'maxColumnsBasic' => '4',
        'widths' => array(
            'label' => '10',
            'field' => '30'
        ),
    ),
    'layout' => array(
        'basic_search' => array(
            'document_name',
        ),
        'advanced_search' => array(
            'document_name',
            'parent_name',
            array(
                'name' => 'assigned_user_id',
                'label' => 'LBL_ASSIGNED_TO',
                'type' => 'enum',
                'function' => array(
                    'name' => 'get_user_array',
                    'params' => array(
                        false
                    ),
                ),
            ),
            'date_entered' => array(
                'type' => 'datetime',
                'label' => 'LBL_DATE_ENTERED',
                'width' => '10%',
                'default' => true,
                'name' => 'date_entered',
            ),
            'date_modified' => array(
                'type' => 'datetime',
                'label' => 'LBL_DATE_MODIFIED',
                'width' => '10%',
                'default' => true,
                'name' => 'date_modified',
            ),
            'modified_user_id' => array(
                'type' => 'enum',
                'label' => 'LBL_MODIFIED',
                'width' => '10%',
                'function' => array(
                    'name' => 'get_user_array',
                    'params' => array(
                        false
                    ),
                ),
                'default' => true,
                'name' => 'modified_user_id',
            ),
            'created_by' => array(
                'type' => 'enum',
                'label' => 'LBL_CREATED',
                'width' => '10%',
                'function' => array(
                    'name' => 'get_user_array',
                    'params' => array(
                        false
                    ),
                ),
                'default' => true,
                'name' => 'created_by',
            ),
        ),
    ),
);
