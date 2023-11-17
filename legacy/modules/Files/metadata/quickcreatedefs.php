<?php

if (!defined('sugarEntry') || !sugarEntry) {
    die('Not A Valid Entry Point');
}

$module_name = 'Files';

$viewdefs[$module_name]['QuickCreate'] = array(
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
                'assigned_user_name',
            ),
            array(
                array(
                    'name' => 'uploadfile',
                    'customCode' => '{if $fields.id.value!=""}
            				{assign var="type" value="hidden"}
            		 		{else}
            		 		{assign var="type" value="file"}
            		  		{/if}
            		  		<input name="uploadfile" type = {$type} size="30" maxlength="" onchange="setvalue(this);" value="{$fields.filename.value}">{$fields.filename.value}',
                    'displayParams' => array('required' => true),
                ),
            ),
            array(
                'active_date',
            ),
            array(
                array('name' => 'description', 'displayParams' => array('rows' => 10, 'cols' => 120)),
            ),
        ),
    ),
);
