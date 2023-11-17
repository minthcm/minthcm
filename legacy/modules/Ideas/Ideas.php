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

require_once 'modules/Ideas/SugarFeeds/IdeasFeed.php';

class Ideas extends Basic
{

    public $new_schema = true;
    public $module_dir = 'Ideas';
    public $object_name = 'Ideas';
    public $table_name = 'ideas';
    public $importable = true;
    public $id;
    public $name;
    public $date_entered;
    public $date_modified;
    public $modified_user_id;
    public $modified_by_name;
    public $created_by;
    public $created_by_name;
    public $description;
    public $deleted;
    public $created_by_link;
    public $modified_user_link;
    public $assigned_user_id;
    public $assigned_user_name;
    public $user_name;
    public $user_id;
    public $assigned_user_link;
    public $SecurityGroups;
    public $status;
    public $explanation;

    public function bean_implements($interface)
    {
        $result = false;
        if ($interface === 'ACL') {
            $result = true;
        }
        return $result;
    }

    protected function postSave()
    {
        global $app_strings;
        $if = new IdeasFeed();
        $if->pushFeed($this, null, null);
        if (!empty($this->user_id) && $this->user_id != $this->fetched_row['user_id']) {
            $this->addDecisionMakerPrivateGroup();
            $description = $app_strings['LBL_ASSIGN_TO_IDEA'];
        } else {
            $description = $app_strings['LBL_IDEA_MODIFIED'];
        }
        $this->addDecisionMakerNotification($this->user_id, $description);
        $this->addDecisionMakerNotification($this->assigned_user_id, $description);
    }

    protected function addDecisionMakerPrivateGroup()
    {
        $user = BeanFactory::getBean('Users', $this->user_id);
        if ($user && !empty($user->id) && $this->load_relationship('SecurityGroups')) {
            $group_id = $user->getUserPrivateGroup();
            if ($group_id) {
                $this->SecurityGroups->add($group_id);
            }
        }
    }

    protected function addDecisionMakerNotification($user_id, $description)
    {
        SugarAutoLoader::requireWithCustom('include/Notifications/Notification.php');
        $notification = new Notification();
        $notification->setAssignedUserId($user_id)
            ->setDescription($description)
            ->setRelatedBean($this->id, 'Ideas')
            ->saveAsAlert();
        $notification->disableUniqueValidation();
    }

}
