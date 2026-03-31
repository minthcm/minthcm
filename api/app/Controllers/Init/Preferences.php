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

namespace MintHCM\Api\Controllers\Init;

use Doctrine\ORM\EntityManagerInterface;
use MintHCM\Api\Entities\Currencies;
use MintHCM\Api\Entities\UserPreferences;
use MintHCM\Api\Repositories\CurrenciesRepository;
use MintHCM\Api\Repositories\UserPreferencesRepository;
use MintHCM\Utils\LuxonMapper;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Psr7\Response;

#[\AllowDynamicProperties]
class Preferences
{
    protected $entityManager;
    protected $user_preferences;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
        $this->setUserPreferences();
    }

    public function __invoke(Request $request, Response $response, array $args): Response
    {
        $response = $response->withHeader('Content-type', 'application/json');
        $response->getBody()->write(json_encode($this->getPreferences()));
        return $response;
    }

    public function getPreferences()
    {
        $response['preferences'] = $this->getUserPreferences();
        $response['global'] = $this->getGlobalSettings();
        return $response;
    }

    public function getGlobalSettings($minified = false, $rebuild_array = [])
    {
        global $sugar_config;

        $global_settings = [
            'calendar' => $sugar_config['calendar'],
            'currency' => $sugar_config['currency'],
            'date_format' => $sugar_config['datef'],
            'time_format' => $sugar_config['timef'],
            'time_zones' => \TimeDate::getTimezoneList(),
            'default_date_format' => $sugar_config["default_date_format"],
            'default_time_format' => $sugar_config["default_time_format"],
            'default_language' => $sugar_config["default_language"],
            'languages' => $sugar_config["languages"],
            'date_formats' => $sugar_config["date_formats"],
            'time_formats' => $sugar_config["time_formats"],
            'name_format' => $sugar_config["default_locale_name_format"],
            'password_rules' => [
                'minpwdlength' => $sugar_config['passwordsetting']['minpwdlength'] ?? null,
                'oneupper' => $sugar_config['passwordsetting']['oneupper'] ?? false,
                'onelower' => $sugar_config['passwordsetting']['onelower'] ?? false,
                'onenumber' => $sugar_config['passwordsetting']['onenumber'] ?? false,
                'onespecial' => $sugar_config['passwordsetting']['onespecial'] ?? false,
            ],
            'name_formats' => (new \Localization())->getUsableLocaleNameOptions($sugar_config['name_formats']),
            'upload_maxsize' => $sugar_config['upload_maxsize'] ?? 0,
            'list_max_entries_per_subpanel' => $sugar_config['list_max_entries_per_subpanel'],
            'list_max_entries_per_page' => $sugar_config['list_max_entries_per_page'] ?? 20,
        ];
        if (!$minified || in_array('reload_currency', $rebuild_array) || empty($global_settings['currencies'])) {
            $global_settings['currencies'] = $this->getCurrenciesList();
        }
        return $global_settings;
    }

    protected function getCurrenciesList()
    {
        /** @var CurrenciesRepository */
        $repository = $this->entityManager->getRepository(Currencies::class);
        $currencies = $repository->getAvailable();
        $currency_list = [];
        foreach ($currencies as $currency) {
            $currency_list[$currency['id']] = $currency;
        }
        return $currency_list;
    }

    public function getUserPreferences()
    {
        global $sugar_config, $locale, $current_user;
        // $pref_tz = $current_user->getPreference('timezone');
        return array(
            'date_format' => LuxonMapper::phpToLuxonFormat($this->user_preferences['global']['datef'] ?? $sugar_config['default_date_format']),
            'time_format' => LuxonMapper::phpToLuxonFormat($this->user_preferences['global']['timef'] ?? $sugar_config['default_time_format']),
            'timezone' => $current_user->getPreference('timezone') ?? $sugar_config['default_timezone'],
            'name_format' => $this->user_preferences["global"]["default_locale_name_format"] ?? $sugar_config['default_locale_name_format'],
            'dec_sep' => $this->user_preferences['global']['dec_sep'] ?? $sugar_config['default_decimal_seperator'],
            'num_grp_sep' => $this->user_preferences['global']['num_grp_sep'] ?? $sugar_config['default_number_grouping_seperator'],
            'default_currency_significant_digits' => $locale->getPrecedentPreference('default_currency_significant_digits', $current_user),
            'first_day_of_week' => $this->user_preferences['global']['fdow'] ?? 0,
            'language' => $_SESSION['authenticated_user_language'],
            'reload_module_menu' => $this->user_preferences['global']['reload_module_menu'] ?? false,
        );
    }

    public function getUserAllPreferences()
    {
        return $this->user_preferences;
    }

    private function setUserPreferences()
    {
        global $current_user;
        if (empty($current_user->id)) {
            return array();
        }

        try {
            /** @var UserPreferencesRepository */
            $repository = $this->entityManager->getRepository(UserPreferences::class);
            /** @var UserPreferences[] */
            $user_preferences = $repository->findAllUndeletedByUserId($current_user->id);

            foreach ($user_preferences as $user_preference) {
                $category = $user_preference->category;
                $preferences[$category] = $user_preference->getContentsAsArray();
            }

            $this->user_preferences = $preferences;
            $this->user_preferences['global']['default_locale_name_format'] = $current_user->getPreference('default_locale_name_format');
            $this->user_preferences['global']['dec_sep'] = $current_user->getPreference('dec_sep');
            $this->user_preferences['global']['num_grp_sep'] = $current_user->getPreference('num_grp_sep');
            $this->user_preferences['global']['datef'] = $current_user->getPreference('datef');
            $this->user_preferences['global']['timef'] = $current_user->getPreference('timef');
        } catch (\Exception $e) {
            // TODO: log 'Failed to load user preferences'
            throw ($e);
        }
    }

}
