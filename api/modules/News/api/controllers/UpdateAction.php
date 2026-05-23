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

namespace MintHCM\Modules\News\api\controllers;

use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Psr7\Response;
use Slim\Exception\HttpBadRequestException;
use MintHCM\Modules\Alerts\api\controllers\ListAction;

class UpdateAction
{
    public function markAlertsAsRead(Request $request, Response $response, array $args): Response
    {
        $news_id = $request->getAttribute('news_id');

        if (!$news_id) {
            throw new HttpBadRequestException($request);
        }

        global $current_user, $db;
        $parent_ids = [$news_id];
        $users_news_id = $this->getUsersNewsForUser($current_user->id, $news_id);

        if (!empty($users_news_id)) {
            array_push($parent_ids, $users_news_id);
        }

        $sql = "UPDATE alerts SET is_read = 1 WHERE parent_id IN ({$db->implodeQuoted($parent_ids)}) AND deleted = 0 AND assigned_user_id = {$db->quoted($current_user->id)} AND parent_type = 'News'";
        $result = $db->query($sql);

        if (!$result) {
            throw new HttpBadRequestException($request);
        }

        $response = $response->withHeader('Content-type', 'application/json');
        $alerts_list_action = new ListAction();
        $response->getBody()->write(json_encode($alerts_list_action->getListData()));

        return $response;
    }

    protected function getUsersNewsForUser($user_id, $news_id)
    {
        global $db;
        $sql = "SELECT id FROM usersnews WHERE news_id = {$db->quoted($news_id)} AND deleted = 0 AND assigned_user_id = {$db->quoted($user_id)}";
        $result = $db->getOne($sql);
        return ($result) ? $result : '';
    }
}
