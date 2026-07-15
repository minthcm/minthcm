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
 * Copyright (C) 2018-2026 MintHCM
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

namespace MintHCM\Modules\Trackers\api\controllers;

use MintHCM\Data\BeanFactory;
use MintHCM\Data\MintDateTime;
use MintHCM\Utils\LegacyConnector;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Psr7\Response;

class RecordViewAction
{

    public function __invoke(Request $request, Response $response, array $args): Response
    {
        $response = $response->withHeader('Content-type', 'application/json');
        $record = $request->getAttribute('record');
        $module_name = $request->getAttribute('module_name');
        if (empty($record) || empty($module_name)) {
            $response->getBody()->write(json_encode(['status' => false]));
        }
        $this->trackDetailView($module_name, $record);
        $response->getBody()->write(json_encode(['status' => true]));

        return $response;
    }

    public function trackDetailView($module_name, $record): void
    {
        global $current_user;
        $bean = BeanFactory::getBean($module_name, $record);
        if (empty($bean->id) || $bean->id !== $record) {
            return;
        }
        // FIXME [CR #181880]: chdir() pattern is fragile and error-prone depending on execution context.
        // Also, $trackerManager is reassigned from LegacyConnector object to static getInstance() result
        // which is confusing. Consider refactoring to avoid chdir() and clarify variable usage.
        // a mój komentarz jest taki, że zamiast zmieniać konstruktor TrackerManager z private na public, lepiej byłoby dodać jakiś
        // LegacyStaticConnector, który by udostępniał tylko statyczne metody, a wtedy nie byłoby problemu z Singletonem, bo nie byłoby potrzeby tworzenia instancji
        $trackerManager = $trackerManager::getInstance();
        chdir('../legacy');
        if ($monitor = $trackerManager->getMonitor('tracker')) {
            chdir('../api');
            $monitor->setValue('date_modified', (new MintDateTime('now'))->toDatabaseDateFormat());
            $monitor->setValue('user_id', $current_user->id);
            $monitor->setValue('module_name', $bean->module_name);
            $monitor->setValue('action', 'detailview');
            $monitor->setValue('item_id', $bean->id);
            $monitor->setValue('item_summary', $bean->get_summary_text());
            $monitor->setValue('visible', $bean->tracker_visibility);
            chdir('../legacy');
            $trackerManager->saveMonitor($monitor, true, true);
            chdir('../api');
        }
    }

}
