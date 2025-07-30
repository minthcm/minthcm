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
 * Copyright (C) 2018-2019 MintHCM
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
require_once 'include/Notifications/NotificationPlugin.php';

class WorkScheduleLeaveCreated extends NotificationPlugin
{
    const TYPE = 'WorkScheduleLeaveCreated';
    const LABEL = 'LBL_WORKSCHEDULE_LEAVE_CREATED';

    public function run()
    {
        if (!$this->bean) {
            return;
        }
        if (empty($this->bean->assigned_user_id)) {
            return;
        }
        global $app_list_strings;
        $user = BeanFactory::getBean('Users', $this->bean->assigned_user_id);
        $superior_id = $user->reports_to_id;
        $message = vsprintf(
            translate('LBL_LEAVE_ALERT', 'WorkSchedules'), 
            [
                $user->full_name,
                $app_list_strings[$this->bean->field_defs['type']['options']][$this->bean->type],
                $this->getWorkScheduleStartDate(), 
                ]
            );
        if ($superior_id) {
            $this->getNewNotification()
                ->setDescription($message)
                ->setAssignedUserId($superior_id)
                ->setRelatedBean($this->bean->id, 'WorkSchedules')
                ->setType($this->getType())
                ->saveAsAlert()->WebPush();
        }

    }
    public function isWebPushableNotification()
    {
        return true;
    }

    protected function getWorkScheduleStartDate()
    {
        $datetime = explode(' ', NotificationManager::toDbDatetime($this->bean->date_start));
        return $datetime[0];
    }

}
