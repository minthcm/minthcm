<?php

/*
 * This File is part of KREST is a Restful service extension for SugarCRM
 * 
 * Copyright (C) 2015 AAC SERVICES K.S., DOSTOJEVSKÉHO RAD 5, 811 09 BRATISLAVA, SLOVAKIA
 * 
 * you can contat us at info@spicecrm.io
 *
 * This program is free software; you can redistribute it and/or modify it under the terms of the GNU General Public License as published by the Free Software Foundation; either version 2 of the License, or (at your option) any later version.
 * 
 * This program is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along with this program; if not, write to the Free Software Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA 02111-1307 USA
 */

class KRESTModuleHandler
{

    var $app = null;
    var $sessionId = null;
    var $tmpSessionId = null;
    var $requestParams = array();
    var $excludeAuthentication = array();
    var $spiceFavoritesClass = null;

    public function __construct($app)
    {
        $this->app = $app;

        // some general global settings
        global $disable_date_format;
        $disable_date_format = true;
    }

    public function get_mod_language($modules, $lang)
    {
        $modLang = array();

        foreach ($modules as $module)
            $modLang[$module] = return_module_language($lang, $module, true);

        return $modLang;
    }

    public function get_dynamic_domains($modules, $language) {

        global $beanList, $dictionary;

        $dynamicDomains = array();

        foreach ($modules as $module) {

            $thisBean = BeanFactory::getBean($module);
            $fieldDefs = $thisBean->getFieldDefinitions();

            //$domainFunctions = array_map(function($fieldDef) { return isset($fieldDef['spice_domain_function']) ? $fieldDef['spice_domain_function'] : array();} , $dictionary[$beanList[$module]]['fields']);
            $fieldDefsWithDomainFunction = array_filter($fieldDefs, function($fieldDef) { return isset($fieldDef['spice_domain_function']);});

            foreach($fieldDefsWithDomainFunction as $fieldDef) {
                $functionName = is_array($fieldDef['spice_domain_function']) ? $fieldDef['spice_domain_function']['name'] : $fieldDef['spice_domain_function'];
                $domainKey = 'spice_domain_function_' . strtolower($functionName) . '_dom';
                $dynamicDomains[$domainKey] = $this->processSpiceDomainFunction($thisBean, $fieldDef, $language);
            }
        }

        return $dynamicDomains;
    }

    public function get_bean_list($beanModule, $searchParams)
    {
        global $current_user, $sugar_config, $dictionary;

        // whitelist currencies modules
        $aclWhitelist = array(
            'Currencies'
        );

        // acl check if user can list
        if (!ACLController::checkAccess($beanModule, 'list', true) && !in_array($beanModule, $aclWhitelist)) {
            http_response_code(403);
            echo('not authorized for module ' . $beanModule);
            exit;
        }

        $thisBean = BeanFactory::getBean($beanModule);

        //echo(print_r($searchParams, true));
        if (is_array(json_decode($searchParams['fields'], true))) {
            $returnFields = json_decode($searchParams['fields'], true);
        } else {
            $returnFields = array();
            $listFields = $this->getModuleListdefs($beanModule, $thisBean, ($searchParams['client'] == 'mobile' ? true : false));
            foreach ($listFields as $thisField)
                $returnFields[] = $thisField['name'];
        }
        $beanData = array();
        $facets = array();
        $totalcount = 0;

        // build the where claues is searchterm is specified
        if (!empty($searchParams['searchterm'])) {
            $searchParams['whereclause'] = '';
            $searchtermArray = explode(' ', $searchParams['searchterm']);
            foreach ($searchtermArray as $thisSearchterm) {
                $searchTerms = array();
                $searchTermFields = $searchParams['searchtermfields'] ? json_decode(html_entity_decode($searchParams['searchtermfields']), true) : [];
                if ($searchTermFields) {
                    foreach ($searchTermFields as $fieldName) {
                        switch ($thisBean->$field_defs_field[$fieldName]['type']) {
                            case 'relate':
                                $searchTerms[] = ($thisBean->field_defs[$fieldName]['join_name'] ?: $thisBean->field_defs[$fieldName]['table']) . '.' . $thisBean->field_defs[$fieldName]['rname'] . ' like \'%' . $thisSearchterm . '%\'';
                                break;
                            default:
                                $searchTerms[] = $thisBean->table_name . '.' . $fieldName . ' like \'%' . $thisSearchterm . '%\'';
                                break;
                        }
                    }
                } else {
                    foreach ($thisBean->field_defs as $fieldName => $fieldData) {
                        if ($fieldData['unified_search'] && $fieldData['source'] != 'non-db')
                            $searchTerms[] = $thisBean->table_name . '.' . $fieldName . ' like \'%' . $thisSearchterm . '%\'';
                    }
                }

                if (count($searchTerms) > 0) {
                    if ($searchParams['whereclause'] != '')
                        $searchParams['whereclause'] .= ' AND ';

                    $searchParams['whereclause'] .= '(' . implode(' OR ', $searchTerms) . ')';
                }
            }
        }


        $addJoins = '';
        if (!empty($searchParams['searchfields'])) {
            $searchConditions = json_decode(html_entity_decode($searchParams['searchfields']), true);
            if (is_array($searchConditions) && count($searchConditions) > 0) {
                $searchConditionWhereClause = $this->buildConditionsWhereClause($thisBean, $searchConditions, $addJoins);
                if ($searchConditionWhereClause) {
                    if ($searchParams['whereclause'] != '')
                        $searchParams['whereclause'] .= ' AND ';

                    $searchParams['whereclause'] .= '(' . $searchConditionWhereClause . ')';
                }
            }
        }

        // set the favorite as mandatory if search by favortes is set
        if ($searchParams['searchmyitems']) {
            if ($searchParams['whereclause'] != '')
                $searchParams['whereclause'] .= ' AND ';

            $searchParams['whereclause'] .= $thisBean->table_name . ".assigned_user_id='" . $current_user->id . "'";
        }

        // set the favorite as mandatory if search by favortes is set
        $favorites = 'query';
        if ($searchParams['searchfavorites'])
            $favorites = 'only';

        //  addd a sort criteria
        if (!empty($searchParams['sortfield'])) {
            if (!json_decode(html_entity_decode($searchParams['sortfield']))) {
                $searchParams['orderby'] = '';
                $searchParams['orderby'] .= /* $thisBean->table_name . '.' . */
                    $searchParams['sortfield'] . ' ' . ($searchParams['sortdirection'] ? strtoupper($searchParams['sortdirection']) : 'ASC');
            } else {
                $sortObject = json_decode(html_entity_decode($searchParams['sortfield']));
                $searchParams['orderby'] = $this->sort_object_handler($thisBean->table_name, $sortObject) . ' ' . ($searchParams['sortdirection'] ? strtoupper($searchParams['sortdirection']) : 'ASC');
            }
        }

        $filterFields = array();
        foreach ($returnFields as $returnField) {
            $filterFields[$returnField] = true;
        }
        // $beanList = $thisBean->get_list($searchParams['orderby'], $searchParams['whereclause'], $searchParams['offset'], $searchParams['limit']);
        $queryArray = $thisBean->create_new_list_query($searchParams['orderby'], $searchParams['whereclause'], $filterFields, array(), false, '', true, $thisBean, true);

        $spiceFavoritesClass = $this->getSpiceFavoritesClass();
        if ($spiceFavoritesClass) {
            $favoritesQueryParts = $spiceFavoritesClass::getBeanListQueryParts($thisBean, $searchParams['searchfavorites']);
            $queryArray['from'] .= $favoritesQueryParts['from'] . $favoritesQueryParts['where'];
            $queryArray['secondary_from'] .= $favoritesQueryParts['from'] . $favoritesQueryParts['where'];
        }

        // any additional joins we might have gotten
        $queryArray['from'] .= ' ' . $addJoins;
        $queryArray['secondary_from'] .= ' ' . $addJoins;

        // build the query
        $query = $queryArray['select'] . $queryArray['from'] . $queryArray['where'] . $queryArray['order_by'];

        // process the query
        if (empty($searchParams['offset']))
            $searchParams['offset'] = 0;

        if (empty($searchParams['limit']))
            $searchParams['limit'] = $sugar_config['list_max_entries_per_page'] ?: 25;

        $beanList = $thisBean->process_list_query($query, $searchParams['offset'], $searchParams['limit'] + 1);

        $includeReminder = $searchParams['includeReminder'] ? true : false;
        $includeNotes = $searchParams['includeNotes'] ? true : false;
        // $beanList = $thisBean->get_full_list($searchParams['orderby'], $searchParams['whereclause']);
        // foreach ($beanList['list'] as $thisBean) {
        foreach ($beanList['list'] as $thisBean) {
            // load all list fields .. force load details is used in opportunity ... not sure if anywhere else
            $thisBean->force_load_details = true;
            $thisBean->fill_in_additional_list_fields();

            $beanData[] = $this->mapBeanToArray($beanModule, $thisBean, $returnFields, $includeReminder, $includeNotes);
        }

        // get the count
        $totalcount = 0;
        if ((isset($searchParams['count']) && $searchParams['count'] === true) || (!isset($searchParams['count']) && !$sugar_config['disable_count_query'])) {
            $count_query = $thisBean->create_list_count_query($query);
            if (!empty($count_query)) {
                // We have a count query.  Run it and get the results.
                $result = $thisBean->db->query($count_query);
                $assoc = $thisBean->db->fetchByAssoc($result);
                if (!empty($assoc['c'])) {
                    $totalcount = $assoc['c'];
                }
            }
        }

        // special handling for currencies since home currency is stored with id -99 and not in the DB
        if ($beanModule == 'Currencies') {
            global $sugar_config;
            $beanData[] = array(
                'id' => '-99',
                'iso4217' => $sugar_config['default_currency_iso4217'],
                'name' => $sugar_config['default_currency_name'],
                'symbol' => $sugar_config['default_currency_symbol'],
                'status' => 'Active',
                'conversion_rate' => 1,
                'deleted' => 0
            );

            $totalcount++;
        }

        return array(
            'totalcount' => $totalcount,
            'list' => $beanData
        );
    }

    private
    function buildConditionsWhereClause($bean, $conditions, &$addJoins)
    {
        $condWhereClause = '';
        if (!empty($conditions['join'])) {
            foreach ($conditions['conditions'] as $condition) {
                if ($condWhereClause != '')
                    $condWhereClause .= ' ' . $conditions['join'] . ' ';

                if (!empty($condition['join']))
                    $condWhereClause .= '(' . $this->buildConditionsWhereClause($bean, $condition, $addJoins) . ')';
                else
                    $condWhereClause .= $this->buildConditionWhereClause($bean, $condition, $addJoins);
            }
        } else
            $condWhereClause .= $this->buildConditionWhereClause($bean, $conditions, $addJoins);

        return $condWhereClause;
    }

    private
    function buildConditionWhereClause($bean, $condition, &$addJoins)
    {
        // check if we have to add the table to the field name
        $fieldName = $condition['field'];
        if (strpos($condition['field'], '.') === false)
            $fieldName = $bean->table_name . '.' . $condition['field'];

        // check if we have an aadditonal join
        if (is_array(($condition['addjoin']))) {

            $addJoins .= ' ' . $condition['addjoin']['jointype'] . ' JOIN ' . $condition['addjoin']['jointable'] . ' ON ' . $bean->table_name . '.' .
                $condition['addjoin']['joinid'] . ' = ' . $condition['addjoin']['jointable'] . '.' . $condition['addjoin']['jointableid'] . ' AND ' .
                $condition['addjoin']['jointable'] . '.deleted = 0';

        }

        switch ($condition['operator']) {
            case 'doy>=':
                return 'DAYOFYEAR(' . $fieldName . ') >= ' . $condition['value'];
                break;
            case 'doy<=':
                return 'DAYOFYEAR(' . $fieldName . ') <= ' . $condition['value'];
                break;
            case 'in':
            case 'not in':
                return $fieldName . ' ' . $condition['operator'] . ' (' . $condition['value'] . ') ';
                break;
            case 'currentuser':
                return $fieldName . ' = \'' . $GLOBALS['current_user']->id . '\'';
                break;
            default:
                return $fieldName . ' ' . $condition['operator'] . ' \'' . $condition['value'] . '\'';
                break;
        }
    }

    public
    function get_bean_detail($beanModule, $beanId, $requestParams)
    {

        // acl check if user can get the detail
        if (!ACLController::checkAccess($beanModule, 'view', true)) {
            http_response_code(403);
            echo('not authorized for module ' . $beanModule);
            exit;
        }

        $thisBean = BeanFactory::getBean($beanModule, $beanId);
        if (!$thisBean) {
            http_response_code(404);
            echo('record not found');
            exit;
        }

        if ($requestParams['writetracker']) {
            $this->write_spiceuitracker($beanModule, $thisBean);
        }

        $includeReminder = $requestParams['includeReminder'] ? true : false;
        $includeNotes = $requestParams['includeNotes'] ? true : false;

        return $this->mapBeanToArray($beanModule, $thisBean, array(), $includeReminder, $includeNotes);

        /*
          return array(
          'details' => $this->mapBeanToArray($thisBean),
          'defs' => $this->getModuleViewdefs($beanModule, $thisBean)
          );

         */
    }

    public
    function get_bean_attachment($beanModule, $beanId)
    {
// acl check if user can get the detail
        if (!ACLController::checkAccess($beanModule, 'detail', true)) {
            http_response_code(403);
            echo('not authorized for module ' . $beanModule);
            exit;
        }
        $thisBean = BeanFactory::getBean($beanModule);
        $thisBean->retrieve($beanId);

        if (!$thisBean) {
            http_response_code(404);
            echo('record not found');
            exit;
        }
        if ($thisBean->filename) {
            require_once('modules/Notes/NoteSoap.php');
            $noteSoap = new NoteSoap();
            $fileData = $noteSoap->retrieveFile($thisBean->id, $thisBean->filename);
            if ($fileData >= -1)
                return array(
                    'filename' => $thisBean->filename,
                    'file' => $fileData,
                    'filetype' => $thisBean->file_mime_type
                );
        }

        // if we did not return before we did not find the file
        http_response_code(404);
        echo('record not found');
        exit;
    }

    private
    function get_acl_actions($bean)
    {
        $aclArray = [];
        $aclActions = ['list', 'detail', 'edit', 'delete'];
        foreach ($aclActions as $aclAction) {
            $aclArray[$aclAction] = $bean->ACLAccess($aclAction);
        }
        return $aclArray;
    }

    public
    function get_related($beanModule, $beanId, $linkName)
    {

// acl check if user can get the detail
        if (!ACLController::checkAccess($beanModule, 'detail', true)) {
            http_response_code(403);
            echo('not authorized for module ' . $beanModule);
            exit;
        }
        // get the bean
        $thisBean = BeanFactory::getBean($beanModule, $beanId);
        if (!$thisBean) {
            http_response_code(404);
            echo('record not found');
            exit;
        }

        if (!ACLController::checkAccess($thisBean->field_defs[$linkName]['module'], 'list', true)) {
            http_response_code(403);
            echo('not authorized for module ' . $thisBean->field_defs[$linkName]['module']);
            exit;
        }
        $thisBean->load_relationship($linkName);

        // get related beans and related module
        $relBeans = $thisBean->get_linked_beans($linkName);
        $relModule = $thisBean->field_defs[$linkName]['module'];

        $retArray = array();
        foreach ($relBeans as $relBean) {
            if (empty($relBean->relid))
                $relBean->relid = create_guid();
            $retArray[$relBean->relid] = $this->mapBeanToArray($relModule, $relBean);
        }

        return $retArray;
    }

    public
    function add_related($beanModule, $beanId, $linkName)
    {

        if (!ACLController::checkAccess($beanModule, 'edit', true)) {
            http_response_code(403);
            echo('not authorized for module ' . $beanModule);
            exit;
        }
        $retArray = array();

        $relatedIds = json_decode($this->app->request->getBody());

        $thisBean = BeanFactory::getBean($beanModule, $beanId);
        if (!$thisBean) {
            http_response_code(404);
            echo('record not found');
            exit;
        }

        if (!ACLController::checkAccess($thisBean->field_defs[$linkName]['module'], 'list', true)) {
            http_response_code(403);
            echo('not authorized for module ' . $thisBean->field_defs[$linkName]['module']);
            exit;
        }
        $thisBean->load_relationship($linkName);
        foreach ($relatedIds as $relatedId) {
            $thisBean->$linkName->add($relatedId);
            $retArray[$relatedId] = $thisBean->$linkName->relationship->relid;
        }

        return $retArray;
    }

    public
    function delete_related($beanModule, $beanId, $linkName)
    {

        if (!ACLController::checkAccess($beanModule, 'edit', true)) {
            http_response_code(403);
            echo('not authorized for module ' . $beanModule);
            exit;
        }
        $retArray = array();

        $postParams = $this->app->request->get();

        $thisBean = BeanFactory::getBean($beanModule, $beanId);
        if (!$thisBean) {
            http_response_code(404);
            echo('record not found');
            exit;
        }

        if (!ACLController::checkAccess($thisBean->field_defs[$linkName]['module'], 'list', true)) {
            http_response_code(403);
            echo('not authorized for module ' . $thisBean->field_defs[$linkName]['module']);
            exit;
        }
        $thisBean->load_relationship($linkName);

        foreach (json_decode($postParams['relatedids'], true) as $relatedId) {
            $thisBean->$linkName->delete($beanId, $relatedId);
        }

        return $retArray;
    }

    public
    function add_mudltiple_related($beanModule, $linkName)
    {

    }

    public
    function add_bean($beanModule, $beanId, $post_params)
    {
        global $current_user;

        if ($post_params['deleted']) {
            $this->delete_bean($beanModule, $beanId);
            return $beanId;
        }

        $thisBean = BeanFactory::getBean($beanModule);
        $thisBean->retrieve($beanId);

        if (empty($thisBean->id) && !empty($beanId)) {
            $thisBean->new_with_id = true;
            $thisBean->id = $beanId;
        }

        foreach ($thisBean->field_defs as $fieldId => $fieldData) {
            if (isset($post_params[$fieldData['name']]))
                $thisBean->{$fieldData['name']} = $post_params[$fieldData['name']];
        }

        // make sure we have an assigned user
        if (empty($thisBean->assigned_user_id))
            $thisBean->assigned_user_id = $current_user->id;

        // save the bean
        $thisBean->save();

        if ($post_params['emailaddresses']) {
            $this->setEmailAddresses($beanModule, $beanId, $post_params['emailaddresses']);
        }

        // see if we have an attachement
        if ($beanModule == 'Notes' && isset($post_params['file']) && isset($post_params['filename'])) {
            require_once('modules/Notes/NoteSoap.php');
            $noteSoap = new NoteSoap();
            $post_params['id'] = $thisBean->id;
            $noteSoap->newSaveFile($post_params);
        }

        // if favorite is set .. update this as well
        if (isset($post_params['favorite'])) {
            if ($post_params['favorite'])
                $this->set_favorite($beanModule, $beanId);
            else
                $this->delete_favorite($beanModule, $beanId);
        }

        return $this->mapBeanToArray($beanModule, $thisBean);
    }

    public
    function delete_bean($beanModule, $beanId)
    {
        if (!ACLController::checkAccess($beanModule, 'delete', true)) {
            http_response_code(403);
            echo('not authorized for module ' . $beanModule);
            exit;
        }
        $thisBean = BeanFactory::getBean($beanModule);
        if (!$thisBean->retrieve($beanId)) {
            http_response_code(404);
            echo('record not found');
            exit;
        }

        if (!$thisBean->ACLAccess('delete')) {
            http_response_code(403);
            echo('not authorized to delete Bean');
            exit;
        }
        $thisBean->mark_deleted($beanId);
        return true;
    }

    private
    function getSpiceFavoritesClass()
    {
        global $sugar_flavor, $dictionary;

        if ($this->spiceFavoritesClass === null) {
            if ($sugar_flavor === 'PRO' && file_exists('include/SpiceFavorites/SpiceFavoritesSugarFavoritesWrapper.php')) {
                require_once 'include/SpiceFavorites/SpiceFavoritesSugarFavoritesWrapper.php';
                $this->spiceFavoritesClass = 'SpiceFavoritesSugarFavoritesWrapper';
            } else {
                if ($dictionary['spicefavorites'] && file_exists('include/SpiceFavorites/SpiceFavorites.php')) {
                    require_once 'include/SpiceFavorites/SpiceFavorites.php';
                    $this->spiceFavoritesClass = 'SpiceFavorites';
                }
            }
        }
        return $this->spiceFavoritesClass;
    }

    public
    function get_favorite($beanModule, $beanId)
    {
        $spiceFavoriteClass = $this->getSpiceFavoritesClass();
        if ($spiceFavoriteClass)
            return $spiceFavoriteClass::get_favorite($beanModule, $beanId);
        else
            return array();
    }

    public
    function set_favorite($beanModule, $beanId)
    {
        $spiceFavoriteClass = $this->getSpiceFavoritesClass();
        if($spiceFavoriteClass)
            $spiceFavoriteClass::set_favorite($beanModule, $beanId);
    }

    public
    function delete_favorite($beanModule, $beanId)
    {
        $spiceFavoriteClass = $this->getSpiceFavoritesClass();
        if ($spiceFavoriteClass)
            $spiceFavoriteClass::delete_favorite($beanModule, $beanId);
        else
            return false;
    }

    private
    function get_reminder($bean)
    {

        global $dictionary, $db, $current_user;

        // check capability and handle old theme customers
        if ($dictionary['spicereminders']) {
            $spiceReminderTable = 'spicereminders';
        } elseif ($dictionary['trreminders']) {
            $spiceReminderTable = 'trreminders';
        } else {
            return null;
        }

        $reminderObj = $db->query("SELECT * FROM $spiceReminderTable WHERE user_id='$current_user->id' AND bean_id='$bean->id' AND bean='$bean->module_dir'");
        if ($reminderRow = $db->fetchByAssoc($reminderObj)) {
            if ($GLOBALS['db']->dbType == 'mssql') {
                $reminderRow['reminder_date'] = str_replace('.000', '', $reminderRow['reminder_date']);
            }
            $reminderRow['summary'] = $bean->get_summary_text();
            return $reminderRow;
        } else {
            return null;
        }
    }

    private
    function get_quicknotes($bean)
    {
        global $dictionary, $current_user, $db;

        // check capability and handle old theme customers
        if ($dictionary['spicenotes']) {
            $spiceNotesTable = 'spicenotes';
        } elseif ($dictionary['trquicknotes']) {
            $spiceNotesTable = 'trquicknotes';
        } else {
            return null;
        }


        $quicknotes = array();

        if ($GLOBALS['db']->dbType == 'mssql') {
            $quicknotesRes = $db->query("SELECT qn.*,u.user_name FROM $spiceNotesTable AS qn LEFT JOIN users AS u ON u.id=qn.user_id WHERE qn.bean_id='{$bean->id}' AND qn.bean_type='{$bean->module_dir}' AND (qn.user_id = '" . $current_user->id . "' OR qn.trglobal = '1') AND qn.deleted = 0 ORDER BY qn.trdate DESC");
        } else {
            $quicknotesRes = $db->query("SELECT qn.*,u.user_name FROM $spiceNotesTable AS qn LEFT JOIN users AS u ON u.id=qn.user_id WHERE qn.bean_id='{$bean->id}' AND qn.bean_type='{$bean->module_dir}' AND (qn.user_id = '" . $current_user->id . "' OR qn.trglobal = '1') AND qn.deleted = 0 ORDER BY qn.trdate DESC");
        }

        if ($GLOBALS['db']->dbType == 'mssql' || $db->getRowCount($quicknotesRes) > 0) {
            while ($thisQuickNote = $db->fetchByAssoc($quicknotesRes)) {
                $quicknotes[] = array(
                    'id' => $thisQuickNote['id'],
                    'user_id' => $thisQuickNote['user_id'],
                    'user_name' => $thisQuickNote['user_name'],
                    'bean_id' => $bean->id,
                    'bean_type' => $bean->module_dir,
                    'own' => ($thisQuickNote['user_id'] == $current_user->id || $current_user->is_admin) ? '1' : '0',
                    'date' => $thisQuickNote['trdate'],
                    'text' => $thisQuickNote['text'],
                    'global' => $thisQuickNote['trglobal'] ? 1 : 0
                );
            }
        }
        return $quicknotes;
    }

    public
    function execute_bean_action($beanModule, $beanId, $beanAction, $postParams)
    {

        $GLOBALS['KREST']['beanID'] = $beanId;
        $GLOBALS['KREST']['beanAction'] = $beanAction;
        $GLOBALS['KREST']['postParams'] = $postParams;

        // get the bean
        $thisBean = BeanFactory::getBean($beanModule);
        if (!empty($beanId))
            $thisBean->retrieve($beanId);


        // get the controller
        require_once "include/MVC/Controller/ControllerFactory.php";
        $controllerFactory = new ControllerFactory();
        $thisBeanController = $controllerFactory->getController($beanModule);

        // check if file exists
        if (file_exists('custom/modules/' . $thisBean->module_dir . '/' . $beanAction . '.php')) {
            include('custom/modules/' . $thisBean->module_dir . '/' . $beanAction . '.php');
        } elseif (file_exists('modules/' . $thisBean->module_dir . '/' . $beanAction . '.php')) {
            include('modules/' . $thisBean->module_dir . '/' . $beanAction . '.php');
        } elseif (method_exists($thisBeanController, 'action_' . $beanAction)) {
            $thisBeanController->bean = $thisBean;
            $cAction = 'action_' . $beanAction;
            return $thisBeanController->$cAction($postParams);
        } elseif (method_exists($thisBean, $beanAction)) {
            return $thisBean->$beanAction($postParams);
        } else
            return false;
    }

    public
    function get_bean_vardefs($beanModule)
    {

        $thisBean = BeanFactory::getBean($beanModule);
        return $thisBean->field_defs;
    }

    public
    function get_beandefs_multiple($beanModules)
    {

        $retArray = array();

        foreach ($beanModules as $thisModule) {
            $retArray[$thisModule] = $this->get_beandefs($thisModule);
        }

        return $retArray;
    }

    public
    function get_modules()
    {

        global $current_language;

        $app_list_strings = return_app_list_strings_language($current_language);
        $modArray = array();
        $mint_disabled_modules = getMintDisabledModulesList();
        foreach ($app_list_strings['moduleList'] as $module => $modulename) {
            if(!in_array($module,$mint_disabled_modules)){
                $modArray[] = array(
                    'module' => $module,
                    'name' => $modulename
                );
            }   
        }
        return $modArray;
    }

    public
    function get_beandefs($beanModule)
    {

        $thisBean = BeanFactory::getBean($beanModule);
        $retArray = array();
        // get the listviewdefs
        $retArray['list'] = $this->getModuleListdefs($beanModule);

        if (file_exists('modules/' . $thisBean->module_dir . '/metadata/listviewdefsmobile.php')) {
            require_once('modules/' . $thisBean->module_dir . '/metadata/listviewdefsmobile.php');
            $retArray['listmobile'] = $listViewDefsMobile[$beanModule];
        } else
            $retArray['listmobile'] = array();

        $retArray['vardefs'] = $this->get_bean_vardefs($beanModule);
        $retArray['detail'] = $this->getModuleViewdefs($beanModule, $thisBean);
        $retArray['language'] = $this->get_bean_language($beanModule);

        return $retArray;
    }

    public
    function get_bean_language($beanModule)
    {

        return return_module_language('', $beanModule);
    }

//private helper functions
    private
    function mapBeanToArray($beanModule, $thisBean, $returnFields = array(), $includeReminder = false, $includeNotes = false)
    {

        global $current_language;


        $app_list_strings = return_app_list_strings_language($current_language);
        $beanDataArray = array();
        foreach ($thisBean->field_defs as $fieldId => $fieldData) {
            if ($fieldId == 'id' || ($fieldData['type'] != 'link' && (count($returnFields) == 0 || (count($returnFields) > 0 && in_array($fieldId, $returnFields))))) {
                $beanDataArray[$fieldId] = html_entity_decode($thisBean->$fieldId, ENT_QUOTES);
            }
        }

        // get the summary text
        $beanDataArray['summary_text'] = $thisBean->get_summary_text();

        $beanDataArray['favorite'] = $this->get_favorite($beanModule, $thisBean->id) ? 1 : 0;

        if ($includeReminder) {
            $beanDataArray['spicereminder'] = $this->get_reminder($thisBean);
        }

        if ($includeNotes) {
            $beanDataArray['spicenotes'] = $this->get_quicknotes($thisBean);
        }

        // get the email addresses
        $beanDataArray['emailaddresses'] = $this->getEmailAddresses($beanModule, $thisBean->id);

        // get the ACL Array
        $beanDataArray['acl'] = $this->get_acl_actions($thisBean);

        return $beanDataArray;
    }

    private
    function getEmailAddresses($beanObject, $beanId)
    {

        $emailAddresses = BeanFactory::getBean('EmailAddresses');
        return $emailAddresses->getAddressesByGUID($beanId, $beanObject);
    }

    private
    function setEmailAddresses($beanModule, $beanId, $emailaddresses)
    {

        $emailAddresses = BeanFactory::getBean('EmailAddresses');
        $emailAddresses->addresses = $emailaddresses;
        $emailAddresses->save($beanId, $beanModule);
    }

    private
    function getModuleListdefs($beanModule, $thisBean = null, $mobile = false)
    {

        if (!$thisBean)
            $thisBean = BeanFactory::getBean($beanModule);

        // get the metadata
        require_once 'modules/' . $thisBean->module_dir . '/metadata/listviewdefs.php';
        $moduleLanguage = $this->get_bean_language($beanModule);
        $retListViewDefs = array();
        foreach ($listViewDefs[$beanModule] as $fieldname => $fielddata) {
            if (($mobile && $fielddata['mobile'] == true) || !$mobile) {
                $retListViewDefs[] = array(
                    'name' => strtolower($fieldname),
                    'label' => !empty($moduleLanguage[$fielddata['label']]) ? $moduleLanguage[$fielddata['label']] : $fieldname,
                    'width' => strpos('%', $fielddata['width']) === false ? $fielddata['width'] . '%' : $fielddata['width'],
                    'default' => $fielddata['default']
                );
            }
        }

        return $retListViewDefs;
    }

    private
    function getModuleViewdefs($beanModule, $thisBean)
    {
        require_once 'modules/' . $thisBean->module_dir . '/metadata/detailviewdefs.php';

        $moduleLanguage = $this->get_bean_language($beanModule);
        $viewDefs = array();
        foreach ($viewdefs[$beanModule]['DetailView']['panels'] as $panelName => $panelData) {
            $panelDataArray = array();
            foreach ($panelData as $panelRow) {
                $panelRowArray = array();
                foreach ($panelRow as $panelField) {
                    if (is_array($panelField)) {
                        $panelRowArray[] = array(
                            'name' => $panelField['name'],
                            'label' => !empty($panelField['label']) && !empty($moduleLanguage[$panelField['label']]) ? $moduleLanguage[$panelField['label']] : $panelField['name']
                        );
                    } else {
                        $panelRowArray[] = array(
                            'name' => $panelField,
                            'label' => !empty($thisBean->field_defs[$panelField]['vname']) ? $moduleLanguage[$thisBean->field_defs[$panelField]['vname']] : $panelField
                        );
                    }
                };
                $panelDataArray[] = $panelRowArray;
            };
            $viewDefs[] = array(
                'label' => !empty($moduleLanguage[strtoupper($panelName)]) ? $moduleLanguage[strtoupper($panelName)] : $panelName,
                'rows' => $panelDataArray
            );
        }

        // get the subpanelDefs
        $subpanelDataArray = array();
        if (file_exists('modules/' . $thisBean->module_dir . '/metadata/subpaneldefs.php')) {
            require_once 'modules/' . $thisBean->module_dir . '/metadata/subpaneldefs.php';
            foreach ($layout_defs[$beanModule]['subpanel_setup'] as $subpanelId => $subpanelDetails) {
                $subpanelDataArray[] = array(
                    'subpanelid' => $subpanelId,
                    'label' => $moduleLanguage[$subpanelDetails['title_key']]
                );
            }
        }


        return array(
            'viewdefs' => $viewDefs,
            'subpaneldefs' => $subpanelDataArray
        );
    }

// for the emails
    public
    function email_getmailboxes()
    {
        global $db;

        $inboundEmails = array();
        while ($inboundEmails[] = $db->fetchByAssoc($db->query("SELECT id, name, email_user FROM inbound_email")))
            return $inboundEmails;
    }

    public
    function email_getmails($mailboxid)
    {
        global $db;

        $emailsobj = $db->query("SELECT *, (SELECT count(id) FROM notes WHERE parent_id=emails.id) attachmentcount FROM emails, emails_text WHERE emails.id = emails_text.email_id AND mailbox_id='" . $mailboxid . "' ORDER BY date_sent DESC");
        $emailsArray = array();
        while ($emailsEntry = $db->fetchByAssoc($emailsobj)) {

            $emailsArray[] = array(
                'id' => $emailsEntry['id'],
                'date_entered' => $emailsEntry['date_entered'],
                'date_sent' => $emailsEntry['date_sent'],
                'name' => $emailsEntry['name'],
                'type' => $emailsEntry['type'],
                'status' => $emailsEntry['status'],
                'from_addr' => html_entity_decode($emailsEntry['from_addr']),
                'to_addrs' => html_entity_decode($emailsEntry['to_addrs']),
                'attachmentcount' => $emailsEntry['attachmentcount']
            );
        };

        return $emailsArray;
    }

    public
    function email_getmail($emailId)
    {
        global $db;

        $emailsEntry = $db->fetchByAssoc($db->query("SELECT * FROM emails, emails_text WHERE emails.id = emails_text.email_id AND id='" . $emailId . "'"));

        $attachements = array();
        $attachementObj = $db->query("SELECT * FROM notes WHERE parent_id='" . $emailId . "'");
        while ($attachement = $db->fetchByAssoc($attachementObj))
            $attachements[] = $attachement;

        return array(
            'id' => $emailsEntry['id'],
            'date_entered' => $emailsEntry['date_entered'],
            'date_sent' => $emailsEntry['date_sent'],
            'name' => $emailsEntry['name'],
            'type' => $emailsEntry['type'],
            'status' => $emailsEntry['status'],
            'from_addr' => html_entity_decode($emailsEntry['from_addr']),
            'to_addrs' => html_entity_decode($emailsEntry['to_addrs']),
            'cc_addrs' => html_entity_decode($emailsEntry['cc_addrs']),
            'bcc_addrs' => html_entity_decode($emailsEntry['bcc_addrs']),
            'reply_to_addr' => html_entity_decode($emailsEntry['reply_to_addr']),
            'description' => html_entity_decode($emailsEntry['description']),
            'description_html' => html_entity_decode($emailsEntry['description_html']),
            'attachements' => $attachements
        );
    }

    private
    function write_spiceuitracker($module, $bean)
    {
        global $db, $timedate, $current_user;

        // check if the last entr from the user is the same id
        $lastRecord = $db->fetchByAssoc($db->limitQuery("SELECT record_id FROM spiceuitrackers ORDER BY date_entered DESC ", 0, 1));

        if ($lastRecord['record_id'] == $bean->id)
            return false;

        // insert a record
        $db->query("INSERT INTO spiceuitrackers (id, user_id, date_entered, record_module, record_id, record_summary) VALUES('" . create_guid() . "', '{$current_user->id}', '" . $timedate->nowDb() . "', '{$module}', '{$bean->id}', '" . $bean->get_summary_text() . "')");
    }

    private function sort_object_handler($table_name, $sort_object)
    {
        switch ($sort_object->sortfunction) {
            case "distance":
                return "POWER(SIN((" . $sort_object->sortparams->current_lat . " - abs(" . $table_name . "." . $sort_object->sortparams->lat_field . ")) * pi()/180 / 2), 2)
              + COS(" . $sort_object->sortparams->current_lon . " * pi()/180 ) * COS(abs(" . $table_name . "." . $sort_object->sortparams->lat_field . ") * pi()/180)
              * POWER(SIN((" . $sort_object->sortparams->current_lon . " - " . $table_name . "." . $sort_object->sortparams->lon_field . ") * pi()/180 / 2), 2)";
                break;
            default:

                break;
        }
    }

    private function processSpiceDomainFunction($thisBean, $fieldDef, $language) {

        if (isset($fieldDef['spice_domain_function'])) {
            $function = $fieldDef['spice_domain_function'];
            if (is_array($function) && isset($function['name'])) {
                $function = $fieldDef['spice_domain_function']['name'];
            } else {
                $function = $fieldDef['spice_domain_function'];
            }

            if (isset($fieldDef['spice_domain_function']['include']) && file_exists($fieldDef['spice_domain_function']['include'])) {
                require_once($fieldDef['spice_domain_function']['include']);
            }

            $domain = call_user_func($function, $thisBean, $fieldDef['name'], $language);
            return $domain;
            
        } else {
            return array();
        }
    }

}
