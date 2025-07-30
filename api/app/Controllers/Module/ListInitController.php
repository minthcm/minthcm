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
 * Copyright (C) 2018-2024 MintHCM
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

namespace MintHCM\Api\Controllers\Module;

use MintHCM\Data\MassActions\Actions as MassActions;
use MintHCM\Utils\ConstantsLoader;
use MintHCM\Data\MassActions\MassActionLoader;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Exception\HttpForbiddenException;
use Slim\Exception\HttpNotFoundException;
use Slim\Psr7\Response;
use Slim\Routing\RouteContext;

#[\AllowDynamicProperties]
class ListInitController
{
    const METADATA_FILES = array(
        '../legacy/custom/modules/{module}/metadata/eslistviewdefs.php',
        '../legacy/modules/{module}/metadata/eslistviewdefs.php',
    );
    const DEFAULT_MASS_ACTIONS = [
        MassActions\Delete::class,
        MassActions\Export::class,
        MassActions\Merge::class,
    ];
    private $request;
    private $module, $metadata, $bean;
    private $eslistmap = [];
    private $mappings = [];

    public function __construct()
    {
        global $app_list_strings, $current_language;
        if (!$app_list_strings) {
            $app_list_strings = return_app_list_strings_language($current_language);
        }
    }

    function __invoke(Request $request, Response $response, array $args): Response
    {
        $this->request = $request;
        $this->setData();
        $response = $response->withHeader('Content-type', 'application/json');

        chdir('../legacy/');
        $has_access = $this->bean->ACLAccess('list');
        chdir('../api/');
        if (!$has_access) {
            throw new HttpForbiddenException($request);
        }

        $data = array(
            'config' => $this->prepareConfig(),
            'defs' => $this->prepareDefs(),
            'module' => $this->module,
            'preferences' => $this->prepareUserPreferences(),
        );

        $response->getBody()->write(json_encode($data));
        return $response;
    }

    function setData()
    {
        $routeContext = RouteContext::fromRequest($this->request);
        $route = $routeContext->getRoute();
        $this->module = str_replace('/', '', $route->getPattern());
        chdir('../legacy/');
        $this->bean = \BeanFactory::newBean($this->module);
        chdir('../api/');
        foreach (static::METADATA_FILES as $file) {
            $file = str_replace('{module}', $this->module, $file);
            if (file_exists($file)) {
                include $file;
                $this->metadata = $ESListViewDefs[$this->module];
                break;
            }
        }
        if (empty($this->metadata) || empty($this->bean)) {
            throw new HttpNotFoundException($this->request);
        }
    }

    function prepareUserPreferences()
    {
        global $current_user;
        chdir('../legacy/');
        $preferences = (new \UserPreference($current_user))->getPreference($this->module, 'eslist');
        if (!$preferences) {
            $preferences = [];
        }
        chdir('../api/');
        return $preferences;
    }

    function prepareConfig()
    {
        global $sugar_config;
        $list_config = ConstantsLoader::getConstants('list_constants');
        $variables = $list_config['variables'];
        $theme = $list_config['theme'];

        foreach ($theme as $property => $objects) {
            foreach ($objects as $object => $value) {
                $theme[$property][$object] = $variables[$property][$value];
            }
        }
        $config = $list_config['config'];
        if (isset($this->metadata['actions'])) {
            $config['actions'] = $this->metadata['actions'];
        }
        $mass_actions = [];
        if (isset($this->metadata['massActions'])) {
            $mass_actions = $this->metadata['massActions'];
        } else {
            $mass_actions = self::DEFAULT_MASS_ACTIONS;
        }
        foreach ($mass_actions as $action) {
            $mass_action = MassActionLoader::getAction($action, $this->module, []);
            if ($mass_action->hasAccess()) {
                $config['massActions'][] = $mass_action->getFrontendData();
            }
        }
        $config['defaultMaxItemsPerPage'] = $sugar_config['list_max_entries_per_page'] ?? $list_config['config']['defaultMaxItemsPerPage'];
        foreach ($config['itemsPerPageOptions'] as $key => $amount) {
            if ($amount > $config['defaultMaxItemsPerPage']) {
                unset($config['itemsPerPageOptions'][$key]);
            }
        }

        return array(
            'config' => $config,
            'theme' => $theme,
        );
    }

    function prepareDefs()
    {
        return [
            'columns' => $this->prepareDefsType("columns"),
            'search' => $this->prepareSearchDefs(),
        ];
    }

    protected function prepareSearchDefs()
    {
        global $mod_strings, $app_strings, $current_language;
        $mod_strings = return_module_language($current_language, $this->module);
        $search = $this->metadata["search"];
        if (empty($search)) {
            return false;
        }
        $search = array_change_key_case($search, CASE_LOWER);
        foreach ($search as $field => $defs) {
            if (empty($this->bean->field_name_map[$field])) {
                $GLOBALS['log']->fatal('[ESListView] prepareSearchDefs: lack of field definition: ' . $field);
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
            if (!empty($search[$field]['type'])) {
                if (in_array($search[$field]['type'], ['multienum', 'enum', 'ColoredEnum'])) {
                    $search[$field]['key'] .= '.keyword';
                } else if ('relate' === $search[$field]['type']) {
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

    function prepareDefsType($type)
    {
        $columns = $this->metadata[$type];

        global $mod_strings, $app_strings, $current_language;
        $mod_strings = return_module_language($current_language, $this->module);
        if (empty($columns)) {
            \LoggerManager::getLogger()->fatal('Columns for ESList View are not defined');
            throw new HttpNotFoundException($this->request);
        }
        $columns = array_change_key_case($columns, CASE_LOWER);
        foreach ($columns as $field => $defs) {
            if (empty($this->bean->field_name_map[$field])) {
                $GLOBALS['log']->fatal('[ESListView] prepareDefsType: lack of field definition: ' . $field);
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
            if (!empty($fieldProps) && 'text' === $fieldProps['type']) {
                $columns[$field]['key'] .= '.keyword';
            }
            $columns[$field]['type'] = $defs['type'] ?? $field_defs['type'];
            $columns[$field]['options'] = $this->getParsedOptions($field_defs);
            $label = $defs['label'] ?? $field_defs['label'] ?? $field_defs['vname'];
            $columns[$field]['label'] = $this->prepareLabel($mod_strings[$label] ?? $app_strings[$label] ?? $label);
        }
        return $columns;
    }
    protected function getMappedFieldProps($key)
    {
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
    function prepareLabel($label)
    {
        $label = trim($label);
        if (in_array(substr($label, -1), [':', '.'])) {
            $label = substr($label, 0, -1);
        }
        return $label;
    }
    protected function getParsedOptions($field_defs)
    {
        if (isset($field_defs['options']) && is_string($field_defs['options'])) {
            return $field_defs['options'];
        }
        if (empty($field_defs['function'])) {
            return null;
        }
        if (!empty($field_defs['function']['include'])) {
            if (file_exists($field_defs['function']['include'])) {
                require_once $field_defs['function']['include'];
            } else if (file_exists('../legacy/' . $field_defs['function']['include'])) {
                require_once '../legacy/' . $field_defs['function']['include'];
            }
        }
        $function = $field_defs['function']['name'] ?? $field_defs['function'];
        $additional_params = $field_defs['function']['additional_params'] ?? null;

        return call_user_func($function, null, null, null, 'eslist', $additional_params);
    }
}
