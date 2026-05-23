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

SugarAutoLoader::requireWithCustom('include/Notifications/Notification.php');

class Kudos extends Basic
{

    public $new_schema = true;
    public $module_dir = 'Kudos';
    public $object_name = 'Kudos';
    public $table_name = 'kudos';
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
    public $assigned_user_link;
    public $SecurityGroups;

    public function bean_implements($interface)
    {
        $result = false;
        if ('ACL' === $interface) {
            $result = true;
        }
        return $result;
    }

    public function save($check_notify = false)
    {
        $this->setName();
        $result = parent::save($check_notify);

        return $result;
    }

    protected function setName()
    {
        if (strlen($this->description) <= 30) {
            return $this->name = $this->description;
        }
        $this->name = substr($this->description, 0, 30) . "...";
    }

    public function ACLAccess($view, $is_owner = 'not_set', $in_group = 'not_set')
    {
        global $current_user;

        if (!parent::ACLAccess($view, $is_owner, $in_group)) {
            return false;
        }

        if (!empty($this->id) && "[SELECT_ID_LIST]" != $this->id && empty($this->employee_id)) {
            $this->retrieve($this->id);
        }

        if (
            in_array($this->id, ["[SELECT_ID_LIST]", null])
            || in_array($current_user->id, [$this->created_by, $this->assigned_user_id])
            || ($current_user->id == $this->employee_id && in_array(fixupView($view), ['view']))
            || ($current_user->isAdmin() || !$this->bean_implements('ACL'))
        ) {
            return true;
        }
        return false;
    }

    public function getOwnerWhere($user_id)
    {
        include 'modules/Employees/access_config.php';

        $owners_array = [];
        if (isset($this->field_defs['assigned_user_id'])) {
            $owners_array[] = " $this->table_name.assigned_user_id ='$user_id' ";
        }

        if (isset($GLOBALS["dictionary"][$this->object_name]["templates"]['employee_related']) && !in_array($this->module_dir, $employee_related_exclude_modules)) {
            $owners_array[] = " $this->table_name.employee_id ='$user_id' ";
        }

        if (count($owners_array)) {
            return " (" . join(") OR (", $owners_array) . ") ";
        }

        if (isset($this->field_defs['created_by'])) {
            return " $this->table_name.created_by ='$user_id' ";
        }

        return '';
    }

    public function isOwner(?string $user_id)
    {
        // MintHCM Begin #70311 - whole isOwner function redesigned
        $is_owner = false;
        include 'modules/Employees/access_config.php';

        //if we don't have an id we must be the owner as we are creating it
        if (!isset($this->id) || "[SELECT_ID_LIST]" == $this->id) {
            return true;
        }
        //if there is an assigned_user that is the owner
        if (!empty($this->fetched_row['assigned_user_id']) && $this->fetched_row['assigned_user_id'] == $user_id) {
            return true;
        } elseif (!empty($this->assigned_user_id) && $this->assigned_user_id == $user_id) {
            $is_owner = true;
        } elseif (isset($GLOBALS["dictionary"][$this->object_name]["templates"]['employee_related']) && !in_array($this->module_dir, $employee_related_exclude_modules)
            && !empty($this->employee_id) && $this->employee_id == $user_id
        ) {
            $is_owner = true;
        } else {
            //other wise if there is a created_by that is the owner
            if (!$is_owner && !empty($this->created_by) && $this->created_by == $user_id) {
                $is_owner = true;
            }
        }

        return $is_owner;
        // MintHCM End #70311
    }
}
