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
use MintHCM\Api\Entities\UserPreferences;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Psr7\Response;

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

    public function getGlobalSettings()
    {
        global $sugar_config;

        return array(
            'calendar' => $sugar_config['calendar'],
            'currency' => $sugar_config['currency'],
            'date_format' => $sugar_config['datef'],
            'time_format' => $sugar_config['timef'],
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
            'time_zones' => \TimeDate::getTimezoneList(),
            'name_formats' => (new \Localization())->getUsableLocaleNameOptions($sugar_config['name_formats']),
            'currencies' => $this->getCurrenciesList(),
        );
    }

    protected function getCurrenciesList()
    {
        $return_list = [];
        chdir('../legacy/');
        $currency = BeanFactory::getBean('Currencies');
        $list = $currency->get_full_list('name');
        $currency->retrieve('-99');
        if (is_array($list)) {
            $list = array_merge(array($currency), $list);
        } else {
            $list = array($currency);
        }
        foreach($list as $currency_bean){
            $return_list[$currency_bean->id] = [
                'id' => $currency_bean->id,
                'iso4217' => $currency_bean->iso4217,
                'name' => $currency_bean->name,
                'status' => $currency_bean->status,
                'conversion_rate' => $currency_bean->conversion_rate,
                'symbol' => $currency_bean->symbol,
                'hidden' => $currency_bean->hidden,
                'currency_on_right' => $currency_bean->currency_on_right,
            ];
        }
        chdir('../api/');
        return $return_list;
    }

    public function getUserPreferences()
    {
        return array(
            'date_format' => $this->user_preferences['global']['datef'] ?? '',
            'time_format' => $this->user_preferences['global']['timef'] ?? '',
            'name_format' => $this->user_preferences["global"]["default_locale_name_format"] ?? '',
        );
    }

    public function getUserAllPreferences()
    {
        return $this->user_preferences;
    }

    private function setUserPreferences()
    {
        global $current_user, $sugar_config;
        if (empty($current_user->id)) {
            return array();
        }

        try {
            $rows = $this->entityManager->getRepository(UserPreferences::class)
                ->findAllUndeletedByUserId($current_user->id);

            foreach ($rows as $row) {
                $category = $row['category'];
                $preferences[$category] = unserialize(base64_decode($row['contents']));
            }

            $this->user_preferences = $preferences;
        } catch (\Exception $e) {
            // TODO: log 'Failed to load user preferences'
            throw ($e);
        }
    }
}
