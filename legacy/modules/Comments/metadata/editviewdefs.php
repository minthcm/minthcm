<?php

$module_name = 'Comments';
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
                'parent_name',                
            ),
            array(
                'reply_to_name',
                'assigned_user_name',
            ),
            array(
                'description',
            ),
        ),
    ),
);
