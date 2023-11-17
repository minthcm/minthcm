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

require_once 'include/Notifications/NotificationManager.php';
require_once 'include/Notifications/NotificationAbstractClass.php';
require_once 'include/Notifications/NotificationNull.php';
require_once 'include/WebPushNotifications/NotificationTypes/WebPushBeanNotification.php';
class Notification  extends NotificationAbstractClass
{

    public function setRelatedBean($related_bean_id, $related_bean_type)
    {
        $this->related_bean_type = $related_bean_type;
        $this->related_bean_id = $related_bean_id;
        return $this;
    }

    public function setRelatedBeanFromBean($related_bean)
    {
        $this->related_bean_type = $related_bean->module_dir;
        $this->related_bean_id = $related_bean->id;
        return $this;
    }

    public function setAssignedUserId($assigned_user_id)
    {
        if (!NotificationManager::isValidUser($assigned_user_id)) {
            return new NotificationNull;
        }
        $this->assigned_user_id = $assigned_user_id;
        return $this;
    }

    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    public function setDescription($description)
    {
        $this->description = $description;
        return $this;
    }
    public function setType($type)
    {
        $this->type = $type;
        return $this;
    }


    public function saveAsAlert($description = true,$override = array())
    {
        if ($this->skip_uniq_validate || $this->isUnique()) {
            $this->simpleAlert($description, $override);
        } else {
            $this->setActive();
        }
        return $this;
    }
    /*
     * WebPush - use only after saveAsAlert or simpleAlert
    */
    public function WebPush($desc = true,$link = true,$override = array())
    {
        if($this->alert_bean instanceof Alert){
            if (!$desc) {
                $this->alert_bean->description = '';
            }
    
            if (count($override)) {
                foreach ($override as $element_name => $element_value) {
                    $this->alert_bean->$element_name = $element_value;
                }
            }
            $webpush = new WebPushBeanNotification($this->alert_bean);
            if ($link) {
                $webpush->setUrl($this->alert_bean->url_redirect);
            }

            $webpush->setType($this->alert_bean->alert_type)->push();
        }
    }

    public function simpleAlert($link = true,$override = array())
    {
        if(empty($this->type)){
            $GLOBALS['log']->fatal("Every notification has to have type defined, no type for ". $this->name);
            return new NotificationNull;
        }

        $bean = BeanFactory::newBean('Alerts');
        
        $bean->name = $bean->date_entered ? $bean->date_entered : date("Y-m-d") . ' ' . NotificationManager::getUserFullName($this->assigned_user_id);
        $bean->parent_type = $this->related_bean_type;
        $bean->parent_id = $this->related_bean_id;
        $bean->assigned_user_id = $this->assigned_user_id;
        $bean->is_read = 0;
        $bean->alert_type = $this->type;
        $bean->description = $this->description;

        if ($link) {
            if (!empty($bean->parent_id)) {
                $bean->url_redirect = 'index.php?&module=' . $bean->parent_type . '&action=DetailView&record=' . $bean->parent_id;
            } else {
                $bean->url_redirect = 'index.php?module=' . $bean->parent_type;
            }
        }
        
        if (count($override)) {
            foreach ($override as $element_name => $element_value) {
                $bean->$element_name = $element_value;
            }
        }

        

        $this->alert_bean = $bean;
        $this->alert_bean->save();
        return $this;
    }
}
