<?php

/**
 * Mint #58447
 * * strip_tags when exporting to XLSX
 * Mint #59232
 * * fix calling function on dynamic enum - view should be set, but with empty value
 * Mint #60147
 * * empty value on enum field can alse have label, so should be processed
 * Mint #61987
 * 0 in currency field was formatted as empty string in CSV/XLSX export
 */
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

require_once 'modules/KReports/utils.php';
require_once 'modules/KReports/KReportQuery.php';

global $dictionary;

//2013-05-14 include files with custom functions .. Bug #478
//2013-10-19 check if file exists before .. Bug #507
if (file_exists('./custom/modules/KReports/includes')) {
    $dirHandle = opendir('./custom/modules/KReports/includes');
    while (false !== ($nextFile = readdir($dirHandle))) {
        if (preg_match('/.php/', $nextFile)) {
            require_once 'custom/modules/KReports/includes/' . $nextFile;
        }
    }
}

class KReportPluginManager
{

    // constructor
    public $plugins = array();

    public function __construct()
    {
        /*
        $objects = new RecursiveIteratorIterator(new RecursiveDirectoryIterator('modules/KReports/plugins'));
        foreach ($objects as $name => $object) {
        if ($object->filename === 'pluginmetadata.php') {
        $pluginmetadata = array();
        $pluginPath = substr($object->pathName, 0, strlen($object->pathName) - 19);
        include($object->pathName);
        switch ($pluginmetadata['type']) {
        case 'presentation':
        $this->plugins[$pluginmetadata['id']]['plugindirectory'] = $pluginPath;
        $this->plugins[$pluginmetadata['id']]['metadata'] = $pluginmetadata;
        break;
        case 'visualization':
        $this->plugins[$pluginmetadata['id']]['plugindirectory'] = $pluginPath;
        $this->plugins[$pluginmetadata['id']]['metadata'] = $pluginmetadata;
        break;
        case 'integration':
        $this->plugins[$pluginmetadata['id']]['plugindirectory'] = $pluginPath;
        $this->plugins[$pluginmetadata['id']]['metadata'] = $pluginmetadata;
        break;
        }
        }
        }
         */
        if (file_exists('modules/KReports/plugins.dictionary')) {
            $plugins = array();
            include 'modules/KReports/plugins.dictionary';

            foreach ($plugins as $thisPlugin => $thisPluginData) {

                // write to the Object varaible so we have all plugins by ID
                $this->plugins[$thisPlugin] = $thisPluginData;

                // add specific plugins metadata to the array
                switch ($thisPluginData['type']) {
                    case 'presentation':
                        $this->plugins[$thisPlugin]['plugindirectory'] = 'modules/KReports/Plugins/Presentation/' . $thisPluginData['directory'];
                        if (file_exists('modules/KReports/Plugins/Presentation/' . $thisPluginData['directory'] . '/pluginmetadata.php')) {
                            $pluginmetadata = array();
                            include 'modules/KReports/Plugins/Presentation/' . $thisPluginData['directory'] . '/pluginmetadata.php';
                            $this->plugins[$thisPlugin]['metadata'] = $pluginmetadata;
                        }
                        break;
                    case 'visualization':
                        $this->plugins[$thisPlugin]['plugindirectory'] = 'modules/KReports/Plugins/Visualization/' . $thisPluginData['directory'];
                        if (file_exists('modules/KReports/Plugins/Visualization/' . $thisPluginData['directory'] . '/pluginmetadata.php')) {
                            $pluginmetadata = array();
                            include 'modules/KReports/Plugins/Visualization/' . $thisPluginData['directory'] . '/pluginmetadata.php';
                            $this->plugins[$thisPlugin]['metadata'] = $pluginmetadata;
                        }
                        break;
                    case 'integration':
                        $this->plugins[$thisPlugin]['plugindirectory'] = 'modules/KReports/Plugins/Integration/' . $thisPluginData['directory'];
                        if (file_exists('modules/KReports/Plugins/Integration/' . $thisPluginData['directory'] . '/pluginmetadata.php')) {
                            $pluginmetadata = array();
                            include 'modules/KReports/Plugins/Integration/' . $thisPluginData['directory'] . '/pluginmetadata.php';
                            $this->plugins[$thisPlugin]['metadata'] = $pluginmetadata;
                        }
                        break;
                }
            }
        }

        // read the plugin metadata
        if (file_exists('custom/modules/KReports/plugins.dictionary')) {
            $plugins = array();
            include 'custom/modules/KReports/plugins.dictionary';

            foreach ($plugins as $thisPlugin => $thisPluginData) {

                // write to the Object varaible so we have all plugins by ID
                $this->plugins[$thisPlugin] = $thisPluginData;

                // add specific plugins metadata to the array
                switch ($thisPluginData['type']) {
                    case 'presentation':
                        $this->plugins[$thisPlugin]['plugindirectory'] = 'custom/modules/KReports/Plugins/Presentation/' . $thisPluginData['directory'];
                        if (file_exists('custom/modules/KReports/Plugins/Presentation/' . $thisPluginData['directory'] . '/pluginmetadata.php')) {
                            $pluginmetadata = array();
                            include 'custom/modules/KReports/Plugins/Presentation/' . $thisPluginData['directory'] . '/pluginmetadata.php';
                            $this->plugins[$thisPlugin]['metadata'] = $pluginmetadata;
                        }
                        break;
                    case 'visualization':
                        $this->plugins[$thisPlugin]['plugindirectory'] = 'custom/modules/KReports/Plugins/Visualization/' . $thisPluginData['directory'];
                        if (file_exists('custom/modules/KReports/Plugins/Visualization/' . $thisPluginData['directory'] . '/pluginmetadata.php')) {
                            $pluginmetadata = array();
                            include 'custom/modules/KReports/Plugins/Visualization/' . $thisPluginData['directory'] . '/pluginmetadata.php';
                            $this->plugins[$thisPlugin]['metadata'] = $pluginmetadata;
                        }
                        break;
                    case 'integration':
                        $this->plugins[$thisPlugin]['plugindirectory'] = 'custom/modules/KReports/Plugins/Integration/' . $thisPluginData['directory'];
                        if (file_exists('custom/modules/KReports/Plugins/Integration/' . $thisPluginData['directory'] . '/pluginmetadata.php')) {
                            $pluginmetadata = array();
                            include 'custom/modules/KReports/Plugins/Integration/' . $thisPluginData['directory'] . '/pluginmetadata.php';
                            $this->plugins[$thisPlugin]['metadata'] = $pluginmetadata;
                        }
                        break;
                }
            }
        }
    }

    public function getPlugins($report = '')
    {

        if ($report) {
            $thisReport = BeanFactory::getBean('KReports', $report);
            $integrationParams = json_decode(html_entity_decode($thisReport->integration_params, ENT_QUOTES, 'UTF-8'));
        }

        foreach ($this->plugins as $pluginId => $pluginData) {
            /*
            if (isset($pluginData['metadata']['includes']['edit']))
            $jsIncludes .= "<script type='text/javascript' src='" . $pluginData['plugindirectory'] . '/' . $pluginData['metadata']['includes']['edit'] . "'></script>";
             */

            switch ($pluginData['type']) {
                case 'presentation':
                    $presentationPlugins[$pluginId] = array(
                        'id' => $pluginId,
                        'plugindirectory' => $pluginData['plugindirectory'],
                        'displayname' => $pluginData['metadata']['displayname'],
                        'metadata' => $pluginData['metadata'],
                    );
                    break;
                case 'visualization':
                    $visualizationPlugins[$pluginId] = array(
                        'id' => $pluginId,
                        'plugindirectory' => $pluginData['plugindirectory'],
                        'displayname' => $pluginData['metadata']['displayname'],
                        'metadata' => $pluginData['metadata'],
                    );
                    break;
                case 'integration':
                    if ($report) {
                        if (1 == $integrationParams->activePlugins->$pluginId) {
                            // for export plugins check export right
                            if ('export' === $pluginData['category'] && !ACLController::checkAccess('KReports', 'export', false)) {
                                continue;
                            }

                            // plugin specific checks
                            if (isset($pluginData['metadata']['integration']['include'])) {
                                require_once $pluginData['plugindirectory'] . '/' . $pluginData['metadata']['integration']['include'];
                                $pluginClass = $pluginData['metadata']['integration']['class'];
                                $thisPlugin = new $pluginClass();
                                if (method_exists($thisPlugin, 'checkAccess') && !$thisPlugin->checkAccess($thisReport)) {
                                    continue;
                                }

                            }

                            $integrationPlugins[$pluginId] = array(
                                'id' => $pluginId,
                                'plugindirectory' => $pluginData['plugindirectory'],
                                'displayname' => $pluginData['metadata']['displayname'],
                                'metadata' => $pluginData['metadata'],
                            );
                        }
                    } else {
                        $integrationPlugins[$pluginId] = array(
                            'id' => $pluginId,
                            'plugindirectory' => $pluginData['plugindirectory'],
                            'displayname' => $pluginData['metadata']['displayname'],
                            'metadata' => $pluginData['metadata'],
                        );
                    }
                    break;
            }
        }

        return array(
            'presentation' => $presentationPlugins,
            'visualization' => $visualizationPlugins,
            'integration' => $integrationPlugins,
        );
    }

    public function getEditViewPlugins($thisView)
    {
        $jsIncludes = '';
        $presentationPlugins = array();
        $visualizationPlugins = array();
        $integrationPlugins = array();
        foreach ($this->plugins as $pluginId => $pluginData) {
            if (isset($pluginData['metadata']['includes']['edit'])) {
                $jsIncludes .= "<script type='text/javascript' src='" . $pluginData['plugindirectory'] . '/' . $pluginData['metadata']['includes']['edit'] . "'></script>";
            }

            switch ($pluginData['type']) {
                case 'presentation':
                    $presentationPlugins[$pluginId] = array(
                        'id' => $pluginId,
                        'displayname' => $pluginData['metadata']['displayname'],
                        'panel' => $pluginData['metadata']['pluginpanel'],
                    );
                    break;
                case 'visualization':
                    $visualizationPlugins[$pluginId] = array(
                        'id' => $pluginId,
                        'displayname' => $pluginData['metadata']['displayname'],
                        'panel' => $pluginData['metadata']['pluginpanel'],
                    );
                    break;
                case 'integration':
                    $integrationPlugins[$pluginId] = array(
                        'id' => $pluginId,
                        'panel' => $pluginData['metadata']['includes']['editPanel'],
                        'displayname' => $pluginData['metadata']['displayname'],
                    );
                    break;
            }
        }
        $pluginData = '<script type="text/javascript">';

        if (count($presentationPlugins) > 0) {
            $pluginData .= 'var kreporterPresentationPlugins = \'' . json_encode($presentationPlugins) . '\';';
        }

        if (count($visualizationPlugins) > 0) {
            $pluginData .= 'var kreporterVisualizationPlugins = \'' . json_encode($visualizationPlugins) . '\';';
        }

        if (count($integrationPlugins) > 0) {
            $pluginData .= 'var kreporterIntegrationPlugins = \'' . json_encode($integrationPlugins) . '\';';
        }

        $pluginData .= "</script>";

        $thisView->ss->assign('pluginJS', $jsIncludes);
        $thisView->ss->assign('pluginData', $pluginData);
    }

    public function getPresentationObject($pluginId)
    {
        if (isset($this->plugins[$pluginId])) {
            if (file_exists('custom/modules/KReports/Plugins/Presentation/' . $this->plugins[$pluginId]['directory'] . '/' . $this->plugins[$pluginId]['metadata']['phpinclude'])) {
                require_once 'custom/modules/KReports/Plugins/Presentation/' . $this->plugins[$pluginId]['directory'] . '/' . $this->plugins[$pluginId]['metadata']['phpinclude'];

                // eval($this->plugins[$pluginId]['id'] . 'detailviewdisplay($view);');
                $className = 'kreportpresentation' . $this->plugins[$pluginId]['id'];
                return new $className();

                return true;
            }
            if (file_exists('modules/KReports/Plugins/Presentation/' . $this->plugins[$pluginId]['directory'] . '/' . $this->plugins[$pluginId]['metadata']['phpinclude'])) {
                require_once 'modules/KReports/Plugins/Presentation/' . $this->plugins[$pluginId]['directory'] . '/' . $this->plugins[$pluginId]['metadata']['phpinclude'];

                //eval($this->plugins[$pluginId]['id'] . 'detailviewdisplay($view);');
                $className = 'kreportpresentation' . $this->plugins[$pluginId]['id'];
                return new $className();
            }
        }

        return false;
    }

    public function getVisualizationObject($plugin)
    {
        if (isset($this->plugins[$plugin])) {
            $file = $this->plugins[$plugin]['plugindirectory'] . '/' . $this->plugins[$plugin]['metadata']['visualization']['include'];
            require_once $this->plugins[$plugin]['plugindirectory'] . '/' . $this->plugins[$plugin]['metadata']['visualization']['include'];
            $visualizationClass = $this->plugins[$plugin]['metadata']['visualization']['class'];
            return new $visualizationClass();
        } else {
            return false;
        }

    }

    public function getIntegrationPlugins($thisReport)
    {

        return '';
    }

    public function processPluginAction($pluginId, $pluginAction, $getParams)
    {
        require_once $this->plugins[$pluginId]['plugindirectory'] . '/controller/plugin' . $this->plugins[$pluginId]['id'] . 'controller.php';
        $controllerclass = 'plugin' . $this->plugins[$pluginId]['id'] . 'controller';
        $pluginController = new $controllerclass();
        return $pluginController->$pluginAction($getParams);
    }

}

class KReport extends SugarBean
{

    // Stored fields
    public $id;
    public $date_entered;
    public $date_modified;
    public $assigned_user_id;
    public $modified_user_id;
    public $created_by;
    public $created_by_name;
    public $modified_by_name;
    public $report_module = '';
    public $reportoptions = '';
    public $team_id;
    public $description;
    public $name;
    public $status;
    public $assigned_user_name;
    public $team_name;
    public $table_name = "kreports";
    public $object_name = "KReport";
    public $module_dir = 'KReports';
    public $importable = true;
    // This is used to retrieve related fields from form posts.
    // var $additional_column_fields = Array('assigned_user_name', 'assigned_user_id', 'contact_name', 'contact_phone', 'contact_email', 'parent_name');

    /*
    //what we need to build the where join string
    var $tablePath;
    var $joinSegments;
    var $rootGuid;
    var $fromString;
     */
    public $whereOverride;
    //2010-02-10 add Field name Mapping
    public $fieldNameMap;
    // the query Array
    public $kQueryArray;
    //2011-02-03 for the total values
    public $totalResult = '';
    // 2011-03-29 array for the formula evaluation
    public $formulaArray = '';
    // variable taht allows to turn off the evaluation of SQL Functions
    // needed if we let the Grid do this
    public $evalSQLFunctions = true;
    // varaible to hold the depth of the join tree
    public $maxDepth;
    // var to hold an array of all list fields with index fieldid
    public $listFieldArrayById = array();
    // for the context handling
    public $hasContext = false;
    public $contextFields = array();
    public $contexts = array();

    public function __construct()
    {
        /*
        // dirty hack to rebuild kreporter plugins files since the constructor is called int eh repair process
        if ($_REQUEST['module'] == 'Administration' && $_REQUEST['action'] == 'repair' && empty($_SESSION['kreporterpluginsrebuilt'])) {
        echo '<br>Rebuiling KReporter Plugins<br>';
        $_SESSION['kreporterpluginsrebuilt'] = true;
        }
         */

        // call the partenr constructor
        // Mint (upgrade to SugarCRM 7.8) start
        // parent::SugarBean();
        parent::__construct();
        // Mint (upgrade to SugarCRM 7.8) end
    }

    public function bean_implements($interface)
    {
        switch ($interface) {
            case 'ACL':
                return true;
        }
        return false;
    }

    public function get_summary_text()
    {
        return $this->name;
    }

    public function retrieve($id = -1, $encode = true, $deleted = true)
    {

        parent::retrieve($id, $encode, $deleted);

        if ('' != $this->id) {
            $arrayList = json_decode(html_entity_decode($this->listfields, ENT_QUOTES, 'UTF-8'), true);
            foreach ($arrayList as $listFieldData) {
                $this->listFieldArrayById[$listFieldData['fieldid']] = $listFieldData;
            }

        }

        return $this;
    }

    // Mint start #49656
    //function save($checkNotify) {
    public function save($checkNotify = false)
    {
        // Mint end #49656
        global $sugar_config;

        // just to make sure ... PRO needs this .. if we are not in PRO it does not hurt
        // just to make sure ... PRO needs this .. if we are not in PRO it does not hurt
        if (empty($this->team_id)) {
            $this->team_id = '1';
        } else {
            $this->team_set_id = '';
        }

//        switch ($sugar_config['KReports']['authCheck']) {
        //            case 'KAuthObjects':
        //                $this->korgobjectmain = $_REQUEST['authaccess_id'];
        //                $this->korgobjectmultiple = json_encode(array('primary' => $_REQUEST['authaccess_id'], 'secondary' => array()));
        //                break;
        //        }
        parent::save($checkNotify);

        //MintHCM #107136 start
        // switch ($sugar_config['KReports']['authCheck']) {
        //     case 'SecurityGroups':
        //         $this->db->query("DELETE FROM securitygroups_records WHERE record_id = '" . $this->id . "'");
        //         $this->db->query("INSERT INTO securitygroups_records (id, securitygroup_id, record_id, module, modified_user_id, created_by, deleted) values ('" . create_guid() . "', '" . $_REQUEST['authaccess_id'] . "', '" . $this->id . "', 'KReports', '" . $GLOBALS['current_user']->id . "', '" . $GLOBALS['current_user']->id . "', 0)");
        //         break;
        // }
        //MintHCM #107136 end
    }

    //2013-02-02 added html formattng support to the description if it is in the listview
    public function get_list_view_data()
    {
        $ld = $this->get_list_view_array();
        $ld['DESCRIPTION'] = html_entity_decode($this->description);
        return $ld;
    }

    /*
     * Function to get the enum values for a field
     */

    public function getEnumValues($fieldId)
    {
        global $app_list_strings, $current_language;
        if (!is_array($app_list_strings)) {
            $app_list_strings = return_app_list_strings_language($current_language);
        }

        // fix 2010-10-25 .. enums not found for charts
        // fix 2011-03-07 ... in a union scenario we might have different enums and need to merge them
        /*
        if(isset($this->fieldNameMap[$fieldId]['fields_name_map_entry']['options']))
        {
        return $app_list_strings[$this->fieldNameMap[$fieldId]['fields_name_map_entry']['options']];
        }
        else
        {
        return '';
        }
         */
        // loop over all kquery entries in teh query array and merge Options from the $app_list_strings
        $retArray = array();
        foreach ($this->kQueryArray->queryArray as $unionid => $unionkQuery) {
            if (isset($unionkQuery['kQuery']->fieldNameMap[$fieldId]['fields_name_map_entry']['options'])) {
                if (is_array($app_list_strings[$unionkQuery['kQuery']->fieldNameMap[$fieldId]['fields_name_map_entry']['options']])) {
                    foreach ($app_list_strings[$unionkQuery['kQuery']->fieldNameMap[$fieldId]['fields_name_map_entry']['options']] as $key => $value) {
                        $retArray[$key] = $value;
                    }
                }

            }
        }

        if (count($retArray) > 0) {
            return $retArray;
        } else {
            return '';
        }

    }

    public function fill_in_additional_detail_fields()
    {
        parent::fill_in_additional_detail_fields();
        if ('' != $this->report_module) {
            //$sqlArray = $this->build_sql_string();
            //$this->sql_statement = $sqlArray['select'] . ' ' . $sqlArray['from'] . ' ' . $sqlArray['where'] . ' ' . $sqlArray['groupby'] . ' ' . $sqlArray['orderby'] ;
        }
    }

    /*
     * Function to return the Fielname from a given Path
     */

    public function getFieldNameFromPath($pathName)
    {
        return substr($pathName, strrpos($pathName, "::") + 2, strlen($pathName));
    }

    /*
     * Function to return the Pathname from a given Path
     */

    public function getPathNameFromPath($pathName)
    {
        return substr($pathName, 0, strrpos($pathName, "::"));
    }

    public function get_report_main_sql_query($evalSQLFunctions = true, $additionalFilter = '', $additionalGroupBy = array(), $parameters = array())
    {
        //global $db, $app_list_strings, $beanList, $beanFiles;
        // bugfix add ENT_QUOTES so we get proper translation of also single quotes 2010-25-12
        $arrayWhere = json_decode(html_entity_decode($this->whereconditions, ENT_QUOTES), true);
        $arrayList = json_decode(html_entity_decode($this->listfields, ENT_QUOTES), true);
        $arrayWhereGroups = json_decode(html_entity_decode($this->wheregroups, ENT_QUOTES), true);
        $arrayUnionList = json_decode(html_entity_decode($this->unionlistfields, ENT_QUOTES), true);

        // evaluate report Options and pass them along to the Query Array
        $reportOptions = json_decode(html_entity_decode($this->reportoptions, ENT_QUOTES), true);

        // Mint start #58178
        $paramsArray = [];
        // Mint end #58178
        if (isset($reportOptions['authCheck'])) {
            $paramsArray['authChecklevel'] = $reportOptions['authCheck'];
        }

        if (isset($reportOptions['showDeleted'])) {
            $paramsArray['showDeleted'] = $reportOptions['showDeleted'];
        }

        // pass along the context of the report query for additional filtering of selection criteria
        if (isset($parameters['context'])) {
            $paramsArray['context'] = $parameters['context'];
        }

        if (isset($parameters['parentbean'])) {
            $paramsArray['parentbean'] = $parameters['parentbean'];
        }

        if (isset($parameters['sortseq'])) {
            $paramsArray['sortseq'] = $parameters['sortseq'];
        }

        if (isset($parameters['sortid'])) {
            $paramsArray['sortid'] = $parameters['sortid'];
        }

        if (isset($parameters['exclusiveGrouping'])) {
            $paramsArray['exclusiveGrouping'] = $parameters['exclusiveGrouping'];
        }

        if (isset($parameters['start']) && isset($parameters['limit'])) {
            $paramsArray['start'] = $parameters['start'];
            $paramsArray['limit'] = $parameters['limit'];
        }

        $this->kQueryArray = new KReportQueryArray($this->report_module, $this->whereOverride, $this->union_modules, $evalSQLFunctions, $arrayList, $arrayUnionList, $arrayWhere, $additionalFilter, $arrayWhereGroups, $additionalGroupBy, $paramsArray);
        $sqlString = $this->kQueryArray->build_query_strings();
        $this->fieldNameMap = $this->kQueryArray->fieldNameMap;

        return $sqlString;

        // return array('select' => $this->kQueryArray->selectString, 'from' => $this->kQueryArray->fromString, 'where' => $this->kQueryArray->whereString ,'fields' => '', 'groupby' => $this->kQueryArray->groupbyString, 'having' => $this->kQueryArray->havingString , 'orderby' => $this->kQueryArray->orderbyString);
    }

    /*
     * build the SQL String
     * deprecated will be removed
     */

    public function build_sql_string()
    {
        global $db, $app_list_strings, $beanList, $beanFiles;

        $arrayWhere = json_decode(html_entity_decode($this->whereconditions, ENT_QUOTES, 'UTF-8'), true);
        $arrayList = json_decode(html_entity_decode($this->listfields, ENT_QUOTES, 'UTF-8'), true);
        $arrayWhereGroups = json_decode(html_entity_decode($this->wheregroups, ENT_QUOTES, 'UTF-8'), true);

        $kQuery = new KReportQuery($this->report_module, $this->evalSQLFunctions, $arrayList, $arrayWhere, $arrayWhereGroups);

        $kQuery->build_query_strings();
        $this->fieldNameMap = $kQuery->fieldNameMap;

        return array('select' => $kQuery->selectString, 'from' => $kQuery->fromString, 'where' => $kQuery->whereString, 'fields' => '', 'groupby' => $kQuery->groupbyString, 'orderby' => $kQuery->orderbyString);
    }

    // 2010-12-18 added function for formatting based on FieldType
    public function getFieldTypeById($fieldID)
    {
        if (null == $this->fieldNameMap) {
            $this->get_report_main_sql_query('', true, '');
        }

        return $this->fieldNameMap[$fieldID]['type'];
    }

    public function buildLinks($fieldArray, $excludeFields = array())
    {

        foreach ($fieldArray as $fieldID => $fieldValue) {
            if (isset($this->fieldNameMap[$fieldID]) && $this->fieldNameMap[$fieldID]['islink'] && !in_array($fieldID, $excludeFields)) {
                // swith if we have aunion query
                if (isset($fieldArray['unionid'])) {
                    $fieldValue = '<a href="index.php?module=' . $this->kQueryArray->queryArray[$fieldArray['unionid']]['kQuery']->fieldNameMap[$fieldID]['module'] . '&action=DetailView&record=' . $fieldArray[$this->kQueryArray->queryArray[$fieldArray['unionid']]['kQuery']->fieldNameMap[$fieldID]['tablealias'] . 'id'] . '" target="_new" class="tabDetailViewDFLink">' . $fieldValue . '</a>';
                } else {
                    $fieldValue = '<a href="index.php?module=' . $this->kQueryArray->queryArray['root']['kQuery']->fieldNameMap[$fieldID]['module'] . '&action=DetailView&record=' . $fieldArray[$this->fieldNameMap[$fieldID]['tablealias'] . 'id'] . '" target="_new" class="tabDetailViewDFLink">' . $fieldValue . '</a>';
                }

            }
            $returnArray[$fieldID] = $fieldValue;
        }
        return $returnArray;
    }

    /*
     * function that loops thourgh all fieldids passed in checks if they are links and returns modules and id fields in the record
     * used to build an arra that is passed to the view so the renderer can create links in the frontend
     */

    public function buildLinkArray($fieldArray)
    {
        global $app_list_strings, $timedate;

        $linkArray = array();

        foreach ($fieldArray as $fieldId => $fieldName) {
            if (isset($this->fieldNameMap[$fieldId]) && $this->fieldNameMap[$fieldId]['islink']) {
                $linkFieldArray = array();

                foreach ($this->kQueryArray->queryArray as $unionid => $unionQuery) {
                    $linkFieldArray[$unionid] = array(
                        'module' => $unionQuery['kQuery']->fieldNameMap[$fieldId]['module'],
                        // 2013-08-21 BUG #491 .. check if custom field and trake root path alias
                        'idfield' => ('custom_fields' == $unionQuery['kQuery']->fieldNameMap[$fieldId]['fields_name_map_entry']['source'] ? $unionQuery['kQuery']->fieldNameMap[$fieldId]['pathalias'] : $unionQuery['kQuery']->fieldNameMap[$fieldId]['tablealias']) . 'id',
                    );
                }

                $linkArray[$fieldId] = $linkFieldArray;
            }
        }
        return $linkArray;
    }

    /* widgets removed
    function evaluateWidgets($fieldArray, $excludeFields = array()) {
    global $app_list_strings, $timedate;

    $listFieldArray = json_decode(html_entity_decode($this->listfields, ENT_QUOTES, 'UTF-8'), true);

    foreach ($fieldArray as $fieldID => $fieldValue) {
    if (isset($this->listFieldArrayById [$fieldID] ['widget']) && $this->listFieldArrayById [$fieldID] ['widget'] != '') {
    require_once ('modules/KReports/KReporterWidgets/' . $this->listFieldArrayById [$fieldID] ['widget'] . '.php');
    $widgetClass = new $this->listFieldArrayById[$fieldID]['widget']();
    $fieldValue = $widgetClass->renderField($fieldValue);
    }
    $returnArray [$fieldID] = $fieldValue;
    }
    return $returnArray;
    }
     */

    public function calculateValueOfTotal($fieldArray, &$cumulatedArray = array())
    {
        // set the returnarray
        $returnArray = $fieldArray;

        // this is ugly .. whould bring this to the front
        foreach ($this->kQueryArray->queryArray['root']['kQuery']->listArray as $thisFieldData) {
            if ('' != $thisFieldData['valuetype'] && '-' != $thisFieldData['valuetype'] && isset($this->totalResult[$thisFieldData['fieldid'] . '_total']) && $this->totalResult[$thisFieldData['fieldid'] . '_total'] > 0) {
                $valuetypeArray = explode('OF', $thisFieldData['valuetype']);
                switch ($valuetypeArray[0]) {
                    case 'P':
                        // calculate the value
                        $returnArray[$thisFieldData['fieldid']] = round((double) $returnArray[$thisFieldData['fieldid']] / (double) $this->totalResult[$thisFieldData['fieldid'] . '_total'] * 100, 2);

                        // set the format to float so we interpret this as number
                        $this->fieldNameMap[$thisFieldData['fieldid']]['type'] = 'float';
                        $this->fieldNameMap[$thisFieldData['fieldid']]['format_suffix'] = '%';
                        break;
                    case 'D':
                        // calculate the value
                        $returnArray[$thisFieldData['fieldid']] = round((double) $returnArray[$thisFieldData['fieldid']] - (double) $this->totalResult[$thisFieldData['fieldid'] . '_total'], 2);
                        break;
                    case 'C':
                        if (!empty($cumulatedArray[$thisFieldData['fieldid']])) {
                            $returnArray[$thisFieldData['fieldid']] += $cumulatedArray[$thisFieldData['fieldid']];
                            $cumulatedArray[$thisFieldData['fieldid']] += $fieldArray[$thisFieldData['fieldid']];
                        } else {
                            $cumulatedArray[$thisFieldData['fieldid']] = $fieldArray[$thisFieldData['fieldid']];
                        }

                        break;
                }
            }
        }

        // return the Results
        return $returnArray;
    }

    public function formatFields($fieldArray, $excludeFields = array(), $toPdf = false, $forceUTF8 = false)
    {
        require_once 'modules/Currencies/Currency.php';

        global $app_list_strings, $mod_strings, $timedate;

        // 2012-03-29 memorize the complete fields ... has issues with the currencies
        $completeFieldArray = $fieldArray;

        $thisRenderer = new KReportRenderer($this);

        foreach ($fieldArray as $fieldID => $fieldValue) {
            // get the FieldDetails from the Query
            $fieldDetails = $this->kQueryArray->queryArray['root']['kQuery']->get_listfieldentry_by_fieldid($fieldID);

            if (false !== $fieldDetails && isset($this->fieldNameMap[$fieldID]) && !in_array($fieldID, $excludeFields) && (!isset($fieldDetails['customsqlfunction']) || (isset($fieldDetails['customsqlfunction']) && '' == $fieldDetails['customsqlfunction']))) {

                // 2013-05-18 individual rendering removed - handled by the Renderer Object
                $thisFieldRenderer = 'k' . (!empty($fieldDetails['overridetype']) ? $fieldDetails['overridetype'] : $this->fieldNameMap[$fieldID]['type']) . 'Renderer';
                if (method_exists($thisRenderer, $thisFieldRenderer)) {
                    $fieldValue = $thisRenderer->$thisFieldRenderer($fieldID, $completeFieldArray);
                }
            }

            $returnArray[$fieldID] = $fieldValue;
        }

        return $returnArray;
    }

    /*
     * only render enums to the language depended values - if we do not format
     */

    public function formatEnums($fieldArray, $excludeFields = array())
    {
        require_once 'modules/Currencies/Currency.php';

        global $app_list_strings, $current_language;

        $app_list_strings = return_app_list_strings_language($current_language);

        foreach ($fieldArray as $fieldID => $fieldValue) {
            // get the FieldDetails from the Query
            $fieldDetails = $this->kQueryArray->queryArray['root']['kQuery']->get_listfieldentry_by_fieldid($fieldID);

            if (isset($this->fieldNameMap[$fieldID]) && !in_array($fieldID, $excludeFields) && (!isset($fieldDetails['customsqlfunction']) || (isset($fieldDetails['customsqlfunction']) && '' == $fieldDetails['customsqlfunction']))) {
                // Mint start #58178
                $fieldType = isset($this->fieldNameMap[$fieldID]['kreporttype']) ? $this->fieldNameMap[$fieldID]['kreporttype'] : $this->fieldNameMap[$fieldID]['type'];
                switch ($fieldType) {
                    // Mint end #58178
                    case 'enum':
                    case 'radioenum':
                        //2013-03-15 check if we have a group concat then translate the individual values
                        if (in_array($this->fieldNameMap[$fieldID]['sqlFunction'], array('GROUP_CONCAT', 'GROUP_CONASC', 'GROUP_CONDSC'))) {
                            $valArray = explode(',', $fieldValue);
                            $fieldValue = '';
                            foreach ($valArray as $thisValue) {
                                if ('' != $fieldValue) {
                                    $fieldValue .= ', ';
                                }

                                if (trim($thisValue) != '' && isset($this->kQueryArray->queryArray[(isset($fieldArray['unionid']) ? $fieldArray['unionid'] : 'root')]['kQuery']->fieldNameMap[$fieldID]['fields_name_map_entry']['options'])) {
                                    $fieldValue .= $app_list_strings[$this->kQueryArray->queryArray[(isset($fieldArray['unionid']) ? $fieldArray['unionid'] : 'root')]['kQuery']->fieldNameMap[$fieldID]['fields_name_map_entry']['options']][trim($thisValue)];
                                }

                            }
                        } else {
                           // 2011-03-07 add the orig value for the treeid
                           $returnArray[$fieldID . '_val'] = $fieldValue;
                           // chek if we have a function set
                           // Mint start #58178
                           if (isset($this->fieldNameMap[$fieldID]['fields_name_map_entry']['function']) && is_array($this->fieldNameMap[$fieldID]['fields_name_map_entry']['function'])) {
                               // Mint end #58178
                               $fielName = $this->fieldNameMap[$fieldID]['fields_name_map_entry']['function']['include'];
                               require_once $fielName;
                               $functionName = $this->fieldNameMap[$fieldID]['fields_name_map_entry']['function']['name'];
                               // Mint start #59232 #74399
                               // $fieldValue = $functionName(null, $this->fieldNameMap[$fieldID]['fieldname'], $fieldValue, '');
                                $function_result = formatEnumArray($functionName(null, $this->fieldNameMap[$fieldID]['fieldname'], $fieldValue, 'KReporterOptions'));
                               if (!empty($function_result)) {
                                   foreach ($function_result as $pair) {
                                       if (
                                           isset($pair['value'])
                                           && isset($pair['text'])
                                           && $pair['value'] == $fieldValue
                                       ) {
                                           $fieldValue = $pair['text'];
                                       }
                                   }
                               }
                               // Mint end #59232 #74399
                           } else {
                               // bug 2011-03-07 fields might have different options if in a join
                               //$fieldValue = $app_list_strings[$this->fieldNameMap[$fieldID]['fields_name_map_entry']['options']][$fieldValue];
                               //Mint start #60147
                               //if ( $fieldValue != '' && isset($this->kQueryArray->queryArray [(isset($fieldArray ['unionid']) ? $fieldArray ['unionid'] : 'root')] ['kQuery']->fieldNameMap [$fieldID] ['fields_name_map_entry'] ['options']) )
                               if (isset($this->kQueryArray->queryArray[(isset($fieldArray['unionid']) ? $fieldArray['unionid'] : 'root')]['kQuery']->fieldNameMap[$fieldID]['fields_name_map_entry']['options'])) {
                                   $fieldValue = $app_list_strings[$this->kQueryArray->queryArray[(isset($fieldArray['unionid']) ? $fieldArray['unionid'] : 'root')]['kQuery']->fieldNameMap[$fieldID]['fields_name_map_entry']['options']][$fieldValue];
                               }
                               //Mint end #60147
                           }
                       }

                        // bug 2011-05-25
                        // if value is empty we return the original value
                        if ('' == $fieldValue) {
                            $fieldValue = $returnArray[$fieldID . '_val'];
                        }

                        break;
                    case 'multienum':
                        // do not format if we have a function (Count ... etc ... )
                        if ('' == $this->fieldNameMap[$fieldID]['sqlFunction']) {
                            $fieldArray = preg_split('/\^,\^/', $fieldValue);
                            //bugfix 2010-09-22 if only one value is selected
                            if (is_array($fieldArray) && count($fieldArray) > 1) {
                                $fieldValue = '';
                                foreach ($fieldArray as $thisFieldValue) {
                                    if ('' != $fieldValue) {
                                        $fieldValue .= ', ';
                                    }

                                    //bugfix 2010-09-22 trim the prefix since this is starting and ending with
                                    // bug 2011-03-07 fields might have different options if in a join
                                    //$fieldValue .=     $app_list_strings[$this->fieldNameMap[$fieldID]['fields_name_map_entry']['options']][trim($thisFieldValue, '^')];
                                    $fieldValue .= $app_list_strings[$this->kQueryArray->queryArray[(isset($fieldArray['unionid']) ? $fieldArray['unionid'] : 'root')]['kQuery']->fieldNameMap[$fieldID]['fields_name_map_entry']['options']][trim($thisFieldValue, '^')];
                                }
                            } else {
                                // bug 2011-03-07 fields might have different options if in a join
                                // $fieldValue = $app_list_strings[$this->fieldNameMap[$fieldID]['fields_name_map_entry']['options']][trim($fieldValue, '^')];
                                $fieldValue = $app_list_strings[$this->kQueryArray->queryArray[(isset($fieldArray['unionid']) ? $fieldArray['unionid'] : 'root')]['kQuery']->fieldNameMap[$fieldID]['fields_name_map_entry']['options']][trim($fieldValue, '^')];
                            }
                        }
                        break;
                }
            }

            $returnArray[$fieldID] = $fieldValue;
        }

        return $returnArray;
    }

    /*
     * 2011-12-07 added fuinction to format dates properly adjusting to the users timezone
     */

    public function formatDates($fieldArray, $excludeFields = array())
    {

        global $app_list_strings, $timedate;

        foreach ($fieldArray as $fieldID => $fieldValue) {
            // get the FieldDetails from the Query
            $fieldDetails = $this->kQueryArray->queryArray['root']['kQuery']->get_listfieldentry_by_fieldid($fieldID);

            if (isset($this->fieldNameMap[$fieldID]) && !in_array($fieldID, $excludeFields) && (!isset($fieldDetails['customsqlfunction']) || (isset($fieldDetails['customsqlfunction']) && '' == $fieldDetails['customsqlfunction']))) {
                switch ($this->fieldNameMap[$fieldID]['type']) {
                    case 'date':
                    case 'datetime':
                    case 'datetimecombo':
                        $fieldValue = ('' != $fieldValue ? $timedate->handle_offset($fieldValue, $timedate->get_db_date_time_format(), true) : '');
                        // $fieldValue =  $timedate->handle_offset ( $fieldValue, $timedate->get_db_date_time_format(), true) ;
                        break;
                }
            }

            $returnArray[$fieldID] = $fieldValue;
        }

        return $returnArray;
    }

    /*
     * only render time Fields
     */

    public function formateDateTime($fieldArray, $excludeFields = array())
    {

        global $app_list_strings, $timedate;

        foreach ($fieldArray as $fieldID => $fieldValue) {
            // get the FieldDetails from the Query
            $fieldDetails = $this->kQueryArray->queryArray['root']['kQuery']->get_listfieldentry_by_fieldid($fieldID);

            if (isset($this->fieldNameMap[$fieldID]) && !in_array($fieldID, $excludeFields) && (!isset($fieldDetails['customsqlfunction']) || (isset($fieldDetails['customsqlfunction']) && '' == $fieldDetails['customsqlfunction']))) {
                switch ($this->fieldNameMap[$fieldID]['type']) {

                    case 'datetime':
                    case 'datetimecombo':
                        // 2012-01-31 return empty value if value is emtpy
                        $fieldValue = $timedate->to_display_date_time($fieldValue);
                        break;
                }
            }

            $returnArray[$fieldID] = $fieldValue;
        }

        return $returnArray;
    }

    public function getXtypeRenderer($fieldType, $fieldID = '')
    {
        global $current_user, $mod_strings;

        // check if we have a custom SQL function -- then reset the value .. we do  not know how to format
        $listFieldArray = $this->kQueryArray->queryArray['root']['kQuery']->get_listfieldentry_by_fieldid($fieldID);

        // 2013-05-16 ... bug #480 since we might query for fields that are not in the report
        // those fields are created dynamically in the pivot for the grid ...
        // there the formatter set in the Pivot Parameters is then used
        // if we do not find the field return false
        if (false === $listFieldArray) {
            return false;
        }

        // manage switching of Fieldtypes
        // TODO: this is ugly here but currently required - no better solution available
        if (isset($listFieldArray['sqlfunction']) && 'COUNT' == $listFieldArray['sqlfunction']) {
            $fieldType = 'int';
        }

        if (isset($listFieldArray['customsqlfunction']) && '' != $listFieldArray['customsqlfunction']) {
            $fieldType = '';
        }

        if (isset($listFieldArray['valuetype']) && '-' != $listFieldArray['valuetype'] && '' != $listFieldArray['valuetype'] && substr($listFieldArray['valuetype'], 0, 1) == 'P') {
            $fieldType = 'percentage';
        }

        // 2012-12-30 properly hande ovverride type
        if (!empty($listFieldArray['overridetype']) && "-" != $listFieldArray['overridetype']) {
            $fieldType = $listFieldArray['overridetype'];
        }

        // process thee fieldtypes
        switch ($fieldType) {
            case 'currency':
                return 'kcurrencyRenderer';
            case 'percentage':
                return 'kpercentageRenderer';
            // bug 2011-03-25 format double & float properly
            case 'double':
            case 'float':
            // 2013-03-01 add number
            case 'number':
            //2013-04-06 type decimal
            case 'decimal':
                return 'knumberRenderer';
            case 'int':
                return 'kintRenderer';
            // return ', renderer: function(value){return value;}';
            case 'date':
                return 'kdateRenderer';
            case 'datetime':
            case 'datetimecombo':
                return 'kdatetimeRenderer';
            case 'datetutc':
                return 'kdatetutcRenderer';
            case 'bool':
                return 'kboolRenderer';
            case 'text':
                return 'ktextRenderer';
            default:
                return '';
        }

        // if we end up here we return an empty string
        return '';
    }

    public function getXtypeAlignment($fieldType, $fieldID)
    {

        // check if we have a custom SQL function -- then reset the value .. we do  not know how to format
        $listFieldArray = $this->kQueryArray->queryArray['root']['kQuery']->get_listfieldentry_by_fieldid($fieldID);

        //2013-03-01 maual alignmetn setting rules
        if (!empty($listFieldArray['overridealignment']) && "-" != $listFieldArray['overridealignment']) {
            return $listFieldArray['overridealignment'];
        }

        // manage switching of Fieldtypes
        // TODO: this is ugly here but currently required - no better solution available
        if (isset($listFieldArray['sqlfunction']) && 'COUNT' == $listFieldArray['sqlfunction']) {
            $fieldType = 'int';
        }

        if (isset($listFieldArray['customsqlfunction']) && '' != $listFieldArray['customsqlfunction']) {
            $fieldType = '';
        }

        if (isset($listFieldArray['valuetype']) && '-' != $listFieldArray['valuetype'] && '' != $listFieldArray['valuetype'] && substr($listFieldArray['valuetype'], 0, 1) == 'P') {
            $fieldType = 'percentage';
        }

        // 2012-12-30 properly hande ovverride type
        if (!empty($listFieldArray['overridetype']) && "-" != $listFieldArray['overridetype']) {
            $fieldType = $listFieldArray['overridetype'];
        }

        // process the fieldtypes
        switch ($fieldType) {
            case 'currency':
                return 'right';
            case 'double':
            case 'float':
            case 'int':
            case 'percentage':
            //2013-04-06 type decimal
            case 'decimal':
                return 'center';
            default:
                return 'left';
        }
    }

    public function createCSV($dynamicolsOverride = '')
    {
        global $current_user;

        $header = '';
        $rows = '';

        $reportParams = array('toCSV' => true);
        if ('' != $dynamicolsOverride) {
            $dynamicolsOverride = json_decode(html_entity_decode($dynamicolsOverride), true);
            foreach ($dynamicolsOverride as $thisOverrideKey => $thisOverrideEntry) {
                if (!empty($thisOverrideEntry['sortState'])) {
                    $reportParams['sortseq'] = $thisOverrideEntry['sortState'];
                    $reportParams['sortid'] = $thisOverrideEntry['dataIndex'];
                }
            }
        }

        // Mint start #42078
        $GLOBALS['disable_date_format'] = false;
        // Mint end #42078
        $results = $this->getSelectionResults($reportParams);
        // Mint start #42078
        $GLOBALS['disable_date_format'] = true;
        // Mint end #42078
        //handel the selection parameters for Excel
        $selParam = '';
        $whereSelectionArray = $this->kQueryArray->get_where_array();
        foreach ($whereSelectionArray as $thisArrayEntry) {
            $selParam .= $thisArrayEntry['name'] . '/' . $thisArrayEntry['operator'] . '/' . $thisArrayEntry['value'];
            $selParam .= "\n";
        }

        $selParam .= "\n";

        $arrayList = json_decode(html_entity_decode($this->listfields, ENT_QUOTES, 'UTF-8'), true);

        //see if we have dynamic cols in the Request ...
        $dynamicolsOverrid = array();
        if ('' != $dynamicolsOverride) {
            $dynamicolsOverride = json_decode(html_entity_decode($dynamicolsOverride, ENT_QUOTES, 'UTF-8'), true);
            $overrideMap = array();
            foreach ($dynamicolsOverride as $thisOverrideKey => $thisOverrideEntry) {
                $overrideMap[$thisOverrideEntry['dataIndex']] = $thisOverrideKey;
            }

            //loop over the listfields
            for ($i = 0; $i < count($arrayList); $i++) {
                if (isset($overrideMap[$arrayList[$i]['fieldid']])) {
                    // set the display flag
                    if ('true' == $dynamicolsOverride[$overrideMap[$arrayList[$i]['fieldid']]]['isHidden']) {
                        $arrayList[$i]['display'] = 'no';
                    } else {
                        $arrayList[$i]['display'] = 'yes';
                    }

                    // set the width
                    $arrayList[$i]['width'] = $dynamicolsOverride[$overrideMap[$arrayList[$i]['fieldid']]]['width'];

                    // set the sequence
                    $arrayList[$i]['sequence'] = $dynamicolsOverride[$overrideMap[$arrayList[$i]['fieldid']]]['sequence'];
                }
            }

            // resort the array
            usort($arrayList, 'sortFieldArrayBySequence');
        }

        // Mint start #50666 KREPORTER: Upgrade dla Suite 7.10
        //$fieldArray = '';
        $fieldArray = [];
        // Mint end #50666 KREPORTER: Upgrade dla Suite 7.10
        $fieldIdArray = array();
        foreach ($arrayList as $thisList) {
            if ('yes' == $thisList['display']) {
                $fieldArray[] = array('label' => utf8_decode($thisList['name']), 'width' => (isset($thisList['width']) && '' != $thisList['width'] && '0' != $thisList['width']) ? $thisList['width'] : '100', 'display' => $thisList['display']);
                $fieldIdArray[] = $thisList['fieldid'];
            }
        }

        if (count($results) > 0) {
            foreach ($results as $record) {
                $getHeader = ('' == $header) ? true : false;
                foreach ($record as $key => $value) {

                    //if($key != 'sugarRecordId')
                    $arrayIndex = array_search($key, $fieldIdArray);
                    if (array_search($key, $fieldIdArray) !== false) {
                        if ($getHeader) {
                            foreach ($arrayList as $fieldId => $fieldArray) {
                                if ($fieldArray['fieldid'] == $key) {
                                    $header .= iconv("UTF-8", $current_user->getPreference('default_export_charset'), $fieldArray['name']) . $current_user->getPreference('export_delimiter');
                                }
                            }

                        }

                        $rows .= '"' . iconv("UTF-8", $current_user->getPreference('default_export_charset') . '//IGNORE', preg_replace(array('/"/'), array('""'), strip_tags(html_entity_decode($value, ENT_QUOTES)))) . '"' . $current_user->getPreference(('export_delimiter'));
                    }
                }
                if ($getHeader) {
                    $header .= "\n";
                }

                $rows .= "\n";
            }
        }

        return $selParam . $header . $rows;
    }

    // Mint eksport do Excela
    public function createArray($dynamicolsOverride = '')
    {
        global $current_user;

        $rows = array();

        $reportParams = array('toCSV' => true);
        if ('' != $dynamicolsOverride) {
            $dynamicolsOverride = json_decode(html_entity_decode($dynamicolsOverride), true);
            foreach ($dynamicolsOverride as $thisOverrideKey => $thisOverrideEntry) {
                if (!empty($thisOverrideEntry['sortState'])) {
                    $reportParams['sortseq'] = $thisOverrideEntry['sortState'];
                    $reportParams['sortid'] = $thisOverrideEntry['dataIndex'];
                }
            }
        }

        $GLOBALS['disable_date_format'] = false;
        $results = $this->getSelectionResults($reportParams);
        $GLOBALS['disable_date_format'] = true;

        //handel the selection parameters for Excel
        $selParam = '';
        $whereSelectionArray = $this->kQueryArray->get_where_array();
        foreach ($whereSelectionArray as $thisArrayEntry) {
            $selParam .= $thisArrayEntry['name'] . '/' . $thisArrayEntry['operator'] . '/' . $thisArrayEntry['value'];
            $selParam .= "\n";
        }

        $selParam .= "\n";

        $arrayList = json_decode(html_entity_decode($this->listfields, ENT_QUOTES, 'UTF-8'), true);

        //see if we have dynamic cols in the Request ...
        $dynamicolsOverrid = array();
        if ('' != $dynamicolsOverride) {
            $dynamicolsOverride = json_decode(html_entity_decode($dynamicolsOverride, ENT_QUOTES, 'UTF-8'), true);
            $overrideMap = array();
            foreach ($dynamicolsOverride as $thisOverrideKey => $thisOverrideEntry) {
                $overrideMap[$thisOverrideEntry['dataIndex']] = $thisOverrideKey;
            }

            //loop over the listfields
            for ($i = 0; $i < count($arrayList); $i++) {
                if (isset($overrideMap[$arrayList[$i]['fieldid']])) {
                    // set the display flag
                    if ('true' == $dynamicolsOverride[$overrideMap[$arrayList[$i]['fieldid']]]['isHidden']) {
                        $arrayList[$i]['display'] = 'no';
                    } else {
                        $arrayList[$i]['display'] = 'yes';
                    }

                    // set the width
                    $arrayList[$i]['width'] = $dynamicolsOverride[$overrideMap[$arrayList[$i]['fieldid']]]['width'];

                    // set the sequence
                    $arrayList[$i]['sequence'] = $dynamicolsOverride[$overrideMap[$arrayList[$i]['fieldid']]]['sequence'];
                }
            }

            // resort the array
            usort($arrayList, 'sortFieldArrayBySequence');
        }

        $fieldArray = [];
        $fieldIdArray = array();
        foreach ($arrayList as $thisList) {
            if ('yes' == $thisList['display']) {
                $fieldArray[] = array('label' => utf8_decode($thisList['name']), 'width' => (isset($thisList['width']) && '' != $thisList['width'] && '0' != $thisList['width']) ? $thisList['width'] : '100', 'display' => $thisList['display']);
                $fieldIdArray[] = $thisList['fieldid'];
            }
        }

        $row_id = 0;
        foreach ($arrayList as $fieldId => $fieldArray) {
            $rows[$row_id][] = iconv("UTF-8", $current_user->getPreference('default_export_charset'), $fieldArray['name']);
        }
        $row_id++;

        if (count($results) > 0) {
            foreach ($results as $record) {

                foreach ($arrayList as $fieldId => $fieldArray) {
// Mint #43877, #58447 START
                    //               $value = $record[$fieldArray['fieldid']];
                    $value = iconv("UTF-8", $current_user->getPreference('default_export_charset') . '//IGNORE', strip_tags(html_entity_decode($record[$fieldArray['fieldid']], ENT_QUOTES)));
// Mint #43877, #58447 END
                    $rows[$row_id][] = $value;
                }

                $row_id++;
            }
        }
        return $rows;
    }

    // Mint end
    public function createTargeList($listname, $campaign_id = '')
    {
        global $current_user, $db;

        $results = $this->getSelectionResults(array());

        if (count($results > 0)) {
            require_once 'modules/ProspectLists/ProspectList.php';
            $newProspectList = new ProspectList();

            $newProspectList->name = $listname;
            $newProspectList->list_type = 'default';
            $newProspectList->assigned_user_id = $current_user->id;
            $newProspectList->save();

            // add to campaign
            if ('' != $campaign_id) {
                require_once 'modules/Campaigns/Campaign.php';
                $thisCampaign = new Campaign();
                $thisCampaign->retrieve($campaign_id);
                $thisCampaign->load_relationships();
                $campaignLinkedFields = $thisCampaign->get_linked_fields();
                foreach ($campaignLinkedFields as $linkedField => $linkedFieldData) {
                    if ('ProspectList' == $thisCampaign->$linkedField->_relationship->rhs_module) {
                        $thisCampaign->$linkedField->add($newProspectList->id);
                    }

                }
            }

            // fill with results:
            $newProspectList->load_relationships();

            $linkedFields = $newProspectList->get_linked_fields();

            foreach ($linkedFields as $linkedField => $linkedFieldData) {
                if ($newProspectList->$linkedField->_relationship->rhs_module == $this->report_module) {
                    foreach ($results as $thisRecord) {
                        $newProspectList->$linkedField->add($thisRecord['sugarRecordId']);
                    }
                } elseif ('Campaigns' == $newProspectList->$linkedField->_relationship->rhs_module and '' != $campaign_id) {
                    $newProspectList->$linkedField->add($campaign_id);
                }
            }
        }
    }

    /*
     * function to fetch Selection Results based on switch of Context
     */

    public function getContextselectionResult($parameters, $getcount = false, $additionalFilter = '', $additionalGroupBy = array())
    {
        $query = '';

        if (!empty($GLOBALS['sugar_config']['k_dbconfig_clone'])) {
            $db = new $GLOBALS['sugar_config']['k_dbconfig_clone']['db_manager']();

            // switch the db
            $db->connect($GLOBALS['sugar_config']['k_dbconfig_clone']);
        } else {
            global $db;
        }

        // retun an empty array
        $retArray = array();

        // process the list
        /*
        if (isset($parameters ['grouping']) && $parameters ['grouping'] == 'off') {
        $query = $this->get_report_main_sql_query(false, $additionalFilter, $additionalGroupBy, $parameters);

        //$query = $sqlArray['select'] . ' ' . $sqlArray['from'] . ' ' . $sqlArray['where'] . ' ' . $sqlArray['having'] . ' ' . $sqlArray['orderby'];
        } else {
        $query = $this->get_report_main_sql_query(true, $additionalFilter, $additionalGroupBy, $parameters);

        //$query = $sqlArray['select'] . ' ' . $sqlArray['from'] . ' ' . $sqlArray['where'] . ' ' . $sqlArray['groupby'] . ' ' . $sqlArray['having'] . ' ' . $sqlArray['orderby'];
        }
         */
        // cehck if we only need the count than we shortcut here
        if ($getcount) {
            unset($parameters['start']);
            unset($parameters['limit']);
            $query = $this->get_report_main_sql_query(false, $additionalFilter, $additionalGroupBy, $parameters);
            // limit the query if a limit is set ...
            // 2012-10-28 .. handle limit
            //

            if ('' != $this->selectionlimit) {
                $isPercentage = false;
                $selectionLimit = trim($this->selectionlimit);
                if (strpos($selectionLimit, 'p') > 0) {
                    $isPercentage = true;
                    $selectionLimit = trim(str_replace('p', '', $this->selectionlimit));
                    $totalRows = $db->getRowCount($queryResults = $db->query($query));
                    $selectionLimit = round($totalRows / 100 * $selectionLimit, 0);
                }
                if ('' != $this->kQueryArray->countSelectString) {
                    $queryResults = $db->fetchByAssoc($db->limitquery($this->kQueryArray->countSelectString, 0, $selectionLimit));
                    echo $this->kQueryArray->countSelectString;
                    return $queryResults['totalCount'];
                } else {
                    return $db->getRowCount($db->limitquery($query, 0, $selectionLimit));
                }
            } else {
                if ('' != $this->kQueryArray->countSelectString) {
                    $queryResults = $db->fetchByAssoc($db->query($this->kQueryArray->countSelectString));
                    return $queryResults['totalCount'];
                } else {
                    return $db->getRowCount($queryResults = $db->query($query));
                }
            }
        }

        // process seleciton limit and run the main query
        if ('' != $this->selectionlimit) {
            $isPercentage = false;
            $selectionLimit = trim($this->selectionlimit);
            // 2013-02-26 check for p and not %
            if (strpos($selectionLimit, 'p') > 0) {
                $isPercentage = true;
                $selectionLimit = trim(str_replace('p', '', $this->selectionlimit));

                // 2013-02-26 if we do not yet have a query ... get it
                if ('' == $query) {
                    $countParameters = $parameters;
                    unset($countParameters['start']);
                    unset($countParameters['limit']);
                    $query = $this->get_report_main_sql_query(false, $additionalFilter, $additionalGroupBy, $countParameters);
                }

                $totalRows = $db->getRowCount($queryResults = $db->query($query));
                $selectionLimit = round($totalRows / 100 * $selectionLimit, 0);
            } //2013-02-26 ... r for records indicator was hurting .. cut off
            else {
                $selectionLimit = trim(str_replace('r', '', $this->selectionlimit));
            }

            if (isset($parameters['limit']) && '' != $parameters['limit'] && isset($parameters['start'])) {
                if ($parameters['limit'] < $selectionLimit) {
                    $selectionLimit = $parameters['limit'];
                }
            }

            //$queryResults = $db->limitquery($query, $parameters ['start'], $selectionLimit);
            // 2014-08-18 bug #521 if stzart is not set set start to zero for selection from Charts
            if (empty($parameters['start'])) {
                $parameters['start'] = 0;
            }

            $parameters['limit'] = $selectionLimit;
        } else {
            if (isset($parameters['limit']) && '' != $parameters['limit'] && isset($parameters['start'])) {
                // $queryResults = $db->limitquery($query, $parameters ['start'], $parameters ['limit']);
            } else {
                unset($parameters['start']);
                unset($parameters['limit']);
                //$queryResults = $db->query($query);
            }
        }

        $query = $this->get_report_main_sql_query(true, $additionalFilter, $additionalGroupBy, $parameters);
        $queryResults = $db->query($query);

        // Mint start #58178
        if (isset($_REQUEST['kreportdebugquery']) && true == $_REQUEST['kreportdebugquery']) {
            echo $query;
        }

        // Mint end #58178
        //
        // 2011-02-03 added for percentage calculation of total
        //see if we need to query the totals
        if ('' != $this->kQueryArray->totalSelectString) {
            $this->totalResult = $db->fetchByAssoc($db->query($this->kQueryArray->totalSelectString));
        }

        // preprocess Formulas
        $this->preProcessFormulas();

        // 2013-02-12 for cumulated fields
        $cumulatedArray = array();

        // get the restul rows and process them
        while ($queryRow = $db->fetchByAssoc($queryResults)) {
            // process formulas
            $this->processFormulas($queryRow);

            // just the basic Row
            $formattedRow = $queryRow;

            // calculate the percentage or dealtavalues
            if ('' != $this->totalResult) {
                $formattedRow = $this->calculateValueOfTotal($formattedRow, $cumulatedArray);
            }

            // format the Fields
            if (!isset($parameters['noFormat']) || (isset($parameters['noFormat']) && !$parameters['noFormat'])) {
                $formattedRow = $this->formatFields($formattedRow, isset($parameters['dontFormat']) ? $parameters['dontFormat'] : array(), isset($parameters['toPDF']) || isset($parameters['toCSV']) ? true : false, isset($parameters['toPDF']) ? true : false);
            } else {
                // bug 2011-03-07 ... for charts enums should not be translated - Chart is handling this
                if (!isset($parameters['noEnumTranslation']) || (isset($parameters['noEnumTranslation']) && !$parameters['noEnumTranslation'])) {
                    $formattedRow = $this->formatEnums($formattedRow, isset($parameters['dontFormat']) ? $parameters['dontFormat'] : array());
                }

                // 2011-12-07 translate times to local time per usersetting
                // $formattedRow = $this->formatDates($formattedRow, isset($parameters ['dontFormat']) ? $parameters ['dontFormat'] : array());
            }

            //build the links
            //bugfix 2011-05-18 for links in export .. changed || to &&
            //     if ((!isset($parameters ['toPDF']) || (isset($parameters ['toPDF']) && !$parameters ['toPDF'])) && (!isset($parameters ['toCSV']) || (isset($parameters ['toCSV']) && !$parameters ['noLinks'])) && (!isset($parameters ['noLinks']) || (isset($parameters ['noLinks']) && !$parameters ['noLinks'])))
            //         $formattedRow = $this->buildLinks($formattedRow, isset($parameters ['dontFormat']) ? $parameters ['dontFormat'] : array() );
            // widget formatting
            //2013-09-07 Widgets only if explicitly set in Sugar Config Bug #497

            /* widgets removed
            if ($GLOBALS['sugarconfig']['evaluateWidgets'] == true)
            $formattedRow = $this->evaluateWidgets($formattedRow);
             */

            // return the formatted row
            $retArray[] = $formattedRow;
        }
        //$db->connect();

        return $retArray;
    }

    /*
     * Parameters:
     *     - grouping: set to off to not have grouping
     *  - start: start from record
     *  - limit: limit to n records from start
     *  - addSQLFunction: array with fields and custom function that should be used to
     *    add/override the basic sql functions
     *  - noFormat: no formatting done
     *  - toPDF: formatting is doen but no links are built (not useful in PDF)
     *  - dontFormat: array with fieldids that should not be formatted when returing
     *    e.g. nbeeded for geocoding
     */

    public function getSelectionResults($parameters, $snapshotid = '0', $getcount = false, $additionalFilter = '', $additionalGroupBy = array())
    {

        // parameter overrid listtype used for Charts
        global $db;

        // set a configurable time limit ...
        //set_time_limit(10);
        // return an empty array if we have nothing else
        $retArray = array();

        // get the sql array or retrieve from snapshot if set
        if ('0' == $snapshotid || 'current' == $snapshotid) {
            $retArray = $this->getContextselectionResult($parameters, $getcount, $additionalFilter, $additionalGroupBy);
        } else {
            $query = 'SELECT data FROM kreportsnapshotsdata WHERE snapshot_id = \'' . $snapshotid . '\'';

            // check if we only need the count than we shortcut here
            if ($getcount) {
                return $this->db->getRowCount($db->query($query));
            }

            // limit the query if requested
            if (isset($parameters['start']) && '' != $parameters['start']) {
                $query .= ' AND record_id >= ' . $parameters['start'];
            }

            if (isset($parameters['limit']) && '' != $parameters['limit']) {
                $query .= ' AND record_id < ' . ($parameters['start'] + $parameters['limit']);
            }

            $query .= ' ORDER BY record_id ASC';

            $snapshotResults = $db->query($query);

            // still need to process this to have all teh setting for theformat
            $sqlArray = $this->get_report_main_sql_query('', true, '');

            while ($snapshotRecordData = $db->fetchByAssoc($snapshotResults)) {

                // just the basic Row
                // 2012-12-05 ... we might find inks in the returned data not properly escaped. Fixed that so the json is not broken
                // $formattedRow = json_decode_kinamu(html_entity_decode($snapshotRecordData ['data'], ENT_QUOTES, 'UTF-8'));
                $jsonstring = html_entity_decode($snapshotRecordData['data']);

                preg_match_all("/\<a(.*)a\>/U", $jsonstring, $matches);
                foreach ($matches[0] as $key => $value) {
                    $jsonstring = str_replace($value, urlencode($value), $jsonstring);
                }

                $formattedRow = json_decode_kinamu($jsonstring);

                // format the Fields
                if (!isset($parameters['noFormat']) || (isset($parameters['noFormat']) && !$parameters['noFormat'])) {
                    $formattedRow = $this->formatFields($formattedRow, isset($parameters['dontFormat']) ? $parameters['dontFormat'] : array());
                }

                //build the links unless we can conserve the ids with the snapshot this will not work ...
                //if(!isset($parameters['toPDF']) || (isset($parameters['toPDF']) && !$parameters['toPDF']))
                //    $formattedRow = $this->buildLinks($formattedRow, isset($parameters['dontFormat']) ? $parameters['dontFormat'] : array());
                // return the formatted row
                $retArray[] = $formattedRow;
            }
        }
        return $retArray;
    }

    /*
     * evaluate if we have listfields with a context
     */

    public function reportHasContextFields()
    {
        $hasContext = false;

        $arrayList = json_decode_kinamu(html_entity_decode($this->listfields, ENT_QUOTES));
        foreach ($arrayList as $thisListEntry) {
            if ('' != $thisListEntry['context']) {
                // make sure we set that we have context
                $hasContext = true;

                // set the field context and the context settings
                $this->contextFields[$thisListEntry['fieldid']] = $thisListEntry['context'];
                // sett the context we found ... replacing spaces as we handle it later on
                $this->contexts[preg_replace('/ /', '', $thisListEntry['context'])] = preg_replace('/ /', '', $thisListEntry['context']);
            }
        }

        return $hasContext;
    }

    /*
     * Preprocessor for Formulas
     */

    public function preProcessFormulas($arrayName = 'row')
    {
        $arrayList = json_decode_kinamu(html_entity_decode($this->listfields, ENT_QUOTES));

        $logicalNameToIdMap = array();

        // map the fields to ids
        foreach ($arrayList as $thisListEntry) {
            if (isset($thisListEntry['assigntovalue']) && '' != $thisListEntry['assigntovalue']) {
                $logicalNameToIdMap[$thisListEntry['assigntovalue']] = $thisListEntry['fieldid'];
            }

        }

        $sequencedFormulas = array();
        $unsequencedFormulas = array();

        // get the formulas
        foreach ($arrayList as $thisListEntry) {
            if (isset($thisListEntry['formulavalue']) && '' != $thisListEntry['formulavalue']) {
                // parse the fieldids into the formula
                //2012-09-18 base64 encode the Formula so we can have funny characters and not break the json encoding
                //2012-10-02 legacy handling checking if the string is a valid base64 string
                //2013-01-22 changed to rawurldecode
                //$formulaRaw = urldecode(base64_decode($thisListEntry ['formulavalue'], true));
                $formulaRaw = rawurldecode(base64_decode($thisListEntry['formulavalue'], true));

                // if the value is not base 64
                if (!$formulaRaw) {
                    $formulaRaw = $thisListEntry['formulavalue'];
                }

                foreach ($logicalNameToIdMap as $valuekey => $fieldid) {
                    $formulaRaw = preg_replace('/\{' . $valuekey . '\}/', '\$' . $arrayName . '[\'' . $fieldid . '\']', $formulaRaw);
                }

                // add the target field id
                $formulaRaw = '$' . $arrayName . '[\'' . $thisListEntry['fieldid'] . '\'] = ' . $formulaRaw;

                // make sure all expressions are matched
                if (preg_match('/\{/', $formulaRaw) == 0 && preg_match('/\}/', $formulaRaw) == 0) {
                    if (isset($thisListEntry['formulasequence']) && '' != $thisListEntry['formulasequence']) {
                        $sequencedFormulas[$thisListEntry['formulasequence']] = $formulaRaw;
                    } else {
                        $unsequencedFormulas[] = $formulaRaw;
                    }

                }
            }
        }

        // sort and merge the array
        ksort($sequencedFormulas);
        $this->formulaArray = array_merge($sequencedFormulas, $unsequencedFormulas);
    }

    /*
     * process the variious functions for a row
     */

    public function processFormulas(&$row)
    {

        if (is_array($this->formulaArray)) {
            foreach ($this->formulaArray as $sequence => $formula) {
                //2013-03-06 suppress error messages
                @eval($formula . ';');
            }
        }
    }

    public function takeSnapshot()
    {
        global $db;

        $snapshotID = create_guid();

        // go get the results
        $results = $this->getSelectionResults(array('toPDF' => true, 'noFormat' => true));

        $i = 0;
        foreach ($results as $resultsrow) {
            $query = 'INSERT INTO kreportsnapshotsdata SET record_id=\'' . $i . '\', snapshot_id = \'' . $snapshotID . '\', data=\'' . json_encode_kinamu($resultsrow) . '\'';
            $db->query($query);
            $i++;
        }

        // create the snapshot record
        $query = 'INSERT INTO kreportsnapshots SET id=\'' . $snapshotID . '\', snapshotdate =\'' . gmdate('Y-m-d H:i:s') . '\', report_id=\'' . $this->id . '\'';
        $db->query($query);
    }

    public function getSnapshots($withoutActual = false)
    {
        // 2012-11-21 change so a label can be used
        global $mod_strings;
        $mod_strings = return_module_language($_SESSION['authenticated_user_language'], 'KReports');

        $retArray = array();

        $query = 'SELECT id, snapshotdate FROM kreportsnapshots WHERE report_id = \'' . $this->id . '\' ORDER BY snapshotdate DESC';

        $snapShotsResults = $this->db->query($query);

        // 2012-11-21 change so a label can be used
        if ('true' == $withoutActual) {
            $retArray[] = array('snapshot' => '0', 'description' => $mod_strings['LBL_CURRENT_SNAPSHOT']);
        }

        while ($thisSnapshot = $this->db->fetchByAssoc($snapShotsResults)) {
            $retArray[] = array('snapshot' => $thisSnapshot['id'], 'description' => $thisSnapshot['snapshotdate']);
        }
        return $retArray;
    }

    public function deleteSnapshot($snapshotId)
    {
        $this->db->query("DELETE FROM kreportsnapshotsdata WHERE snapshot_id = '$snapshotId'");
        $this->db->query("DELETE FROM kreportsnapshots WHERE id = '$snapshotId'");
    }

    public function getListFields()
    {

        // anlyze all the pathes we have
        //$this->build_path();
        // build the from clause and all join segments
        //$this->build_joinsegments();

        $arrayList = json_decode_kinamu(html_entity_decode($this->listfields, ENT_QUOTES, 'UTF-8'));

        $retArray[] = array('fieldid' => '-', 'fieldname' => '-');

        if (is_array($arrayList)) {
            foreach ($arrayList as $thisList) {
                //$pathName = $this->getPathNameFromPath($thisList['path']);
                //$fieldName = explode(':', $this->getFieldNameFromPath($thisList['path']));
                //if($this->joinSegments[$pathName]['object']->field_name_map[$fieldname[1]]->type == 'currency')
                $retArray[] = array('fieldid' => $thisList['fieldid'], 'fieldname' => $thisList['name']);
            }
        } else {
            $retArray = '';
        }

        return $retArray;
    }

    public function getListFieldsArray()
    {
        $fieldArray = json_decode_kinamu(html_entity_decode($this->listfields, ENT_QUOTES, 'UTF-8'));

        foreach ($fieldArray as $fieldCount => $fieldData) {
            $returnArray[$fieldData['fieldid']] = $fieldData;
        }

        return $returnArray;
    }

    /*
    function getGroupLevelId($groupLevel){
    $arrayList =  json_decode_kinamu( html_entity_decode_utf8($this->listfields));

    if(is_array($arrayList))
    {
    foreach($arrayList as $thisList)
    {
    //manage the damned primary clause
    if($thisList['groupby'] == 'primary') $thisList['groupby'] = '1';

    if($thisList['groupby'] == $groupLevel)
    return     $thisList['fieldid'];
    }

    }

    // not an array or not found
    return  '';

    }

    function getMaxGroupLevel(){
    $arrayList =  json_decode_kinamu( html_entity_decode_utf8($this->listfields));

    $maxGroupLevel = '';

    if(is_array($arrayList))
    {
    foreach($arrayList as $thisList)
    {
    //manage the damned primary clause
    if($thisList['groupby'] == 'primary') $thisList['groupby'] = '1';

    if($thisList['groupby'] != 'no' && $thisList['groupby'] != 'yes' && $thisList['groupby'] > $maxGroupLevel )
    $maxGroupLevel = $thisList['groupby'];
    }
    }

    // not an array or not found
    return  $maxGroupLevel;

    }
     */

    // for the GeoCoding
    public function massGeoCode()
    {
        global $app_list_strings, $mod_strings, $beanList, $beanFiles;

        require_once 'modules/KReports/BingMaps/BingMaps.php';

        // flag to memorize if we hjave different beans for longitude and latiitude
        // not sure when this would happen buit it could happen
        $longlatDiff = false;

        // get the map details for the report
        $mapDetails = json_decode(html_entity_decode($this->mapoptions, ENT_QUOTES, 'UTF-8'));

        $serverName = dirname($_SERVER['HTTP_HOST'] . $_SERVER['SCRIPT_NAME']);

        // get the report results
        $results = $this->getSelectionResults();

        // get the ids for longitude and latitude
        $long_bean_id = $this->kQueryArray->queryArray['root']['kQuery']->joinSegments[$this->kQueryArray->fieldNameMap[$mapDetails->longitude]['path']]['alias'];
        $lat_bean_id = $this->kQueryArray->queryArray['root']['kQuery']->joinSegments[$this->kQueryArray->fieldNameMap[$mapDetails->latitude]['path']]['alias'];

        // get the beans
        $long_bean = $this->kQueryArray->queryArray['root']['kQuery']->joinSegments[$this->kQueryArray->fieldNameMap[$mapDetails->longitude]['path']]['object'];
        if ($long_bean_id != $lat_bean_id) {
            $longlatDiff = true;
            $lat_bean = $this->kQueryArray->queryArray['root']['kQuery']->joinSegments[$this->kQueryArray->fieldNameMap[$mapDetails->latitude]['path']]['object'];
        }

        if (count($results) > 0) {

            $mapService = new kReportBingMaps();
            require_once 'modules/Accounts/Account.php';

            foreach ($results as $thisResult) {
                if (('' == $thisResult[$mapDetails->latitude] || null == $thisResult[$mapDetails->latitude] || '0,00' == $thisResult[$mapDetails->latitude]) || ('' == $thisResult[$mapDetails->longitude] || null == $thisResult[$mapDetails->longitude] || '0,00' == $thisResult[$mapDetails->longitude])) {

                    //$query = $thisResult[$mapDetails->geocodeStreet] . ', ' .  $thisResult[$mapDetails->geocodePostalcode] . ' ' .  $thisResult[$mapDetails->geocodeCity] . ' ' .  $thisResult[$mapDetails->geocodeCountry];
                    $addressArray = array('AddressLine' => $thisResult[$mapDetails->geocodeStreet], 'PostalCode' => $thisResult[$mapDetails->geocodePostalcode], 'Locality' => $thisResult[$mapDetails->geocodeCity], 'CountryRegion' => $thisResult[$mapDetails->geocodeCountry]);
                    $geoCodeResult = $mapService->geocode($addressArray);

                    // update object
                    $long_bean->retrieve($thisResult[$long_bean_id . 'id']);
                    $long_bean->{$this->kQueryArray->fieldNameMap[$mapDetails->longitude][fieldname]} = $geoCodeResult['longitude'];

                    //2010-12-6 format numbers after mass geocode
                    $long_bean->format_field($long_bean->field_defs[$this->kQueryArray->fieldNameMap[$mapDetails->longitude][fieldname]]);

                    // see if we have different beans
                    // should be the exceptionbut we never know
                    if (!$longlatDiff) {
                        $long_bean->{$this->kQueryArray->fieldNameMap[$mapDetails->latitude][fieldname]} = $geoCodeResult['latitude'];

                        //2010-12-6 format numbers after mass geocode
                        $long_bean->format_field($long_bean->field_defs[$this->kQueryArray->fieldNameMap[$mapDetails->latitude][fieldname]]);
                    } else {
                        $lat_bean->retrieve($thisResult[$lat_bean_id . 'id']);
                        $lat_bean->{$this->kQueryArray->fieldNameMap[$mapDetails->latitude][fieldname]} = $geoCodeResult['latitude'];

                        //2010-12-6 format numbers after mass geocode
                        $lat_bean->format_field($lat_bean->field_defs[$this->kQueryArray->fieldNameMap[$mapDetails->latitude][fieldname]]);

                        $lat_bean->save();
                    }

                    $long_bean->save();
                }
            }
        }
    }

    public function getGeoCodes()
    {
        global $app_list_strings, $mod_strings;

        $mapDetails = json_decode(html_entity_decode($this->mapoptions, ENT_QUOTES, 'UTF-8'));
        // $jsonerror = json_last_error();
        //build an array with the field value and the image name if we have entries set
        if ('' != $mapDetails->imageMap) {
            $imageMapArray = json_decode($mapDetails->imageMap, true);
            foreach ($imageMapArray as $imageMapentry) {
                $imageMapRecArray[$imageMapentry['value']] = $imageMapentry['image'];
            }

        }

        // an empty array to return
        $returnArray = array();

        // get the report results
        $results = $this->getSelectionResults(array('dontFormat' => array($mapDetails->longitude, $mapDetails->latitude)));

        $categoryArray = array();
        $categoryCount = 1;

        $mapBounds = array('topLeft' => array('x' => 0, 'y' => 0), 'bottomRight' => array('x' => 0, 'y' => 0));

        if (count($results > 0)) {
            foreach ($results as $thisRecord) {
                //see if we have a category

                if (isset($mapDetails->typeimages) && 'true' == $mapDetails->typeimages && isset($thisRecord[$mapDetails->type]) && '' != $thisRecord[$mapDetails->type]) {
                    //    if (isset ( $mapDetails->type ) && $mapDetails->type != '' && isset ( $thisRecord [$mapDetails->type] ) && $thisRecord [$mapDetails->type] != '') {
                    if (!isset($categoryArray[$thisRecord[$mapDetails->type]])) {
                        $categoryArray[$thisRecord[$mapDetails->type]] = $categoryCount;
                        $categoryCount++;
                    }
                    $returnArray['data'][] = array('id' => $thisRecord['sugarRecordId'], 'geox' => $thisRecord[$mapDetails->longitude], 'geoy' => $thisRecord[$mapDetails->latitude], 'category_id' => (string) $categoryArray[$thisRecord[$mapDetails->type]], 'category' => $thisRecord[$mapDetails->type], 'image' => $imageMapRecArray[$thisRecord[$mapDetails->type]], 'line1' => /* $thisRecord[$mapDetails->longitude] . '/' . $thisRecord[$mapDetails->latitude] . '<br>' . */
                        $thisRecord[$mapDetails->line1] . '<br>' . $thisRecord[$mapDetails->line2] . '<br>' . $thisRecord[$mapDetails->line3] . '<br>' . $thisRecord[$mapDetails->line4] . '<br>');
                } else {
                    // $elementRecord[] = $thisRecord['sugarRecordId'];
                    $returnArray['data'][] = array('id' => $thisRecord['sugarRecordId'], 'geox' => $thisRecord[$mapDetails->longitude], 'geoy' => $thisRecord[$mapDetails->latitude], 'category' => '', 'image' => '', 'line1' => /* $thisRecord[$mapDetails->longitude] . '/' . $thisRecord[$mapDetails->latitude] . '<br>' . */
                        $thisRecord[$mapDetails->line1] . '<br>' . $thisRecord[$mapDetails->line2] . '<br>' . $thisRecord[$mapDetails->line3] . '<br>' . $thisRecord[$mapDetails->line4] . '<br>');
                }

                // set bounds
                if (floatval($thisRecord[$mapDetails->longitude]) != 0 && floatval($thisRecord[$mapDetails->latitude]) != 0) {
                    if (0 == $mapBounds['topLeft']['x'] || floatval($thisRecord[$mapDetails->longitude]) < floatval($mapBounds['topLeft']['x'])) {
                        $mapBounds['topLeft']['x'] = floatval($thisRecord[$mapDetails->longitude]);
                    }

                    if (0 == $mapBounds['topLeft']['y'] || floatval($thisRecord[$mapDetails->latitude]) > floatval($mapBounds['topLeft']['y'])) {
                        $mapBounds['topLeft']['y'] = floatval($thisRecord[$mapDetails->latitude]);
                    }

                    if (0 == $mapBounds['bottomRight']['x'] || floatval($thisRecord[$mapDetails->longitude]) > floatval($mapBounds['bottomRight']['x'])) {
                        $mapBounds['bottomRight']['x'] = floatval($thisRecord[$mapDetails->longitude]);
                    }

                    if (0 == $mapBounds['bottomRight']['y'] || floatval($thisRecord[$mapDetails->latitude]) < floatval($mapBounds['bottomRight']['y'])) {
                        $mapBounds['bottomRight']['y'] = floatval($thisRecord[$mapDetails->latitude]);
                    }

                }
            }

            // add two record for the bounds
            $returnArray['data'][] = array('id' => 'topLeft', 'geox' => $mapBounds['topLeft']['x'], 'geoy' => $mapBounds['topLeft']['y'], 'category' => 'TL', 'line1' => 'topLeft' . $mapBounds['topLeft']['x'] . '/' . $mapBounds['topLeft']['y']);

            $returnArray['data'][] = array('id' => 'bottomRight', 'geox' => $mapBounds['bottomRight']['x'], 'geoy' => $mapBounds['bottomRight']['y'], 'category' => 'BR', 'line1' => 'bottomRight' . $mapBounds['bottomRight']['x'] . '/' . $mapBounds['bottomRight']['y']);
        }

        return json_encode($returnArray);
    }

    /*
     * funtion to tranbslate certain operators if required to switch values at runtime
     */

    public function get_runtime_wherefilters()
    {
        // return Array
        $editableWhereFields = array();

        // get the Where Fields
        $whereFieldsList = json_decode_kinamu(html_entity_decode($this->whereconditions, ENT_QUOTES));

        // loop over the Fields
        foreach ($whereFieldsList as $whereFieldKey => $whereField) {
            if ('no' != $whereField['usereditable']) {
                // 2011-03-10 for values where pe parse for the editview differently
                // special handling for specific types
                switch ($whereField['operator']) {
                    case 'lastnddays':
                        switch ($whereField['type']) {
                            case 'datetimecombo':
                            case 'datetime':
                                $origValue = $whereField['value'];
                                $whereField['value'] = date($GLOBALS['timedate']->get_date_format(), gmmktime() - $origValue * 86400) . ' 00:00:00';
                                $whereField['valuekey'] = date($GLOBALS['timedate']->get_db_date_format(), gmmktime() - $origValue * 86400) . ' 00:00:00';
                                break;
                            default:
                                $origValue = $whereField['value'];
                                $whereField['value'] = date($GLOBALS['timedate']->get_date_format(), gmmktime() - $origValue * 86400);
                                $whereField['valuekey'] = date($GLOBALS['timedate']->get_db_date_format(), gmmktime() - $origValue * 86400);
                                break;
                        }
                        break;
                    case 'nextnddays':
                        switch ($whereField['type']) {
                            case 'datetimecombo':
                            case 'datetime':
                                $origValue = $whereField['value'];
                                $whereField['value'] = date($GLOBALS['timedate']->get_date_format(), gmmktime() + $origValue * 86400) . ' 00:00:00';
                                $whereField['valuekey'] = date($GLOBALS['timedate']->get_db_date_format(), gmmktime() + $origValue * 86400) . ' 00:00:00';
                                break;
                            default:
                                $origValue = $whereField['value'];
                                $whereField['value'] = date($GLOBALS['timedate']->get_date_format(), gmmktime() + $origValue * 86400);
                                $whereField['valuekey'] = date($GLOBALS['timedate']->get_db_date_format(), gmmktime() + $origValue * 86400);
                                break;
                        }
                        break;
                    case 'betwnddays':
                        switch ($whereField['type']) {
                            case 'datetimecombo':
                            case 'datetime':
                                $origValue = $whereField['value'];
                                $origValueto = $whereField['valueto'];
                                $whereField['value'] = date($GLOBALS['timedate']->get_date_format(), gmmktime() + $origValue * 86400) . ' 00:00:00';
                                $whereField['valuekey'] = date($GLOBALS['timedate']->get_db_date_format(), gmmktime() + $origValue * 86400) . ' 00:00:00';
                                $whereField['valueto'] = date($GLOBALS['timedate']->get_date_format(), gmmktime() + $origValueto * 86400) . ' 00:00:00';
                                $whereField['valuetokey'] = date($GLOBALS['timedate']->get_db_date_format(), gmmktime() + $origValueto * 86400) . ' 00:00:00';
                                break;
                            default:
                                $origValue = $whereField['value'];
                                $origValueto = $whereField['valueto'];
                                $whereField['value'] = date($GLOBALS['timedate']->get_date_format(), gmmktime() + $origValue * 86400);
                                $whereField['valuekey'] = date($GLOBALS['timedate']->get_db_date_format(), gmmktime() + $origValue * 86400);
                                $whereField['valueto'] = date($GLOBALS['timedate']->get_date_format(), gmmktime() + $origValueto * 86400);
                                $whereField['valuetokey'] = date($GLOBALS['timedate']->get_db_date_format(), gmmktime() + $origValueto * 86400);

                                break;
                        }
                        break;
                    case 'lastndays':
                    case 'lastnfdays':
                    case 'lastnweeks':
                    case 'lastnfmonth':
                    case 'lastnfweeks':
                    case 'nextndays':
                    case 'nextnweeks':
                    case 'betwndays':
                        break;
                    default:
                        // handle date formating for datetime fields
                        switch ($whereField['type']) {
                            case 'datetimecombo':
                            case 'datetime':
                                if (isset($whereField['valuekey'])) {
                                    $valKeyArray = explode(' ', $whereField['valuekey']);
                                    $whereField['value'] = $GLOBALS['timedate']->to_display_date($valKeyArray[0]) . ' ' . $valKeyArray[1];
                                }
                                if (isset($whereField['valuetokey'])) {
                                    $valKeyArray = explode(' ', $whereField['valuetokey']);
                                    $whereField['valueto'] = $GLOBALS['timedate']->to_display_date($valKeyArray[0]) . ' ' . $valKeyArray[1];
                                }
                                break;
                            case 'date':
                                if (isset($whereField['valuekey'])) {
                                    $whereField['value'] = $GLOBALS['timedate']->to_display_date($whereField['valuekey']);
                                }

                        }
                        break;
                }

                // return the Fields
                $editableWhereFields[] = $whereField;
            }
        }

        return $editableWhereFields;
    }

    /*
     * function to return values for the Dashlet Where Clause
     * parsed afterwards dynamically into a toolbar in the Dashlet
     */

    public function getDashletWhereClause()
    {
        //generic return array
        $returnArray = array();

        // get the where fields
        $arrayWhere = $this->get_runtime_wherefilters(); // json_decode_kinamu( html_entity_decode($this->whereconditions, ENT_QUOTES));
        //parse them
        foreach ($arrayWhere as $thisWhereField) {
            if (isset($thisWhereField['dashleteditable']) && 'no' != $thisWhereField['dashleteditable']) {
                // reset '---'
                if ('---' == $thisWhereField['valuekey']) {
                    $thisWhereField['valuekey'] = '';
                }

                if ('---' == $thisWhereField['value']) {
                    $thisWhereField['value'] = '';
                }

                // if needed switch the type for the dashlet
                switch ($thisWhereField['operator']) {
                    case 'lastndays':
                    case 'nextndays':
                        $thisWhereField['type'] = 'int';
                        break;
                }

                // get a return array
                $returnArray[] = array('fieldid' => $thisWhereField['fieldid'], 'operator' => $thisWhereField['operator'], 'sequence' => isset($thisWhereField['sequence']) ? $thisWhereField['sequence'] : '', 'options' => in_array($thisWhereField['type'], array('enum', 'radioenum', 'multienum', 'user_name', 'assigned_user_name')) ? $this->get_enum_from_path($thisWhereField['path']) : '', 'type' => $thisWhereField['type'], 'renderType' => 'yo1' == $thisWhereField['usereditable'] || 'yo2' == $thisWhereField['usereditable'] ? 'checkbox' : '', 'name' => $thisWhereField['name'], 'value' => (isset($thisWhereField['valuekey']) && '' != $thisWhereField['valuekey'] ? $thisWhereField['valuekey'] : $thisWhereField['value']));
            }
        }

        return $returnArray;
    }

    public function get_enum_from_path($path)
    {

        global $app_list_strings, $beanFiles, $beanList, $db;

        // explode the path
        $pathArray = explode('::', $path);

        // get Field and Module from the path
        $fieldArray = explode(':', $pathArray[count($pathArray) - 1]);
        $moduleArray = explode(':', $pathArray[count($pathArray) - 2]);

        // load the parent module
        require_once $beanFiles[$beanList[$moduleArray[1]]];
        $parentModule = new $beanList[$moduleArray[1]]();

        if ('link' == $moduleArray[0]) {
            // load the Relationshop to get the module
            $parentModule->load_relationship($moduleArray[2]);

            //PHP7 - 5.6 COMPAT: use creaetd variable as dynamic property
            $moduleArrayEl = $moduleArray[2];

            // load the Module
            $thisModuleName = $parentModule->$moduleArrayEl->getRelatedModuleName();
            require_once $beanFiles[$beanList[$parentModule->$moduleArrayEl->getRelatedModuleName()]];
            $thisModule = new $beanList[$parentModule->$moduleArrayEl->getRelatedModuleName()]();

            // pars the otpions into the return array
            switch ($thisModule->field_defs[$fieldArray[1]]['type']) {
                case 'enum':
                case 'radioenum':
                case 'multienum':
                    foreach ($app_list_strings[$thisModule->field_defs[$fieldArray[1]]['options']] as $value => $text) {
                        $returnArray[] = array('value' => $value, 'text' => $text);
                    }
                    break;
                case 'user_name':
                case 'assigned_user_name':
                    $returnArray[] = array('value' => 'current_user_id', 'text' => 'active user');
                    $usersResult = $db->query('SELECT id, user_name FROM users WHERE deleted = \'0\' AND status = \'Active\'');
                    while ($userRecord = $db->fetchByAssoc($usersResult)) {
                        // bugfix 2010-09-28 since id was asisgned and not user name ..  no properly evaluates active user
                        $returnArray[] = array('value' => $userRecord['user_name'], 'text' => $userRecord['user_name']);
                    }
                    break;
            }
        } else {
            // we have the root module
            switch ($parentModule->field_defs[$fieldArray[1]]['type']) {
                case 'enum':
                case 'radioenum':
                case 'multienum':
                    foreach ($app_list_strings[$parentModule->field_defs[$fieldArray[1]]['options']] as $value => $text) {
                        $returnArray[] = array('value' => $value, 'text' => $text);
                    }
                    break;
                case 'user_name':
                case 'assigned_user_name':
                    $returnArray[] = array('value' => 'current_user_id', 'text' => 'active user');
                    $usersResult = $db->query('SELECT id, user_name FROM users WHERE deleted = \'0\' AND status = \'Active\'');
                    while ($userRecord = $db->fetchByAssoc($usersResult)) {
                        // bugfix 2010-09-28 since id was asisgned and not user name ..  no properly evaluates active user
                        $returnArray[] = array('value' => $userRecord['user_name'], 'text' => $userRecord['user_name']);
                    }
                    break;
            }
        }

        return $returnArray;
    }

    public static function getMenuReports($module, &$module_menu)
    {
        global $db;

        $thisReport = new KReport();

        $reportsArray = array();

        $repQuery = "select kreports.id, name from kreports ";
        if ('PRO' == $GLOBALS['sugar_flavor']) {
            $thisReport->add_team_security_where_clause($repQuery, 'kreports');
        }

        $repQuery .= " where kreports.deleted = false and publishoptions like '%\"publishMenuModule\":\"" . $module . "\"%'";
        $reportsObj = $db->query($repQuery);

        while ($report = $db->fetchByAssoc($reportsObj)) {
            $module_menu[] = array(
                "index.php?module=KReports&action=DetailView&record=" . $report['id'],
                $report['name'],
                "KReports",
                'KReports');
        }

        return true;
    }

    // for the listing (exclude utility Reports unless we ae admin
    // Mint start #58178
    public function create_new_list_query($order_by, $where, $filter = array(), $params = array(), $show_deleted = 0, $join_type = '', $return_array = false, $parentbean = null, $singleSelect = false, $ifListForExport = false)
    {
        $ret_array = parent::create_new_list_query($order_by, $where, $filter, $params, $show_deleted, $join_type, true, $parentbean, $singleSelect, $ifListForExport);
        // Mint end #58178
        // add selection clause to $ret:array['where']

        if ($return_array) {
            return $ret_array;
        }
        return $ret_array['select'] . $ret_array['from'] . $ret_array['where'] . $ret_array['order_by'];
    }

}

/*
 * separate class that handles formatting fd field bnased on the fieldrenderer and the record
 * renderes are returned from getXtyperenderer and are the sames as in the userinterface in Sencha
 */

class KReportRenderer
{

    public $report = null;

    // Mint start #49656
    //public function __construct($thisReport) {
    public function __construct($thisReport = null)
    {
        // Mint end #49656
        $this->report = $thisReport;
    }

    public static function kcurrencyRenderer($fieldid, $record)
    {
        // Mint start #61987
        //if ( $record[$fieldid] == '' || $record[$fieldid] == 0)
        //   return '';
        // Mint end #61987
        // 2014-02-25 .. set teh default system currency .. otherwise sugar might take the default users currency
        if (empty($record[$fieldid . '_curid'])) {
            $record[$fieldid . '_curid'] = '99';
        }

        return currency_format_number($record[$fieldid], array('currency_id' => $record[$fieldid . '_curid'], 'currency_symbol' => true));
    }

    public function kenumRenderer($fieldid, $record)
    {
        global $app_list_strings;
        // Mint start #41967
        $field_list = $this->report->kQueryArray->queryArray[(isset($record['unionid']) ? $record['unionid'] : 'root')]['kQuery']->fieldNameMap[$fieldid]['fields_name_map_entry']['options'];
        if (!isset($app_list_strings) || !isset($app_list_strings[$field_list])) {
            $lang = $GLOBALS['current_language'];
            if (isset($_SESSION['authenticated_user_language'])) {
                $lang = $_SESSION['authenticated_user_language'];
            }
            $app_list_strings = return_app_list_strings_language($lang);
        }
        // Mint end #41967
        //2013-03-15 check if we have a group concat then translate the individual values
        if (in_array($this->report->fieldNameMap[$fieldid]['sqlFunction'], array('GROUP_CONCAT', 'GROUP_CONASC', 'GROUP_CONDSC'))) {
            $valArray = explode(',', $record[$fieldid]);
            $fieldValue = '';
            foreach ($valArray as $thisValue) {
                if ('' != $fieldValue) {
                    $fieldValue .= ', ';
                }

                if (trim($thisValue) != '' && isset( /* Mint start #41967 */$field_list/* Mint end #41967 */)) {
                    if (is_array($this->report->fieldNameMap[$fieldid]['fields_name_map_entry']['function'])) {
                        $fielName = $this->report->fieldNameMap[$fieldid]['fields_name_map_entry']['function']['include'];
                        require_once $fielName;
                        $functionName = $this->report->fieldNameMap[$fieldid]['fields_name_map_entry']['function']['name'];
                        $fieldValue = $functionName(null, $this->report->fieldNameMap[$fieldid]['fieldname'], $record[$thisValue], '');
                    } else {
                        // Mint start #41967
                        $fieldValue .= $app_list_strings[$field_list][trim($thisValue)];
                        // Mint end #41967
                    }
                }
            }
        } else {
            // Mint start #58178
            if (isset($this->report->fieldNameMap[$fieldid]['fields_name_map_entry']['function']) && is_array($this->report->fieldNameMap[$fieldid]['fields_name_map_entry']['function'])) {
                // Mint end #58178
                $fielName = $this->report->fieldNameMap[$fieldid]['fields_name_map_entry']['function']['include'];
                require_once $fielName;
                $functionName = $this->report->fieldNameMap[$fieldid]['fields_name_map_entry']['function']['name'];
                if(isset($this->report->fieldNameMap[$fieldid]['fields_name_map_entry']['function']['additional_params'])) {
                    $options_list = $functionName(null, null, null, null, $this->report->fieldNameMap[$fieldid]['fields_name_map_entry']['function']['additional_params']);
                    $fieldValue = $options_list[$record[$fieldid]];
                } else {
                    $fieldValue = $functionName(null, $this->report->fieldNameMap[$fieldid]['fieldname'], $record[$fieldid]);
                }
            } else {
                // Mint start #41967
                $fieldValue = $app_list_strings[$field_list][$record[$fieldid]];
                // Mint end #41967
            }
        }

        // if value is empty we return the original value
        if (empty($fieldValue)) {
            $fieldValue = $record[$fieldid];
        }

        return $fieldValue;
    }

    public function kradioenumRenderer($fieldid, $record)
    {
        return $this->kenumRenderer($fieldid, $record);
    }

    public function kmultienumRenderer($fieldid, $record)
    {
        global $app_list_strings;
        if ('' == $this->report->fieldNameMap[$fieldid]['sqlFunction']) {
            // Mint start #41967
            if (!isset($app_list_strings)) {
                $lang = $GLOBALS['current_language'];
                if (isset($_SESSION['authenticated_user_language'])) {
                    $lang = $_SESSION['authenticated_user_language'];
                }
                $app_list_strings = return_app_list_strings_language($lang);
            }
            // Mint end #41967
            $fieldArray = preg_split('/\^,\^/', $record[$fieldid]);
            //bugfix 2010-09-22 if only one value is selected
            if (is_array($fieldArray) && count($fieldArray) > 1) {
                $fieldValue = '';
                foreach ($fieldArray as $thisFieldValue) {
                    if ('' != $fieldValue) {
                        $fieldValue .= ', ';
                    }

                    $fieldValue .= $app_list_strings[$this->report->kQueryArray->queryArray[(isset($fieldArray['unionid']) ? $fieldArray['unionid'] : 'root')]['kQuery']->fieldNameMap[$fieldid]['fields_name_map_entry']['options']][trim($thisFieldValue, '^')];
                }
            } else {
                $fieldValue = $app_list_strings[$this->report->kQueryArray->queryArray[(isset($fieldArray['unionid']) ? $fieldArray['unionid'] : 'root')]['kQuery']->fieldNameMap[$fieldid]['fields_name_map_entry']['options']][trim($record[$fieldid], '^')];
            }
        }
        return $fieldValue;
    }

    public static function kpercentageRenderer($fieldid, $record)
    {
        return round($record[$fieldid], 2) . '%';
    }

    public static function knumberRenderer($fieldid, $record)
    {
        // Mint start ref #48478 Nieprawidłowe przekazywanie liczb zmiennoprzecinkowych do wykresów
        $separated = explode('.', $record[$fieldid]);
        $rest_len = min(strlen($separated[1]), 6);
        return number_format($record[$fieldid], $rest_len, $GLOBALS['current_user']->getPreference('dec_sep'), $GLOBALS['current_user']->getPreference('num_grp_sep'));
        // return round($record[$fieldid], 2);
        // Mint end ref #48478 Nieprawidłowe przekazywanie liczb zmiennoprzecinkowych do wykresów
        //        return format_number($record[$fieldid], $GLOBALS['current_user']->getPreference('default_currency_significant_digits'));
    }

    public static function kintRenderer($fieldid, $record)
    {
        // Mint start ref #48478 Nieprawidłowe przekazywanie liczb zmiennoprzecinkowych do wykresów
        return number_format($record[$fieldid], 0, $GLOBALS['current_user']->getPreference('dec_sep'), $GLOBALS['current_user']->getPreference('num_grp_sep'));
        // return round($record[$fieldid]);
        // Mint end #48478 Nieprawidłowe przekazywanie liczb zmiennoprzecinkowych do wykresów
        //        return format_number($record[$fieldid], 0);
    }

    public static function kdateRenderer($fieldid, $record)
    {
        global $timedate;
        // 2013-10-03 no Date TZ Conversion Bug#504
        return ('' != $record[$fieldid] ? $timedate->to_display_date($record[$fieldid], false) : '');
    }

    public static function kdatetimeRenderer($fieldid, $record)
    {
        global $timedate;
        return ('' != $record[$fieldid] ? $timedate->to_display_date_time($record[$fieldid]) : '');
    }

    // Mint start #42078
    public static function kdatetimecomboRenderer($fieldid, $record)
    {
        return self::kdatetimeRenderer($fieldid, $record);
    }

    // Mint end #42078

    public static function kdatetutcRenderer($fieldid, $record)
    {
        global $timedate;
        return ('' != $record[$fieldid] ? $timedate->to_display_date_time($record[$fieldid], true, false) : '');
    }

    public static function kboolRenderer($fieldid, $record)
    {
        global $mod_strings;
        // Mint start #41967
        if (!isset($mod_strings)) {
            $lang = $GLOBALS['current_language'];
            if (isset($_SESSION['authenticated_user_language'])) {
                $lang = $_SESSION['authenticated_user_language'];
            }
            $mod_strings = return_module_language($lang, 'KReports');
        }
        // Mint end #41967
        return ('1' == $record[$fieldid] ? $mod_strings['LBL_BOOL_1'] : $mod_strings['LBL_BOOL_0']);
    }

    public static function ktextRenderer($fieldid, $record)
    {
        return $record[$fieldid];
    }

}
