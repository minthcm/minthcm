<?php
$module_name = 'AOR_Scheduled_Reports';
$_object_name = 'aor_scheduled_reports';
$viewdefs [$module_name] =
    array(
        'EditView' =>
            array(
                'templateMeta' =>
                    array(
                        'form' =>
                            array(
                                'buttons' =>
                                    array(
                                        0 => 'SAVE',
                                        1 => 'CANCEL',
                                    ),
                            ),
                        'maxColumns' => '2',
                        'widths' =>
                            array(
                                0 =>
                                    array(
                                        'label' => '10',
                                        'field' => '30',
                                    ),
                                1 =>
                                    array(
                                        'label' => '10',
                                        'field' => '30',
                                    ),
                            ),
                        
                        'useTabs' => false,
                        'tabDefs' =>
                            array(
                                'LBL_SCHEDULED_REPORTS_INFORMATION' =>
                                    array(
                                        'newTab' => false,
                                        'panelDefault' => 'expanded',
                                    ),
                            ),
                        'syncDetailEditViews' => true,
                    ),
                'panels' =>
                    array(
                        'lbl_scheduled_reports_information' =>
                            array(
                                0 =>
                                    array(
                                        0 => 'name',
                                        1 => 'status',
                                    ),
                                1 =>
                                    array(
                                        0 =>
                                            array(
                                                'name' => 'aor_report_name',
                                            ),
                                    ),
                                2 =>
                                    array(
                                        0 =>
                                            array(
                                                'name' => 'schedule',
                                                'label' => 'LBL_SCHEDULE',
                                            ),
                                        1 => array(
                                            'name' => 'last_run',
                                            'displayParams' => array('readOnly' => true),
                                        ),
                                    ),
                                3 =>
                                    array(
                                        0 => 'email_recipients',
                                    ),
                                4 =>
                                    array(
                                        0 => 'description',
                                    ),
                            ),
                    ),
            ),
    );
