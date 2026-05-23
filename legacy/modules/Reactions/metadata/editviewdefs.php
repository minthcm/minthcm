<?php

$module_name = 'Reactions';
$viewdefs[$module_name]['EditView'] = array(
    'templateMeta' => array(
        'maxColumns' => '2',
        'widths' => array(
            array('label' => '10', 'field' => '30'),
            array('label' => '10', 'field' => '30'),
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
                'assigned_user_name',
            ),
        ),
    ),
);
