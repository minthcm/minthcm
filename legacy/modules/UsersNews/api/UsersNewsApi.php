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
SugarAutoLoader::requireWithCustom('modules/Comments/RelatedComments.php');
class UsersNewsApi
{

    public function getNewsForUser()
    {
        return array_merge($this->getAnnouncements(), $this->getReminders());
    }

    public function createOrUpdateUsersNews($args)
    {
        if (!empty($args['record_id'])) {
            $news = BeanFactory::getBean('News', $args['record_id']);
            if ($news && !empty($news->id) && !$this->updateUserNews($news->id, $news->news_type) && $news->news_type == 'reminder') {
                $this->createUserNews($news->id, $news->name);
            }
        }
    }

    protected function updateUserNews($news_id, $news_type)
    {
        global $current_user, $db, $timedate;
        $sql = "SELECT id FROM usersnews WHERE news_id = '{$news_id}' AND assigned_user_id = '{$current_user->id}' AND deleted = 0";
        $result = $db->getOne($sql);
        $user_news = BeanFactory::getBean('UsersNews', $result);
        if ($user_news && !empty($user_news->id)) {
            if ($news_type == 'announcement') {
                $user_news->news_read = true;
            } else {
                $user_news->not_display = $timedate->nowDbDate();
            }
            $user_news->save();
            return true;
        }
        return false;
    }

    protected function createUserNews($news_id, $news_name)
    {
        global $current_user, $timedate;
        $user_news = BeanFactory::newBean('UsersNews');
        $user_news->news_id = $news_id;
        $user_news->news_name = $news_name;
        $user_news->assigned_user_id = $current_user->id;
        $user_news->assigned_user_name = $current_user->name;
        $user_news->not_display = $timedate->nowDbDate();
        $user_news->save();
    }

    protected function getAnnouncements()
    {
        global $current_user, $db;
        $results = array();
        $sql = "SELECT id, name, content_of_announcement, news_type FROM news WHERE id IN (SELECT news_id FROM usersnews WHERE news_read = 0 AND assigned_user_id = '{$current_user->id}' AND deleted = 0) AND news_type='announcement' AND news_status <> 'draft' AND publication_date <= CURDATE() AND deleted = 0";
        $result = $db->query($sql);
        while ($row = $db->fetchByAssoc($result)) {
            $results[] = new NewsInfo($row);
        }
        return $results;
    }

    protected function getReminders()
    {
        global $current_user, $db;
        $results = array();
        $sql = "SELECT n.id, n.name, n.content_of_announcement, n.news_type, un.id user_news_id FROM news n
LEFT JOIN usersnews un ON un.not_display > (CURDATE() - INTERVAL 30 DAY) AND un.news_id = n.id AND un.assigned_user_id = '{$current_user->id}' AND un.deleted = 0
WHERE n.news_type='reminder' AND n.display_date = CURDATE() AND n.news_status = 'published' AND n.deleted = 0 AND un.id IS NULL";
        $result = $db->query($sql);
        while ($row = $db->fetchByAssoc($result)) {
            $news = BeanFactory::getBean('News', $row['id']);
            if ($news && !empty($news->id) && $news->canBeDisplayForUser($current_user->id)) {
                $results[] = new NewsInfo($row);
            }
        }
        return $results;
    }

}

class NewsInfo
{

    public $id;
    public $name;
    public $content_of_announcement;
    public $news_type;
    public $comments;

    public function __construct($row)
    {
        $this->id = $row['id'];
        $this->name = $row['name'];
        $this->content_of_announcement = htmlspecialchars_decode($row['content_of_announcement']);
        $this->news_type = $row['news_type'];
        $this->comments = $this->getComments($row['id']);
    }
    protected function getComments($news_id)
    {
        $bean = BeanFactory::getBean('News', $news_id);
        return display_comments($bean);
    }

}
