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

if (!defined('sugarEntry')) {
    define('sugarEntry', true);
}
require_once 'include/Notifications/Notification.php';

class GenerateUsersNews
{

    protected $record_id;

    public function __construct($data)
    {
        $this->record_id = (isset($data)) ? $data : null;
    }

    public function generate()
    {
        $news = BeanFactory::getBean('News', $this->record_id);
        if ($news && !empty($news->id)) {
            $organizational_units_ids = $news->getRelatedOrganizationalUnitsIDs();
            $organizationalunits_controller = ControllerFactory::getController('SecurityGroups');
            $users_ids = $organizationalunits_controller->getActiveUsers($organizational_units_ids);
            $this->createOrUpdateUsersNews($users_ids);
        }
    }

    protected function createOrUpdateUsersNews($users_ids)
    {
        foreach ($users_ids as $user_id) {
            $users_news_id = $this->getUsersNewsForUser($user_id);
            if ($users_news_id) {
                $this->updateUsersNewsReadFlag($users_news_id);
            } else {
                $this->createUsersNewsForUser($user_id);
            }
        }
    }

    protected function getUsersNewsForUser($user_id)
    {
        global $db;
        $sql = "SELECT id FROM usersnews WHERE news_id='{$this->record_id}' AND assigned_user_id='{$user_id}' AND deleted = 0";
        $result = $db->getOne($sql);
        return ($result) ? $result : false;
    }

    protected function updateUsersNewsReadFlag($users_news_id)
    {
        $users_news = BeanFactory::getBean('UsersNews', $users_news_id);
        if ($users_news && !empty($users_news->id)) {
            $users_news->news_read = false;
            $users_news->save();

            $override = ['description' => translate("LBL_NEW_USERS_NEWS_UPDATED", "News")];
            (new Notification())->setRelatedBeanFromBean($users_news)->setAssignedUserId($users_news->assigned_user_id)->setName($users_news->news_name)->setType('UserNews')
                ->simpleAlert(true, $override)->WebPush(false, true, $override);
        }
    }

    protected function createUsersNewsForUser($user_id)
    {
        $user = BeanFactory::getBean('Users', $user_id);
        $news = BeanFactory::getBean('News', $this->record_id);

        if ($user && !empty($user->id) && $news && !empty($news->id)) {
            $users_news = BeanFactory::newBean('UsersNews');
            $users_news->news_id = $news->id;
            $users_news->news_name = $news->name;
            $users_news->assigned_user_id = $user->id;
            $users_news->assigned_user_name = $user->name;
            $users_news->save();

            $override = ['description' => translate("LBL_NEW_USERS_NEWS", "News")];

            (new Notification())->setRelatedBeanFromBean($news)->setAssignedUserId($user_id)->setName($users_news->news_name)->setType('UserNews')
                ->simpleAlert(true, $override)->WebPush(false, true, $override);
        }
    }

    protected function getPrivateGroups($users_ids)
    {
        global $db;
        $results = array();
        $sql = "SELECT id FROM securitygroups WHERE group_type='private' AND deleted = 0 AND assigned_user_id IN ('" . implode('\',\'', $users_ids) . "')";
        $result = $db->query($sql);
        while ($row = $db->fetchByAssoc($result)) {
            $results[] = $row['id'];
        }
        return $results;
    }

}
