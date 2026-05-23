<?php

if (!defined('sugarEntry') || !sugarEntry) {
    die('Not A Valid Entry Point');
}

$module_name = 'Files';
$viewdefs[$module_name]['EditView'] = array(
    'templateMeta' => array(
        'form' => array(
            'enctype' => 'multipart/form-data',
            'hidden' => array(),
        ),
        'maxColumns' => '2',
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
        'javascript' => '{sugar_getscript file="include/javascript/popup_parent_helper.js"}
	                    {sugar_getscript file="cache/include/javascript/sugar_grp_jsolait.js"}
	                    {sugar_getscript file="modules/Documents/documents.js"}',
    ),
    'panels' => array(
        'default' => array(
            array(
                'document_name',
                array(
                    'name' => 'uploadfile',
                    'displayParams' => array('onchangeSetFileNameTo' => 'document_name'),
                ),
            ),
            array(
                'assigned_user_name',
            ),
            array(
                'active_date',
                'exp_date',
            ),
            array(
                'status_id',
                'parent_name',
            ),
            array(
                array(
                    'name' => 'description'
                ),
            ),
            array(
                'photos',
            ),
        ),
    ),
);
