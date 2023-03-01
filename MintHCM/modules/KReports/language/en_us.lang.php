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
    'LBL_LOADMASK' => '... loading data ...',
    'LBL_SAVEMASK' => '... saving ...',
    'LBL_ERROR' => 'Error',
    'LBL_ERROR_NAME' => 'Please, fill in report name!',
    'LBL_ASSIGNED_USER_LABEL' => 'User',
    'LBL_ASSIGNED_TEAM_LABEL' => 'Team',
    'LBL_KORGOBJECTS_LABEL' => 'Territory',
    'LBL_REPORT_OPTIONS' => 'Options',
    'LBL_DEFAULT_NAME' => 'new Report',
    'LBL_SEARCHING' => 'searching ...',
    'LBL_LONGTEXT_LABEL' => 'Description',
    'LBL_CHART_NODATA' => 'no DATA from K Reporter to display available',
    'LBL_REPORT_RELOAD' => 'Apply Filters',
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
    'LBL_RESULTS' => 'Results collapsed',
    'LBL_PANEL_OPEN' => 'open',
    'LBL_PANEL_COLLAPSED' => 'collapsed',
    'LBL_OPTIONS_MENUITEMS' => 'Toolbar Items',
    'LBL_ADVANCEDOPTIONS' => 'Advanced Options',
    'LBL_AOP_EXPORTTOPLANNING' => 'Export to Planning Nodes',
    'LBL_TOOLBARITEMS_FS' => 'Toolbar Items',
    'LBL_TOOLBARITEMS_SHOW' => 'Show',
    'LBL_SHOW_EXPORT' => 'Export',
    'LBL_SHOW_SNAPSHOTS' => 'Snapshots',
    'LBL_SHOW_TOOLS' => 'Tools',
    'LBL_DATA_UPDATE' => 'Data update',
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
    'LBL_SELECT_TAB' => 'Select a Tab',
    'LBL_ENTER_SEARCH_TERM' => 'Enter search term',
    'LBL_LIST_MODULE' => 'Module',
    'LBL_LIST_ASSIGNED_USER_NAME' => 'Assigned User',
    'LBL_DEFINITIONS' => 'Report Definition',
    'LBL_MODULES' => 'Modules',
    'LBL_LISTFIELDS' => 'manipulate',
    'LBL_PRESENTATION' => 'present',
    'LBL_CHARTDEFINITION' => 'Chart Details',
    'LBL_TARGETLIST_NAME' => 'Target List Name',
    'LBL_TARGETLIST_PROMPT' => 'Name of the new Targetlist',
    'LBL_DUPLICATE_NAME' => 'New Report Name',
    'LBL_DUPLICATE_PROMPT' => 'Enter the name for the new report',
    'LBL_DYNAMIC_OPTIONS' => 'Search/Filter Criteria',
    
    // various 
    'LBL_PIVOTVIEW' => 'Pivot',
    'LBL_STANDARDWSUMMARY' => 'Standard with Summary',
    'LBL_STANDARDWPREVIEW' => 'Standard with Preview',
    'LBL_TREEVIEW' => 'Treeview',
    'LBL_TREEVIEWPROPERTIES_GRPUNTIL' => 'group until',

    'LBL_GROUPEDVIEW' => 'Grouped View',
    'LBL_STANDARDVIEW' => 'Standard View',
    'LBL_EDITPLUGIN' => 'Edit View (beta)',
    
    'LBL_GOOGLECHARTS' => 'Google Charts',
    'LBL_FUSIONCHARTS' => 'Fusion Charts',
    'LBL_HIGHCHARTS' => 'High Charts',
    'LBL_GOOGLEMAPS' => 'Google Maps',
    'LBL_GOOGLEGEO' => 'Google Geo',
    'LBL_SUGARCHARTS' => 'Sugar Charts',
    'LBL_AMCHARTS' => 'Am Charts',
    'LBL_AMMAP' => 'Am Maps',
    'LBL_AMMAP_TYPE_AREA' => 'World Map color countries',
    'LBL_AMMAP_TYPE_BUBBLES' => 'World Map with Bubbles',

    'LBL_GEOOPTIONS_TITLE' => 'Title',
    'LBL_GEOOPTIONS_REGION' => 'Display Region',
    'LBL_GEOOPTIONS_RESOLUTION' => 'Resolution',
    'LBL_GEOOPTIONS_COUNTRIES' => 'Countries',
    'LBL_GEOOPTIONS_PROVINCES' => 'Provinces',
    'LBL_GEOOPTIONS_METROS' => 'Metros',

    'LBL_ANMAPOPTIONS_COLORSTEPS' => 'Number of color steps',
    
    'LBL_PUBLISH_CNTENTRIES' => 'number of entries displayed',

    'LBL_PDF_CHARTSONSEPARATEPAGE' => 'Charts on separate Page',

    'LBL_KPDRILLDOWN' => 'Presentation Drilldown',
    'LBL_LINK_LINKTYPE' => 'Link',
    'LBL_POPUP_LINKTYPE' => 'Popup',
    'LBL_CHART_LINKTYPE' => 'Chart',
    'LBL_DRILLDOWNMENUTITLE' => 'Drill Down',
    'LBL_ADDLINKEDREPORT_TITLE' => 'Link Report',
    'LBL_ADDLINK_ADD' => 'Add',
    'LBL_MAPPING_TITLE' => 'InputMapping',

    'LBL_EDITABLE' => 'editable',

    'LBL_PUBLISH_ASDASHLET' => 'publish as Dashlet',
    'LBL_PUBLISH_ASSUBPANEL' => 'publish as Subpanel',
    'LBL_PUBLISH_MOBILE' => 'publish Mobile',

    'LBL_CHARTOPTIONS_ROTATELABELS' => 'Rotate Labels',
    'LBL_STACK_NONE' => 'not stacked',
    'LBL_STACK_NORMAL' => 'stracked',
    'LBL_STACK_PERCENT' => 'percentage',
    'LBL_CHARTRENDER_SPLINE' => 'Spline',
    'LBL_CHARTTYPE_SPLINE' => 'Spline',
    'LBL_CHARTTYPE_AREASPLINE' => 'Area Spline',

    'LBL_CHARTTYPE_ARTRD' => 'Area w. Trendline',
    'LBL_CHARTTYPE_ARSPLINETRD' => 'Area Spline w. Trendline',

    'LBL_CHARTTYPE_ARSPLINEPOLR' => 'Area Spline Polar',
    'LBL_CHARTTYPE_ARSPLINESTCKPOL' => 'Area Spline stacked Polar',

    'LBL_CHARTTYPE_COLTRD' => 'Column w. Trendline',
    'LBL_CHARTTYPE_COLPLR' => 'Column Polar',
    'LBL_CHARTTYPE_LPOLR' => 'Line polar',
    'LBL_CHARTTYPE_DONUT' => 'Donut',
    'LBL_CHARTTYPE_PIE180' => 'Pie 180°',
    'LBL_CHARTTYPE_DONUT180' => 'Donut 180°',

    'LBL_CHARTTYPE_COLSTACKED' => 'Columns stacked',
    'LBL_CHARTTYPE_COLSTKPOLR' => 'Columns stacked polar',
    'LBL_CHARTTYPE_COLSTCKPER' => 'Columns stacked 100%',
    'LBL_CHARTTYPE_BRSTACKED' => 'Bars stacked',
    'LBL_CHARTTYPE_BRSTCKPER' => 'Bars stacked 100%',
    'LBL_CHARTTYPE_ARSTACKED' => 'Area stacked',
    'LBL_CHARTTYPE_ARSTCKPER' => 'Area stacked 100%',
    'LBL_CHARTTYPE_ARSPLINESTACKED' => 'Area Spline stacked',
    'LBL_CHARTTYPE_ARSPLINESTCKPER' => 'Area Spline stacked 100%',
    'LBL_CHARTTYPE_LPLR' => 'Line Polar',
    'LBL_CHARTTYPE_COLSTKPPL' => 'Columns stacked 100% polar',
    'LBL_CHARTTYPE_SPLPLR' => 'Spline polar',
    'LBL_CHARTTYPE_ARSTCKPOL' => 'Area stacked polar',
    'LBL_CHARTTYPE_ARSTCKPPL' => 'Area stacked 100% polar',
    'LBL_CHARTTYPE_ARSPLINESTCKPPL' => 'Area Spline stacked polar',
    'LBL_CHARTTYPE_ARSPLINESTCKPPL' => 'Area Spline stacked 100% polar',
    'LBL_CHARTTYPE_ARPOLR' => 'Area polar',
    'LBL_CHARTTYPE_FUNNEL' => 'Funnel',
    'LBL_CHARTTYPE_PYRAMID' => 'Pyramid',
    'LBL_TRENDLINENAME' => 'Trend',
    'LBL_KSNAPSHOTANALYZER' => 'Snapshot Analyzer',
    'LBL_SNAPSHOTANALYZER_TITLE' => 'ad hoc Snapshot Analyzer',
    'LBL_KPLANNER_EXPORT' => 'KPlanner Export',

    'LBL_GOOGLEMAPS_LABEL' => 'Google Maps',
    'LBL_GOOGLEMAPS_COLORSOPTIONS' => 'Colors',
    'LBL_GOOGLEMAPS_COLORS' => 'Colors',
    'LBL_GOOGLEMAPS_COLORCRITERIA' => 'Color by',
    'LBL_GOOGLEMAPS_INFO' => 'Popupinfo',
    'LBL_GOOGLEMAPS_LEGEND' => 'Display legend',
    'LBL_GOOGLEMAPS_COLORSOPTIONSRESET' => 'reset',
    'LBL_GOOGLEMAPS_SPIDERFY' => 'Spiderfy pins',
    'LBL_GOOGLEMAPS_ROUTEPLANNER_OPTIONS' => 'Route planner options',
    'LBL_GOOGLEMAPS_ROUTEPLANNER_RESET' => 'reset',
    'LBL_GOOGLEMAPS_ROUTEPLANNER_DISPLAY' => 'Display route planner',
    'LBL_GOOGLEMAPS_ROUTEPLANNER_WAYPOINTLABEL' => 'Waypoint name',
    'LBL_GOOGLEMAPS_ROUTEPLANNER_WAYPOINTADDRESS' => 'Waypoint address',
    'LBL_GOOGLEMAPS_ROUTEPLANNER_TITLE' => 'Route planner',
    'LBL_GOOGLEMAPS_ROUTEPLANNER_SUMMARY' => 'Route details',
    'LBL_GOOGLEMAPS_ROUTEPLANNER_START' => 'Start',
    'LBL_GOOGLEMAPS_ROUTEPLANNER_END' => 'End',
    'LBL_GOOGLEMAPS_ROUTEPLANNER_WAYPOINTS' => 'Waypoints',
    'LBL_GOOGLEMAPS_ROUTEPLANNER_BTN' => 'Plan route',
    'LBL_GOOGLEMAPS_ROUTEPLANNER_RESETBTN' => 'Delete route',
    'LBL_GOOGLEMAPS_ROUTEPLANNER_WAYPTGCBY' => 'Waypoint geocode by',
    'LBL_GOOGLEMAPS_ROUTEPLANNER_SEG' => 'Route segment',
    'LBL_GOOGLEMAPS_ROUTEPLANNER_SEGFROM' => 'from',
    'LBL_GOOGLEMAPS_ROUTEPLANNER_SEGTO' => 'to',
    'LBL_GOOGLEMAPS_RESIZEMAP_BTN' => 'Resize map',
    'LBL_GOOGLEMAPS_CIRCLEDESIGNER_OPTIONS' => 'Periphery search options',
    'LBL_GOOGLEMAPS_CIRCLEDESIGNER_RESET' => 'reset',
    'LBL_GOOGLEMAPS_CIRCLEDESIGNER_DISPLAY' => 'Display periphery search',
    'LBL_GOOGLEMAPS_CIRCLEDESIGNER_LABEL' => 'Periphery start point name',
    'LBL_GOOGLEMAPS_CIRCLEDESIGNER_TITLE' => 'Periphery search',
    'LBL_GOOGLEMAPS_CIRCLEDESIGNER_SUMMARY' => 'Periphery details',
    'LBL_GOOGLEMAPS_CIRCLEDESIGNER_DISTANCE' => 'Distance in Km',
    'LBL_GOOGLEMAPS_CIRCLEDESIGNER_WAYPOINTS' => 'Search from',
    'LBL_GOOGLEMAPS_CIRCLEDESIGNER_BTN' => 'Periphery search',
    'LBL_GOOGLEMAPS_CIRCLEDESIGNER_RESETBTN' => 'Delete circle(s)',
    'LBL_GOOGLEMAPS_CIRCLEDESIGNER_MODULE' => 'Module',
    'LBL_GOOGLEMAPS_CIRCLEDESIGNER_DISPLAYFIELDS' => 'Query config',

    'LBL_PREVIEW' => 'Preview for',

    'LBL_PDF_WHERE' => 'Print Selection criteria',
    
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
    'LBL_WIDGET' => 'Widget',
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
    'LBL_DISPLAYFUNCTION' => 'Disp. Funct.',
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
    'LBL_ONOFF_COLUMN' => 'y/n',
    // Title and Headers for Multiselect Popup
    'LBL_MUTLISELECT_POPUP_TITLE' => 'Select Values',
    'LBL_MULTISELECT_VALUE_HEADER' => 'ID',
    'LBL_MULTISELECT_TEXT_HEADER' => 'Value',
    'LBL_MUTLISELECT_CLOSE_BUTTON' => 'Update',
    'LBL_MUTLISELECT_CANCEL_BUTTON' => 'Cancel',
    // Title and Headers for Datetimepicker Popup
    'LBL_DATETIMEPICKER_POPUP_TITLE' => 'Select Date/Time',
    'LBL_DATETIMEPICKER_CLOSE_BUTTON' => 'Update',
    'LBL_DATETIMEPICKER_CANCEL_BUTTON' => 'Cancel',
    'LBL_DATETIMEPICKER_DATE' => 'Date',
    // for the Snapshot Comaprison
    'LBL_SNAPSHOTCOMPARISON_POPUP_TITLE' => 'Chart by Chart',
    'LBL_SNAPSHOTTRENDANALYSIS_POPUP_TITLE' => 'Trend Analysis',
    'LBL_SNAPSHOTCOMPARISON_SNAPHOT_HEADER' => 'Snapshot',
    'LBL_SNAPSHOTCOMPARISON_DESCRIPTION_HEADER' => 'Description',
    'LBL_SNAPSHOTCOMPARISON_SELECT_CHART' => 'Select Chart:',
    'LBL_SNAPSHOTCOMPARISON_SELECT_LEFT' => 'Select left source:',
    'LBL_SNAPSHOTCOMPARISON_SELECT_RIGHT' => 'Select right source:',
    'LBL_SNAPSHOTCOMPARISON_DATASERIES' => 'Data',
    'LBL_SNAPSHOTCOMPARISON_DATADIMENSION' => 'Dimension',
    'LBL_SNAPSHOTCOMPARISON_CHARTTYPE' => 'Charttype',
    'LBL_BASIC_TRENDLINE_BUTTON_LABEL' => 'Trend Analysis',
    'LBL_SNAPSHOTCOMPARISON_CHARTTYPE_MSLINE' => 'Line',
    'LBL_SNAPSHOTCOMPARISON_CHARTTYPE_STACKEDAREA2D' => 'Area',
    'LBL_SNAPSHOTCOMPARISON_CHARTTYPE_MSBAR2D' => 'Bars 2D',
    'LBL_SNAPSHOTCOMPARISON_CHARTTYPE_MSBAR3D' => 'Bars 3D',
    'LBL_SNAPSHOTCOMPARISON_CHARTTYPE_MSCOLUMN2D' => 'Column 2D',
    'LBL_SNAPSHOTCOMPARISON_CHARTTYPE_MSCOLUMN3D' => 'Column 3D',
    'LBL_SNAPSHOTCOMPARISON_LOADINGCHARTMSG' => 'loading Chart',
    // Operator Names
    'LBL_OP_IGNORE' => 'ignore',
    'LBL_OP_EQUALS' => '=',
    'LBL_OP_EQGROUPED' => 'is (bucket)',
    'LBL_OP_AUTOCOMPLETE' => 'autocomplete name',
    'LBL_OP_SOUNDSLIKE' => 'sounds like',
    'LBL_OP_NOTEQUAL' => '≠',
    'LBL_OP_STARTS' => 'starts with',
    'LBL_OP_CONTAINS' => 'contains',
    'LBL_OP_NOTSTARTS' => 'does not start with',
    'LBL_OP_NOTCONTAINS' => 'does not contain',
    'LBL_OP_BETWEEN' => 'is between',
    'LBL_OP_ISEMPTY' => 'is empty',
    'LBL_OP_ISEMPTYORNULL' => 'is empty or NULL',
    'LBL_OP_ISNULL' => 'is NULL',
    'LBL_OP_ISNOTEMPTY' => 'is not empty',
    'LBL_OP_FIRSTDAYOFMONTH' => 'first Day of current Month',
    'LBL_OP_FIRSTDAYNEXTMONTH' => 'first Day of next Month',
    'LBL_OP_NTHDAYOFMONTH' => 'nth day of current month',
    'LBL_OP_THISMONTH' => 'current month',
    'LBL_OP_NOTTHISMONTH' => 'not current month',
    'LBL_OP_THISWEEK' => 'current week',
    'LBL_OP_NEXTNMONTH' => 'within the next n full month',    
    'LBL_OP_NEXTNMONTHDAILY' => 'within the next n month Daily',    
    'LBL_OP_NEXT3MONTH' => 'within the next 3 month',
    'LBL_OP_NEXT3MONTHDAILY' => 'within the next 3 month Daily',
    'LBL_OP_NEXT6MONTH' => 'within the next 6 month',
    'LBL_OP_NEXT6MONTHDAILY' => 'within the next 6 month Daily',
    'LBL_OP_LAST3MONTHDAILY' => 'within the last 3 month Daily',
    'LBL_OP_LAST6MONTH' => 'within the last 6 month',
    'LBL_OP_LAST6MONTHDAILY' => 'within the last 6 month daily',
    'LBL_OP_LASTNFMONTH' => 'within the last n full month',
    'LBL_OP_LASTNMONTHDAILY' => 'within the last n month daily',
    'LBL_OP_LASTNYEAR' => 'within the last n full year',
    'LBL_OP_LASTNYEARDAILY' => 'within the last n year daily',
    'LBL_OP_NEXTNYEAR' => 'within the next n full year',
    'LBL_OP_NEXTNYEARDAILY' => 'within the next n year daily',
    'LBL_OP_TODAY' => 'today',
    'LBL_OP_PAST' => 'in the past',
    'LBL_OP_FUTURE' => 'in the future',
    'LBL_OP_LASTNDAYS' => 'in the last n days (count)',
    'LBL_OP_LASTNFDAYS' => 'in the last full n days (count)',
    'LBL_OP_LASTNDDAYS' => 'in the last n days (Date)',
    'LBL_OP_LASTNWEEKS' => 'in the last n weeks',
    'LBL_OP_LASTNFQUARTER' => 'in the last n full quarters',
    'LBL_OP_NOTLASTNWEEKS' => 'not in the last n weeks',
    'LBL_OP_LASTNFWEEKS' => 'in the last full n weeks',
    'LBL_OP_NEXTNDAYS' => 'in the next n days (count)',
    'LBL_OP_NEXTNDDAYS' => 'in the next n days (Date)',
    'LBL_OP_NEXTNWEEKS' => 'in the next n weeks',
    'LBL_OP_NEXTNFQUARTER' => 'in the next n full quarters',
    'LBL_OP_NOTNEXTNWEEKS' => 'not in the next n weeks',
    'LBL_OP_BETWNDAYS' => 'between n days (count)',
    'LBL_OP_BETWNDDAYS' => 'between n days (Date)',
    'LBL_OP_BEFORE' => 'before',
    'LBL_OP_AFTER' => 'after',
    'LBL_OP_LASTMONTH' => 'last month',
    'LBL_OP_LAST3MONTH' => 'within the last 3 month',
    'LBL_OP_THISYEAR' => 'this year',
    'LBL_OP_LASTYEAR' => 'last year',
    'LBL_OP_TYYTD' => 'YTD',
    'LBL_OP_LYYTD' => 'last Year YTD',
    'LBL_OP_GREATER' => '>',
    'LBL_OP_LESS' => '<',
    'LBL_OP_GREATEREQUAL' => '>=',
    'LBL_OP_LESSEQUAL' => '<=',
    'LBL_OP_ONEOF' => 'is one of',
    'LBL_OP_ONEOFNOT' => 'is not one of',
    'LBL_OP_ONEOFNOTORNULL' => 'is not one of or NULL',
    'LBL_OP_PARENT_ASSIGN' => 'assign from Parent',
    'LBL_OP_FUNCTION' => 'function',
    'LBL_OP_REFERENCE' => 'reference',
    'LBL_BOOL_0' => 'false',
    'LBL_BOOL_1' => 'true',
    // for the List view Menu
    'LBL_LISTVIEW_OPTIONS' => 'List Options',
    // List Limits
    'LBL_LI_TOP10' => 'top 10',
    'LBL_LI_TOP20' => 'top 20',
    'LBL_LI_TOP50' => 'top 50',
    'LBL_LI_TOP250' => 'top 250',
    'LBL_LI_BOTTOM50' => 'bottom 50',
    'LBL_LI_BOTTOM10' => 'bottom 10',
    'LBL_LI_NOLIMIT' => 'no limit',

    // buttons
    'LBL_CHANGE_GROUP_NAME' => 'Change Name of Group',
    'LBL_CHANGE_GROUP_NAME_PROMPT' => 'Name :',
    'LBL_ADD_GROUP_NAME' => 'Create new Group',

    'LBL_SELECTION_CLAUSE' => 'Select Clause: ',
    'LBL_SELECTION_LIMIT' => 'Limit List to:',
    'LBL_RECORDS' => 'Records',
    'LBL_PERCENTAGE' => '%',
    'LBL_EDIT_BUTTON_LABEL' => 'Edit',
    'LBL_DELETE_BUTTON_LABEL' => 'Delete',
    'LBL_ADD_BUTTON_LABEL' => 'Add',
    'LBL_ADDEMTPY_BUTTON_LABEL' => 'Add fixed',
    'LBL_DOWN_BUTTON_LABEL' => '',
    'LBL_UP_BUTTON_LABEL' => '',
    'LBL_SNAPSHOT_BUTTON_LABEL' => 'Take Snapshot',
    'LBL_CURRENT_SNAPSHOT' => 'actual',
    'LBL_SNAPSHOTMENU_BUTTON_LABEL' => 'Snapshots',
    'LBL_TOOLSMENU_BUTTON_LABEL' => 'Tools',
    'LBL_EXPORTMENU_BUTTON_LABEL' => 'Export',
    'LBL_COMPARE_SNAPSHOTS_BUTTON_LABEL' => 'Chart by Chart Comparison',
    'LBL_EXPORT_TO_EXCEL_BUTTON_LABEL' => 'EXCEL',
    'LBL_EXPORT_TO_KLM_BUTTON_LABEL' => 'Google Earth KML',
    'LBL_EXPORT_TO_PDF_BUTTON_LABEL' => 'PDF',
    'LBL_EXPORT_TO_PDFWCHART_BUTTON_LABEL' => 'PDF w. Chart',
    'LBL_EXPORT_TO_TARGETLIST_BUTTON_LABEL' => 'Targetlist',
    'LBL_SQL_BUTTON_LABEL' => 'SQL',
    'LBL_DUPLICATE_REPORT_BUTTON_LABEL' => 'Duplicate',
    'LBL_LISTTYPE' => 'List Type',
    'LBL_CHART_LAYOUTS' => 'Layout',
    'LBL_CHART_TYPE' => 'Type',
    'LBL_CHART_DIMENSION' => 'Dimension',
    'LBL_CHART_INDEX_LABEL' => 'Chart Index',
    'LBL_CHART_INDEX_EMPTY_TEXT' => 'Select a Chart ID',
    'LBL_CHART_LABEL' => 'Chart',
    'LBL_CHART_HEIGHT_LABEL' => 'Chart Height',

    // Dropdown Values
    'LBL_DD_1' => 'yes',
    'LBL_DD_0' => 'no',

    // DropDownValues
    'LBL_DD_SEQ_YES' => 'Yes',
    'LBL_DD_SEQ_NO' => 'No',
    'LBL_DD_SEQ_PRIMARY' => '1',
    'LBL_DD_SEQ_2' => '2',
    'LBL_DD_SEQ_3' => '3',
    'LBL_DD_SEQ_4' => '4',
    'LBL_DD_SEQ_5' => '5',
    // Panel Titles
    'LBL_WHERRE_CLAUSE_TITLE' => 'select',
    //Confirm Dialog
    'LBL_DIALOG_CONFIRM' => 'Confirm',
    'LBL_DIALOG_DELETE_YN' => 'are you sure you want to delete this Report?',

    // for the views options
    'LBL_RESET_BUTTON' => 'Reset',
    'LBL_TREESTRCUTUREGRID_TITLE' => 'Tree Hierarchy',
    'LBL_REPOSITORYGRID_TITLE' => 'available Fields',
    'LBL_CANCEL_BUTTON' => 'Cancel',
    'LBL_CLOSE_BUTTON' => 'Close',
    'LBL_LISTTYPEPROPERTIES' => 'Properties',
    'LBL_XAXIS_TITLE' => 'X-Axis Fields',
    'LBL_YAXIS_TITLE' => 'Y-Axis Fields',
    'LBL_VALUES_TITLE' => 'Value Fields',
    'LBL_SUMMARIZATION_TITLE' => 'Sumamrization Fields',
    'LBL_FUNCTION' => 'Function',
    'LBL_FUNCTION_SUM' => 'Sum',
    'LBL_FUNCTION_CUMSUM' => 'Sum cumulated',
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
    'LBL_STANDARDGRIDPANELTITLE' => 'Report Result',
    'LBL_STANDRDGRIDPANEL_FOOTERWCOUNT' => 'Displaying Records {0} - {1} of {2}',
    'LBL_STANDRDGRIDPANEL_FOOTERWOCOUNT' => 'Displaying Records {0} - {1}',
    'LBL_STANDARDGRIDPROPERTIES_COUNT' => 'process Count',
    'LBL_STANDARDGRIDPROPERTIES_SYNCHRONOUSCOUNT' => 'syncronous',
    'LBL_STANDARDGRIDPROPERTIES_ASYNCHRONOUSCOUNT' => 'asyncronous',
    'LBL_STANDARDGRIDPROPERTIES_NOCOUNT' => 'no count',
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
    'LBL_TRENDLINE_STARTVALUE' => 'StartValue',
    'LBL_TRENDLINE_ENDVALUE' => 'EndValue',
    'LBL_ADD_TRENDLINE' => 'add Trendline',
    'LBL_DELETE_TRENDLINE' => 'delete Trendline',
    'LBL_TRENDLINE_MIN' => 'Minimum',
    'LBL_TRENDLINE_MAX' => 'Maximum',
    'LBL_TRENDLINE_AVG' => 'Average',
    'LBL_TRENDLINE_AMM' => 'Area Min/Max',
    'LBL_TRENDLINE_LRG' => 'linear Regression',
    'LBL_TRENDLINE_CST' => 'Custom',
    'LBL_STANDARDTYPE' => 'Type',
    'LBL_TRENDLINE_STYLE' => 'Style',
    'LBL_TRENDLINE_VAL' => 'Value',
    'LBL_TRENDLINE_TXT' => 'Name',
    'LBL_TRENDLINE_NOT' => '-',
    'LBL_TRENDLINE_DISPLAY' => 'Info',
    // for report publishing
    'LBL_PUBLISH_OPTION' => 'publish Report',
    'LBL_PUBLISHPOPUP_TITLE' => 'Publish Report Options',
    'LBL_PUBLISHPOPUP_SUBPANEL' => 'Subpanel',
    'LBL_PUBLISHPOPUP_DASHLET' => 'Dashlet',
    'LBL_PUBLISHPOPUP_GRID' => 'publish Grid',
    'LBL_PUBLISHPOPUP_CHART' => 'publish Chart',
    'LBL_PUBLISHPOPUP_SUBPANELORDER' => 'Subpanel Order',
    'LBL_PUBLISHPOPUP_CLOSE' => 'Close',
    'LBL_PUBLISHPOPUP_MENU' => 'publish as Menu item',
    'LBL_PUBLISH_ASDASHLET' => 'Publish as Dashlet',
    'LBL_PUBLISH_MOBILE' => 'Publish to SpiceCRM Mobile',
    'LBL_PUBLISH_ASSUBPANEL' => 'Publish as Subpanel',
    'LBL_PUBLISH_DASHLET_NAME' => 'Dshlet Title',
    'LBL_PUBLISH_DASHLETREPORT' => 'Dshlet Report',

    // for Export to Planning
    'LBL_EXPORTTOPLANINGPOPUP_TITLE' => 'Export to Planning Nodes Settings',
    // for the pdf
    'LBL_PDF_DATE_LEADIN' => ' created on ',
    'LBL_PDF_DATE_LEADOUT' => '',
    'LBL_PDF_PAGE_LEADIN' => 'Page ',
    'LBL_PDF_PAGE_SEPARATOR' => ' of ',
    // for the targetlist Export
    'LBL_TARGETLISTEXPORTPOPUP_TITLE' => 'Export to Targetlist',
    'LBL_TARGETLISTPOUPFIELDSET_LABEL' => 'Export Options',
    'LBL_TGLLISTPOPUP_CLOSE' => 'Close',
    'LBL_TGLLISTPOPUP_EXEC' => 'Run',
    'LBL_TARGETLISTPOUP_OPTIONS' => 'Action',
    'LBL_TGLEXP_NEW' => 'create new',
    'LBL_TGLEXP_UPD' => 'update existing',
    'LBL_TARGETLISTPOUPNEWFIELDSET_LABEL' => 'New Targetlist',
    'LBL_TARGETLISTPERFSETTINGS_LABEL' => 'Performance Settings',
    'LBL_TARGETLISTPERFCHECKBOX_LABEL' => 'create direct',
    'LBL_TARGETLISTPOUP_NEWNAME' => 'Targetlist Name',
    'LBL_TARGETLISTPOUPCHANGEFIELDSET_LABEL' => 'Update Targetlist',
    'LBL_TARGETLISTPOUP_LISTS' => 'Target Lists',
    'LBL_TARGETLISTPOUP_ACTIONS' => 'Action',
    'LBL_TGLACT_REP' => 'update',
    'LBL_TGLACT_ADD' => 'add',
    'LBL_TGLACT_SUB' => 'subtract',
    'LBL_TARGETLISTPOUP_CAMPAIGNS' => 'add to campaign',
    'LBL_LAST_DAY_OF_MONTH' => 'last day of month',
    'LBL_EXPORT_TO_PLANNER_BUTTON_LABEL' => 'Export to KPlanner',
    'LBL_PLANNEREXPORTPOPUP_TITLE' => 'Export to KPlanner',
    'LBL_EXPORTPOPUP_CLOSE' => 'Cancel',
    'LBL_EXPORTPOPUP_EXEC' => 'Export to KPlanner',
    'LBL_PLANNEREXPORTPOPUP_SCOPESETS' => 'Scope Set',
    'LBL_PLANNINCHARACTERISTICSGRID_TITLE' => 'Planning Characteristics',
    'LBL_CHARFIELDVALUE' => 'Characteristic Value',
    'LBL_CHARFIELDNAME' => 'Characteristic Name',
    'LBL_CHARFIXEDVALUE' => 'Fixed Value',
    'LBL_PLANNEREXPORTPOPUP_NODENAME' => 'Nodename',
    // for the Drilldown
    'LBL_KPDRILLDOWN' => 'Drilldown',
    // for the Viualization
    'LBL_VISUALIZATION' => 'Visualize',
    'LBL_VISUALIZATIONPLUGIN' => 'type',
    'LBL_VISUALIZATIONTOOLBAR_LAYOUT' => 'Layout',
    'LBL_VISUALIZATION_HEIGHT' => 'height (px)',
    'LBL_GOOGLECHARTS' => 'Google Charts',
    'LBL_CHARTFS_TYPE' => 'Chart Type',
    'LBL_CHARTFS_DATA' => 'Chart Data',
    'LBL_CHARTFS_SERIES' => 'Dataseries',
    'LBL_CHARTFS_VALUES' => 'Values',
    'LBL_DIMENSIONS' => 'Dimensions',
    'LBL_DIMENSION_111' => 'one dimensional (series)',
    'LBL_DIMENSION_10N' => 'one dimensional (values)',
    'LBL_DIMENSION_220' => 'two dimensional (no values)',
    'LBL_DIMENSION_221' => 'two dimensional (series)',
    'LBL_DIMENSION_21N' => 'two dimensional (values)',
    'LBL_DIMENSION_331' => 'three dimensional (series)',
    'LBL_DIMENSION_32N' => 'three dimensional (values)',
    'LBL_CHARTTYPE_DIMENSION1' => 'Dimension 1',
    'LBL_CHARTTYPE_DIMENSION2' => 'Dimension 2',
    'LBL_CHARTTYPE_DIMENSION3' => 'Dimension 3',
    'LBL_CHARTTYPE_MULTIPLIER' => 'Multiplier',
    'LBL_CHARTTYPE_COLORS' => 'Colors',
    'LBL_CHARTTYPE_COLORPREVIEW' => 'Preview',
    'LBL_CHARTTYPE_DATASERIES' => 'Dataseries',
    'LBL_CHARTTYPES' => 'Type',
    'LBL_CHARTTYPE_AREA' => 'Area Chart',
    'LBL_CHARTTYPE_STEPPEDAREA' => 'Stepped Area Chart',
    'LBL_CHARTTYPE_BAR' => 'Bar Chart',
    'LBL_CHARTTYPE_BUBBLE' => 'Bubble Chart',
    'LBL_CHARTTYPE_SANKEY' => 'Sankey Chart',
    'LBL_CHARTTYPE_COLUMN' => 'Column Chart',
    'LBL_CHARTTYPE_GAUGE' => 'Gauges',
    'LBL_CHARTTYPE_PIE' => 'Pie Chart',
    'LBL_CHARTTYPE_LINE' => 'Line Chart',
    'LBL_CHARTTYPE_SCATTER' => 'Scatter Chart',
    'LBL_CHARTTYPE_COMBO' => 'Combo Chart',
    'LBL_CHARTTYPE_CANDLESTICK' => 'Candlestick',
    'LBL_CHARTFUNCTION' => 'Function',
    'LBL_MEANING' => 'Meaning',
    'LBL_COLOR' => 'Color',
    'LBL_AXIS' => 'Axis',
    'LBL_CHARTAXIS_P' => 'Primary',
    'LBL_CHARTAXIS_S' => 'Secondary',
    'LBL_RENDERER' => 'render as',
    'LBL_CHARTRENDER_DEFAULT' => 'default',
    'LBL_CHARTRENDER_BARS' => 'Bars',
    'LBL_CHARTRENDER_COLUMN' => 'Column',
    'LBL_CHARTRENDER_LINE' => 'Line',
    'LBL_CHARTRENDER_AREA' => 'Area',
    'LBL_CHARTRENDER_STEPPEDAREA' => 'Stepped Area',
    'LBL_CHARTOPTIONS_FS' => 'Chartoptions',
    'LBL_CHARTOPTIONS_TITLE' => 'Title',
    'LBL_CHARTOPTIONS_CONTEXT' => 'Context',
    'LBL_CHARTOPTIONS_VMINMAX' => 'V Axis Min/Max',
    'LBL_CHARTOPTIONS_HMINMAX' => 'H Axis Min/Max',
    'LBL_CHARTOPTIONS_GREEN' => 'Green from/to',
    'LBL_CHARTOPTIONS_YELLOW' => 'Yellow from/to',
    'LBL_CHARTOPTIONS_RED' => 'Red from/to',
    'LBL_CHARTOPTIONS_LEGEND' => 'display Legend',
    'LBL_CHARTOPTIONS_EMTPY' => 'show empty Values',
    'LBL_CHARTOPTIONS_NOVLABLES' => 'hide V-Axis Labels',
    'LBL_CHARTOPTIONS_NOHLABLES' => 'hide H-Axis Labels',
    'LBL_CHARTOPTIONS_LOGV' => 'logarithmic V Scale',
    'LBL_CHARTOPTIONS_LOGH' => 'logarithmic H Scale',
    'LBL_CHARTOPTIONS_3D' => '3 dimensional',
    'LBL_CHARTOPTIONS_STACKED' => 'stacked Series',
    'LBL_CHARTOPTIONS_REVERSED' => 'reverse Series',
    'LBL_CHARTOPTIONS_CTFUNCTION' => 'smoothed Line',
    'LBL_CHARTOPTIONS_POINTS' => 'show Points',
    'LBL_CHARTOPTIONS_MATERIAL' => 'material Design',
    'LBL_CHARTOPTIONS_ALLOWOVERLAP' => 'allow data labels to overlap',

    // for Fusion Charts ... needs to be moved
    'LBL_CHARTTYPE_COLUMN2D' => 'Column 2D',
    'LBL_CHARTTYPE_COLUMN3D' => 'Column 3D',
    'LBL_CHARTTYPE_PIE2D' => 'Pie 2D',
    'LBL_CHARTTYPE_PIE3D' => 'Pie 3D',
    'LBL_CHARTTYPE_DOUGNUT2D' => 'Dougnut 2D',
    'LBL_CHARTTYPE_DOUGNUT3D' => 'Dougnut 3D',
    'LBL_CHARTTYPE_BAR2D' => 'Bar 2D',
    'LBL_CHARTTYPE_AREA2D' => 'Area 2D',
    'LBL_CHARTTYPE_STACKEDAREA2D' => 'stacked area 2D',
    'LBL_CHARTTYPE_PARETO2D' => 'Pareto 2D',
    'LBL_CHARTTYPE_PARETO3D' => 'Pareto 3D',
    'LBL_CHARTTYPE_STACKEDCOLUMN2D' => 'stacked Column 2D',
    'LBL_CHARTTYPE_STACKEDCOLUMN3D' => 'stacked Column 3D',
    'LBL_CHARTTYPE_MSCOLUMN2D' => 'multiseries Column 2D',
    'LBL_CHARTTYPE_MSCOLUMN3D' => 'multiseries Column 3D',
    'LBL_CHARTTYPE_MSBAR2D' => 'multiseries Bar 2D',
    'LBL_CHARTTYPE_MSBAR3D' => 'multiseries Bar 3D',
    'LBL_CHARTTYPE_STACKEDBAR2D' => 'stacked Bar 2D',
    'LBL_CHARTTYPE_STACKEDBAR3D' => 'stacked Bar 3D',
    'LBL_CHARTTYPE_MARIMEKKO' => 'Marimekko Chart',
    'LBL_CHARTTYPE_MSLINE' => 'multiseries Line',
    'LBL_CHARTTYPE_MSAREA' => 'multiseries Area',
    'LBL_CHARTTYPE_MSCOMBIDY2D' => 'multiseries Combination dual',
    'LBL_CHARTOPTIONS_ROUNDEDGES' => 'round Edges',
    'LBL_CHARTOPTIONS_HIDELABELS' => 'hide Labels',
    'LBL_CHARTOPTIONS_HIDEVALUES' => 'hide Values',
    'LBL_CHARTOPTIONS_FORMATNUMBERSCALE' => 'scale Numbers',
    'LBL_CHARTOPTIONS_ROTATEVALUES' => 'rotate Value',
    'LBL_CHARTOPTIONS_PLACEVALUESINSIDE' => 'place Values inside',
    'LBL_CHARTOPTIONS_SHOWSHADOE' => 'show Shadow',
    'LBL_CHARTOPTIONS_LPOS' => 'Legend',
    'LBL_LPOS_NONE' => 'none',
    'LBL_LPOS_RIGHT' => 'right',
    'LBL_LPOS_LEFT' => 'left',
    'LBL_LPOS_BOTTOM' => 'bottom',
    'LBL_LPOS_TOP' => 'top',


    'LBL_STANDARDPLUGIN' => 'Standard View',


    // for the Google Maps
    'LBL_GOOGLEMAPSFS_GEOCODEBY' => 'Geo by',
    'LBL_GOOGLEMAPSFS_GEOCODELATLONG' => 'Lat/Long',
    'LBL_GOOGLEMAPSFS_GEOCODEADDRESS' => 'Address',
    'LBL_GOOGLEMAPS_LONGITUDE' => 'Longitude',
    'LBL_GOOGLEMAPS_LATITUDE' => 'Latitude',
    'LBL_GOOGLEMAPSFS_LATLONG' => 'Geocoordinates',
    'LBL_GOOGLEMAPS_STREET' => 'Street',
    'LBL_GOOGLEMAPS_CITY' => 'City',
    'LBL_GOOGLEMAPS_PC' => 'Postalcode',
    'LBL_GOOGLEMAPS_COUNTRY' => 'Country',
    'LBL_GOOGLEMAPS_ADDRESS' => 'Address',
    'LBL_GOOGLEMAPSFS_TITLE' => 'Pin Info',
    'LBL_GOOGLEMAPS_TITLE' => 'Title',
    'LBL_GOOGLEMAPS_CLUSTER' => 'Cluster Pins',
    'LBL_GOOGLEMAPS_LABEL' => 'Google Maps',
    'LBL_GOOGLEMAPS_COLORSOPTIONS' => 'Colors',
    'LBL_GOOGLEMAPS_COLORS' => 'Colors',
    'LBL_GOOGLEMAPS_COLORCRITERIA' => 'Color by',
    'LBL_GOOGLEMAPS_INFO' => 'Popupinfo',
    'LBL_GOOGLEMAPS_LEGEND' => 'Display legend',
    'LBL_GOOGLEMAPS_COLORSOPTIONSRESET' => 'reset',
    'LBL_GOOGLEMAPS_SPIDERFY' => 'Spiderfy pins',
    'LBL_GOOGLEMAPS_ROUTEPLANNER_OPTIONS' => 'Route planner options',
    'LBL_GOOGLEMAPS_ROUTEPLANNER_RESET' => 'reset',
    'LBL_GOOGLEMAPS_ROUTEPLANNER_DISPLAY' => 'Display route planner',
    'LBL_GOOGLEMAPS_ROUTEPLANNER_WAYPOINTLABEL' => 'Waypoint name',
    'LBL_GOOGLEMAPS_ROUTEPLANNER_WAYPOINTADDRESS' => 'Waypoint address',
    'LBL_GOOGLEMAPS_ROUTEPLANNER_TITLE' => 'Route planner',
    'LBL_GOOGLEMAPS_ROUTEPLANNER_SUMMARY' => 'Route details',
    'LBL_GOOGLEMAPS_ROUTEPLANNER_START' => 'Start',
    'LBL_GOOGLEMAPS_ROUTEPLANNER_END' => 'End',
    'LBL_GOOGLEMAPS_ROUTEPLANNER_WAYPOINTS' => 'Waypoints',
    'LBL_GOOGLEMAPS_ROUTEPLANNER_BTN' => 'Plan route',
    'LBL_GOOGLEMAPS_ROUTEPLANNER_RESETBTN' => 'Delete route',
    'LBL_GOOGLEMAPS_ROUTEPLANNER_WAYPTGCBY' => 'Waypoint geocode by',
    'LBL_GOOGLEMAPS_ROUTEPLANNER_SEG' => 'Route segment',
    'LBL_GOOGLEMAPS_ROUTEPLANNER_SEGFROM' => 'from',
    'LBL_GOOGLEMAPS_ROUTEPLANNER_SEGTO' => 'to',
    'LBL_GOOGLEMAPS_RESIZEMAP_BTN' => 'Resize map',
    'LBL_GOOGLEMAPS_CIRCLEDESIGNER_OPTIONS' => 'Periphery search options',
    'LBL_GOOGLEMAPS_CIRCLEDESIGNER_RESET' => 'reset',
    'LBL_GOOGLEMAPS_CIRCLEDESIGNER_DISPLAY' => 'Display periphery search',
    'LBL_GOOGLEMAPS_CIRCLEDESIGNER_LABEL' => 'Periphery start point name',
    'LBL_GOOGLEMAPS_CIRCLEDESIGNER_TITLE' => 'Periphery search',
    'LBL_GOOGLEMAPS_CIRCLEDESIGNER_SUMMARY' => 'Periphery details',
    'LBL_GOOGLEMAPS_CIRCLEDESIGNER_DISTANCE' => 'Distance in Km',
    'LBL_GOOGLEMAPS_CIRCLEDESIGNER_WAYPOINTS' => 'Search from',
    'LBL_GOOGLEMAPS_CIRCLEDESIGNER_BTN' => 'Periphery search',
    'LBL_GOOGLEMAPS_CIRCLEDESIGNER_RESETBTN' => 'Delete circle(s)',
    'LBL_GOOGLEMAPS_CIRCLEDESIGNER_MODULE' => 'Module',
    'LBL_GOOGLEMAPS_CIRCLEDESIGNER_DISPLAYFIELDS' => 'Query config',


    // for the Plugins
    'LBL_PRESENTATION_PLUGIN' => 'Plugin',
    'LBL_PRESENTATION_PARAMS' => 'Presentation Parameters',
    'LBL_DEFAULT_GROUPBY' => 'Default Group By',
    'LBL_INTEGRATION' => 'integrate',
    'LBL_INTEGRATION_PLUGINNAME' => 'Plugin',
    'LBL_CSV_EXPORT' => 'Export to CSV',
    'LBL_EXCEL_EXPORT' => 'Export to Excel',
    'LBL_TARGETLIST_EXPORT' => 'Export to Targetlist',
    'LBL_SNAPSHOT_EXPORT' => 'take Snapshot',
    'LBL_QUERY_ANALIZER' => 'Query Analyzer',
    'LBL_SCHEDULE_REPORT' => 'schedule Report',
    'LBL_PUBLISH_REPORT' => 'publish Report',
    'LBL_PUBLISH_DASHLET' => 'Publish as Dashlet',
    'LBL_PUBLISH_DASHLETREPORT' => 'Select Report',
    'LBL_PUBLISH_DASHLETTITLE' => 'Dashlet Title',
    'LBL_PUBLISH_DASHLET_PRESENTATION' => 'Presentation',
    'LBL_PUBLISH_DASHLET_PRESENTATION_VISUALIZATION' => 'Visualization',
    'LBL_PUBLISH_DASHLET_NAME' => 'Name',
    'LBL_PUBLISH_SUBPANEL_SEQUENCE' => 'Sequence',
    'LBL_PUBLISH_SUBPANEL_MODULE' => 'Module',
    'LBL_PUBLISH_SUBPANEL_TAB' => 'Tab',


    // Scheduler Options
    'LBL_KSCHEDULING_SAVETOACTION_DONOTHING' => '&nbsp;',
    'LBL_KSCHEDULING_SAVETOACTION_ADD' => 'add',
    'LBL_KSCHEDULING_SAVETOACTION_REPLACE' => 'replace',

    // PDF Export Option
    'LBL_PDF_EXPORT' => 'PDF Export',
    'LBL_PDF_EXPORTOPTIONS_GENERAL' => 'General',
    'LBL_PDF_LAYOUT' => 'PDF Layout',
    'LBL_PDF_FORMAT' => 'Format',
    'LBL_PDFFORMAT_LTR' => 'Letter',
    'LBL_PDFFORMAT_LGL' => 'Legal',
    'LBL_PDFFORMAT_A4' => 'A4',
    'LBL_PDFFORMAT_A5' => 'A5',
    'LBL_PDF_ORIENTATION' => 'Orientation',
    'LBL_PDF_MULTILINE' => 'multiline',
    'LBL_PDFORIENT_P' => 'Portrait',
    'LBL_PDFORIENT_L' => 'Landscape',
    'LBL_PDF_PALIGNMENT' => 'Data Alignment',
    'LBL_PDFPALIGNMENT_L' => 'Left',
    'LBL_PDFPALIGNMENT_R' => 'Right',
    'LBL_PDFPALIGNMENT_C' => 'Center',
    'LBL_PDFPALIGNMENT_S' => 'Stretch',
    'LBL_PDF_NEWPAGEPERGROUP' => 'new Page per Group',
    'LBL_PDF_HEADERPERPAGE' => 'header on each page',

    // Pivot Plugin ... to be moved later
    'LBL_PIVOT_SETTINGS' => 'Pivot table settings',
    'LBL_PIVOT_ADVANCED' => 'Advanced Settings',
    'LBL_PIVOT_REPOSITORY' => 'available Fields',
    'LBL_PIVOT_COLUMNS' => 'Columns',
    'LBL_PIVOT_ROWS' => 'Rows',
    'LBL_PIVOT_ADDROWINFO' => 'additonal Row Info',
    'LBL_PIVOT_VALUES' => 'Values',
    'LBL_PIVOT_FUNCTiON' => 'Function',
    'LBL_PIVOT_TOTALS' => 'show totals',
    'LBL_PIVOT_SUMS' => 'show sum',
    'LBL_PIVOT_ROTATEHEADERS' => 'rotate Headers',
    'LBL_PIVOT_EMPTYCOLUMNS' => 'show empty Columnns',
    'LBL_PIVOT_ADJUSTCOLUMNS' => 'adjust column width',
    'LBL_PIVOT_SORTCOLUMNS' => 'sort Columns',
    'LBL_PIVOT_LBLPIVOTDATA' => 'Pivot Data',
    'LBL_PIVOT_NAMECOLUMNWIDTH' => 'Item Column Width',
    'LBL_PIVOT_MINCOLUMNWIDTH' => 'min Column Width',

    // the field renderer
    'LBL_RENDERER_-' => '-',
    'LBL_RENDERER_CURRENCY' => 'Currency',
    'LBL_RENDERER_SCURRENCY' => 'System Currency',
    'LBL_RENDERER_UCURRENCY' => 'User Currency',
    'LBL_RENDERER_PERCENTAGE' => 'Percentage',
    'LBL_RENDERER_NUMBER' => 'Number',
    'LBL_RENDERER_INT' => 'Integer',
    'LBL_RENDERER_DATE' => 'Date',
    'LBL_RENDERER_DATETIME' => 'Datetime',
    'LBL_RENDERER_DATETUTC' => 'Datetime (UTC)',
    'LBL_RENDERER_FLOAT' => 'Float',
    'LBL_RENDERER_BOOL' => 'Boolean',
    'LBL_RENDERER_TEXT' => 'Text',
    'LBL_RENDERER_NONE' => 'do not Format',

    // override Alignment
    'LBL_OVERRIDEALIGNMENT' => 'override Alignment',
    'LBL_ALIGNMENT_-' => '-',
    'LBL_ALIGNMENT_LEFT' => 'left',
    'LBL_ALIGNMENT_RIGHT' => 'right',
    'LBL_ALIGNMENT_CENTER' => 'center',

    'LBL_REPORTTIMEOUT' => 'Timeout',
    'LBL_RT30' => '30 seconds',
    'LBL_RT60' => '1 minute',
    'LBL_RT120' => '2 minutes',
    'LBL_RT240' => '3 minutes',
    'LBL_RT300' => '4 minutes',

    'LBL_KSNAPSHOTS' => 'Snapshots',
    'LBL_KSNAPSHOT' => 'Snapshot',
    'LBL_TAKING_SNAPSHOT' => 'taking snapshot ... ',
    'LBL_GROUPING' => 'Grouping',
    'LBL_PICK_DATETIME' => 'choose Date/Time'

);


$mod_strings['LBL_PIVOTVIEW'] = 'Pivot';
$mod_strings['LBL_STANDARDWSUMMARY'] = 'Standard with Summary';
$mod_strings['LBL_STANDARDWPREVIEW'] = 'Standard with Preview';
$mod_strings['LBL_TREEVIEW'] = 'Treeview';
$mod_strings['LBL_TREEVIEWPROPERTIES_GRPUNTIL'] = 'group until';

$mod_strings['LBL_GROUPEDVIEW'] = 'Grouped View';
$mod_strings['LBL_STANDARDVIEW'] = 'Standard View';
$mod_strings['LBL_EDITPLUGIN'] = 'Edit View (beta)';

$mod_strings['LBL_GOOGLECHARTS'] = 'Google Charts';
$mod_strings['LBL_FUSIONCHARTS'] = 'Fusion Charts';
$mod_strings['LBL_HIGHCHARTS'] = 'High Charts';
$mod_strings['LBL_GOOGLEMAPS'] = 'Google Maps';
$mod_strings['LBL_GOOGLEGEO'] = 'Google Geo';
$mod_strings['LBL_SUGARCHARTS'] = 'Sugar Charts';

$mod_strings['LBL_GEOOPTIONS_TITLE'] = 'Title';
$mod_strings['LBL_GEOOPTIONS_REGION'] = 'Display Region';
$mod_strings['LBL_GEOOPTIONS_RESOLUTION'] = 'Resolution';
$mod_strings['LBL_GEOOPTIONS_COUNTRIES'] = 'Countries';
$mod_strings['LBL_GEOOPTIONS_PROVINCES'] = 'Provinces';
$mod_strings['LBL_GEOOPTIONS_METROS'] = 'Metros';

$mod_strings['LBL_PUBLISH_CNTENTRIES'] = 'number of entries displayed';

$mod_strings['LBL_PDF_CHARTSONSEPARATEPAGE'] = 'Charts on separate Page';

$mod_strings['LBL_KPDRILLDOWN'] = 'Presentation Drilldown';
$mod_strings['LBL_LINK_LINKTYPE'] = 'Link';
$mod_strings['LBL_POPUP_LINKTYPE'] = 'Popup';
$mod_strings['LBL_CHART_LINKTYPE'] = 'Chart';
$mod_strings['LBL_DRILLDOWNMENUTITLE'] = 'Drill Down';
$mod_strings['LBL_ADDLINKEDREPORT_TITLE'] = 'Link Report';
$mod_strings['LBL_ADDLINK_ADD'] = 'Add';
$mod_strings['LBL_MAPPING_TITLE'] = 'InputMapping';

$mod_strings['LBL_EDITABLE'] = 'editable';

$mod_strings['LBL_PUBLISH_ASDASHLET'] = 'publish as Dashlet';
$mod_strings['LBL_PUBLISH_ASSUBPANEL'] = 'publish as Subpanel';
$mod_strings['LBL_PUBLISH_MOBILE'] = 'publish Mobile';

$mod_strings['LBL_CHARTOPTIONS_ROTATELABELS'] = 'Rotate Labels';
$mod_strings['LBL_STACK_NONE'] = 'not stacked';
$mod_strings['LBL_STACK_NORMAL'] = 'stracked';
$mod_strings['LBL_STACK_PERCENT'] = 'percentage';
$mod_strings['LBL_CHARTRENDER_SPLINE'] = 'Spline';
$mod_strings['LBL_CHARTTYPE_SPLINE'] = 'Spline';
$mod_strings['LBL_CHARTTYPE_AREASPLINE'] = 'Area Spline';

$mod_strings['LBL_CHARTTYPE_ARTRD'] = 'Area w. Trendline';
$mod_strings['LBL_CHARTTYPE_ARSPLINETRD'] = 'Area Spline w. Trendline';

$mod_strings['LBL_CHARTTYPE_ARSPLINEPOLR'] = 'Area Spline Polar';
$mod_strings['LBL_CHARTTYPE_ARSPLINESTCKPOL'] = 'Area Spline stacked Polar';

$mod_strings['LBL_CHARTTYPE_COLTRD'] = 'Column w. Trendline';
$mod_strings['LBL_CHARTTYPE_COLPLR'] = 'Column Polar';
$mod_strings['LBL_CHARTTYPE_LPOLR'] = 'Line polar';
$mod_strings['LBL_CHARTTYPE_DONUT'] = 'Donut';
$mod_strings['LBL_CHARTTYPE_PIE180'] = 'Pie 180°';
$mod_strings['LBL_CHARTTYPE_DONUT180'] = 'Donut 180°';

$mod_strings['LBL_CHARTTYPE_COLSTACKED'] = 'Columns stacked';
$mod_strings['LBL_CHARTTYPE_COLSTKPOLR'] = 'Columns stacked polar';
$mod_strings['LBL_CHARTTYPE_COLSTCKPER'] = 'Columns stacked 100%';
$mod_strings['LBL_CHARTTYPE_BRSTACKED'] = 'Bars stacked';
$mod_strings['LBL_CHARTTYPE_BRSTCKPER'] = 'Bars stacked 100%';
$mod_strings['LBL_CHARTTYPE_ARSTACKED'] = 'Area stacked';
$mod_strings['LBL_CHARTTYPE_ARSTCKPER'] = 'Area stacked 100%';
$mod_strings['LBL_CHARTTYPE_ARSPLINESTACKED'] = 'Area Spline stacked';
$mod_strings['LBL_CHARTTYPE_ARSPLINESTCKPER'] = 'Area Spline stacked 100%';
$mod_strings['LBL_CHARTTYPE_LPLR'] = 'Line Polar';
$mod_strings['LBL_CHARTTYPE_COLSTKPPL'] = 'Columns stacked 100% polar';
$mod_strings['LBL_CHARTTYPE_SPLPLR'] = 'Spline polar';
$mod_strings['LBL_CHARTTYPE_ARSTCKPOL'] = 'Area stacked polar';
$mod_strings['LBL_CHARTTYPE_ARSTCKPPL'] = 'Area stacked 100% polar';
$mod_strings['LBL_CHARTTYPE_ARSPLINESTCKPPL'] = 'Area Spline stacked polar';
$mod_strings['LBL_CHARTTYPE_ARSPLINESTCKPPL'] = 'Area Spline stacked 100% polar';
$mod_strings['LBL_CHARTTYPE_ARPOLR'] = 'Area polar';
$mod_strings['LBL_CHARTTYPE_FUNNEL'] = 'Funnel';
$mod_strings['LBL_CHARTTYPE_PYRAMID'] = 'Pyramid';
$mod_strings['LBL_TRENDLINENAME'] = 'Trend';
$mod_strings['LBL_KSNAPSHOTANALYZER'] = 'Snapshot Analyzer';
$mod_strings['LBL_SNAPSHOTANALYZER_TITLE'] = 'ad hoc Snapshot Analyzer';
$mod_strings['LBL_KPLANNER_EXPORT'] = 'KPlanner Export';

$mod_strings['LBL_GOOGLEMAPS_LABEL'] = 'Google Maps';
$mod_strings['LBL_GOOGLEMAPS_COLORSOPTIONS'] = 'Colors';
$mod_strings['LBL_GOOGLEMAPS_COLORS'] = 'Colors';
$mod_strings['LBL_GOOGLEMAPS_COLORCRITERIA'] = 'Color by';
$mod_strings['LBL_GOOGLEMAPS_INFO'] = 'Popupinfo';
$mod_strings['LBL_GOOGLEMAPS_LEGEND'] = 'Display legend';
$mod_strings['LBL_GOOGLEMAPS_COLORSOPTIONSRESET'] = 'reset';
$mod_strings['LBL_GOOGLEMAPS_SPIDERFY'] = 'Spiderfy pins';
$mod_strings['LBL_GOOGLEMAPS_ROUTEPLANNER_OPTIONS'] = 'Route planner options';
$mod_strings['LBL_GOOGLEMAPS_ROUTEPLANNER_RESET'] = 'reset';
$mod_strings['LBL_GOOGLEMAPS_ROUTEPLANNER_DISPLAY'] = 'Display route planner';
$mod_strings['LBL_GOOGLEMAPS_ROUTEPLANNER_WAYPOINTLABEL'] = 'Waypoint name';
$mod_strings['LBL_GOOGLEMAPS_ROUTEPLANNER_WAYPOINTADDRESS'] = 'Waypoint address';
$mod_strings['LBL_GOOGLEMAPS_ROUTEPLANNER_TITLE'] = 'Route planner';
$mod_strings['LBL_GOOGLEMAPS_ROUTEPLANNER_SUMMARY'] = 'Route details';
$mod_strings['LBL_GOOGLEMAPS_ROUTEPLANNER_START'] = 'Start';
$mod_strings['LBL_GOOGLEMAPS_ROUTEPLANNER_END'] = 'End';
$mod_strings['LBL_GOOGLEMAPS_ROUTEPLANNER_WAYPOINTS'] = 'Waypoints';
$mod_strings['LBL_GOOGLEMAPS_ROUTEPLANNER_BTN'] = 'Plan route';
$mod_strings['LBL_GOOGLEMAPS_ROUTEPLANNER_RESETBTN'] = 'Delete route';
$mod_strings['LBL_GOOGLEMAPS_ROUTEPLANNER_WAYPTGCBY'] = 'Waypoint geocode by';
$mod_strings['LBL_GOOGLEMAPS_ROUTEPLANNER_SEG'] = 'Route segment';
$mod_strings['LBL_GOOGLEMAPS_ROUTEPLANNER_SEGFROM'] = 'from';
$mod_strings['LBL_GOOGLEMAPS_ROUTEPLANNER_SEGTO'] = 'to';
$mod_strings['LBL_GOOGLEMAPS_RESIZEMAP_BTN'] = 'Resize map';
$mod_strings['LBL_GOOGLEMAPS_CIRCLEDESIGNER_OPTIONS'] = 'Periphery search options';
$mod_strings['LBL_GOOGLEMAPS_CIRCLEDESIGNER_RESET'] = 'reset';
$mod_strings['LBL_GOOGLEMAPS_CIRCLEDESIGNER_DISPLAY'] = 'Display periphery search';
$mod_strings['LBL_GOOGLEMAPS_CIRCLEDESIGNER_LABEL'] = 'Periphery start point name';
$mod_strings['LBL_GOOGLEMAPS_CIRCLEDESIGNER_TITLE'] = 'Periphery search';
$mod_strings['LBL_GOOGLEMAPS_CIRCLEDESIGNER_SUMMARY'] = 'Periphery details';
$mod_strings['LBL_GOOGLEMAPS_CIRCLEDESIGNER_DISTANCE'] = 'Distance in Km';
$mod_strings['LBL_GOOGLEMAPS_CIRCLEDESIGNER_WAYPOINTS'] = 'Search from';
$mod_strings['LBL_GOOGLEMAPS_CIRCLEDESIGNER_BTN'] = 'Periphery search';
$mod_strings['LBL_GOOGLEMAPS_CIRCLEDESIGNER_RESETBTN'] = 'Delete circle(s)';
$mod_strings['LBL_GOOGLEMAPS_CIRCLEDESIGNER_MODULE'] = 'Module';
$mod_strings['LBL_GOOGLEMAPS_CIRCLEDESIGNER_DISPLAYFIELDS'] = 'Query config';

$mod_strings['LBL_PREVIEW'] = 'Preview for';

$mod_strings['LBL_PDF_WHERE'] = 'Print Selection criteria';



    //Bucket Manager
$mod_strings['LNK_MANAGE_BUCKETS'] = 'Bucket Manager';
$mod_strings['LBL_GROUPING_ID'] = 'Grouping ID';
$mod_strings['LBL_GROUPING_NAME'] = 'Grouping Name';
$mod_strings['LBL_GROUPING_DESCRIPTION'] = 'Description';
$mod_strings['LBL_GROUPING_MODULENAME'] = 'Module Name';
$mod_strings['LBL_GROUPING_FIELDNAME'] = 'Field Name';
$mod_strings['LBL_GROUPING_MAPPING'] = 'Mapping';
$mod_strings['LBL_GROUPING_CREATE'] = 'Create New Grouping';
$mod_strings['LBL_GROUPING_ENUMFIELDS'] = 'Available fields';
$mod_strings['LBL_MAPPING_VALUE'] = 'Mapping Name';
$mod_strings['LBL_MAPPING_NEWVALUE'] = 'New Mapping';
$mod_strings['LBL_MAPPING_PROMPT_NAME'] = 'Please, enter a name';
$mod_strings['LBL_ENUMVALUE_ID'] = 'ID';
$mod_strings['LBL_ENUMVALUE_VALUE'] = 'Value';
$mod_strings['LBL_ENUMVALUE_LABEL'] = 'Label';
$mod_strings['LBL_ENUMVALUE_HANDLER'] = 'Group remaining values into \'others\'';
$mod_strings['LBL_GRID_GROUPINGS'] = 'Groupings';
$mod_strings['LBL_GRID_MODULEFIELD'] = 'Source';
$mod_strings['LBL_GRID_MAPPINGS'] = 'Mappings';
$mod_strings['LBL_GRID_ENUMVALUES'] = '<< Drag Values';
$mod_strings['LBL_ERROR'] = 'Error:';
$mod_strings['LBL_ERROR_REQUIREDALL'] = 'All fields are required!';
$mod_strings['LBL_ERROR_DELETEGROUPING'] = 'Grouping could not be deleted. Please, check Sugar log.';
$mod_strings['LBL_ERROR_UPDATEGROUPING'] = 'Grouping could not be saved. Please, check Sugar log.';
$mod_strings['LBL_ERROR_NOGROUPINGSELECTED'] = 'Please, select a grouping!';

    //DList Manager   
$mod_strings['LNK_MANAGE_DLISTS'] = 'DList Manager';
$mod_strings['LBL_GRID_DLISTS'] = 'Distributions lists';
$mod_strings['LBL_DLIST_FILTER'] = 'Filter';
$mod_strings['LBL_DLIST_SEARCH'] = 'Search';
$mod_strings['LBL_DLIST_ID'] = 'ID';
$mod_strings['LBL_DLIST_NAME'] = 'Name';
$mod_strings['LBL_DLIST_USER_ID'] = 'User ID';
$mod_strings['LBL_DLIST_USER_USERNAME'] = 'User Name';
$mod_strings['LBL_DLIST_FIRSTNAME'] = 'First Name';
$mod_strings['LBL_DLIST_LASTNAME'] = 'Last Name';
$mod_strings['LBL_DLIST_EMAIL1'] = 'e-mail';
$mod_strings['LBL_DLIST_CONTACT_ID'] = 'Contact ID';
$mod_strings['LBL_DLIST_CONTACT_ACCOUNTNAME'] = 'Account Name';
$mod_strings['LBL_DLIST_CONTACT_ACCOUNTID'] = 'Account ID';
$mod_strings['LBL_DLIST_KREPORT_MODULENAME'] = 'Module';
$mod_strings['LBL_DLIST_WINDOW_ADDKREPORTS_TITLE'] = 'Add Advanced Report';
$mod_strings['LBL_DLIST_WINDOW_ADDUSERS_TITLE'] = 'Add Users';
$mod_strings['LBL_DLIST_WINDOW_ADDCONTACTS_TITLE'] = 'Add Contacts';

    //ksavedfilters
$mod_strings['LBL_KSAVEDFILTERS'] = 'Saved Filters';
$mod_strings['LBL_KSAVEDFILTERS_ID'] = 'Saved Filter ID';
$mod_strings['LBL_KSAVEDFILTERS_NAME'] = 'Name';
$mod_strings['LBL_KSAVEDFILTERS_ASSIGNED_USER_ID'] = 'Assigned User ID';
$mod_strings['LBL_KSAVEDFILTERS_ASSIGNED_USER_NAME'] = 'Assigned user';
$mod_strings['LBL_KSAVEDFILTERS_IS_GLOBAL'] = 'set for all users';
$mod_strings['LBL_KSAVEDFILTERS_IS_GLOBAL_MARK'] = '(G)';
$mod_strings['LBL_KSAVEDFILTERS_SELECTEDFILTERS'] = 'Filters';
$mod_strings['LBL_KSAVEDFILTERS_SAVE_BTN'] = 'Save Filters';
$mod_strings['LBL_KSAVEDFILTERS_DELETE_BTN'] = 'Delete';
$mod_strings['LBL_KSAVESFILTERS_EMPTYTEXT'] = '-- select a filter --';
$mod_strings['LBL_KSAVEDFILTERS_WINDOW_TITLE'] = 'Save Filter';
$mod_strings['LBL_KSAVEDFILTERS_STATUS'] = 'Status';
$mod_strings['LBL_KSAVEDFILTERS_CONTENT'] = 'Filter details';

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