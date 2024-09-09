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
******************************************************************************* */


if (!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');

$mod_strings = array(
    'LBL_SAVE_BUTTON_LABEL' => 'Save',
    'LBL_CANCEL_BUTTON_LABEL' => 'Cancel',
    'LBL_REMOVE_BUTTON_LABEL' => 'Remove',
    'LBL_REPORT_NAME_LABEL' => 'Name',
    'LBL_SAVE_LAYOUT_BUTTON_LABEL' => 'Save Layout',
    'LBL_SAVEMASK' => '... saving ...',
    'LBL_ERROR' => 'Error',
    'LBL_ERROR_NAME' => 'Please, fill in report name!',
    'LBL_ASSIGNED_USER_LABEL' => 'User',
    'LBL_ASSIGNED_TEAM_LABEL' => 'Team',
    'LBL_KORGOBJECTS_LABEL' => 'Territory',
    'LBL_REPORT_OPTIONS' => 'Options',
    'LBL_DEFAULT_NAME' => 'new Report',
    'LBL_SEARCHING' => 'searching ...',
    'LBL_CHART_NODATA' => 'no DATA from K Reporter to display available',
    'LBL_LIST_LISTTYPE' => 'List Type',
    'LBL_LIST_CHART_LAYOUT' => 'Chart Layout',
    'LBL_LIST_DATEENTERED' => 'Date created',
    'LBL_LIST_DATEMODIFIED' => 'Date changed',
    'LBL_SEARCHING' => 'Searching...',
    'LBL_SELECT' => 'Select',
    'LBL_MANIPULATE' => 'Manipulate',
    'LBL_AUTH_CHECK' => 'Authorization Check',
    'LBL_AUTH_FULL' => 'On all Nodes',
    'LBL_AUTH_TOP' => 'Top Node only',
    'LBL_AUTH_NONE' => 'Disabled',
    'LBL_SHOW_DELETED' => 'Show deleted',
    'LBL_FOLDED_PANELS' => 'Collapsible Panels',
    'LBL_DYNOPTIONS' => 'Dynamic Options',
    'LBL_PANEL_OPEN' => 'open',
    'LBL_PANEL_COLLAPSED' => 'collapsed',
    'LBL_ADVANCEDOPTIONS' => 'Advanced Options',
    'LBL_UPDATE_ON_REQUEST' => 'on User Request',
    'LBL_MODULE_NAME' => 'K Reports',
    'LBL_REPORT_STATUS' => 'Report Status',
    'LBL_MODULE_TITLE' => 'K Reports',
    'LBL_SEARCH_FORM_TITLE' => 'Report Search',
    'LBL_LIST_FORM_TITLE' => 'Report List',
    'LBL_NEW_FORM_TITLE' => 'Create K Report',
    'LBL_LIST_CLOSE' => 'Close',
    'LBL_LIST_SUBJECT' => 'Title',
    'LBL_DESCRIPTION' => 'Description:',
    'LNK_NEW_REPORT' => 'Create new Report',
    'LNK_REPORT_LIST' => 'List Reports',
    'LBL_UNIONTREE' => 'union Modules',
    'LBL_UNIONLISTFIELDS' => 'Union List Fields',
    'LBL_UNIONFIELDDISPLAYPATH' => 'Union Path',
    'LBL_UNIONFIELDNAME' => 'Union Field name',
    'LBL_SELECT_MODULE' => 'Select a module',
    'LBL_LIST_MODULE' => 'Module',
    'LBL_LIST_ASSIGNED_USER_NAME' => 'Assigned User',
    'LBL_MODULES' => 'Modules',
    'LBL_LISTFIELDS' => 'manipulate',
    'LBL_PRESENTATION' => 'present',
    'LBL_DUPLICATE_NAME' => 'New Report Name',
    'LBL_DUPLICATE_PROMPT' => 'Enter the name for the new report',
    'LBL_DYNAMIC_OPTIONS' => 'Search/Filter Criteria',
    
    // various 
    'LBL_TREEVIEW' => 'Treeview',
    'LBL_TREEVIEWPROPERTIES_GRPUNTIL' => 'group until',

    
    'LBL_GOOGLECHARTS' => 'Google Charts',


    


    'LBL_LINK_LINKTYPE' => 'Link',






    'LBL_CHARTTYPE_DONUT' => 'Donut',



    'LBL_PREVIEW' => 'Preview for',

    
    // Grid headers
    'LBL_FIELDNAME' => 'Fieldname',
    'LBL_NAME' => 'Name',
    'LBL_OPERATOR' => 'Operator',
    'LBL_VALUE_FROM' => 'Equals/From',
    'LBL_VALUE_TO' => 'To',
    'LBL_JOIN_TYPE' => 'Required',
    'LBL_TYPE' => 'Type',
    'LBL_WIDTH' => 'Width',
    'LBL_SORTPRIORITY' => 'Sortseq.',
    'LBL_SORTSEQUENCE' => 'Sort',
    'LBL_EXPORTPDF' => 'show in PDF',
    'LBL_DISPLAY' => 'Display',
    'LBL_OVERRIDETYPE' => 'override Type',
    'LBL_LINK' => 'Link',
    'LBL_FIXEDVALUE' => 'Fixed Value',
    'LBL_ASSIGNTOVALUE' => 'Store',
    'LBL_FORMULAVALUE' => 'Formula',
    'LBL_FORMULASEQUENCE' => 'Seq.',
    'LBL_PATH' => 'Path',
    'LBL_FULLPATH' => 'technical Path',
    'LBL_SEQUENCE' => 'Seq.',
    'LBL_GROUPBY' => 'Group by',
    'LBL_SQLFUNCTION' => 'Function',
    'LBL_CUSTOMSQLFUNCTION' => 'CustomFunction',
    'LBL_VALUETYPE' => 'Value Type',
    'LBL_USEREDITABLE' => 'Allow Edit',
    'LBL_DASHLETEDITABLE' => 'Dashlet Option',
    'LBL_QUERYCONTEXT' => 'Context',
    'LBL_QUERYREFERENCE' => 'Reference',
    'LBL_UEOPTION_YES' => 'yes',
    'LBL_UEOPTION_NO' => 'no',
    'LBL_UEOPTION_YFO' => 'value only',
    'LBL_UEOPTION_YO1' => 'on/(off)',
    'LBL_UEOPTION_YO2' => '(on)/off',
    'LBL_DEOPTION_YES' => 'yes',
    'LBL_DEOPTION_NO' => 'no',
    'LBL_ONOFF_YO1' => 'yes',
    'LBL_ONOFF_YO2' => 'no',
    // Title and Headers for Multiselect Popup
    'LBL_MUTLISELECT_POPUP_TITLE' => 'Select Values',
    'LBL_MULTISELECT_TEXT_HEADER' => 'Value',
    'LBL_MUTLISELECT_CLOSE_BUTTON' => 'Update',
    'LBL_MUTLISELECT_CANCEL_BUTTON' => 'Cancel',
    // Title and Headers for Datetimepicker Popup
    // for the Snapshot Comaprison
    // Operator Names
    'LBL_OP_IGNORE' => 'ignore',
    'LBL_OP_PARENT_ASSIGN' => 'assign from Parent',
    'LBL_OP_FUNCTION' => 'function',
    'LBL_OP_REFERENCE' => 'reference',
    'LBL_BOOL_0' => 'false',
    'LBL_BOOL_1' => 'true',
    // for the List view Menu
    // List Limits

    // buttons
    'LBL_ADD_GROUP_NAME' => 'Create new Group',

    'LBL_SELECTION_LIMIT' => 'Limit List to:',
    'LBL_RECORDS' => 'Records',
    'LBL_PERCENTAGE' => '%',
    'LBL_EDIT_BUTTON_LABEL' => 'Edit',
    'LBL_DELETE_BUTTON_LABEL' => 'Delete',
    'LBL_ADD_BUTTON_LABEL' => 'Add',
    'LBL_ADDEMTPY_BUTTON_LABEL' => 'Add fixed',
    'LBL_CURRENT_SNAPSHOT' => 'actual',
    'LBL_TOOLSMENU_BUTTON_LABEL' => 'Tools',
    'LBL_EXPORTMENU_BUTTON_LABEL' => 'Export',
    'LBL_DUPLICATE_REPORT_BUTTON_LABEL' => 'Duplicate',
    'LBL_LISTTYPE' => 'List Type',
    'LBL_CHART_TYPE' => 'Type',
    'LBL_CHART_LABEL' => 'Chart',

    // Dropdown Values

    // DropDownValues
    // Panel Titles
    //Confirm Dialog
    'LBL_DIALOG_CONFIRM' => 'Confirm',
    'LBL_DIALOG_DELETE_YN' => 'are you sure you want to delete this Report?',

    // for the views options
    'LBL_CANCEL_BUTTON' => 'Cancel',
    'LBL_FUNCTION' => 'Function',
    'LBL_FUNCTION_SUM' => 'Sum',
    'LBL_FUNCTION_COUNT' => 'Count',
    'LBL_FUNCTION_COUNT_DISTINCT' => 'Count Distinct',
    'LBL_FUNCTION_AVG' => 'Average',
    'LBL_FUNCTION_MIN' => 'Minimum',
    'LBL_FUNCTION_MAX' => 'Maximum',
    'LBL_FUNCTION_GROUP_CONCAT' => 'Group Concat',
    //2013-03-01 Sort function for Group Concat
    'LBL_FUNCTION_GROUP_CONASC' => 'Group Concat (asc)',
    'LBL_FUNCTION_GROUP_CONDSC' => 'Group Concat (desc)',
    // Value Types
    'LBL_VALUETYPE_TOFSUM' => 'display Sum',
    'LBL_VALUETYPE_POFSUM' => '% of Sum',
    'LBL_VALUETYPE_POFCOUNT' => '% of Count',
    'LBL_VALUETYPE_POFAVG' => '% of Average',
    'LBL_VALUETYPE_DOFSUM' => 'Δ to Sum',
    'LBL_VALUETYPE_DOFCOUNT' => 'Δ to Count',
    'LBL_VALUETYPE_DOFAVG' => 'Δ to Average',
    'LBL_VALUETYPE_C' => 'Cumulated',
    // panel title
    'LBL_STANDARDGRIDPROPERTIES_COUNT' => 'process Count',
    'LBL_STANDARDGRIDPROPERTIES_SYNCHRONOUSCOUNT' => 'syncronous',
    'LBL_STANDARDGRIDPROPERTIES_ASYNCHRONOUSCOUNT' => 'asyncronous',
    'LBL_STANDARDGRIDENTRIES_COUNT' => 'records per page',
    // General Labels
    'LBL_YES' => 'yes',
    'LBL_NO' => 'no',
    'LBL_HID' => 'hidden',
    'LBL_SORT_ASC' => 'asc.',
    'LBL_SORT_DESC' => 'desc.',
    'LBL_SORT_SORTABLE' => 'sortable',
    'LBL_AND' => 'AND',
    'LBL_OR' => 'OR',
    'LBL_JT_OPTIONAL' => 'optional',
    'LBL_JT_REQUIRED' => 'required',
    //Trendlines
    // for report publishing

    // for Export to Planning
    // for the pdf
    // for the targetlist Export
    'LBL_TARGETLISTEXPORTPOPUP_TITLE' => 'Export to Targetlist',
    'LBL_TARGETLISTPOUPFIELDSET_LABEL' => 'Export Options',
    'LBL_TGLLISTPOPUP_CLOSE' => 'Close',
    'LBL_TGLLISTPOPUP_EXEC' => 'Run',
    'LBL_TARGETLISTPOUPNEWFIELDSET_LABEL' => 'New Targetlist',
    'LBL_TARGETLISTPOUP_NEWNAME' => 'Targetlist Name',
    // for the Drilldown
    // for the Viualization
    'LBL_VISUALIZATION' => 'Visualize',
    'LBL_VISUALIZATIONTOOLBAR_LAYOUT' => 'Layout',
    'LBL_VISUALIZATION_HEIGHT' => 'height (px)',
    'LBL_GOOGLECHARTS' => 'Google Charts',
    'LBL_CHARTFS_TYPE' => 'Chart Type',
    'LBL_CHARTFS_DATA' => 'Chart Data',
    'LBL_DIMENSIONS' => 'Dimensions',
    'LBL_DIMENSION_111' => 'one dimensional (series)',
    'LBL_CHARTTYPE_DIMENSION1' => 'Dimension 1',
    'LBL_CHARTTYPE_COLORS' => 'Colors',
    'LBL_CHARTTYPE_COLORPREVIEW' => 'Preview',
    'LBL_CHARTTYPE_DATASERIES' => 'Dataseries',
    'LBL_CHARTTYPES' => 'Type',
    'LBL_CHARTTYPE_AREA' => 'Area Chart',
    'LBL_CHARTTYPE_STEPPEDAREA' => 'Stepped Area Chart',
    'LBL_CHARTTYPE_BAR' => 'Bar Chart',
    'LBL_CHARTTYPE_COLUMN' => 'Column Chart',
    'LBL_CHARTTYPE_PIE' => 'Pie Chart',
    'LBL_CHARTTYPE_LINE' => 'Line Chart',
    'LBL_CHARTOPTIONS_FS' => 'Chartoptions',
    'LBL_CHARTOPTIONS_TITLE' => 'Title',
    'LBL_CHARTOPTIONS_CONTEXT' => 'Context',
    'LBL_CHARTOPTIONS_LEGEND' => 'display Legend',
    'LBL_CHARTOPTIONS_3D' => '3 dimensional',

    // for Fusion Charts ... needs to be moved


    'LBL_STANDARDPLUGIN' => 'Standard View',


    // for the Google Maps


    // for the Plugins
    'LBL_PRESENTATION_PLUGIN' => 'Plugin',
    'LBL_PRESENTATION_PARAMS' => 'Presentation Parameters',
    'LBL_INTEGRATION' => 'integrate',
    'LBL_INTEGRATION_PLUGINNAME' => 'Plugin',
    'LBL_CSV_EXPORT' => 'Export to CSV',
    'LBL_EXCEL_EXPORT' => 'Export to Excel',
    'LBL_TARGETLIST_EXPORT' => 'Export to Targetlist',
    'LBL_SCHEDULE_REPORT' => 'schedule Report',


    // Scheduler Options

    // PDF Export Option
    'LBL_PDF_EXPORT' => 'PDF Export',
    'LBL_PDF_FORMAT' => 'Format',
    'LBL_PDF_ORIENTATION' => 'Orientation',
    'LBL_PDFORIENT_P' => 'Portrait',
    'LBL_PDFORIENT_L' => 'Landscape',

    // Pivot Plugin ... to be moved later

    // the field renderer

    // override Alignment
    'LBL_OVERRIDEALIGNMENT' => 'override Alignment',
    'LBL_ALIGNMENT_LEFT' => 'left',
    'LBL_ALIGNMENT_RIGHT' => 'right',
    'LBL_ALIGNMENT_CENTER' => 'center',


    'LBL_GROUPING' => 'Grouping',
    'LBL_PICK_DATETIME' => 'choose Date/Time'

);


$mod_strings['LBL_TREEVIEW'] = 'Treeview';
$mod_strings['LBL_TREEVIEWPROPERTIES_GRPUNTIL'] = 'group until';


$mod_strings['LBL_GOOGLECHARTS'] = 'Google Charts';




$mod_strings['LBL_LINK_LINKTYPE'] = 'Link';






$mod_strings['LBL_CHARTTYPE_DONUT'] = 'Donut';



$mod_strings['LBL_PREVIEW'] = 'Preview for';




    //Bucket Manager
$mod_strings['LBL_ERROR'] = 'Error:';

    //DList Manager   

    //ksavedfilters

// Mint start additional labels
$mod_strings['LBL_NEW_FIELD_NAME'] = 'enter name';
$mod_strings['LBL_ROOTNODE'] = 'rootnode';
$mod_strings['LBL_EDITOR'] = 'Editor';
$mod_strings['LBL_FIELD'] = 'Field';
$mod_strings['LBL_GROUP'] = 'Group';
$mod_strings['LBL_LOGIC_FUNCTION'] = 'Logic function';
$mod_strings['LBL_OK'] = 'OK';
$mod_strings['LBL_DEFAULT_PDF_TEMPLATE'] = 'Default';
$mod_strings['LBL_SELECT_PDF_TEMPLATE'] = 'Select PDF Template';
$mod_strings['LBL_FILTER_NOTEXISTING'] = 'notexisting';
$mod_strings['LBL_STANDARDGRIDPROPERTIES_NONE'] = 'None';
$mod_strings['LBL_NEW_SCHEDULE_REPORT'] = 'New Schedule Report';
$mod_strings['LBL_SCHEDULE_REPORTS'] = 'View Schedule Reports';
$mod_strings['LBL_PDF_EXPORT_CHARTS'] = 'Export with chart/s';
$mod_strings['LBL_PDF_SEPARATE_CHARTS'] = 'Charts on separated page';
// Mint end

$mod_strings['LBL_MODULE_NAME'] = 'Advanced Reports';
$mod_strings['LBL_MODULE_TITLE'] = 'Advanced Reports';
$mod_strings['LBL_SEARCH_FORM_TITLE'] = 'Advanced Report Search';
$mod_strings['LBL_LIST_FORM_TITLE'] = 'Advanced Report List';
$mod_strings['LBL_NEW_FORM_TITLE'] = 'New Advanced Report';
$mod_strings['LNK_NEW_REPORT'] = 'Create Advanced Report';
$mod_strings['LNK_REPORT_LIST'] = 'View Advanced Reports';
$mod_strings['LBL_EXCEL_EXPORT'] = 'Export to XLSX';

$mod_strings['LBL_AUTH_CHECK_TITLE'] = 'Authorization';

$mod_strings['LBL_INTEGRATION_PARAMS'] = 'Integration Params';
$mod_strings['LBL_VISUALIZATION_PARAMS'] = 'Visualization Params';
$mod_strings['LBL_WHERECONDITION'] = 'Where Condition';
$mod_strings['LBL_WHEREGROUPS'] = 'Where Groups';