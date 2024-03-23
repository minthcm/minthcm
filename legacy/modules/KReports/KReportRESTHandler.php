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




if (!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');
require_once('modules/KReports/utils.php');

class KReporterRESTHandler
{

    function __construct()
    {

    }

    function getCurrencies()
    {
        $curResArray = $GLOBALS['db']->query('SELECT id, symbol, conversion_rate FROM currencies WHERE deleted = \'0\'');

        $curArray = array();
        $curArray['-99'] = array(
            'symbol' => $GLOBALS['sugar_config']['default_currency_symbol'],
            'conversion_rate' => 1
        );
        while ($thisCurEntry = $GLOBALS['db']->fetchByAssoc($curResArray)) {
            $curArray[$thisCurEntry['id']] = array(
                'symbol' => $thisCurEntry['symbol'],
                'conversion_rate' => $thisCurEntry['conversion_rate']
            );
        }

        return $curArray;
    }

    function getWhereFunctions()
    {
        $retarray = array();
        $retarray[] = array(
            'field' => '',
            'description' => '-'
        );
        $customFunctionInclude = '';
        $kreportCustomFunctions = array();
        include('modules/KReports/kreportsConfig.php');
        if ($customFunctionInclude != '') {
            include($customFunctionInclude);
            if (is_array($kreportCustomFunctions) && count($kreportCustomFunctions)
                > 0) {
                foreach ($kreportCustomFunctions as $functionname => $functiondescription) {
                    $retarray[] = array(
                        'field' => $functionname,
                        'description' => $functiondescription
                    );
                }
            }
        }

        return $retarray;
    }

    function getLayouts()
    {
        include('modules/KReports/config/KReportLayouts.php');
        $layouts = array();
        $layouts[] = array(
            'name' => '-',
            'count' => 0
        );
        if (is_array($kreportLayouts)) {
            foreach ($kreportLayouts as $layoutName => $layoutData) {
                $layouts[] = array(
                    'name' => $layoutName,
                    'count' => count($layoutData['items'])
                );
            }
        }

        return $layouts;
    }

    function getBuckets()
    {
        global $db;
        $buckets = array();

        $bucketRecords = $db->query("SELECT * FROM kreportgroupings");
        while ($bucketRecord = $db->fetchByAssoc($bucketRecords)) {
            $buckets = array(
                'id' => $bucketRecord['id'],
                'name' => $bucketRecord['name'],
                'modulename' => $bucketRecord['modulename'],
                'fieldname' => $bucketRecord['fieldname'],
            );
        }

        return $buckets;
    }

    function getVizColors()
    {
        $colors = [];
        include('modules/KReports/config/KReportColors.php');
        foreach ($kreportColors as $colorSchema => $colorDetails) {
            $colors[] = array(
                'id' => $colorSchema,
                'name' => $colorDetails['name'],
                'colors' => implode('*', $colorDetails['colors'])
            );
        }
        return $colors;
    }

    function getSysinfo()
    {
        global $sugar_config, $db, $current_user;

        $uc = $db->fetchByAssoc($db->query(base64_decode('c2VsZWN0IGNvdW50KGlkKSBjaSBmcm9tIHVzZXJzIHdoZXJlIHN0YXR1cyA9ICdBY3RpdmUn')));

        return array(
            'systemkey' => $sugar_config['kreports_unique_key'] ?: $sugar_config['unique_key'],
            'count' => $uc['ci'],
            'server' => $_SERVER['SERVER_ADDR'],
            'current_user_id' => $current_user->id,
            'current_user_name' => $current_user->name,
        );
    }

    function getModuleFields($module)
    {
        global $current_language, $beanFiles, $beanList;

        $retarray = array();
        if (!empty($module)) {
            $inputModule = BeanFactory::getBean($module);

            //2013-01-18 take in account the users language
            $langArray = return_module_language($current_language, $module);

            foreach ($inputModule->field_defs as $fieldname => $fielddefs) {
                $retarray[] = array(
                    'field' => $fieldname,
                    'description' => isset($fielddefs['vname']) ? isset($langArray[$fielddefs['vname']])
                        ? $langArray[$fielddefs['vname']] : $fielddefs['vname'] : $fieldname,
                    'type' => $fielddefs['type']
                );
            }
        }

        return $retarray;
    }

    function get_user_datetime_format()
    {
        global $current_user;
        $timef = $current_user->getPreference('timef');
        if (empty($timef)) {
            $timef = $GLOBALS['sugar_config']['default_time_format'];
        }
        if (empty($timef)) {
            $timef = '';
        }

        $datef = $current_user->getPreference('datef');
        if (empty($datef)) {
            $datef = $GLOBALS['sugar_config']['default_date_format'];
        }
        if (empty($datef)) {
            $datef = '';
        }

        return array('timef' => $timef, 'datef' => $datef);
    }

    function get_user_prefs()
    {
        global $current_user;
        $timef = $current_user->getPreference('timef');
        if (empty($timef)) {
            $timef = $GLOBALS['sugar_config']['default_time_format'];
        }
        if (empty($timef)) {
            $timef = '';
        }

        $datef = $current_user->getPreference('datef');
        if (empty($datef)) {
            $datef = $GLOBALS['sugar_config']['default_date_format'];
        }
        if (empty($datef)) {
            $datef = '';
        }

        $precision = $current_user->getPreference('default_currency_significant_digits');
        if (empty($precision)) {
            $precision = $GLOBALS['sugar_config']['default_currency_significant_digits'];
        }
        if (empty($precision)) {
            $precision = '';
        }

        return array('timef' => $timef, 'datef' => $datef, 'precision' => $precision);
    }

    function get_users_list($params = array())
    {
        $responseArray = array();
        $tmpBean = new User();
        $order_by = "users.last_name ASC";
        $where = ""; // status='Active' ?
        $row_offset = 0;
        $limit = -1;
        //begin handle search param
        if (isset($params['searchterm']) && !empty($params['searchterm'])) {
            if (isset($params['searchtermfields'])) {
                $whereParts = array();
                $searchtermfields = json_decode($params['searchtermfields'], true);
                foreach ($searchtermfields as $searchfield) {
                    $whereParts[] = "users.$searchfield LIKE '%".$params['searchterm']."%'";
                }
                if (!empty($whereParts))
                        $where .= "(".implode(" OR ", $whereParts).")";
            }
        }
        if (isset($params['limit'])) {
            $limit = $params['limit'];
        }
        if (isset($params['start'])) {
            $row_offset = $params['start'];
        }
        //end handle search param

        $users = $tmpBean->get_list($order_by, $where, $row_offset, $limit);

        //get total count
        $usersForCount = $tmpBean->get_list($order_by, $where);
        $responseArray = array('list' => array(), 'totalcount' => $usersForCount['row_count']);

        foreach ($users['list'] as $user) {
            $responseArray['list'][] = array('id' => $user->id, 'user_name' => $user->user_name,
                'name' => $user->full_name);
        }
        return $responseArray;
    }

    function whereInitialize()
    {
        include('modules/KReports/config/KReportWhereOperators.php');
        return array(
            'operatorCount' => $kreporterWhereOperatorCount
        );
    }

    function geAutoCompletevalues($path, $query, $start, $limit)
    {
        global $beanFiles, $beanList, $db;

        $returnArray = array();
        $fieldArray = array();


        // explode the path
        $pathArray = explode('::', $path);

        // get Field and Module from the path
        $fieldArray = explode(':', $pathArray[count($pathArray) - 1]);
        $moduleArray = explode(':', $pathArray[count($pathArray) - 2]);

        // load the parent module
        require_once($beanFiles[$beanList[$moduleArray[1]]]);
        $parentModule = new $beanList[$moduleArray[1]];

        if ($moduleArray[0] == 'link') {
            // load the Relationshop to get the module
            $parentModule->load_relationship($moduleArray[2]);

            // load the Module
            //PHP7 - 5.6 COMPAT
            //ORIGINAL: $parentModule->$moduleArray[2]->getRelatedModuleName();
            $moduleArrayEl = $moduleArray[2];
            $thisModuleName = $parentModule->$moduleArrayEl->getRelatedModuleName();
            require_once($beanFiles[$beanList[$parentModule->$moduleArrayEl->getRelatedModuleName()]]);
            $thisModule = new $beanList[$parentModule->$moduleArrayEl->getRelatedModuleName()];
            //END
        } else $thisModule = $parentModule;

        if ($thisModule->table_name != '') {

            // determine the field name we need to go for
            $fieldName = 'name';
            // #bug 520 changed to object rather than array.
            if ($fieldArray[0] == 'field' && isset($thisModule->field_defs[$fieldArray[1]])
                && $fieldArray[1] != 'id') $fieldName = $fieldArray[1];

            $query_res = $db->limitQuery("SELECT id, ".$fieldName." FROM $thisModule->table_name WHERE ".(!empty($query)
                    ? "name like '%".$query."%' AND" : "")." deleted='0' ORDER BY name ASC", (!empty($start)
                    ? $start : 0), (!empty($limit) ? $limit : 25));
            while ($thisEntry = $db->fetchByAssoc($query_res)) {
                $returnArray['data'][] = array('itemid' => $thisEntry['id'], 'itemtext' => $thisEntry[$fieldName]);
            }

            // get count
            $totalRec = $db->fetchByAssoc($db->query("SELECT count(*) as count FROM $thisModule->table_name WHERE ".(!empty($query)
                        ? "name like '%".$query."%' AND" : "")." deleted='0'"));
            $returnArray['total'] = $totalRec['count'];
        }

        return $returnArray;
    }

    function getWhereOperators($path, $grouping, $designer)
    {
        global $app_list_strings, $beanFiles, $beanList, $db, $current_language;

        $app_list_strings = return_app_list_strings_language($current_language);

        include('modules/KReports/config/KReportWhereOperators.php');

        //2013-01-18 take in account the users language
        $mod_strings = return_module_language($GLOBALS['current_language'], 'KReports');

        $retarray[] = array(
            'operator' => 'ignore',
            'values' => $kreporterWhereOperatorCount['ignore'],
            'display' => $mod_strings['LBL_OP_IGNORE']
        );


        //2013-08-07 check if path is set
        if (empty($path)) {
            // parse the options into the return array
            foreach ($kreporterWhereOperatorTypes[$kreporterWhereOperatorAssignments['fixed']] as $operator)
                $retarray[] = array(
                    'operator' => $operator,
                    'values' => $kreporterWhereOperatorCount[$operator],
                    'display' => $mod_strings['LBL_OP_'.strtoupper($operator)]
                );
        } else {

            // explode the path
            $pathArray = explode('::', $path);

            // get Field and Module from the path
            $fieldArray = explode(':', $pathArray[count($pathArray) - 1]);
            $moduleArray = explode(':', $pathArray[count($pathArray) - 2]);

            // load the parent module
            require_once($beanFiles[$beanList[$moduleArray[1]]]);
            $parentModule = new $beanList[$moduleArray[1]];

            // get the module we need to determine the type
            if ($moduleArray[0] == 'link') {
                // load the Relationshop to get the module
                $parentModule->load_relationship($moduleArray[2]);

                // load the Module
                //PHP7 - 5.6 COMPAT
                //ORIGINAL: require_once($beanFiles[$beanList[$parentModule->$moduleArray[2]->getRelatedModuleName()]]);
                $moduleArrayEl = $moduleArray[2];
                require_once($beanFiles[$beanList[$parentModule->$moduleArrayEl->getRelatedModuleName()]]);
                $thisModule = new $beanList[$parentModule->$moduleArrayEl->getRelatedModuleName()];
                //END
            } //2013-09-25 add support for relate fields BUG #498
            elseif ($moduleArray[0] == 'relate') {
                require_once($beanFiles[$beanList[$moduleArray['1']]]);
                $nodeModule = new $beanList[$moduleArray['1']]();
                $thisModuleName = $nodeModule->field_defs[$moduleArray[2]]['module'];
                require_once($beanFiles[$beanList[$thisModuleName]]);
                $thisModule = new $beanList[$thisModuleName]();
            } else $thisModule = $parentModule;

            //2013-02-26 ... added operators for audit fields
            if ($moduleArray[0] == 'audit') {
                //2013-04-12 handle where operators for types ... Bug #465
                switch ($fieldArray[1]) {
                    case 'date_created':
                        foreach ($kreporterWhereOperatorTypes['date'] as $operator)
                            $retarray[] = array(
                                'operator' => $operator,
                                'values' => $kreporterWhereOperatorCount[$operator],
                                'display' => $mod_strings['LBL_OP_'.strtoupper($operator)]
                            );
                        break;
                    default:
                        foreach ($kreporterWhereOperatorTypes['varchar'] as $operator)
                            $retarray[] = array(
                                'operator' => $operator,
                                'values' => $kreporterWhereOperatorCount[$operator],
                                'display' => $mod_strings['LBL_OP_'.strtoupper($operator)]
                            );
                        break;
                }
            }

            // special handling for Kreporttype if we have an eval array
            if ($thisModule->field_defs[$fieldArray[1]]['type'] == 'kreporter' && is_array($thisModule->field_defs[$fieldArray[1]]['eval'])) {
                foreach ($thisModule->field_defs[$fieldArray[1]]['eval']['selection'] as $operator => $eval)
                    $retarray[] = array(
                        'operator' => $operator,
                        'values' => $kreporterWhereOperatorCount[$operator],
                        'display' => $mod_strings['LBL_OP_'.strtoupper($operator)]
                    );

                //2013-02-26 ... add reference also for kreporter fields
                if ($designer) {
                    $retarray[] = array(
                        'operator' => 'reference',
                        'values' => 1,
                        'display' => $mod_strings['LBL_OP_REFERENCE']
                    );
                }
            } else {
                // parse the options into the return array
                // Mint start
                if ($fieldArray[1] == 'first_name' || $fieldArray[1] == 'last_name') {
                    $wheretype = $kreporterWhereOperatorAssignments['name2'];
                } else {
                    $wheretype = $kreporterWhereOperatorAssignments[isset($thisModule->field_defs[$fieldArray[1]]['kreporttype'])
                            ? $thisModule->field_defs[$fieldArray[1]]['kreporttype']
                            : $thisModule->field_defs[$fieldArray[1]]['type']];
                }
                // Mint end
                if (!empty($grouping) && isset($kreporterWhereOperatorTypes[$wheretype.'grouped']))
                        $wheretype .= 'grouped';

                foreach ($kreporterWhereOperatorTypes[$wheretype] as $operator)
                    $retarray[] = array(
                        'operator' => $operator,
                        'values' => $kreporterWhereOperatorCount[$operator],
                        'display' => $mod_strings['LBL_OP_'.strtoupper($operator)]
                    );

                if ($designer) {
                    $retarray[] = array(
                        'operator' => 'function',
                        'values' => 1,
                        'display' => $mod_strings['LBL_OP_FUNCTION']
                    );
                    $retarray[] = array(
                        'operator' => 'reference',
                        'values' => 1,
                        'display' => $mod_strings['LBL_OP_REFERENCE']
                    );
                }
            }
        }

        return $retarray;
    }

    function getEnumOptions($path, $grouping = '', $operators = array())
    {

        global $current_language, $beanFiles, $beanList, $db;

        $returnArray = array();

        $app_list_strings = return_app_list_strings_language($current_language);

        // explode the path
        $pathArray = explode('::', $path);

        // get Field and Module from the path
        $fieldArray = explode(':', $pathArray[count($pathArray) - 1]);
        $moduleArray = explode(':', $pathArray[count($pathArray) - 2]);


        // load the parent module
        require_once($beanFiles[$beanList[$moduleArray[1]]]);
        $parentModule = new $beanList[$moduleArray[1]];

        // Mint start
        $fieldType = "";
        if ($fieldArray[1] == 'first_name' || $fieldArray[1] == 'last_name') {
            $fieldType = 'name2';
        }
        // Mint end

        if ($moduleArray[0] == 'link' || $moduleArray[0] == 'relate') {
            switch ($moduleArray[0]) {
                case 'link':
                    // load the Relationshop to get the module
                    $parentModule->load_relationship($moduleArray[2]);

                    // load the Module
                    //PHP7 - 5.6 COMPAT
                    //ORIGINAL: $parentModule->$moduleArray[2]->getRelatedModuleName();
                    $moduleArrayEl = $moduleArray[2];
                    $thisModuleName = $parentModule->$moduleArrayEl->getRelatedModuleName();
                    require_once($beanFiles[$beanList[$parentModule->$moduleArrayEl->getRelatedModuleName()]]);
                    $thisModule = new $beanList[$parentModule->$moduleArrayEl->getRelatedModuleName()];
                    //END
                    break;
                //2013-09-25 add support for relate fields BUG #499
                case 'relate':
                    require_once($beanFiles[$beanList[$moduleArray['1']]]);
                    $nodeModule = new $beanList[$moduleArray['1']]();
                    $thisModuleName = $nodeModule->field_defs[$moduleArray[2]]['module'];
                    require_once($beanFiles[$beanList[$thisModuleName]]);
                    $thisModule = new $beanList[$thisModuleName]();
                    break;
            }

            //2013-02-28 if we have the kreporttype set ... override the type
            if ($thisModule->field_defs[$fieldArray[1]]['type'] == 'kreporter' && !empty($thisModule->field_defs[$fieldArray[1]]['kreporttype']))
                    $thisModule->field_defs[$fieldArray[1]]['type'] = $thisModule->field_defs[$fieldArray[1]]['kreporttype'];


            // pars the otpions into the return array
            // Mint start
            if (empty($fieldType)) {
                $fieldType = $thisModule->field_defs[$fieldArray[1]]['kreporttype']
                        ?: $thisModule->field_defs[$fieldArray[1]]['type'];
            }
            switch ($fieldType) {
                // Mint end
                case 'enum':
                case 'radioenum':
                case 'multienum':
                    if ($thisModule->field_defs[$fieldArray[1]]['function']) {
                        require_once($thisModule->field_defs[$fieldArray[1]]['function']['include']);
                        $functionName = $thisModule->field_defs[$fieldArray[1]]['function']['name'];
                        $returnArray = $functionName($thisModule, $fieldArray[1], '', 'KReporterOptions', $operators);
                    } else {
                        foreach ($app_list_strings[$thisModule->field_defs[$fieldArray[1]]['options']] as $value => $text) {
                            if ($value !== "")
                                    $returnArray[] = array('value' => $value, 'text' => (!empty($text)
                                        ? $text : '-'));
                        }
                    }
                    break;
                case 'parent_type':
                    // bug 2011-08-08 we assume it is parent_name
                    // not completely correct since we should look for the field where the name is the type but will be sufficient
                    foreach ($app_list_strings[$thisModule->field_defs['parent_name']['options']] as $value => $text) {
                        $returnArray[] = array('value' => $value, 'text' => $text);
                    }
                    break;
                // Mint start
                case 'name':
                    $rootArray = explode(':', $pathArray[0]);
                    $thisModule->kreporter_call = true; // start / needed for few clients (filtered enum values list in get_list)
                    $thisModule->kreporter_module = $rootArray[1]; //end
                    array_push($returnArray, array('value' => '', 'text' => ''));
                    $relatedBeans = $thisModule->get_list($fieldArray[1], "", 0, -99, -99);
                    foreach ($relatedBeans['list'] as $relatedBean) {
                        $newValue = array('value' => $relatedBean->name, 'text' => $relatedBean->name);
                        if (!array_search($newValue, $returnArray)) {
                            array_push($returnArray, $newValue);
                        }
                    }
                    array_unique($returnArray);
                    break;
                case 'name2':
                    array_push($returnArray, array('value' => '', 'text' => ''));
                    $sql = "SELECT DISTINCT(`{$fieldArray[1]}`) AS `field` FROM `{$thisModule->table_name}` WHERE `deleted`='0' ORDER BY {$fieldArray[1]};";
                    $result = $db->query($sql);
                    while ($row = $db->fetchByAssoc($result)) {
                        array_push($returnArray, array('value' => $row['field'],
                            'text' => $row['field']));
                    }
                    break;
                // Mint end
                case 'user_name':
                case 'username': //sugar7
                case 'assigned_user_name':
                    global $locale;
                    $returnArray[] = array('value' => 'current_user_id', 'text' => 'active user');
                    $usersResult = $db->query('SELECT id, user_name, first_name, last_name FROM users WHERE deleted = \'0\' AND status=\'Active\' ORDER BY last_name'); //  AND status = \'Active\'');
                    while ($userRecord = $db->fetchByAssoc($usersResult)) {
                        // bugfix 2010-09-28 since id was asisgned and not user name ..  no properly evaluates active user
                        // bugfix 2012-03-29 proper name formatting based on user preferences
                        // $returnArray[] = array('value' => $userRecord['user_name'], 'text' => ($userRecord['last_name'] =! '' ? $userRecord['first_name'] . ' ' . $userRecord['last_name'] : $userRecord['user_name']));
                        $returnArray[] = array('value' => $userRecord['user_name'],
                            'text' => ($userRecord['last_name'] = !'' ? $locale->getLocaleFormattedName($userRecord['first_name'], $userRecord['last_name'], '')
                                : $userRecord['user_name']));
                    }
                    break;
                case 'team_name':
                    $teamsResult = $db->query('SELECT team_name FROM teams WHERE deleted = \'0\' ORDER BY name'); //  AND status = \'Active\'');
                    while ($teamRecord = $db->fetchByAssoc($teamsResult)) {
                        // bugfix 2010-09-28 since id was asisgned and not user name ..  no properly evaluates active user
                        $returnArray[] = array('value' => $teamRecord['name'], 'text' => $teamRecord['name']);
                    }
                    break;
            }
        } else {

            //2013-02-28 if we have the kreporttype set ... override the type
            if ($parentModule->field_defs[$fieldArray[1]]['type'] == 'kreporter'
                && !empty($parentModule->field_defs[$fieldArray[1]]['kreporttype']))
                    $parentModule->field_defs[$fieldArray[1]]['type'] = $parentModule->field_defs[$fieldArray[1]]['kreporttype'];

            // Mint start
            if (empty($fieldType)) {
                $fieldType = $parentModule->field_defs[$fieldArray[1]]['kreporttype']
                        ?: $parentModule->field_defs[$fieldArray[1]]['type'];
            }
            // we have the root module
            switch ($fieldType) {
                // Mint end
                case 'enum':
                case 'radioenum':
                case 'multienum':
                    if ($parentModule->field_defs[$fieldArray[1]]['function']) {
                        require_once($parentModule->field_defs[$fieldArray[1]]['function']['include']);
                        $functionName = $parentModule->field_defs[$fieldArray[1]]['function']['name'];
                        if(isset($parentModule->field_defs[$fieldArray[1]]['function']['additional_params'])){
                            $operators['additional_params'] = $parentModule->field_defs[$fieldArray[1]]['function']['additional_params'];
                        }
                        $returnArray = formatEnumArray($functionName($parentModule, $fieldArray[1], '', 'KReporterOptions', $operators));
                    } else {
                        foreach ($app_list_strings[$parentModule->field_defs[$fieldArray[1]]['options']] as $value => $text) {
                            if ($value !== "")
                                    $returnArray[] = array('value' => $value, 'text' => (!empty($text)
                                        ? $text : '-'));
                        }
                    }
                    break;
                case 'parent_type':
                    // bug 2011-08-08 we assume it is parent_name
                    // not completely correct since we should look for the field where the name is the type but will be sufficient
                    foreach ($app_list_strings[$parentModule->field_defs['parent_name']['options']] as $value => $text) {
                        $returnArray[] = array('value' => $value, 'text' => $text);
                    }
                    break;
                // Mint start
                case 'name':
                case 'name2':
                    array_push($returnArray, array('value' => '', 'text' => ''));
                    $sql = "SELECT DISTINCT(`{$fieldArray[1]}`) AS `field` FROM `{$parentModule->table_name}` WHERE `deleted`='0' ORDER BY {$fieldArray[1]};";
                    $result = $db->query($sql);
                    while ($row = $db->fetchByAssoc($result)) {
                        array_push($returnArray, array('value' => $row['field'],
                            'text' => $row['field']));
                    }
                    break;
                // Mint end
                case 'user_name':
                case 'username': //sugar7
                case 'assigned_user_name':
                    $returnArray[] = array('value' => 'current_user_id', 'text' => 'active user');
                    $usersResult = $db->query('SELECT id, user_name FROM users WHERE deleted = \'0\' AND status = \'Active\'');
                    while ($userRecord = $db->fetchByAssoc($usersResult)) {
                        // bugfix 2010-09-28 since id was asisgned and not user name ..  no properly evaluates active user
                        $returnArray[] = array('value' => $userRecord['user_name'],
                            'text' => $userRecord['user_name']);
                    }
                    break;
            }
        }

        if (!empty($grouping)) {
            $groupedReturnArray = array();
            $groupingDetail = $db->fetchByAssoc($db->query("SELECT * FROM kreportgroupings WHERE id = '$grouping'"));
            $groupingMapping = json_decode(html_entity_decode($groupingDetail['mapping']), true);

            if ($groupingMapping['others']) {
                foreach ($groupingMapping['mappings'] as $mappingDetail) {
                    $groupedReturnArray[] = array('value' => $mappingDetail['mappingvalue'],
                        'text' => $mappingDetail['mappingvalue']);
                }

                $groupedReturnArray[] = array('value' => 'other', 'text' => 'other');

                $returnArray = $groupedReturnArray;
            } else {
                foreach ($groupingMapping['mappings'] as $mappingDetail) {
                    $groupedReturnArray[] = array('value' => $mappingDetail['mappingvalue'],
                        'text' => $mappingDetail['mappingvalue']);
                    foreach ($mappingDetail['children'] as $mappedValue) {
                        foreach ($returnArray as $returnIndex => $returnEntry)
                            if ($returnEntry['value'] == $mappedValue)
                                    unset($returnArray[$returnIndex]);
                    }
                }
                $returnArray = array_merge($groupedReturnArray, $returnArray);
            }
        }


        return $returnArray;
    }

    function getFields($nodeid)
    {
        global $_REQUEST, $beanFiles, $beanList;
        $pathArray = explode('::', $nodeid);
        //print_r($pathArray);
        $pathEnd = end($pathArray);
        //print_r($pathEnd);
        $nodeArray = explode(':', end($pathArray));

        $returnArray = array();

        // check if we have the root module or a union module ...
        if ($nodeArray[0] == 'root' || preg_match('/union/', $nodeArray[0]) == 1) {
            return $this->buildFieldArray($nodeArray['1']);
        }
        if ($nodeArray[0] == 'link') {
            $nodeModule = BeanFactory::getBean($nodeArray['1']);
            $nodeModule->load_relationship($nodeArray['2']);

            //PHP7 - 5.6 COMPAT
            //ORIGINAL: $this->buildFieldArray(nodeModule->$nodeArray['2']->getRelatedModuleName());
            $nodeArrayEl = $nodeArray['2'];
            $returnArray = $this->buildFieldArray($nodeModule->$nodeArrayEl->getRelatedModuleName());
            //END
        }

        //2013-01-09 add support for Studio Relate Fields
        if ($nodeArray[0] == 'relate') {
            require_once($beanFiles[$beanList[$nodeArray['1']]]);
            $nodeModule = new $beanList[$nodeArray['1']];

            $returnArray = $this->buildFieldArray($nodeModule->field_defs[$nodeArray[2]]['module']);
        }

        if ($nodeArray[0] == 'relationship') {
            require_once($beanFiles[$beanList[$nodeArray['1']]]);
            $nodeModule = new $beanList[$nodeArray['1']];
            $nodeModule->load_relationship($nodeArray['2']);

            //PHP7 - 5.6 COMPAT
            //ORIGINAL $returnArray = $this->buildLinkFieldArray($nodeModule->$nodeArray['2']);
            $nodeArrayEl = $nodeArray['2'];
            $returnArray = $this->buildLinkFieldArray($nodeModule->$nodeArrayEl);
            //END
        }
        if ($nodeArray[0] == 'audit') {
            $returnArray = $this->buildAuditFieldArray();
        }

        return $returnArray;
    }

    function getNodes($nodeid)
    {
        // main processing
        global $beanFiles, $beanList;

        $pathArray = explode('::', $nodeid);
        //print_r($pathArray);
        $pathEnd = end($pathArray);
        //print_r($pathEnd);
        $nodeArray = explode(':', end($pathArray));

        $returnArray = array();

        if ($nodeArray[0] == 'root' || preg_match('/union/', $nodeArray[0]) > 0) {
            return $this->buildNodeArray($nodeArray['1']);
        }

        if ($nodeArray[0] == 'link') {
            require_once($beanFiles[$beanList[$nodeArray['1']]]);
            $nodeModule = new $beanList[$nodeArray['1']];
            $nodeModule->load_relationship($nodeArray['2']);
            //PHP7 - 5.6 COMPAT
            //ORIGINAL $returnArray = return $this->buildNodeArray($nodeModule->$nodeArray['2']->getRelatedModuleName(), $nodeModule->{$nodeArray['2']});
            $nodeArrayEl = $nodeArray['2'];
            return $this->buildNodeArray($nodeModule->$nodeArrayEl->getRelatedModuleName(), $nodeModule->$nodeArrayEl);
            //END
        }

        //2013-01-09 add support for Studio Relate Fields
        if ($nodeArray[0] == 'relate') {
            require_once($beanFiles[$beanList[$nodeArray['1']]]);
            $nodeModule = new $beanList[$nodeArray['1']];

            return $this->buildNodeArray($nodeModule->field_defs[$nodeArray[2]]['module']);
        }
    }
    /*
     * Helper function to get the Fields for a module
     */

    private function buildNodeArray($module, $thisLink = '')
    {
        global $beanFiles, $beanList;
        require_once('include/utils.php');

        include('modules/KReports/kreportsConfig.php');

        $returnArray = array();

        // 2013-08-21 BUG #492 create a functional eleent holding the leafs for audit and relationships to make sure they stay on top after sorting
        $functionsArray = array();

        if (file_exists($beanFiles[$beanList[$module]])) {
            $nodeModule = BeanFactory::getBean($module);

            $nodeModule->load_relationships();
            // print_r($GLOBALS['dictionary']);//
            // 2011-07-21 add audit table
            if (isset($GLOBALS['dictionary'][$nodeModule->object_name]['audited'])
                && $GLOBALS['dictionary'] [$nodeModule->object_name]['audited'])
                    $functionsArray[] = array(
                    'path' => /* ($requester != '' ? $requester. '#': '') . */
                    'audit:'.$module.':audit',
                    'module' => 'auditRecords',
                    'leaf' => true);

            //2011-08-15 add relationship fields in many-to.many relationships
            //2012-03-20 change for 6.4
            if (
                $thisLink != '' && get_class($thisLink) == 'Link2'
            ) {
                if ($thisLink != '' && $thisLink->_relationship->relationship_type
                    == 'many-to-many')
                        $functionsArray[] = array(
                        'path' => /*  ($requester != '' ? $requester. '#': '') . */
                        'relationship:'.$thisLink->focus->module_dir /* $module */.':'.$thisLink->name,
                        'module' => 'relationship Fields',
                        'leaf' => true
                    );
            } else {
                if ($thisLink != '' && $thisLink->_relationship->relationship_type
                    == 'many-to-many')
                        $functionsArray[] = array(
                        'path' => /*  ($requester != '' ? $requester. '#': '') . */
                        'relationship:'.$thisLink->_bean->module_dir /* $module */.':'.$thisLink->name,
                        'name' => 'relationship Fields',
                        'leaf' => true
                    );
            }

            foreach ($nodeModule->field_defs as $field_name => $field_defs) {
                // 2011-03-23 also exculde the excluded modules from the config in the Module Tree
                //if ($field_defs['type'] == 'link' && (!isset($field_defs['module']) || (isset($field_defs['module']) && array_search($field_defs['module'], $excludedModules) == false))) {
                if ($field_defs['type'] == 'link' && (!isset($field_defs['reportable'])
                    || (isset($field_defs ['reportable']) && $field_defs['reportable']))
                    && (!isset($field_defs['module']) || (isset($field_defs['module'])
                    && array_search($field_defs['module'], $excludedModules) == false))) {


                    //BUGFIX 2010/07/13 to display alternative module name if vname is not maintained
                    if (isset($field_defs['vname']))
                            $returnArray[] = array(
                            'path' => /* ($requester != '' ? $requester. '#': '') . */
                            'link:'.$module.':'.$field_name,
                            'module' => ((translate($field_defs['vname'], $module))
                            == "" ? ('['.$field_defs['name'].']') : (translate($field_defs
                                ['vname'], $module))),
                            'bean' => $nodeModule->$field_name->focus->object_name,
                            'leaf' => false
                        );
                    elseif (isset($field_defs['module']))
                            $returnArray[] = array(
                            'path' => /*  ($requester != '' ? $requester. '#': '') . */
                            'link:'.$module.':'.$field_name,
                            'module' => translate($field_defs['module'], $module),
                            'bean' => $nodeModule->$field_name->focus->object_name,
                            'leaf' => false
                        );
                    else {
                        $field_defs_rel = $field_defs['relationship'];
                        $returnArray[] = array(
                            'path' => /* ($requester != '' ? $requester. '#': '') . */
                            'link:'.$module.':'.$field_name,
                            'module' => get_class($nodeModule->$field_defs_rel->_bean), //PHP7 - 5.6 COMPAT $nodeModule->$field_defs['relationship']->_bean
                            'bean' => $nodeModule->$field_name->focus->object_name,
                            'leaf' => false
                        );
                    }
                }

                //2013-01-09 add support for Studio Relate Fields
                // get all relate fields where the link is empty ... those with link we get via the link anyway properly
                if ($field_defs['type'] == 'relate' && empty($field_defs['link'])
                    && (!isset($field_defs['reportable']) || (isset($field_defs ['reportable'])
                    && $field_defs['reportable'])) && (!isset($field_defs['module'])
                    || (
                    isset($field_defs['module']) && array_search($field_defs['module'], $excludedModules)
                    == false))
                ) {
                    if (isset($field_defs['vname']))
                            $returnArray [] = array(
                            'path' => 'relate:'.$module.':'.$field_name,
                            'module' => ((translate($field_defs['vname'], $module))
                            == "" ? ('['.$field_defs['name'].']') : (translate($field_defs
                                ['vname'], $module))),
                            'bean' => $field_defs['module'],
                            'leaf' => false
                        );
                    elseif (isset($field_defs['module']))
                            $returnArray[] = array(
                            'path' => 'relate:'.$module.':'.$field_name,
                            'module' => translate($field_defs['module'], $module),
                            'bean' => $field_defs['module'],
                            'leaf' => false
                        );
                    else
                            $returnArray[] = array(
                            'path' => 'relate:'.$module.':'.$field_name,
                            'module' => $field_defs['name'],
                            'bean' => $field_defs['module'],
                            'leaf' => false
                        );
                }
            }
        }
        // 2013-08-21 BUG #492 added sorting for the module tree
        usort($returnArray, function ($a, $b) {
            if (strtolower($a['module']) > strtolower($b['module'])) return 1;
            elseif (strtolower($a['module']) == strtolower($b['module']))
                    return 0;
            else return -1;
        });

        // 2013-08-21 BUG #492 merge with the basic functional elelements
        return array_merge($functionsArray, $returnArray);
    }

    private function buildFieldArray($module)
    {
        global $beanFiles, $beanList;
        require_once('include/utils.php');
        $returnArray = array();
        if ($module != '' && $module != 'undefined' && file_exists($beanFiles[$beanList [$module]])) {
            require_once($beanFiles[$beanList[$module]]);
            $nodeModule = new $beanList[$module];

            foreach ($nodeModule->field_defs as $field_name => $field_defs) {
                if ($field_defs['type'] != 'link' && (!isset($field_defs['reportable'])
                    || (isset($field_defs['reportable']) && $field_defs['reportable']
                    == true))
                    //&& $field_defs['type'] != 'relate'
                    && (!array_key_exists('source', $field_defs) || (array_key_exists('source', $field_defs)
                    && (
                    $field_defs['source'] != 'non-db' || ($field_defs['source'] == 'non-db'
                    && $field_defs['type'] == 'kreporter')
                    )
                    ))
                ) {
                    $returnArray[] = array(
                        'id' => 'field:'.$field_defs['name'],
                        'name' => $field_defs['name'],
                        // in case of a kreporter field return the report_data_type so operators ar processed properly
                        // 2011-05-31 changed to kreporttype returned if fieldttype is kreporter
                        // 2011-10-15 if the kreporttype is set return it
                        //'type' => ($field_defs['type'] == 'kreporter') ? $field_defs['kreporttype'] :  $field_defs['type'],
                        'type' => (isset($field_defs['kreporttype'])) ? $field_defs['kreporttype']
                            : $field_defs['type'],
                        'text' => (translate($field_defs['vname'], $module) != '')
                            ? translate($field_defs['vname'], $module) : $field_defs['name'],
                        'leaf' => true,
                        'options' => $field_defs['options']
                    );
                }
            }
        }

        // 2013-08-21 Bug#493 sorting name for the fields
        usort($returnArray, "arraySortByName");

        return $returnArray;
    }

    private function buildAuditFieldArray()
    {

        //date_created
        $returnArray[] = array(
            'id' => 'field:date_created',
            'text' => 'date_created',
            'type' => 'datetime',
            'name' => 'date_created',
            'leaf' => true
        );

        $returnArray[] = array(
            'id' => 'field:created_by',
            'text' => 'created_by',
            'type' => 'varchar',
            'name' => 'created_by',
            'leaf' => true
        );

        $returnArray[] = array(
            'id' => 'field:field_name',
            'text' => 'field_name',
            'type' => 'varchar',
            'name' => 'field_name',
            'leaf' => true
        );

        $returnArray[] = array(
            'id' => 'field:before_value_string',
            'text' => 'before_value_string',
            'type' => 'varchar',
            'name' => 'before_value_string',
            'leaf' => true
        );

        $returnArray[] = array(
            'id' => 'field:after_value_string',
            'text' => 'after_value_string',
            'type' => 'varchar',
            'name' => 'after_value_string',
            'leaf' => true
        );

        $returnArray[] = array(
            'id' => 'field:before_value_text',
            'text' => 'before_value_text',
            'type' => 'text',
            'name' => 'before_value_text',
            'leaf' => true
        );

        $returnArray[] = array(
            'id' => 'field:after_value_text',
            'text' => 'after_value_text',
            'type' => 'text',
            'name' => 'after_value_text',
            'leaf' => true
        );
        return $returnArray;
    }

    private function buildLinkFieldArray($thisLink)
    {

        global $db;
        // bug #528
        if ($GLOBALS['db']->dbType == 'oci8')
                $queryRes = $db->query("SELECT column_name FROM all_tab_columns WHERE  table_name = '".strtoupper($thisLink->_relationship->join_table)."'");
        else
                $queryRes = $db->query('describe '.$thisLink->_relationship->join_table);

        while ($thisRow = $db->fetchByAssoc($queryRes)) {
            $returnArray[] = array(
                'id' => 'field:'.($thisRow['Field'] ? $thisRow['Field'] : $thisRow['column_name']),
                'text' => ($thisRow['Field'] ? $thisRow['Field'] : $thisRow['column_name']),
                // in case of a kreporter field return the report_data_type so operators ar processed properly
                'type' => 'varchar',
                'name' => ($thisRow['Field'] ? $thisRow['Field'] : $thisRow['column_name']),
                'leaf' => true
            );
        }

        return $returnArray;
    }

    function getPresentation($reportId, $requestParams)
    {

        global $db, $app_list_string, $current_language;
        $app_list_strings = return_app_list_strings_language($current_language);

        // initialize Return Array
        $retData = array();
        // get the report and the vizParams
        $thisReport = BeanFactory::getBean('KReports', $reportId);

        if (!isset($requestParams['start'])) $requestParams['start'] = 0;
        if (!isset($requestParams['limit'])) $requestParams['limit'] = 0;

        // set request Paramaters
        $reportParams = array('noFormat' => true, 'start' => isset($requestParams['start'])
                ? $requestParams['start'] : 0, 'limit' => isset($requestParams['limit'])
                ? $requestParams['limit'] : 0);

        if (isset($requestParams['sort']) && isset($requestParams['dir'])) {
            $reportParams['sortseq'] = $requestParams['dir'];
            $reportParams['sortid'] = $requestParams['sort'];
        } elseif (isset($requestParams['sort'])) {
            $sortParams = json_decode(html_entity_decode($requestParams['sort']));
            $reportParams['sortid'] = $sortParams[0]->property;
            $reportParams['sortseq'] = $sortParams[0]->direction;
        }

        if (isset($requestParams['whereConditions'])) {
            $thisReport->whereOverride = json_decode(html_entity_decode($requestParams['whereConditions']), true);
        }

        // if a filter is set evaluate it .. comes from the dashlet
        if (!empty($requestParams['filter'])) {
            $filter = $db->fetchByAssoc($db->query("SELECT selectedfilters FROM kreportsavedfilters WHERE id = '".$requestParams['filter']."'"));
            $thisReport->whereOverride = json_decode(html_entity_decode($filter['selectedfilters']), true);
        }

        //get parent bean
        if (isset($requestParams['parentbeanId']) && isset($requestParams['parentbeanModule'])) {
            $parentbean = BeanFactory::getBean($requestParams['parentbeanModule'], $requestParams['parentbeanId']);
            if ($parentbean->id) $reportParams['parentbean'] = $parentbean;
        }

        // print_r(json_decode(html_entity_decode($requestParams['whereConditions']), true));
        //catch dynamic options sent by drilldown plugin at first load
        $whereconditions = json_decode(html_entity_decode($thisReport->whereconditions), true);
        $whereoverride = array();
        if (isset($requestParams['dynamicoptions']) && !empty($requestParams['dynamicoptions'])) {
            $dynamicoptions = json_decode(html_entity_decode(base64_decode($requestParams['dynamicoptions'])), true);
            foreach ($whereconditions as $idx => $wherecondition) {
                foreach ($dynamicoptions as $idxdo => $dynamicoption) {
                    if ((!empty($dynamicoption['fieldid']) && $dynamicoption['fieldid']
                        == $wherecondition['fieldid']) ||
                        (!empty($dynamicoption['reference']) && $dynamicoption['reference']
                        == $wherecondition['reference'])) {
                        $whereconditions[$idx]['operator'] = $dynamicoption['operator'];
                        if (isset($dynamicoption['value']))
                                $whereconditions[$idx]['value'] = $dynamicoption['value'];
                        if (isset($dynamicoption['valuekey']))
                                $whereconditions[$idx]['valuekey'] = $dynamicoption['valuekey'];
                        if (isset($dynamicoption['valueto']))
                                $whereconditions[$idx]['valueto'] = $dynamicoption['valueto'];
                        if (isset($dynamicoption['valuetokey']))
                                $whereconditions[$idx]['valuetokey'] = $dynamicoption['valuetokey'];
                        $whereoverride [] = $whereconditions[$idx];
                    }
                }
            }
        }
        //allocate dynamicoptions to whereOverride
        if (is_array($thisReport->whereOverride)) {
            $thisReport->whereOverride = array_merge($thisReport->whereOverride, $whereoverride);
        } else $thisReport->whereOverride = $whereoverride;


        $retData['records'] = $thisReport->getSelectionResults($reportParams, isset($requestParams['snapshotid'])
                ? $requestParams['snapshotid'] : '0', false);

        // rework ... load from kQuery fieldArray
        $fieldArr = array();

        //2012-12-01 added link array to add to metadata for buiilding links in the frontend
        $linkArray = $thisReport->buildLinkArray($thisReport->kQueryArray->queryArray['root']['kQuery']->fieldArray);

        foreach ($thisReport->kQueryArray->queryArray['root']['kQuery']->fieldArray as $fieldid => $fieldname) {
            $thisFieldArray = array('name' => $fieldname);
            switch ($thisReport->fieldNameMap[$fieldid]['type']) {
                case 'int':
                    $thisFieldArray['type'] = 'integer';
                    break;
                case 'currency':
                case 'float':
                    $thisFieldArray['type'] = 'number';
                    break;
//type date will modified date format in store and check on timezone on user's machine! 
//2016-06-25 will become Sat Jun 24 2016 20:00:00 GMT-0400 (Zentalbrasilianische Normalzeit)                
//                case 'date':
//                    $thisFieldArray['type'] = 'date'; 
//                    break;
                default:
                    $thisFieldArray['type'] = 'string';
                    break;
            }

            // BUG #524 if we have a custom function then send string sicne we do not know what tpye is returned
            if ($thisReport->fieldNameMap[$fieldid]['customFunction'] != '')
                    $thisFieldArray['type'] = 'string';

            if (isset($linkArray[$fieldid]))
                    $thisFieldArray['linkInfo'] = json_encode($linkArray[$fieldid]);
            $fieldArr[] = $thisFieldArray;
        }

        $retData['metaData'] = array(
            'totalProperty' => 'count',
            'root' => 'records',
            'fields' => $fieldArr
        );

        $thisPresentationManager = new KReportPresentationManager();
        $addMetaData = $thisPresentationManager->getPresentationMetadata($thisReport);

        $retData['metaData'] = array_merge($retData['metaData'], $addMetaData);

        $retData['reportmetadata'] = array(
            'presentation_params' => json_decode(html_entity_decode($thisReport->presentation_params), true),
            'fields' => []
            //'ff' => json_decode(html_entity_decode($thisReport->listfields), true),
            //'fm' => $thisReport->fieldNameMap
        );

        foreach (json_decode(html_entity_decode($thisReport->listfields), true) as $reportField) {
            $retData['reportmetadata']['fields'][] = array(
                'fieldid' => $reportField['fieldid'],
                'fieldname' => $reportField['fieldname'],
                'name' => $reportField['name'],
                'type' => !empty($reportField['overridetype']) ? $reportField['overridetype']
                    : $thisReport->fieldNameMap[$reportField['fieldid']]['type'],
                'display' => $reportField['display'],
                'width' => $reportField['width'],
                'path' => $reportField['path'],
                'link' => $reportField['link'],
                'linkinfo' => $linkArray[$fieldid] ? $linkArray[$reportField['fieldid']]
                    : []
            );
        };

        if ($thisReport->kQueryArray->summarySelectString != '') {
            $retData['recordtotal'] = $db->fetchByAssoc($db->query($thisReport->kQueryArray->summarySelectString));
            $thisReport->processFormulas($retData['recordtotal']);
        }

        // do a count
        $parameters = array('start' => $requestParams['start'], 'limit' => $requestParams['limit']);
        if ($parentbean) $parameters['parentbean'] = $parentbean;
        $retData['count'] = $thisReport->getSelectionResults($parameters, isset($requestParams['snapshotid'])
                ? $requestParams['snapshotid'] : '0', true);
        $GLOBALS['log']->debug('Generated report presentation data: '.$retData);
        return $retData;
    }

    function getVisualization($reportId, $requestParams)
    {
        global $db;

        $retData = array();

        // get the report and the vizParams
        $thisReport = BeanFactory::getBean('KReports', $reportId);

        if (isset($requestParams['whereConditions'])) {
            $thisReport->whereOverride = json_decode(html_entity_decode($requestParams['whereConditions']), true);
        }

        // if a filter is set evaluate it .. comes from the dashlet
        if (!empty($requestParams['filter'])) {
            $filter = $db->fetchByAssoc($db->query("SELECT selectedfilters FROM kreportsavedfilters WHERE id = '".$requestParams['filter']."'"));
            $thisReport->whereOverride = json_decode(html_entity_decode($filter['selectedfilters']), true);
        }

        //get parent bean
        if (isset($requestParams['parentbeanId']) && isset($requestParams['parentbeanModule'])) {
            $parentbean = BeanFactory::getBean($requestParams['parentbeanModule'], $requestParams['parentbeanId']);
            if ($parentbean->id) $reportParams['parentbean'] = $parentbean;
        }


        //catch dynamic options sent by drilldown plugin at first load
        if (isset($requestParams['dynamicoptions']) && !empty($requestParams['dynamicoptions'])) {
            $dynamicoptions = json_decode(html_entity_decode(base64_decode($requestParams['dynamicoptions'])), true);
            if (count($thisReport->whereOverride) <= 0)
                    $thisReport->whereOverride = $dynamicoptions;
            else {
                $dynamicoptions = json_decode(html_entity_decode($requestParams['dynamicoptions']), true);
                foreach ($thisReport->whereOverride as $idx => $whereOverride) {
                    foreach ($dynamicoptions as $idxdo => $dynamicoption) {
                        if ($dynamicoption['fieldid'] == $whereOverride['fieldid']
                            || $dynamicoption['reference'] == $whereOverride['reference']) {
                            $thisReport->whereOverride[$idx] = $dynamicoption;
                        }
                    }
                }
            }
        }

        $vizData = json_decode(html_entity_decode($thisReport->visualization_params, ENT_QUOTES, 'UTF-8'), true);

        // get the managers
        $vizManager = new KReportVisualizationManager();
        $pluginManager = new KReportPluginManager();

        if (!is_array($reportParams)) $reportParams = array();

        // loop over the plugins
        for ($i = 0; $i < count($vizManager->layouts[$vizData['layout']]['items']); $i++) {
            // add the layout
            $itemData = array(
                'plugin' => $vizData[$i + 1]['plugin']
            );

            // get the object
            $vizObject = $pluginManager->getVisualizationObject($itemData['plugin']);

            // add the layout
            $itemData['layout'] = array(
                "top" => $vizManager->layouts[$vizData['layout']]['items'][$i]['top'],
                "left" => $vizManager->layouts[$vizData['layout']]['items'][$i]['left'],
                "height" => $vizManager->layouts[$vizData['layout']]['items'][$i]['height'],
                "width" => $vizManager->layouts[$vizData['layout']]['items'][$i]['width']
            );

            // add the data of the item
            $itemData['uid'] = $vizData[$i + 1][$itemData['plugin']]['uid'];
            // Mint start #39707
            if ($vizObject) {
                $itemData['data'] = $vizObject->getItem($vizData[$i + 1][$itemData['plugin']]['uid'], $thisReport, $vizData[$i
                    + 1][$itemData['plugin']], $reportParams, $requestParams['snapshotid']
                        ? $requestParams['snapshotid'] : '0');
            }
            // Mint end #39707

            $retData[] = $itemData;
        }

        return $retData;
    }

    function saveStandardLayout($reportId, $layoutParams)
    {
        $thisReport = new KReport();
        $thisReport->retrieve($reportId);

        $layoutParams = json_decode(html_entity_decode($layoutParams), true);

        $listFields = json_decode(html_entity_decode($thisReport->listfields), true);

        // process the Fields
        foreach ($listFields as $thisFieldIndex => $thisListField) {
            reset($layoutParams);
            foreach ($layoutParams as $thisLayoutParam) {
                if ($thisLayoutParam['dataIndex'] == $thisListField['fieldid']) {
                    $thisListField['width'] = $thisLayoutParam['width'];
                    $thisListField['sequence'] = (string) $thisLayoutParam['sequence'];

                    // bug 2011-03-04 sequence needs leading 0
                    if (strlen($thisListField['sequence']) < 2)
                            $thisListField['sequence'] = '0'.$thisListField['sequence'];

                    $thisListField['display'] = $thisLayoutParam['isHidden'] ? 'no'
                            : 'yes';
                    $listFields[$thisFieldIndex] = $thisListField;
                    break;
                }
            }
        }

        usort($listFields, 'arraySortBySequence');

        $thisReport->listfields = json_encode($listFields);
        $thisReport->save();
    }
###################### BEGIN SavedFilters ksavedfilters ######################

    public function getSavedFilters($params = array())
    {
        global $db;
        $whereClause = "";
        if (!empty($params['assigneduserid']))
                $whereClause = " AND (ksf.assigned_user_id='".$params['assigneduserid']."' OR ksf.is_global > 0)";
        $results = array();
        $mod_strings = return_module_language($GLOBALS['current_language'], 'KReports');

        //set empty record for Viewer only
        if (isset($params['context']) && $params['context'] == "Viewer") {
            $results[] = array(
                'savedfilter_id' => 'none',
                'name' => '---',
                'kreport_id' => $params['reportid'],
                'assigned_user_id' => null,
                'assigned_user_name' => null,
                'is_global' => 1,
                'selectedfilters' => null
            );
        }

        //read passed whereConditions
        $whereconditions = array();
        if (isset($params['currentWhereConditions'])) {
            $whereconditions = json_decode(html_entity_decode($params['currentWhereConditions'], ENT_QUOTES), true);
        }

        //get data
        $records = $db->query("SELECT ksf.*, u.user_name FROM kreportsavedfilters ksf "
            ."INNER JOIN users u ON u.id = ksf.assigned_user_id "
            ."WHERE ksf.deleted= 0 AND ksf.kreport_id='".$params['reportid']."' "
            .$whereClause
            ." ORDER BY ksf.name ASC");

        //prepare records
        while ($record = $db->fetchByAssoc($records)) {
            $selectedfilters = json_decode(html_entity_decode($record['selectedfilters'], ENT_QUOTES), true);
            $whereconditionsFieldids = array();
            foreach ($whereconditions as $idx => $condition) {
                $whereconditionsFieldids[] = $condition['fieldid'];
            }

            //loop selectedfilters over whereconditions and set status
            //if one of the filters IDs does not correspond to whereconditions, the whole savedfilter is status =0
            $status = 1;
            foreach ($selectedfilters as $idxfi => $filter) {
                if (!in_array($filter['fieldid'], $whereconditionsFieldids)) {
                    $status = 0;
                    break;
                }
            }

            //set entry values
            $results[] = array(
                'savedfilter_id' => $record['id'],
                'name' => ($record['is_global'] ? $mod_strings['LBL_KSAVEDFILTERS_IS_GLOBAL_MARK'].' '
                    : '').$record['name'],
                'kreport_id' => $record['kreport_id'],
                'assigned_user_id' => $record['assigned_user_id'],
                'assigned_user_name' => $record['user_name'],
                'is_global' => $record['is_global'],
                'selectedfilters' => $record['selectedfilters'],
                'status' => $status,
            );
        }

        return $results;
    }
###################### END SavedFilters ksavedfilters ######################    
###################### BEGIN BucketManager ######################    

    /**
     * Handler for Bucketmanager
     * @global type $_REQUEST
     * @global type $beanFiles
     * @global type $beanList
     * @param type $nodeid
     * @return array
     */
    public function getGroupings()
    {
        $returnArray = array();
        $queryOnTable = false;

        //check first if table exists (PRO version). If not, just return empty array
        $resArray = $GLOBALS['db']->query("SHOW TABLES like 'kreportgroupings'");
        if ($GLOBALS['db']->getRowCount($resArray) > 0) $queryOnTable = true;

        //get groupings
        if ($queryOnTable) {
            $resArray = $GLOBALS['db']->query('SELECT id, name, description, modulename, fieldname, fieldtype, mapping FROM kreportgroupings WHERE deleted = \'0\'');

            while ($thisEntry = $GLOBALS['db']->fetchByAssoc($resArray)) {
                $returnArray[] = array(
                    'id' => $thisEntry['id'],
                    'name' => $thisEntry['name'],
                    //                'description' => $thisEntry['description'],
                    'modulename' => $thisEntry['modulename'],
                    'fieldname' => $thisEntry['fieldname'],
                    'fieldtype' => $thisEntry['fieldtype'],
                    'mapping' => $thisEntry['mapping']
                );
            }
        }
        return $returnArray;
    }

    /**
     * Handler for Bucketmanager
     * Get enumfields defined in module
     * Add 2016-04-26; get also fields of type char, varchar, text
     * @global type $_REQUEST
     * @global type $beanFiles
     * @global type $beanList
     * @param type $nodeid
     * @return array
     */
    public function getEnumfields($module)
    {
        $allowedFieldTypes = array('enum', 'char', 'varchar', 'text', 'name');
        $fieldsArray = $this->buildFieldArray($module);
        if (is_array($fieldsArray)) {
            foreach ($fieldsArray as $idx => $field) {
                if (!in_array($field['type'], $allowedFieldTypes)) {
                    unset($fieldsArray[$idx]);
                }
            }
            //re-index
            $fieldsArray = array_values($fieldsArray);
        } else $fieldsArray = array();

        return $fieldsArray;
    }

    /**
     * @todo: when dom is an array because values are generated by a function
     * For now case when dom is a string
     * @global type $app_list_strings
     * @global type $app_strings
     * @global type $current_language
     * @param type $params
     * @return array
     */
    public function getEnumfieldvalues($params)
    {
        global $app_list_strings, $app_strings, $current_language;

        //process params: make keys to variables conatining values
//        foreach($params as $p => $pp)
//            $$p = $pp;
        $module = $params['modulename'];
        $fieldname = $params['fieldname'];
        $fieldvalue = $params['fieldvalue'];
        $start = $params['start'];
        $limit = $params['limit'];


        if (empty($module) || empty($fieldname)) return array();

        $useArray = array();
        $fieldsArray = $this->buildFieldArray($module);
        foreach ($fieldsArray as $idx => $field) {
            if ($field['type'] == 'enum' && $field['name'] == $fieldname) {
                $dom = $fieldsArray[$idx]['options'];
                $getValuesFrom = 'dom';
                break;
            } elseif ($field['name'] == $fieldname && !empty($fieldvalue)) {
                $getValuesFrom = 'db';
                break;
            }
        }

        switch ($getValuesFrom) {
            case 'dom':
                $app_list_strings = return_app_list_strings_language($current_language);
                $app_strings = return_application_language($current_language);

                if (isset($app_list_strings[$dom])) {
                    $useArray = $app_list_strings[$dom];
                } elseif (isset($app_strings[$dom])) {
                    $useArray = $app_strings[$dom];
                }

                //splice array according to start and limit
                $totalCount = count($useArray);
                if (isset($limit)) {
                    $useArray = array_slice($useArray, $start, $limit);
                }
                break;
            case 'db':
                //query on table
                $bean = BeanFactory::getBean($module);
                //Get table_name from $module
                $q = "SELECT DISTINCT(".$fieldname.") colvalue "
                    ." FROM ".$bean->table_name." "
                    ." WHERE ".$fieldname." LIKE '".$fieldvalue."%' AND deleted = 0 "
                    ." LIMIT ".$start.", ".$limit.";";
                if (!$res = $GLOBALS['db']->query($q))
                        $GLOBALS['log']->fatal("DB query error ".$GLOBALS['db']->last_error);
                while ($row = $GLOBALS['db']->fetchByAssoc($res)) {
                    $useArray[$row['colvalue']] = $row['colvalue'];
                }

                //total count
                $q = "SELECT count(DISTINCT(".$fieldname.")) total "
                    ." FROM ".$bean->table_name." "
                    ." WHERE ".$fieldname." LIKE '".$fieldvalue."%' AND deleted = 0 ";
                if (!$res = $GLOBALS['db']->query($q))
                        $GLOBALS['log']->fatal("DB query error count ".$GLOBALS['db']->last_error);
                while ($row = $GLOBALS['db']->fetchByAssoc($res)) {
                    $totalCount = $row['total'];
                }
                break;
        }

        $fieldsArray = array();
        foreach ($useArray as $value => $lbl)
            $fieldsArray[] = array('enumvalue' => $value, 'label' => (empty($value)
                    ? '-- empty --' : $lbl));

        return array('items' => $fieldsArray, 'total' => $totalCount);
    }

    public function saveNewGrouping($params)
    {
        $returnArray = array();
        $q = "INSERT INTO kreportgroupings (id,name,modulename,fieldname,fieldtype) "
            ."VALUES('".$params['id']."','".$params['name']."','".$params['modulename']."','".$params['fieldname']."', '".$this->getFieldType($params['modulename'], $params['fieldname'])."')";
        if ($GLOBALS['db']->query($q)) {
            $returnArray = array('success' => 1, 'groupingid' => $params['id']);
        } else
                $returnArray = array('success' => 0, 'msg' => 'Could not insert record into DB.kreportgroupings');

        return $returnArray;
    }

    public function deleteGrouping($params)
    {
        $returnArray = array();
        $q = "UPDATE kreportgroupings SET "
            ."deleted = 1 "
            ."WHERE id='".$params['id']."'";
        if ($GLOBALS['db']->query($q)) {
            $returnArray = array('success' => 1, 'groupingid' => $params['id']);
        } else
                $returnArray = array('success' => 0, 'msg' => 'Could not set record deleted into DB.kreportgroupings');

        return $returnArray;
    }

    public function updateGrouping($params)
    {
        $returnArray = array();
        $q = "UPDATE kreportgroupings SET "
            ."name = '".$GLOBALS['db']->quote($params['name'])."', "
            ."modulename = '".$GLOBALS['db']->quote($params['modulename'])."', "
            ."fieldname = '".$GLOBALS['db']->quote($params['fieldname'])."', "
            ."fieldtype = '".(empty($params['fieldtype']) ? $this->getFieldType($params['modulename'], $params['fieldname'])
                : $params['fieldtype'])."', "
            ."mapping='".$GLOBALS['db']->quote($params['mapping'])."' "
            ."WHERE id='".$params['id']."'";

        if ($GLOBALS['db']->query($q)) {
            $returnArray = array('success' => 1, 'groupingid' => $params['id']);
        } else
                $returnArray = array('success' => 0, 'msg' => 'Could not update record in DB.kreportgroupings');

        return $returnArray;
    }
//    public function saveMapping($params){
//        $return = array();
//        $q = "UPDATE kreportgroupings SET "
//                . "mapping='".$GLOBALS['db']->quote($params['mapping'])."'"
//                . "WHERE id='".$params['groupingid']."'";
//        if($GLOBALS['db']->query($q)){
//            $return = array('success' => 1, 'groupingid' => $params['groupingid']);
//        }
//        else
//           $return = array('success' => 0, 'msg' => 'Could not save mapping into DB.kreportgroupings');
//        
//        echo json_encode($return);
//    }
###################### END BucketManager ######################    
###################### BEGIN DListManager ######################    

    /**
     * Handler for DListManager
     * @global type $_REQUEST
     * @global type $beanFiles
     * @global type $beanList
     * @param type $nodeid
     * @return array
     */
    public function getDLists()
    {
        $returnArray = array();
        $queryOnTable = false;

        //check first if table exists (PRO version). If not, just return empty array
        $resArray = $GLOBALS['db']->query("SHOW TABLES like 'kreportdlists'");
        if ($GLOBALS['db']->getRowCount($resArray) > 0) $queryOnTable = true;

        //get dlists
        if ($queryOnTable) {
            $resArray = $GLOBALS['db']->query('SELECT id, name, description, dlistdata FROM kreportdlists WHERE deleted = \'0\'');
            while ($thisEntry = $GLOBALS['db']->fetchByAssoc($resArray)) {
                $returnArray[] = array(
                    'id' => $thisEntry['id'],
                    'name' => $thisEntry['name'],
                    //                'description' => $thisEntry['description'],
                    'dlistdata' => $thisEntry['dlistdata'],
                );
            }
        }
        return $returnArray;
    }

    /**
     * Handler for DListManager
     * @global type $_REQUEST
     * @global type $beanFiles
     * @global type $beanList
     * @param type $nodeid
     * @return array
     */
    public function getDList($id)
    {
        $returnArray = array();
        $resArray = $GLOBALS['db']->query('SELECT id, name, description, dlistdata FROM kreportdlists WHERE id = \''.$id.'\'');

        while ($thisEntry = $GLOBALS['db']->fetchByAssoc($resArray)) {
            $returnArray[] = array(
                'id' => $thisEntry['id'],
                'name' => $thisEntry['name'],
//                'description' => $thisEntry['description'],
                'dlistdata' => $thisEntry['dlistdata'],
            );
        }
        return $returnArray;
    }

    public function getUsers($params)
    {
        $returnArray = array();
        //get users list
        $callGetList = false;
        $user = new User();
        $order_by = "users.last_name ASC";

        if (!empty($params['userids'])) {
            $where = "users.id IN('".implode("','", json_decode($params['userids'], true))."')";
            $callGetList = true;
        } elseif ($params['all'] == '*') {
            $where = "";
            $callGetList = true;
        }

        if (!empty($params['nameFilter'])) {
            $where = " users.first_name like '%".$params['nameFilter']."%' OR users.last_name like '%".$params['nameFilter']."%' OR users.user_name like '%".$params['nameFilter']."%' ";
        }

        if ($callGetList) {
            $users = $user->get_list($order_by, $where);

            if (is_array($users)) {
                foreach ($users['list'] as $thisEntry) {
                    //grab accoutn data
                    $thisEntry->fill_in_additional_detail_fields();
                    //grab primary e-mail
                    if (empty($thisEntry->email1)) {
                        $emails = $thisEntry->get_linked_beans('email_addresses_primary', 'EmailAddress');
                        for ($i = 0; $i < count($emails); $i++) {
                            $thisEntry->email1[] = $emails[0]->email_address;
                        }
                        unset($emails);
                    }
                    $returnArray[] = array(
                        'id' => $thisEntry->id,
                        'firstname' => $thisEntry->first_name,
                        'lastname' => $thisEntry->last_name,
                        'username' => $thisEntry->user_name,
                        'email1' => (is_array($thisEntry->email1) ? implode(", ", $thisEntry->email1)
                            : $thisEntry->email1),
                    );
                }
            }
            unset($users);
        }
        unset($user);
        return $returnArray;
    }

    public function getContacts($params)
    {
        $returnArray = array();

//get contacts list
        $callGetList = false;
        $contact = new Contact();
        $order_by = "contacts.last_name ASC";

        if (!empty($params['contactids'])) {
            $where = "contacts.id IN('".implode("','", json_decode($params['contactids'], true))."')";
            $callGetList = true;
        } elseif (!empty($params['like'])) {
            $where = "contacts.last_name LIKE '".$params['like']."%' "
                ."OR contacts.first_name LIKE '".$params['like']."%'";
            $callGetList = true;
        }
        //get contact list
        if ($callGetList) {
            //get contact beans
            $contacts = $contact->get_list($order_by, $where);

            if (is_array($contacts)) {
                foreach ($contacts['list'] as $thisEntry) {
                    //grab accoutn data
                    $thisEntry->fill_in_additional_detail_fields();
                    //grab primary e-mail
                    if (empty($thisEntry->email1)) {
                        $emails = $thisEntry->get_linked_beans('email_addresses_primary', 'EmailAddress');
                        for ($i = 0; $i < count($emails); $i++) {
                            $thisEntry->email1[] = $emails[0]->email_address;
                        }
                        unset($emails);
                    }
                    $returnArray[] = array(
                        'id' => $thisEntry->id,
                        'firstname' => $thisEntry->first_name,
                        'lastname' => $thisEntry->last_name,
                        'username' => $thisEntry->user_name,
                        'email1' => (is_array($thisEntry->email1) ? implode(", ", $thisEntry->email1)
                            : $thisEntry->email1),
                        'accountname' => $thisEntry->account_name,
                        'accountid' => $thisEntry->account_id,
                    );
                }
            }
            unset($contacts);
            unset($contact);
        }
        return $returnArray;
    }

    public function getKReports($params)
    {
        $returnArray = array();

        //get kreports list
        $callGetList = false;
        $kreport = new KReport();
        $order_by = "kreports.name ASC";

        if (!empty($params['kreportids'])) {
            $where = "kreports.id IN('".implode("','", json_decode($params['kreportids'], true))."')";
            $callGetList = true;
        } elseif ($params['all'] == '*') {
            $where = "";
            $callGetList = true;
        }

        if (!empty($params['nameFilter'])) {
            $where = " kreports.name like '%".$params['nameFilter']."%'";
            $callGetList = true;
        }

        if (!empty($where)) $where .= ' AND ';
        $where .= " kreports.report_module in ('Users', 'Contacts') ";

        if ($callGetList) {
            $kreports = $kreport->get_list($order_by, $where, 0, 100);
            if (is_array($kreports)) {
                foreach ($kreports['list'] as $thisEntry) {
                    $returnArray[] = array(
                        'id' => $thisEntry->id,
                        'name' => $thisEntry->name,
                        'modulename' => $thisEntry->report_module,
                    );
                }
            }
            unset($kreports);
        }
        unset($kreport);
        return $returnArray;
    }

    public function saveNewDList($params)
    {
        $returnArray = array();
        $q = "INSERT INTO kreportdlists (id, name, description) "
            ."VALUES('".$params['id']."','".$params['name']."', null)";
        if ($GLOBALS['db']->query($q)) {
            $returnArray = array('success' => 1, 'dlistid' => $params['id']);
        } else
                $returnArray = array('success' => 0, 'msg' => 'Could not insert record into DB.kreportdlists');

        return $returnArray;
    }

    public function deleteDList($params)
    {

        $returnArray = array();
        $q = "UPDATE kreportdlists SET "
            ."deleted = 1 "
            ."WHERE id='".$params['id']."'";
        if ($GLOBALS['db']->query($q)) {
            $returnArray = array('success' => 1, 'dlistid' => $params['id']);
        } else
                $returnArray = array('success' => 0, 'msg' => 'Could not set record deleted into DB.kreportdlists');

        return $returnArray;
    }

    public function updateDList($params)
    {
        $returnArray = array();
        $q = "UPDATE kreportdlists SET "
            ."name = '".$GLOBALS['db']->quote($params['name'])."', "
            ."dlistdata = '".$GLOBALS['db']->quote($params['dlistdata'])."' "
            ."WHERE id='".$params['id']."'";

        if ($GLOBALS['db']->query($q)) {
            $returnArray = array('success' => 1, 'dlistid' => $params['id']);
        } else
                $returnArray = array('success' => 0, 'msg' => 'Could not update record in DB.kreportdlists');

        return $returnArray;
    }
###################### END DListManager ######################      

    public function getFieldType($modulename, $fieldname)
    {
        $fieldtype = "";
        $modulefields = $this->getModuleFields($modulename);
        foreach ($modulefields as $idx => $fielddata) {
            if ($fielddata['field'] == $fieldname)
                    $fieldtype = $fielddata['type'];
        }

        //simplify field types
        switch ($fieldtype) {
            case 'char':
            case 'varchar':
            case 'name':
            case 'text':
                $fieldtype = 'text';
                break;
            case 'enum':
                $fieldtype = 'enum';
                break;
            case 'int':
            case 'currency':
                $fieldtype = 'number';
                break;
        }

        return $fieldtype;
    }

    /**
     * get KReporter related config vars from config.php and config_override.php
     */
    public function getConfig()
    {
        require_once 'modules/Configurator/Configurator.php';
        $configurator = new Configurator();
        $configurator->loadConfig();

        return array('KReports' => $configurator->config['KReports']);
    }

    /**
     * get KReporter labels (needed in kpublishing to subpanel
     */
    public function getLabels()
    {
        $labels = return_module_language((empty($GLOBALS['current_language']) ? $GLOBALS['sugar_config']['default_language']
                : $GLOBALS['current_language']), 'KReports');

        return $labels;
    }
}
/*
 * function for array sorting
 */

function arraySortBySequence($a, $b)
{
    return ($a['sequence'] < $b['sequence']) ? -1 : 1;
}

// 2013-08-21 BUG #492 function to be called from usort to sort by Text
function arraySortByText($a, $b)
{
    if (strtolower($a['text']) > strtolower($b['text'])) return 1;
    elseif (strtolower($a['text']) == strtolower($b['text'])) return 0;
    else return -1;
}

// 2013-08-21 Bug#493 sorting name for the fields
function arraySortByName($a, $b)
{
    if (strtolower($a['name']) > strtolower($b['name'])) return 1;
    elseif (strtolower($a['name']) == strtolower($b['name'])) return 0;
    else return -1;
}

// 2014-03-26 sorting of modules Bug #517
function arraySortByDescription($a, $b)
{
    if (strtolower($a['description']) > strtolower($b['description'])) return 1;
    elseif (strtolower($a['description']) == strtolower($b['description']))
            return 0;
    else return -1;
}
