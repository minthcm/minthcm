<?php

$module_name = 'Comments';
$viewdefs[$module_name]['DetailView'] = array(
    'templateMeta' => array(
        'form' => array(
            'buttons' => array(
                'EDIT',
                'DUPLICATE',
                'DELETE',
                'FIND_DUPLICATES',
            )
        ),
        'maxColumns' => '2',
        'widths' => array(
            array('label' => '10', 'field' => '30'),
            array('label' => '10', 'field' => '30')
        ),
        'useTabs' => true,
        'tabDefs' => array(
            'DEFAULT' => array(
                'newTab' => true,
                'panelDefault' => 'expanded',
            ),            
        ),
    ),

    'panels' =>
        array(
            'default' =>
                array(
                    array(
                        'name',
                        'parent_name',                        
                    ),
                    array(
                        'reply_to_name',
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
                    array(
                        'description',
                    ),
                )
        )
);
