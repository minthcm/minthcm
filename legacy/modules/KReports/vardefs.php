<?php

/* * *******************************************************************************
 * This file is part of KReporter. KReporter is an enhancement developed
 * by aac services k.s.. All rights are (c) 2016 by aac services k.s.
 *
 * This Version of the KReporter is licensed software and may only be used in
 * alignment with the License Agreement received with this Software.
 * This Software is copyrighted and may not be further distributed without
 * witten consent of aac services k.s.
 *
 * You can contact us at info@kreporter.org
 * ****************************************************************************** */

if (!defined('sugarEntry') || !sugarEntry) {
    die('Not A Valid Entry Point');
}

$dictionary['KReport'] = array(
    'table' => 'kreports',
    'fields' => array(
        'report_module' => array(
            'name' => 'report_module',
            'type' => 'enum',
            'options' => 'moduleList',
            'len' => '45',
            'vname' => 'LBL_MODULE',
            'massupdate' => false,
            'comment' => 'The module or data source for which this KReport is configured and generated',
        ),
        'report_status' => array(
            'name' => 'report_status',
            'type' => 'enum',
            'options' => 'kreportstatus',
            'len' => '1',
            'vname' => 'LBL_REPORT_STATUS',
            'comment' => 'The current status of the KReport (e.g., draft, published, archived)',
        ),
        'union_modules' => array(
            'name' => 'union_modules',
            'type' => 'text',
            'comment' => 'Additional modules that can be joined or unioned with the primary report module for complex queries',
        ),
        'reportoptions' => array(
            'name' => 'reportoptions',
            'type' => 'text',
            'vname' => 'LBL_REPORT_OPTIONS',
            'comment' => 'Configuration options for the report, such as display settings, export formats, or runtime parameters',
        ),
        'listtype' => array(
            'name' => 'listtype',
            'type' => 'varchar',
            'len' => '10',
            'vname' => 'LBL_LISTTYPE',
            'massupdate' => false,
            'comment' => 'The type of list or view used for displaying the report results (e.g., standard, grouped, or custom)',
        ),
        'listtypeproperties' => array(
            'name' => 'listtypeproperties',
            'type' => 'text',
            'comment' => 'Properties and settings specific to the selected list type, controlling how data is presented',
        ),
        'selectionlimit' => array(
            'name' => 'selectionlimit',
            'type' => 'varchar',
            'len' => '25',
            'vname' => 'LBL_SELECTION_LIMIT',
            'massupdate' => false,
            'comment' => 'The maximum number of records to be retrieved or displayed in the report results',
        ),
        'presentation_params' => array(
            'name' => 'presentation_params',
            'type' => 'text',
            'vname' => 'LBL_PRESENTATION_PARAMS',
            'comment' => 'Parameters controlling the visual presentation of the report, such as layout, styling, or formatting options',
        ),
        'visualization_params' => array(
            'name' => 'visualization_params',
            'type' => 'text',
            'vname' => 'LBL_VISUALIZATION_PARAMS',
            'comment' => 'Settings for data visualization elements in the report, such as charts, graphs, or dashboard widgets',
        ),
        'integration_params' => array(
            'name' => 'integration_params',
            'type' => 'text',
            'vname' => 'LBL_INTEGRATION_PARAMS',
            'comment' => 'Parameters for integrating the report with external systems or APIs, including connection settings and data mapping',
        ),
        'wheregroups' => array(
            'name' => 'wheregroups',
            'type' => 'text',
            'vname' => 'LBL_WHEREGROUPS',
            'default' => '[]',
            'comment' => 'Groups of WHERE conditions used to filter the report data, allowing complex logical combinations',
        ),
        'whereconditions' => array(
            'name' => 'whereconditions',
            'type' => 'text',
            'vname' => 'LBL_WHERECONDITION',
            'default' => '[]',
            'comment' => 'Individual WHERE conditions applied to filter the report data based on field values and operators',
        ),
        'listfields' => array(
            'name' => 'listfields',
            'type' => 'text',
            'vname' => 'LBL_LISTFIELDS',
            'comment' => 'The fields selected for display in the report list view, defining the columns and data to be shown',
        ),
        'unionlistfields' => array(
            'name' => 'unionlistfields',
            'type' => 'text',
            'vname' => 'LBL_UNIONLISTFIELDS',
            'comment' => 'Fields from unioned modules that are included in the report list view',
        ),
        'advancedoptions' => array(
            'name' => 'advancedoptions',
            'type' => 'text',
            'vname' => 'LBL_ADVANCEDOPTIONS',
            'comment' => 'Advanced configuration options for the report, such as custom SQL, scripting, or specialized processing',
        ),
        'prospectlists' => array(
            'name' => 'prospectlists',
            'type' => 'link',
            'relationship' => 'prospectlists',
            'source' => 'non-db',
            'module' => 'prospectlists',
            'bean_name' => 'ProspectList',
            'vname' => 'LBL_PROSPECTLISTS',
        ),
    ),
    'indices' => array(
        array('name' => 'idx_reminder_name', 'type' => 'index', 'fields' => array('name')),
    ),
    'optimistic_locking' => true,
);

// Mint start #60447
VardefManager::createVardef('KReports', 'KReport', array('default', 'assignable', 'security_groups'));
// Mint end #60447

