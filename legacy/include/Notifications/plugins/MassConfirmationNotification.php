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
require_once 'include/Notifications/NotificationPlugin.php';
require_once 'include/Integrations/Firebase/autoload.php'; // MintHCM #122506

class MassConfirmationNotification extends NotificationPlugin
{
    const TYPE = 'MassConfirmationNotification';
    const LABEL = 'LBL_MASSCONFIRMATIONNOTIFICATION';

    protected $user_id;
    protected $description;

    public function run()
    {
        if (empty($this->user_id)) {
            $GLOBALS['log']->fatal("MassConfirmationNotification requires a user ID to run.");
            return;
        }
        if (empty($this->description)) {
            $GLOBALS['log']->fatal("MassConfirmationNotification requires a description to run.");
            return;
        }
        $this->getNewNotification()
            ->disableUniqueValidation()
            ->setAssignedUserId($this->user_id)
            ->setDescription($this->description)
            ->setType($this->getType())
            ->saveAsAlert(true)
            ->WebPush(true, true);
    }

    public function isWebPushableNotification()
    {
        return true;
    }
    public function getWebPushDescriptionConfig()
    {
        return true;
    }
    public function getWebPushLinkConfig()
    {
        return true;
    }
    public function getWebPushOverrideConfig()
    {
        return $options = ['url_redirect' => 'index.php?module=WorkSchedules'];
    }

    public function setUserId($user_id)
    {
        $this->user_id = $user_id;
    }
    public function setDescription($text)
    {
        $this->description = $text;
    }
    public function canBeManagedByUser()
    {
        return false;
    }
}
