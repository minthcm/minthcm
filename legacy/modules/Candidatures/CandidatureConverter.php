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

SugarAutoLoader::requireWithCustom('modules/Candidatures/CompetencyRatingCreator.php');
SugarAutoLoader::requireWithCustom('modules/Candidatures/EmployeeCreator.php');
SugarAutoLoader::requireWithCustom('modules/Candidatures/AppraisalsLoader.php');
SugarAutoLoader::requireWithCustom('modules/Candidatures/CertificatesUpdater.php');

class CandidatureConverter
{

    const CANDIDATES_MODULE_NAME = 'Candidates';
    const POSITIONS_MODULE_NAME = 'Positions';
    const RECRUITEMENT_MODULE_NAME = 'Recruitments';
    const CANDIDATURE_MODLE_NAME = 'Candidatures';
    const APPRAISAL_ITEMS_MODULE_NAME = 'AppraisalItems';
    const APPRAISAL_MODULE_NAME = 'Appraisals';
    const COMPETENCIES_MODULE_NAME = 'Competencies';

    protected $converted_candidature_record_id = '';
    protected $converted_candidature_login = '';
    protected $converted_candidature_bean = '';
    protected $latest_appraisal_bean = '';
    protected $latest_appraisal_items_beans = array();

    public function __construct($record_id, $login)
    {
        $this->converted_candidature_record_id = $record_id;
        $this->converted_candidature_login = $login;
    }

    public function convert()
    {
        $beans_for_employee = $this->setModulesBeans();
        if (empty($beans_for_employee)) {
            return false;
        }

        $create_employee = new EmployeeCreator($beans_for_employee);
        $employee = $create_employee->createOrUpdate();

        if (!is_null($this->latest_appraisal_bean) && !empty($this->latest_appraisal_items_beans) && !$employee['candidate_employee_relation']) {

            foreach ($this->latest_appraisal_items_beans as $one_appraisal_item_bean) {
                if (self::COMPETENCIES_MODULE_NAME == $one_appraisal_item_bean->parent_type) {

                    $create_competency_rating = new CompetencyRatingCreator($one_appraisal_item_bean, $employee['employee_bean']);
                    $create_competency_rating->createOrUpdateRecords();
                }
            }
        }

        $certificate_updater = new CertificatesUpdater($beans_for_employee[static::CANDIDATES_MODULE_NAME], $employee['employee_bean']);
        $certificate_updater->updateCertificate();

        return $employee['employee_bean']->id;
    }

    protected function setModulesBeans()
    {
        $this->converted_candidature_bean = $this->getConvertedCandidatureBean();
        if ($this->converted_candidature_bean->parent_type !== 'Candidates') {
            return null;
        }

        $appraisals = new AppraisalsLoader();
        $this->latest_appraisal_bean = $appraisals->getLatestAppraisalBean($this->converted_candidature_bean);
        $this->latest_appraisal_items_beans = $appraisals->getLatestAppraisalItemsBeans($this->latest_appraisal_bean);

        $candidate_bean = $this->getCandidateBean();
        $position_bean = $this->getPositionBean();

        return array(
            self::POSITIONS_MODULE_NAME => $position_bean,
            self::CANDIDATES_MODULE_NAME => $candidate_bean,
            self::APPRAISAL_MODULE_NAME => $this->latest_appraisal_bean,
            self::APPRAISAL_ITEMS_MODULE_NAME => $this->latest_appraisal_items_beans,
            'login' => $this->converted_candidature_login,
        );
    }

    protected function getConvertedCandidatureBean(): Candidatures
    {
        return BeanFactory::getBean(self::CANDIDATURE_MODLE_NAME, $this->converted_candidature_record_id);
    }

    protected function getPositionBean(): Positions
    {
        global $db;

        $recruitement_id = (empty($this->converted_candidature_bean->recruitment_end_id) ? $this->converted_candidature_bean->recruitment_id : $this->converted_candidature_bean->recruitment_end_id);
        $sql = "SELECT position_id FROM recruitments WHERE id='{$recruitement_id}' AND deleted=0";
        $result_position_id = $db->getOne($sql);
        return BeanFactory::getBean(self::POSITIONS_MODULE_NAME, $result_position_id);
    }

    protected function getCandidateBean(): Candidates
    {
        global $db;
        $sql = "SELECT parent_id FROM candidatures WHERE id='{$this->converted_candidature_bean->id}'";
        $result_candidate_id = $db->getOne($sql);

        return BeanFactory::getBean(self::CANDIDATES_MODULE_NAME, $result_candidate_id);
    }

}
