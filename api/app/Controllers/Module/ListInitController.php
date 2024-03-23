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

namespace MintHCM\Api\Controllers\Module;

use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Exception\HttpForbiddenException;
use Slim\Exception\HttpNotFoundException;
use Slim\Psr7\Response;
use Slim\Routing\RouteContext;

class ListInitController
{
    const METADATA_FILES = array(
        '../legacy/custom/modules/{module}/metadata/eslistviewdefs.php',
        '../legacy/modules/{module}/metadata/eslistviewdefs.php',
    );

    private $request;
    private $module, $metadata, $bean;

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
        $preferences = (new \UserPreference($current_user))->getPreference($this->bean->module_name, 'eslist');
        chdir('../api/');
        return $preferences;
    }

    function prepareConfig()
    {
        global $list_config, $sugar_config;
        $variables = $list_config['variables'];
        $theme = $list_config['theme'];

        foreach ($theme as $property => $objects) {
            foreach ($objects as $object => $value) {
                $theme[$property][$object] = $variables[$property][$value];
            }
        }

        $config = $list_config['config'];
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
            'search' => $this->prepareDefsType("search"),
        ];
    }

    function prepareDefsType($type)
    {
        $data = $this->metadata[$type];
        if (empty($data)) {
            throw new HttpNotFoundException($this->request);
        }
        $data = array_change_key_case($data, CASE_LOWER);
        foreach ($data as $field => $defs) {
            $field_defs = $this->bean->field_name_map[$field];

            if (empty($field_defs)) {
                unset($data[$field]);
                continue;
            }
            if (
                !empty($field_defs['has_access']['function'])
                && function_exists($field_defs['has_access']['function'])
                && !$field_defs['has_access']['function']()
            ) {
                unset($data[$field]);
                continue;
            }
            $data[$field]['name'] = $defs['name'] ?? $field;
            $data[$field]['type'] = $defs['type'] ?? $field_defs['type'];
            $data[$field]['options'] = $field_defs['options'];
            $label = $defs['label'] ?? $field_defs['label'] ?? $field_defs['vname'];
            $data[$field]['label'] = $label;
        }
        return $data;
    }

    function prepareLabel($label)
    {
        $label = trim($label);
        if (in_array(substr($label, -1), [':', '.'])) {
            $label = substr($label, 0, -1);
        }
        return $label;
    }

}
