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
if (!defined('sugarEntry')) {
    define('sugarEntry', true);
}

SugarAutoLoader::requireWithCustom('include/Notifications/Notification.php');

class GenerateOnboardingOffboarding
{
    protected $module_name;
    protected $template_id;
    protected $employee_id;
    protected $date_start;
    protected $record_id;
    protected $process;
    protected $user_scheduled_onboarding;

    public function __construct($data)
    {
        $this->module_name = (isset($data['module_name'])) ? $data['module_name']
        : null;
        $this->template_id = (isset($data['template_id'])) ? $data['template_id']
        : null;
        $this->employee_id = (isset($data['employee_id'])) ? $data['employee_id']
        : null;
        $this->date_start = (isset($data['date_start'])) ? $data['date_start'] : null;
        $this->record_id = (isset($data['record_id'])) ? $data['record_id'] : null;
        if (isset($data['user_scheduled_onboarding_id'])) {
            $this->user_scheduled_onboarding = BeanFactory::getBean('Users',
                $data['user_scheduled_onboarding_id']);
        } else {
            $this->user_scheduled_onboarding = BeanFactory::newBean('Users');
        }
    }

    public function generate()
    {
        $template = BeanFactory::getBean($this->module_name, $this->template_id);
        if ('OnboardingTemplates' == $this->module_name) {
            $this->createProcess('Onboardings', 'onboardingtemplate_id',
                $template->assigned_user_id);
        } else {
            $this->createProcess('Offboardings', 'offboardingtemplate_id',
                $template->assigned_user_id);
        }
        if ($template->load_relationship('elements')) {
            foreach ($template->elements->getBeans() as $element) {
                $this->createRecordOfElementType($element);
            }
        }
        $this->addNotification();
    }

    protected function createProcess($process_name, $relate_id_field_name,
        $assigned_user_id) {
        $bean = BeanFactory::newBean($process_name);
        $bean->date_start = $this->date_start;
        $bean->employee_id = $this->employee_id;
        $bean->$relate_id_field_name = $this->template_id;
        $bean->assigned_user_id = $assigned_user_id;
        $bean->save();
        $this->addSecurityGroupToRecord($bean,
            $this->user_scheduled_onboarding->getUserPrivateGroup());
        $this->addSecurityGroupToEmployee($bean);
        $this->process = $bean;
    }

    protected function addSecurityGroupToRecord($bean, $sg_id)
    {
        $sg_relation_name = 'SecurityGroups';
        if ($bean->load_relationship($sg_relation_name)) {
            return $bean->$sg_relation_name->add($sg_id);
        } else {
            $GLOBALS['log']->fatal("Unable to load relationship {$sg_relation_name} for {$bean->object_name}");
            return false;
        }
    }

    protected function addSecurityGroupToEmployee($bean)
    {
        if (!empty($bean->employee_id)) {
            /** @var Employee|User $employee */
            $employee = BeanFactory::getBean('Users', $bean->employee_id);
            if (
                !empty($employee)
                && $employee->id === $bean->employee_id
                && !empty($user_private_group_id = $employee->getUserPrivateGroup())
            ) {
                $this->addSecurityGroupToRecord($bean, $user_private_group_id);
            }
        }
    }

    protected function createRecordOfElementType($element)
    {
        switch ($element->type) {
            case 'task':
                $bean = $this->createTask($element);
                break;
            case 'training':
                $bean = $this->createTraining($element);
                $meeting_bean = $this->createMeeting($element, $bean);
                break;
            case 'exit_interview':
                $bean = $this->createExitInterview($element);
                break;
            default:
                return false;
        }
        return $this->addSecurityGroupToRecord($bean,
            $this->user_scheduled_onboarding->getUserPrivateGroup());
    }

    protected function createTask($element)
    {
        global $timedate;
        $bean = BeanFactory::newBean('Tasks');
        $bean->name = $element->name;
        $bean->description = $element->description;
        $bean->assigned_user_id = $this->getAssignedUserIDBasedOnKindOfElement($element);
        $date_start_object = new DateTime($this->date_start);
        $days_from_start = (int) $element->days_from_start;
        $date_start_object->modify("+{$days_from_start} days");
        $bean->date_start = $date_start_object->format($timedate->get_db_date_time_format());
        $duration_hours = (int) $element->task_duration_hours;
        $duration_minutes = (int) $element->task_duration_minutes;
        $bean->date_due = $date_start_object->modify("+{$duration_hours} hours {$duration_minutes} minutes")->format($timedate->get_db_date_time_format());
        $bean->parent_type = $this->process->module_name;
        $bean->parent_id = $this->process->id;
        $bean->save();
        $this->addSecurityGroupToEmployee($bean);
        $this->addSecurityGroupToRecord($bean, $this->user_scheduled_onboarding->getUserPrivateGroup());
        return $bean;
    }

    protected function createTraining($element)
    {
        global $timedate;
        $bean = BeanFactory::newBean('Trainings');
        $bean->name = $element->name;
        $bean->description = $element->description;
        $bean->assigned_user_id = $this->getAssignedUserIDBasedOnKindOfElement($element);
        $bean->assigned_user_name = $element->user_name;
        $date_start_object = new DateTime($this->date_start);
        $days_from_start = (int) $element->days_from_start;
        $date_start_object->modify("+{$days_from_start} days");
        $bean->date_start = $date_start_object->format($timedate->get_db_date_time_format());
        $duration_hours = (int) $element->task_duration_hours;
        $duration_minutes = (int) $element->task_duration_minutes;
        $bean->date_end = $date_start_object->modify("+{$duration_hours} hours {$duration_minutes} minutes")->format($timedate->get_db_date_time_format());
        $bean->training_type = "internal";
        $bean->parent_type = $this->process->module_name;
        $bean->parent_id = $this->process->id;
        $bean->element_id = $element->id;
        $bean->save();
        $this->addSecurityGroupToRecord($bean,
            $this->user_scheduled_onboarding->getUserPrivateGroup());
        return $bean;
    }

    protected function createMeeting($element, $training_bean)
    {
        global $timedate;
        $meeting_bean = BeanFactory::newBean('Meetings');
        $meeting_bean->name = $training_bean->name;
        $meeting_bean->type = 'training';
        $meeting_bean->assigned_user_id = $this->getAssignedUserIDBasedOnKindOfElement($element);
        $meeting_bean->assigned_user_name = $element->user_name;
        $date_start_object = new DateTime($this->date_start);
        $days_from_start = (int) $element->days_from_start;
        $date_start_object->modify("+{$days_from_start} days");
        $meeting_bean->date_start = $date_start_object->format($timedate->get_db_date_time_format());
        $duration_hours = (int) $element->task_duration_hours;
        $duration_minutes = (int) $element->task_duration_minutes;
        $meeting_bean->duration_hours = $duration_hours;
        $meeting_bean->duration_minutes = $duration_minutes;
        $meeting_bean->date_end = $date_start_object->modify("+{$duration_hours} hours {$duration_minutes} minutes")->format($timedate->get_db_date_time_format());
        $meeting_bean->parent_type = $this->process->module_name;
        $meeting_bean->parent_id = $this->process->id;
        $meeting_bean->save();
        if (!empty($meeting_bean->id) && !empty($training_bean->id) && $meeting_bean->load_relationship('trainings') && $meeting_bean->load_relationship('users')) {
            $meeting_bean->trainings->add($training_bean->id);
            $meeting_bean->users->add($this->process->employee_id);
            $meeting_bean->users->add($element->user_id);
        }
        $this->addSecurityGroupToRecord($meeting_bean,
            $this->user_scheduled_onboarding->getUserPrivateGroup());
    }

    protected function createExitInterview($element)
    {
        global $timedate;
        $bean = BeanFactory::newBean('ExitInterviews');
        $bean->name = $element->name;
        $bean->assigned_user_id = $this->getAssignedUserIDBasedOnKindOfElement($element);
        $bean->employee_id = $this->employee_id;
        $date_start_object = new DateTime($this->date_start);
        $days_from_start = (int) $element->days_from_start;
        $date_start_object->modify("+{$days_from_start} days");
        $bean->date_start = $date_start_object->format($timedate->get_db_date_time_format());
        $duration_hours = (int) $element->task_duration_hours;
        $duration_minutes = (int) $element->task_duration_minutes;
        $bean->date_end = $date_start_object->modify("+{$duration_hours} hours {$duration_minutes} minutes")->format($timedate->get_db_date_time_format());
        if ('Offboardings' == $this->process->module_name) {
            $bean->offboarding_id = $this->process->id;
        }
        $bean->save();
        return $bean;
    }

    protected function addNotification()
    {
        global $app_strings, $current_user;
        $notification = new Notification();
        $notification->setAssignedUserId($current_user->id);
        $notification->setDescription(translate($app_strings['LBL_GENERATE_ONBOARDING_OFFBOARDING']));
        $notification->disableUniqueValidation();
        $notification->saveAsAlert();
    }

    protected function getAssignedUserIDBasedOnKindOfElement($element)
    {
        $assigned_user_id = "";
        switch ($element->kind_of_element) {
            case 'self';
                $assigned_user_id = $this->employee_id;
                break;
            case 'employee_manager';
                $user = BeanFactory::getBean('Users', $this->employee_id);
                if ($user && !empty($user->reports_to_id)) {
                    $assigned_user_id = $user->reports_to_id;
                }
                break;
            case 'organizational_unit_manager';
                $organizational_unit = BeanFactory::getBean('SecurityGroups', $element->securitygroup_unit_id);
                if ($organizational_unit && !empty($organizational_unit->current_manager_id)) {
                    $assigned_user_id = $organizational_unit->current_manager_id;
                }
                break;
            case 'specific_user';
                $assigned_user_id = $element->user_id;
                break;
        }
        return $assigned_user_id;
    }
}
