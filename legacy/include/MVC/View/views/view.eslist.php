<?php
/**
 *
 * SugarCRM Community Edition is a customer relationship management program developed by
 * SugarCRM, Inc. Copyright (C) 2004-2013 SugarCRM Inc.
 *
 * SuiteCRM is an extension to SugarCRM Community Edition developed by SalesAgility Ltd.
 * Copyright (C) 2011 - 2018 SalesAgility Ltd.
 *
 * MintHCM is a Human Capital Management software based on SuiteCRM developed by MintHCM, 
 * Copyright (C) 2018-2023 MintHCM
 *
 * This program is free software; you can redistribute it and/or modify it under
 * the terms of the GNU Affero General Public License version 3 as published by the
 * Free Software Foundation with the addition of the following permission added
 * to Section 15 as permitted in Section 7(a): FOR ANY PART OF THE COVERED WORK
 * IN WHICH THE COPYRIGHT IS OWNED BY SUGARCRM, SUGARCRM DISCLAIMS THE WARRANTY
 * OF NON INFRINGEMENT OF THIRD PARTY RIGHTS.
 *
 * This program is distributed in the hope that it will be useful, but WITHOUT
 * ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS
 * FOR A PARTICULAR PURPOSE. See the GNU Affero General Public License for more
 * details.
 *
 * You should have received a copy of the GNU Affero General Public License along with
 * this program; if not, see http://www.gnu.org/licenses or write to the Free
 * Software Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA
 * 02110-1301 USA.
 *
 * You can contact SugarCRM, Inc. headquarters at 10050 North Wolfe Road,
 * SW2-130, Cupertino, CA 95014, USA. or at email address contact@sugarcrm.com.
 *
 * The interactive user interfaces in modified source and object code versions
 * of this program must display Appropriate Legal Notices, as required under
 * Section 5 of the GNU Affero General Public License version 3.
 *
 * In accordance with Section 7(b) of the GNU Affero General Public License version 3,
 * these Appropriate Legal Notices must retain the display of the "Powered by SugarCRM" 
 * logo and "Supercharged by SuiteCRM" logo and "Reinvented by MintHCM" logo. 
 * If the display of the logos is not reasonably feasible for technical reasons, the 
 * Appropriate Legal Notices must display the words "Powered by SugarCRM" and 
 * "Supercharged by SuiteCRM" and "Reinvented by MintHCM".
 */
require_once('include/MVC/View/SugarView.php');
require_once '../api/vendor/autoload.php';

use MintHCM\Data\MassActions\Actions as MassActions;

class ViewESList extends SugarView
{
    const DEFAULT_MASS_ACTIONS = [
        MassActions\Delete::class,
        MassActions\Export::class,
        MassActions\Merge::class,
    ];

        /**
     * @var string $type
     */
    public $type = 'ESList';

    /**
     * @var SugarBean
     */
    public $seed;

    public $ss; // smarty object

    /**
     * @var array $ESListViewDefs
     */
    public $ESListViewDefs;

    /**
     * ESListView constructor.
     */
    public function __construct()
    {
        parent::__construct();
    }

    public function display()
    {
        if (!$this->bean || !$this->bean->ACLAccess('list')) {
            ACLController::displayNoAccess();
        } else {
            $this->prepareESListView();
            $this->ss = new Sugar_Smarty();
            $this->ss->assign('config', $this->prepareConfig());
            $this->ss->assign('defs', $this->prepareDefs());
            $this->ss->assign('module', $this->bean->module_name);
            $this->ss->assign('preferences', $this->prepareUserPreferences());
            echo $this->ss->fetch($this->getTplFile());
        }
    }

    //temp
    public function getInitialData()
    {
        $this->prepareESListView();
        $data = new stdClass();
        $data->config = $this->prepareConfig();
        $data->defs = $this->prepareDefs();
        $data->module = $this->bean->module_name;
        $data->preferences = $this->prepareUserPreferences();
        return $data;
    }

    protected function prepareESListView()
    {
        global $sugar_config, $mint_config;
        if (!isset($this->bean->module_name)) {
            LoggerManager::getLogger()->fatal('Undefined module for eslist view');
            return false;
        }
        $metadataFile = $this->getMetaDataFile();
        if (!file_exists($metadataFile)) {
            sugar_die(sprintf($GLOBALS['app_strings']['LBL_NO_ACTION'], $this->do_action));
        }
        require($metadataFile);
        $this->ESListViewDefs = $ESListViewDefs;

        require('include/ESListView/eslist.map.php');
        $this->eslistmap = $eslistmap;

        $host = $sugar_config['search']['ElasticSearch']['host'];
        $port = $mint_config['search']['engines']['ElasticSearch'][0]['port'];
        $host = $this->validateHostAndPort($host, $port);
        $protocol = $sugar_config['search']['ElasticSearch']['protocol']?? 'http';
        
        $es_module = $ESListViewDefs[$this->module]['es_module'] ?? $this->module;
        $index = $sugar_config['unique_key'] . '_'.strtolower($es_module);
        $mappings = json_decode(file_get_contents("{$protocol}://{$host}/{$index}/_mappings"), true);
        if(empty($mappings)){
            return;
        }
        
        $this->mappings = array_values($mappings)[0]['mappings'];
    }

    protected function validateHostAndPort($host, $port) {
        if (strpos($host, ':') !== false) {
            return $host;
        }

        if (!filter_var($host, FILTER_VALIDATE_IP) && !preg_match("/^([a-z\d](-*[a-z\d])*)(\.([a-z\d](-*[a-z\d])*))*$/i", $host) && preg_match("/^.{1,253}$/", $host) && preg_match("/^[^\.]{1,63}(\.[^\.]{1,63})*$/", $host)) {
            throw new Exception('Elasticsearch: Invalid host');
        }
    
        if (!filter_var($port, FILTER_VALIDATE_INT, array("options" => array("min_range"=>1, "max_range"=>65535)))) {
            throw new Exception('Elasticsearch: Invalid port');
        }
        return $host . ":" . $port;
    }

    protected function prepareConfig()
    {
        $this->config = json_decode(file_get_contents('include/ESListView/config/eslist.config.json'), true);
        $this->config['itemsPerPageOptions'] = $this->prepareItemsPerPageOptions();
        $variables = json_decode(file_get_contents('include/ESListView/config/eslist.variables.json'), true);
        $theme = json_decode(file_get_contents('include/ESListView/config/eslist.theme.json'), true);

        if (isset($this->ESListViewDefs[$this->module]['actions'])) {
            $this->config['actions'] = $this->ESListViewDefs[$this->module]['actions'] ?? [];
        }

        $mass_actions = [];
        if (isset($this->ESListViewDefs[$this->module]['massActions'])) {
            $mass_actions = $this->ESListViewDefs[$this->module]['massActions'];
        } else {
            $mass_actions = self::DEFAULT_MASS_ACTIONS;
        }
        
        chdir('../api');
        foreach ($mass_actions as $action) {
            $mass_action = new $action($this->module, []);
            if ($mass_action->hasAccess()) {
                $this->config['massActions'][] = $mass_action->getFrontendData();
            }
        }
        chdir('../legacy');

        foreach ($theme as $property => $objects) {
            foreach ($objects as $object => $value) {
                $theme[$property][$object] = $variables[$property][$value];
            }
        }
        return json_encode([
            'config' => $this->config,
            'theme' => $theme,
        ]);
    }

    protected function prepareDefs()
    {
        return json_encode([
            'columns' => $this->prepareColumnsDefs(),
            'search' => $this->prepareSearchDefs(),
        ]);
    }

    protected function prepareColumnsDefs()
    {
        global $mod_strings, $app_strings, $current_language;
        $mod_strings = return_module_language($current_language, $this->module);
        $columns = $this->ESListViewDefs[$this->module]['columns'];
        if (empty($columns)) {
            LoggerManager::getLogger()->fatal('Columns for ESList View are not defined');
            return false;
        }
        $columns = array_change_key_case($columns, CASE_LOWER);
        foreach ($columns as $field => $defs) {
            if (empty($this->bean->field_name_map[$field])) {
                $GLOBALS['log']->fatal('[ESListView] prepareColumnsDefs: brak definicji pola ' . $field);
                unset($columns[$field]);
                continue;
            }
            $field_defs = $this->bean->field_name_map[$field];
            if (
                !empty($field_defs['has_access']['function'])
                && function_exists($field_defs['has_access']['function'])
                && !$field_defs['has_access']['function']()
            ) {
                unset($columns[$field]);
                continue;
            }
            $columns[$field] = array_merge($field_defs, $columns[$field]);
            $columns[$field]['name'] = $defs['name'] ?? $field;
            $columns[$field]['key'] = $defs['key'] ?? $this->eslistmap[$field] ?? $field;
            $fieldProps = $this->getMappedFieldProps($columns[$field]['key']);
            if (!empty($fieldProps) && $fieldProps['type'] === 'text') {
                $columns[$field]['key'] .= '.keyword';
            }
            $columns[$field]['type'] = $defs['type'] ?? $field_defs['type'];
            $columns[$field]['options'] = $this->getParsedOptions($field_defs);
            $label = $defs['label'] ?? $field_defs['label'] ?? $field_defs['vname'];
            $columns[$field]['label'] = $this->prepareLabel($mod_strings[$label] ?? $app_strings[$label] ?? $label);
        }
        return $columns;
    }

    protected function prepareSearchDefs()
    {
        global $mod_strings, $app_strings, $current_language;
        $mod_strings = return_module_language($current_language, $this->module);
        $search = $this->ESListViewDefs[$this->module]['search'];
        if (empty($search)) {
            return false;
        }
        $search = array_change_key_case($search, CASE_LOWER);
        foreach ($search as $field => $defs) {
            if (empty($this->bean->field_name_map[$field])) {
                $GLOBALS['log']->fatal('[ESListView] prepareSearchDefs: brak definicji pola ' . $field);
                unset($search[$field]);
                continue;
            }
            $field_defs = $this->bean->field_name_map[$field];
            if (
                !empty($field_defs['has_access']['function'])
                && function_exists($field_defs['has_access']['function'])
                && !$field_defs['has_access']['function']()
            ) {
                unset($search[$field]);
                continue;
            }
            $search[$field] = array_merge($field_defs, $search[$field]);
            $search[$field]['name'] = $defs['name'] ?? $field;
            $search_field_name = $field_defs['id_name'] ?? $field;
            $search[$field]['key'] = $defs['key'] ?? $this->eslistmap[$search_field_name] ?? $search_field_name;
            $search[$field]['type'] = $defs['type'] ?? $field_defs['type'];
            if (!empty($search[$field]['type']) ) {
                if (in_array($search[$field]['type'], ['multienum', 'enum'])) {
                    $search[$field]['key'] .= '.keyword';
                } else if ($search[$field]['type'] === 'relate') {
                    $field_id = $field_defs['id_name'];
                    $search[$field]['key'] = $defs['key'] ?? $this->eslistmap[$field_id] ?? $field_id;
                }
            }
            $search[$field]['options'] = $this->getParsedOptions($field_defs);
            $label = $defs['label'] ?? $field_defs['label'] ?? $field_defs['vname'];
            $search[$field]['label'] = $this->prepareLabel($mod_strings[$label] ?? $app_strings[$label] ?? $label);
        }
        return $search;
    }

    protected function prepareUserPreferences()
    {
        global $current_user;
        $preferences = (new UserPreference($current_user))->getPreference($this->bean->module_name, 'eslist');
        return json_encode($preferences);
    }

    protected function getTplFile()
    {
        $module_path = 'modules/'.$this->module.'/include/ESListView/ESListViewGeneric.tpl';
        if (file_exists('custom/'.$module_path)) {
            return 'custom/'.$module_path;
        } else if (file_exists($module_path)) {
            return $module_path;
        }
        $include_path = 'include/ESListView/ESListViewGeneric.tpl';
        if (file_exists('custom/'.$include_path)) {
            return 'custom/'.$include_path;
        } else if (file_exists($include_path)) {
            return $include_path;
        }
        $GLOBALS['log']->fatal("ESList TPL file does not exist");
        return '';
    }

    protected function getMappedFieldProps($key) {
        if (empty($this->mappings) || empty($key)) {
            return null;
        }
        $nestedProps = explode('.', $key);
        $fieldProps = $this->mappings;
        foreach ($nestedProps as $prop) {
            if (empty($fieldProps['properties'][$prop])) {
                return null;
            }
            $fieldProps = $fieldProps['properties'][$prop];
        }
        return $fieldProps;
    }

    protected function prepareLabel($label) {
        $label = trim($label);
        if (in_array(substr($label, -1), [':', '.'])) {
            $label = substr($label, 0, -1);
        }
        return $label;
    }

    protected function prepareItemsPerPageOptions() {
        global $sugar_config;
        $maxItemsPerPage = $sugar_config['list_max_entries_per_page'] ?? $this->config['defaultMaxItemsPerPage'];
        $options = $this->config['itemsPerPageOptions'];
        foreach ($options as $key => $option) {
            if ($option['value'] > $maxItemsPerPage) {
                array_splice($options, $key);
                if ($options[$key - 1]['value'] != $maxItemsPerPage) {
                    $options[$key] = [
                        'value' => $maxItemsPerPage,
                        'title' => (string)$maxItemsPerPage,
                    ];
                }
                return $options;
            }
        }
        if (end($options)['value'] < $maxItemsPerPage) {
            array_push($options, [
                'value' => $maxItemsPerPage,
                'title' => (string)$maxItemsPerPage,
            ]);
        }

        return $options;
    }

    protected function getParsedOptions($field_defs)
    {
        if (isset($field_defs['options']) && is_string($field_defs['options'])) {
            return $field_defs['options'];
}
        if (empty($field_defs['function'])) {
            return null;
        }
        if (!empty($field_defs['function']['include']) && file_exists($field_defs['function']['include'])) {
            require_once($field_defs['function']['include']);
        }
        $function = $field_defs['function']['name'] ?? $field_defs['function'];
        $additional_params = $field_defs['function']['additional_params'] ?? null;

        return call_user_func($function, null, null, null, null, $additional_params);
    }
}
