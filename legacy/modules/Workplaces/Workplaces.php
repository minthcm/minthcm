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

class Workplaces extends Basic
{

    public $new_schema = true;
    public $module_dir = 'Workplaces';
    public $object_name = 'Workplaces';
    public $table_name = 'workplaces';
    public $importable = true;
    public $id;
    public $room_id;
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
    public $mode;

    public function bean_implements($interface)
    {
        if ($interface === "ACL") {
            return true;
        } else {
            return false;
        }
    }
    public function save($check_notify = false)
    {
        $room_id = $this->room_id;
        $return_value = parent::save($check_notify);
        $this->recount($room_id);
        return $return_value;
    }
    public function mark_deleted($id)
    {
        $room_id = $this->getRoomId($id);
        parent::mark_deleted($id);
        $this->recountOne($room_id);
    }
    protected function getRoomId($record_id)
    {
        $db = DBManagerFactory::getInstance();
        $room_id = $db->getOne("SELECT room_id FROM {$this->table_name} where id='{$record_id}' and deleted=0 ");
        return $room_id;
    }
    public function recount($room_id)
    {
        $this->recountOne($room_id);
        if (!empty($this->fetched_row['room_id'] && $this->fetched_row['room_id'] != $room_id)) {
            $this->recountOne($this->fetched_row['room_id']);
        }
    }
    public function recountOne($room_id)
    {
        if (!empty($room_id)) {
            $db = DBManagerFactory::getInstance();
            $query = "SELECT COUNT(id) FROM {$this->table_name} WHERE room_id = '{$room_id}' AND deleted=0";
            $count = $db->getOne($query) ?? 0;
            $room = BeanFactory::getBean('Rooms', $room_id);
            if ($room->number_of_seats != $count) {
                $room->number_of_seats = $count;
                $room->save();
            }
        }
    }
}