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

SugarAutoLoader::requireWithCustom('CompetencyRatingCreator.php');

class EmployeeCreator
{

    const USERS_MODULE_NAME = 'Users';
    const EMPLOYEES_MODULE_NAME = 'Employees';
    const EMPLOYEE_ACTIVE_STATUS = 'Active';
    const CANDIDATES_USERS_RELATION = 'candidates';
    const COMPETENCIES_MODULE_NAME = 'Competencies';

    protected $position_bean = '';
    protected $candidate_bean = '';
    protected $latest_appraisal_bean = '';
    protected $latest_appraisal_items_beans = '';
    protected $converted_candidature_login = '';

    public function __construct($modules_beans)
    {
        $this->position_bean = $modules_beans['Positions'];
        $this->candidate_bean = $modules_beans['Candidates'];
        $this->latest_appraisal_items_beans = $modules_beans['AppraisalItems'];
        $this->latest_appraisal_bean = $modules_beans['Appraisals'];
        $this->converted_candidature_login = $modules_beans['login'];
    }

    public function createOrUpdate(): array
    {
        $employee_bean = null;
        $employee_candidate_relation = $this->isCandidateAlreadyRelatedWithEmployee();

        if (!$employee_candidate_relation) {
            $employee_bean = $this->newEmployeeRecord();
        } else {
            $employee_bean = $this->updateExistingEmployeeRecord();
        }

        return array(
            'employee_bean' => $employee_bean,
            'candidate_employee_relation' => $employee_candidate_relation,
        );
    }

    protected function newEmployeeRecord(): Employee
    {
        $employee_bean = BeanFactory::newBean(self::EMPLOYEES_MODULE_NAME);

        $employee_bean->first_name = $this->candidate_bean->first_name;
        $employee_bean->last_name = $this->candidate_bean->last_name;
        $employee_bean->position_id = $this->position_bean->id;
        if (strlen($this->converted_candidature_login) > 0) {
            $employee_bean->user_name = $this->converted_candidature_login;
            $employee_bean->status = 'Inactive';
        } else {
            $employee_bean->status = 'Inactive';
        }
        $employee_bean = $this->assignEmployeeContactFields($employee_bean);

        $employee_bean->employee_status = self::EMPLOYEE_ACTIVE_STATUS;
        $employee_bean->skip_vt_validation = true;
        $employee_bean->save();
        $this->addCandidateRelationToUser($employee_bean);
        return $employee_bean;
    }

    protected function addCandidateRelationToUser(Employee $employee_bean)
    {
        $relation_name = self::CANDIDATES_USERS_RELATION;

        $user_bean = BeanFactory::getBean(self::USERS_MODULE_NAME, $employee_bean->id);
        $user_bean->load_relationship($relation_name);
        $user_bean->$relation_name->add($this->candidate_bean);
    }

    protected function updateExistingEmployeeRecord(): Employee
    {
        global $db;
        $sql = "SELECT employee_id FROM candidates_employees WHERE parent_id ='{$this->candidate_bean->id}' AND deleted=0 LIMIT 1;";
        $result_employee_id = $db->getOne($sql);
        $related_employee_bean = BeanFactory::getBean(self::EMPLOYEES_MODULE_NAME, $result_employee_id);

        $this->updateEmployeeStatus($related_employee_bean);
        $this->updateEmployeeCompetencies($related_employee_bean);
        $related_employee_bean = $this->assignEmployeeContactFields($related_employee_bean);
        if (strlen($this->converted_candidature_login) > 0) {
            $related_employee_bean->user_name = $this->converted_candidature_login;
            $related_employee_bean->status = 'Active';
        } else {
            $related_employee_bean->status = 'Inactive';
        }
        $related_employee_bean->position_id = $this->position_bean->id;
        $employee_bean->skip_vt_validation = true;
        $related_employee_bean->save();

        return $related_employee_bean;
    }

    protected function updateEmployeeStatus(Employee $related_employee)
    {
        $related_employee->employee_status = self::EMPLOYEE_ACTIVE_STATUS;
        $related_employee->status = $related_employee->employee_status;
        $related_employee->save();
    }

    protected function updateEmployeeCompetencies($related_employee_bean)
    {
        if (!is_null($this->latest_appraisal_bean) && !empty($this->latest_appraisal_items_beans)) {

            foreach ($this->latest_appraisal_items_beans as $one_appraisal_item_bean) {
                if (!is_null($one_appraisal_item_bean->id) && self::COMPETENCIES_MODULE_NAME == $one_appraisal_item_bean->parent_type) {
                    $create_competency_rating = new CompetencyRatingCreator($one_appraisal_item_bean, $related_employee_bean);
                    $create_competency_rating->createOrUpdateRecords();
                }
            }
        }
    }

    protected function isCandidateAlreadyRelatedWithEmployee(): bool
    {
        global $db;
        $sql = "SELECT employee_id FROM candidates_employees WHERE parent_id ='{$this->candidate_bean->id}' AND deleted=0 LIMIT 1;";
        $result_employee_id = $db->getOne($sql);
        $related_employee_bean = BeanFactory::getBean(self::EMPLOYEES_MODULE_NAME, $result_employee_id);
        return isset($related_employee_bean->id);
    }

    private function assignEmployeeContactFields(Employee $employee_bean): Employee
    {
        $employee_bean->address_street = $this->candidate_bean->primary_address_street;
        $employee_bean->address_city = $this->candidate_bean->primary_address_city;
        $employee_bean->address_state = $this->candidate_bean->primary_address_state;
        $employee_bean->address_country = $this->candidate_bean->primary_address_country;
        $employee_bean->address_postalcode = $this->candidate_bean->primary_address_postalcode;

        $employee_bean->email1 = $this->candidate_bean->email1;
        $employee_bean->phone_mobile = $this->candidate_bean->phone_mobile;
        $employee_bean->save();

        return $employee_bean;
    }

}
