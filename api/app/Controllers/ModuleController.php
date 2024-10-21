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

namespace MintHCM\Api\Controllers;

use BeanFactory;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Psr7\Response;
use Slim\Routing\RouteContext;

class ModuleController
{

    public function detail(Request $request, Response $response, array $args): Response
    {
        $response = $response->withHeader('Content-type', 'application/json');
        $data = ['message' => 'detail'];
        $response->getBody()->write(json_encode($data));
        return $response;
    }

    function list(Request $request, Response $response, array $args): Response{
        $response = $response->withHeader('Content-type', 'application/json');
        $data = ['message' => 'list'];
        $response->getBody()->write(json_encode($data));
        return $response;
    }

    public function create(Request $request, Response $response, array $args): Response
    {
        $response = $response->withHeader('Content-type', 'application/json');
        $module = $this->getModuleFromRoute($request);
        chdir('../legacy/');

        $current_time_zone = date_default_timezone_get();
        date_default_timezone_set('UTC');
        $disable_date_format = $GLOBALS['disable_date_format'];
        $GLOBALS['disable_date_format'] = true;

        $bean = BeanFactory::newBean($module);
        if (empty($bean)) {
            return $response->withStatus(404);
        }
        if (!$bean->ACLAccess('edit')) {
            return $response->withStatus(403);
        }
        $record_data = $request->getAttribute("record_data");
        foreach ($record_data as $field_name => $value) {
            if (isset($bean->field_defs[$field_name])) {
                if ('id' === $field_name && !empty($value)) {
                    $bean->new_with_id = true;
                }
                $bean->$field_name = $value;
            }
        }
        $bean->save(false);
        $bean->retrieve();

        date_default_timezone_set($current_time_zone);
        $GLOBALS['disable_date_format'] = $disable_date_format;

        if (!empty($bean) && !empty($bean->id)) {
            $record_data = $this->mergeRecordData($bean);
        }

        chdir('../api/');
        $response = $response->withStatus(201);
        $response->getBody()->write(json_encode($record_data));
        return $response;
    }

    public function update(Request $request, Response $response, array $args): Response
    {
        $response = $response->withHeader('Content-type', 'application/json');
        $module = $this->getModuleFromRoute($request);
        chdir('../legacy/');
        $record_data = $request->getAttribute("record_data");
        $record_id = $request->getAttribute("id");
        
        $current_time_zone = date_default_timezone_get();
        date_default_timezone_set('UTC');
        $disable_date_format = $GLOBALS['disable_date_format'];
        $GLOBALS['disable_date_format'] = true;

        $bean = BeanFactory::getBean($module, $record_id);

        if (empty($bean) || $bean->id !== $record_id) {
            return $response->withStatus(404);
        }
        if (!$bean->ACLAccess('edit')) {
            return $response->withStatus(403);
        }
        foreach ($record_data as $field_name => $value) {
            if (isset($bean->field_defs[$field_name]) && "id" !== $field_name) {
                $bean->$field_name = $value;
            }
        }
        $bean->save(false);
        BeanFactory::unregisterBean($bean->module_name, $bean->id);
        $bean = BeanFactory::getBean($bean->module_name, $bean->id);
        // $bean->retrieve();

        date_default_timezone_set($current_time_zone);
        $GLOBALS['disable_date_format'] = $disable_date_format;

        if (!empty($bean) && $bean->id === $record_id) {
            $record_data = $this->mergeRecordData($bean);
        }

        chdir('../api/');
        $response = $response->withStatus(200);
        $response->getBody()->write(json_encode($record_data));
        return $response;
    }

    public function getRecord(Request $request, Response $response, array $args): Response
    {
        $response = $response->withHeader('Content-type', 'application/json');
        $module = $this->getModuleFromRoute($request);
        chdir('../legacy/');
        $record_id = $request->getAttribute("id");

        $current_time_zone = date_default_timezone_get();
        date_default_timezone_set('UTC');
        $disable_date_format = $GLOBALS['disable_date_format'];
        $GLOBALS['disable_date_format'] = true;

        $bean = BeanFactory::getBean($module,$record_id);

        date_default_timezone_set($current_time_zone);
        $GLOBALS['disable_date_format'] = $disable_date_format;

        if (empty($bean) || $bean->id !== $record_id) {
            return $response->withStatus(404);
        }
        if (!$bean->ACLAccess('view')) {
            return $response->withStatus(403);
        }
        if (!empty($bean) && $bean->id === $record_id) {
            $record_data = $this->mergeRecordData($bean);
        }
        chdir('../api/');
        $response->getBody()->write(json_encode($record_data));
        return $response;
    }

    public function delete(Request $request, Response $response, array $args): Response
    {
        $module = $this->getModuleFromRoute($request);
        $id = $request->getAttribute('id');
        chdir('../legacy/');
        $f = BeanFactory::getBean($module, $id);
        if (empty($f->id)) {
            $response = $response->withStatus(404);
            return $response;
        } else {
            if (!$f->ACLAccess('delete')) {
                $response = $response->withStatus(403);
                return $response;
            }
        }
        $f->mark_deleted($id);
        chdir('../api/');
        $response = $response->withStatus(200);
        return $response;
    }

    protected function getModuleFromRoute(Request $request): ?string
    {
        $routeContext = RouteContext::fromRequest($request);
        $route = $routeContext->getRoute();
        return explode('/', $route->getPattern())[1] ?? null;
    }

    public function subpanelRecords(Request $request, Response $response, array $args): Response
    {
        $module = $this->getModuleFromRoute($request);
        $id = $request->getAttribute('id');
        chdir('../legacy/');
        $focus = BeanFactory::getBean($module, $id);
        if (empty($focus->id)) {
            $response = $response->withStatus(404);
            return $response;
        }
        $related_name = $request->getAttribute('relation_name');
        require_once 'include/SubPanel/SubPanelDefinitions.php';
        $spd = new \SubPanelDefinitions($focus, $module);
        if (isset($spd->layout_defs['subpanel_setup'][$related_name])) {
            
            $target_module = $spd->layout_defs['subpanel_setup'][$related_name]['module'];
            $target_bean = BeanFactory::getBean($target_module);
            if (!$target_bean->ACLAccess('list')) {
                return $response->withStatus(403);
            }

            require_once 'include/ListView/ListViewSubPanel.php';
            $list_view = new \ListViewSubPanel();
            $subpanel_def = $spd->load_subpanel($related_name);
            $data = $list_view->process_dynamic_listview($module, $focus, $subpanel_def, true);
            $list = $data['list'];
            chdir('../api/');
            $response = $response->withStatus(200);
            $return_list = [];
            foreach ($list as $record_id => $record) {
                $record->fill_in_additional_detail_fields();
                foreach ($record->field_defs as $field_name => $field_def) {
                    $return_list[$record_id][$field_name] = $record->$field_name;
                }

            }
            $response->getBody()->write(json_encode($return_list));
            return $response;
        }
        chdir('../api/');
        $response = $response->withStatus(404);
        return $response;
    }

    protected function mergeRecordData($bean)
    {
        return array_merge(
            $bean->toArray(),
            [
                'module_name' => $bean->module_name,
                'acl_access' => [
                    'edit' => $bean->ACLAccess('edit'),
                    'delete' => $bean->ACLAccess('delete'),
                    'view' => $bean->ACLAccess('view'),
                ],
            ]
        );
    }
    
}