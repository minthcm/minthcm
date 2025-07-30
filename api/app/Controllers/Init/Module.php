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

namespace MintHCM\Api\Controllers\Init;

use BeanFactory;
use Doctrine\ORM\EntityManagerInterface;
use Slim\Psr7\Response;
use Slim\Routing\RouteContext;
use Slim\Exception\HttpBadRequestException;
use MintHCM\Api\Controllers\Init\Preferences;
use MintHCM\Api\Controllers\MetaController;
use Psr\Http\Message\ServerRequestInterface as Request;
use MintHCM\Utils\ConstantsLoader;

class Module
{
    const VIEW_META = [
        "DetailView",
        "EditView",
        "Subpanels",
        "RecordView",
    ];

    protected $preferences_controller, $sugar_view, $modules_icons, $action_icons, $module_meta_controller;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->module_meta_controller = new MetaController();
        $this->preferences_controller = new Preferences($entityManager);
        $this->sugar_view = new \SugarView();
        $this->modules_icons = ConstantsLoader::getConstants('module_icons');
        $this->action_icons = ConstantsLoader::getConstants('menu_icons');
    }

    public function __invoke(Request $request, Response $response, array $args): Response
    {
        $response = $response->withHeader('Content-type', 'application/json');

        $routeContext = RouteContext::fromRequest($request);
        $route = $routeContext->getRoute();
        $module = explode('/', $route->getPattern())[1] ?? null;
        if(!$module) {
            throw new HttpBadRequestException($request);
        }

        $response->getBody()->write(json_encode($this->getData($module)));
        return $response;
    }

    public function getData($module)
    {
        $response = array();
        $response['lang'] = $this->getModuleLang($module);
        $response['data'] = $this->getModuleData($module);
        return $response;
    }

    public function getModuleLang($module)
    {
        global $current_language;
        chdir('../legacy/');
            $response = return_module_language($current_language, $module);
        chdir('../api/');
        return $response;
    }

    public function getModuleData($module)
    {
        return array(
            "name" => $module,
            "icon" => $this->modules_icons[$module] ?? $this->modules_icons['default'],
            "actions" => $this->getModuleMenu($module),
            "vardefs" => $this->getVardefs($module),
            "metadata" => $this->getMetadata($module),
            "acl" => $this->getACLForModule($module),
            "dashboards" => 'Home' === $module ? $this->getHomeMenu() : array(),
        );
    }

    public function getACLs(){
        global $moduleList;
        $acls = array();
        foreach ($moduleList as $module) {
            $acls[$module] = $this->getACLForModule($module);
        }
        return $acls;
    }

    private function getACLForModule($module)
    {
        global $current_user;
        $acl = $_SESSION['ACL'][$current_user->id];
        if (empty($acl)) {
            chdir('../legacy');
            $acl = \ACLAction::getUserActions($current_user->id, false) ?? [];
            chdir('../api');
        }
        if(is_array($acl[$module]['module'])){
            foreach($acl[$module]['module'] as $view => $access){
                if($current_user->isAdmin()){
                    switch($view){
                        case 'access':
                            $acl[$module]['module'][$view]['aclaccess'] = ACL_ALLOW_ENABLED;
                        break;
                        default:
                            $acl[$module]['module'][$view]['aclaccess'] = ACL_ALLOW_ALL;
                        break;
                    }
                }
            }
        }
        return array_map(function ($view) { return (int)$view['aclaccess']; }, $acl[$module]['module'] ?? []);
    }

    private function getHomeMenu()
    {
        $user_pref = $this->preferences_controller->getUserAllPreferences();

        $response = array();
        if (empty($user_pref["Home"]["pages"])) {
            return $response;
        }

        foreach ($user_pref["Home"]["pages"] as $page) {
            $response[] = array(
                "dashboard_label" => $page['pageTitleLabel'] ?? "",
                "dashboard_name" => $page['pageTitle'] ?? "",
            );
        }
        return $response;
    }

    private function getModuleMenu($module)
    {
        chdir('../legacy/');
        $menu = $this->sugar_view->getMenu($module);
        chdir('../api/');

        $response = array();
        foreach ($menu as $item) {
            $row = array(
                "url" => $item[0],
                "name" => $item[1],
                "action" => $item[2],
                "icon" => $this->action_icons[strtolower($item[2])] ?? $this->action_icons['default'],
                "params" => $item[3] ?? array(),
            );
            if (isset($item[3])) {
                $row['module'] = $item[3];
            }
            $response[] = $row;
        }

        return $response;
    }

    private function getMetadata($module_name)
    {
        $metadata = [];
        if($module_name !== "OAuthKeys") {
            foreach(static::VIEW_META as $index=>$view) {
                $method = "get" . $view . "Meta";
                $metadata[$view] = $this->module_meta_controller->$method($module_name);
            }
        }
        return $metadata;
    }

    private function getVardefs($module_name)
    {
        chdir('../legacy/');
        $bean = BeanFactory::newBean($module_name);
        chdir('../api/');
        return $bean->field_defs ?? [];
    }
}
