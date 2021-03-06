<?php

$module_name = 'Reactions';
$viewdefs[$module_name]['DetailView'] = array(
    'templateMeta' => array(
        'form' => array(
            'buttons' => array(
                'EDIT',
                'DUPLICATE',
                'DELETE',
                'FIND_DUPLICATES',
            ),
        ),
        'maxColumns' => '2',
        'widths' => array(
            array('label' => '10', 'field' => '30'),
            array('label' => '10', 'field' => '30'),
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
                'name',
                'reaction_type',
            ),
            array(
                'parent_name',
                '',
            ),
        ),
        'LBL_PANEL_ASSIGNMENT' => array(
            array(
                'assigned_user_name',
                '',
            ),
            array(
                array(
                    'name' => 'date_entered',
                    'customCode' => '{$fields.date_entered.value} {$APP.LBL_BY} {$fields.created_by_name.value}',
                ),
                array(
                    'name' => 'date_modified',
                    'customCode' => '{$fields.date_modified.value} {$APP.LBL_BY} {$fields.modified_by_name.value}',
                ),
            ),
        ),
    ),
);
