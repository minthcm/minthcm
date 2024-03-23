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
class OnboardingOffboardingElements extends Basic
{
    public $new_schema            = true;
    public $module_dir            = 'OnboardingOffboardingElements';
    public $object_name           = 'OnboardingOffboardingElements';
    public $table_name            = 'onboardingoffboardingelements';
    public $importable            = true;
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
    public $type;
    public $task_duration;
    public $task_duration_hours;
    public $task_duration_minutes;
    public $days_from_start;
    public $hours_value_default   = 1;
    public $minutes_value_default = 0;
    public $minutes_values        = array('0' => '0');

    public function bean_implements($interface)
    {
        if ("ACL" === $interface) {
            return true;
        } else {
            return false;
        }
    }

    public function __construct()
    {
        parent::__construct();

        $this->setupCustomFields('OnboardingOffboardingElements');

        foreach ($this->field_defs as $field) {
            $this->field_name_map[$field['name']] = $field;
        }

        if (!empty($GLOBALS['app_list_strings']['duration_intervals'])) {
            $this->minutes_values = $GLOBALS['app_list_strings']['duration_intervals'];
        }
    }

    public function fill_in_additional_detail_fields()
    {
        parent::fill_in_additional_detail_fields();
        if (!isset($this->task_duration_minutes)) {
            $this->task_duration_minutes = $this->minutes_value_default;
        }
        if (!isset($this->task_duration_hours)) {
            $this->task_duration_hours = $this->hours_value_default;
        }
    }

    public function get_list_view_array()
    {
        global $current_language;
        $onboarding_offboarding_elements_mod_strings = return_module_language($current_language,
            'OnboardingOffboardingElements');
        $return_array                                = parent::get_list_view_array();
        $return_array['TASK_DURATION']               = "{$this->task_duration_hours}{$onboarding_offboarding_elements_mod_strings['LBL_HOURS_ABBREV']} {$this->task_duration_minutes}{$onboarding_offboarding_elements_mod_strings['LBL_MINSS_ABBREV']}";
        return $return_array;
    }
}