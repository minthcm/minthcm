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
class Onboardings extends Basic
{

    public $new_schema = true;
    public $module_dir = 'Onboardings';
    public $object_name = 'Onboardings';
    public $table_name = 'onboardings';
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
    public $status;
    public $date_start;

    const SUGAR_FEED_CLASS_NAME = "OnboardingsFeed";

    public function bean_implements($interface)
    {
        if ("ACL" === $interface) {
            return true;
        } else {
            return false;
        }
    }

    public function save($check_notify = false)
    {
        $this->concatName();
        $id = parent::save($check_notify);
        require_once 'modules/' . $this->module_dir . '/SugarFeeds/' . static::SUGAR_FEED_CLASS_NAME . '.php';
        $sugar_feed_class_name = static::SUGAR_FEED_CLASS_NAME;
        $feed = new $sugar_feed_class_name;
        $feed->pushFeed($this, null, null);
        return $id;
    }

    protected function concatName()
    {
        global $timedate, $current_user;
        $test_converted_to_db = $this->date_start;
        $date_time_regex = '/^\d\d\d\d-(0[1-9]|1[0-2])-(0[1-9]|[12][0-9]|3[01]) (0[0-9]|1[0-9]|2[0-3]):(0[0-9]|[0-5][0-9]):(0[0-9]|[0-5][0-9])$/m';
        preg_match($date_time_regex, $test_converted_to_db, $matches);
        if (empty($matches)) {
            $test_converted_to_db = $timedate->to_db($test_converted_to_db);
        }
        $converted_to_db_date = '';
        if (empty($test_converted_to_db)) {
            $GLOBALS['log']->fatal("Onboardings::concatName(): Incorrect date_start format, given: {$this->date_start}, returns empty string");
        } else {
            $coverted_to_user_date = $timedate->to_display_date_time($test_converted_to_db, $current_user);
            $converted_to_db_date = $timedate->to_db_date($coverted_to_user_date, false);
        }
        $employee = BeanFactory::getBean('Employees', $this->employee_id);
        $this->name = $employee->last_name . ' ' . $employee->first_name . ' - ' . $converted_to_db_date;
    }

    public function ACLAccess($view, $is_owner = 'not_set', $in_group = 'not_set')
    {
        if (in_array(strtolower($view), array('edit', 'save', 'popupeditview', 'editview')) && !$this->id) {
            return false;
        }
        return parent::ACLAccess($view, $is_owner, $in_group);
    }

}
