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
use MintHCM\Api\Controllers\Init\Module;
use MintHCM\Api\Controllers\Init\Languages;
use MintHCM\Api\Controllers\Init\Preferences;
use Psr\Http\Message\ServerRequestInterface as Request;

class Init
{
    protected $preferences_controller, $languages_controller, $module_init_controller;

    const VIEW_META = [
        "DetailView",
        "EditView",
        "Subpanels",
        "RecordView",
    ];

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->preferences_controller = new Preferences($entityManager);
        $this->languages_controller = new Languages();
        $this->module_init_controller = new Module($entityManager);
    }

    public function __invoke(Request $request, Response $response, array $args): Response
    {   
        $response = $response->withHeader('Content-type', 'application/json');

        $response_body = $this->getData();
        $response->getBody()->write(json_encode($response_body));
        return $response;
    }

    public function getData()
    {
        $response_body = array();
        $response_body['installed'] = true;
        $response_body['languages'] = $this->languages_controller->getLanguages();
        $response_body['user'] = $this->getCurrentUserData();
        $response_body['preferences'] = $this->preferences_controller->getUserPreferences();
        $response_body['global'] = $this->preferences_controller->getGlobalSettings();
        [$modules_menu, $modules_data] = $this->getModules();
        $response_body['menu_modules'] = $modules_menu;
        $response_body['modules'] = $modules_data;
        $response_body['quick_create'] = $this->getQuickCreate();
        $response_body['legacy_views'] = $this->getLegacyViews();
        return $response_body;
    }

    public function getCurrentUserData()
    {
        global $current_user;
        if (empty($current_user->id)) {
            return array();
        }
        $preferences = [];
        $preferences['date_time_preferences'] = $current_user->getUserDateTimePreferences();
        $preferences['first_day_of_week'] = $current_user->getPreference('fdow');
        $preferences['timezone'] = $current_user->getPreference('timezone');
        $preferences['name_format'] = $current_user->getPreference('default_locale_name_format');
        $preferences['dec_sep'] = $current_user->getPreference('dec_sep');
        $preferences['num_grp_sep'] = $current_user->getPreference('num_grp_sep');
        $preferences['default_currency_significant_digits'] = $current_user->getPreference('default_currency_significant_digits');
        return array(
            "id" => $current_user->id,
            "is_admin" => "1" === $current_user->is_admin ? true : false,
            "first_name" => $current_user->first_name,
            "last_name" => $current_user->last_name,
            "full_name" => $current_user->full_name,
            "email" => $current_user->email1,
            "photo" => $current_user->photo,
            "preferences" => $preferences,
            "show_login_wizard" => empty($current_user->getPreference('ut')),
        );
    }

    private function getModules()
    {
        global $current_user, $app_list_strings;
        chdir('../legacy');
        $modules = query_module_access_list($current_user);
        chdir('../api');
        $modules_data = array();
        if (!is_array($modules)) {
            return $modules_data;
        }
        foreach ($modules as $module) {
            $modules_data[$module] = $this->module_init_controller->getModuleData($module);
        }
        return $this->getMenuForAllModules($modules_data,$modules);
    }

    private function getQuickCreate()
    {
        chdir('../api');
        $modules = include "constants/quick_create.php";
        $response = array();

        if (!is_array($modules)) {
            return $response;
        }

        foreach ($modules as $module => $name) {
            $response[] = array(
                "module" => $module,
                "name" => $name,
            );
        }
        return $response;
    }

    private function getLegacyViews()
    {
        $legacy_views = include "constants/legacy_views.php";
        return $legacy_views;
    }

    private function getMenuForAllModules($modules_data,$modules)
    {
        global $beanList,$current_user;
        foreach($beanList as $key=>$module) {
            if(!array_key_exists($key,$modules_data)){
                $modules_data[$key] = $this->module_init_controller->getModuleData($key);
            }
        }
        return [array_keys($modules), $modules_data];
    }
}
