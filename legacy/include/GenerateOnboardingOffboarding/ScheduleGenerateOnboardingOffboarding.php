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
class ScheduleGenerateOnboardingOffboarding
{
    protected $module_name;
    protected $template_id;
    protected $employee_id;
    protected $date_start;

    public function __construct($module_name, $template_id, $employee_id, $date_start)
    {
        global $timedate;
        $this->module_name = $module_name;
        $this->template_id = $template_id;
        $this->employee_id = $employee_id;
        $this->date_start = $date_start;
        if (!empty($this->date_start)) {
            $ds = getDateTimeObject($this->date_start);
            $this->date_start = $ds->format($timedate->get_db_date_time_format());
        }
    }

    public function schedule()
    {
        $errors = $this->validate();
        if (empty($errors)) {
            $this->createJob();
        }
        return !empty($errors) ? json_encode($errors) : true;
    }

    protected function createJob()
    {
        global $current_user;
        $jq = new SugarJobQueue();
        $job = new SchedulersJob();
        $job->name = "Schedule Generate Onboarding/Offboarding";
        $job->target = "class::GenerateOnboardingOffboardingJob";
        $data = base64_encode(json_encode(array(
            'module_name' => $this->module_name,
            'template_id' => $this->template_id,
            'employee_id' => $this->employee_id,
            'date_start' => $this->date_start,
        )));
        $job->data = $data;
        $job->assigned_user_id = $current_user->id;
        $jq->submitJob($job);
    }

    protected function validate()
    {
        $errors = [];
        $template = BeanFactory::getBean($this->module_name, $this->template_id);
        if ($template && !empty($template->id) && $template->load_relationship('elements')) {
            foreach ($template->elements->get() as $element_id) {
                $this->validateElement($element_id, $errors);
            }
        }
        return $errors;
    }

    protected function validateElement($element_id, &$errors)
    {
        $element = BeanFactory::getBean('OnboardingOffboardingElements', $element_id);
        if ($element && !empty($element->id)) {
            switch ($element->kind_of_element) {
                case 'self';
                    $employee = BeanFactory::getBean('Employees', $this->employee_id);
                    if (empty($errors[$element->kind_of_element]) && (empty($employee->user_name) || $employee->status != 'Active')) {
                        $errors[$element->kind_of_element] = [
                            'module' => 'Employees',
                            'id' => $employee->id,
                            'name' => $employee->full_name,
                        ];
                    }
                    break;
                case 'employee_manager';
                    $user = BeanFactory::getBean('Users', $this->employee_id);
                    if (empty($errors[$element->kind_of_element]) && empty($user->reports_to_id)) {
                        $errors[$element->kind_of_element] = [
                            'module' => 'Users',
                            'id' => $user->id,
                            'name' => $user->full_name,
                        ];
                    }
                    break;
                case 'organizational_unit_manager';
                    $organizational_unit = BeanFactory::getBean('SecurityGroups', $element->securitygroup_unit_id);
                    if (empty($errors[$element->kind_of_element][$organizational_unit->id]) && empty($organizational_unit->current_manager_id)) {
                        $errors[$element->kind_of_element][$organizational_unit->id] = [
                            'module' => 'SecurityGroups',
                            'id' => $organizational_unit->id,
                            'name' => $organizational_unit->name,
                        ];
                    }
                    break;
            }
        }
    }
}
