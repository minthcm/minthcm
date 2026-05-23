<?php
$module_name = 'SecurityGroups';
$viewdefs[$module_name]['DetailView'] = array(
    'templateMeta' => array('form' => array('buttons' => array('EDIT', 'DUPLICATE',
                'DELETE',
            )),
        'maxColumns' => '2',
        'widths' => array(
            array('label' => '10', 'field' => '30'),
            array('label' => '10', 'field' => '30')
        ),
        'useTabs' => true,
        'tabDefs' =>
        array(
            'DEFAULT' =>
            array(
                'newTab' => true,
                'panelDefault' => 'expanded',
            ),
            'LBL_PANEL_ASSIGNMENT' =>
            array(
                'newTab' => true,
                'panelDefault' => 'expanded',
            ),
        ),
    ),
    'panels' => array(
        'default' => array(
            array(
                'name',
                'group_type',
            ),
            array(
                array(
                    'name' => 'parent_name',
                    'label' => 'LBL_MEMBER_OF'
                ),
            ),
            array(
                array(
                    'name' => 'current_manager_name',
                    'label' => 'LBL_CURRENT_MANAGER_NAME',
                ),
                array(
                    'name' => 'position_leader_name',
                    'label' => 'LBL_POSITION_LEADER_NAME'
                ),
            ),
            array(
                'noninheritable',
            ),
            array(
                'description',
            ),
        ),
        'LBL_PANEL_ASSIGNMENT' =>
        array(
            array(
                'assigned_user_name',
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
