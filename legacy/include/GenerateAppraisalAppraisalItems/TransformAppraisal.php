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

SugarAutoLoader::requireWithCustom('include/GenerateAppraisalAppraisalItems/SetAppraisalRelatedModulesRelations.php');
SugarAutoLoader::requireWithCustom('include/GenerateAppraisalAppraisalItems/CreateNewAppraisalItemRecord.php');
SugarAutoLoader::requireWithCustom('include/GenerateAppraisalAppraisalItems/CreateNewAppraisalRecord.php');
SugarAutoLoader::requireWithCustom('include/Notifications/Notification.php');

class TransformAppraisal extends SugarController {

   public $transformed_module_name = '';
   public $transformed_record_id = '';
   public $appraisal_name = '';

   public function __construct($data) {
      $this->transformed_module_name = (isset($data['module'])) ? $data['module'] : null;
      $this->transformed_record_id = (isset($data['record_id'])) ? $data['record_id'] : null;
      $this->appraisal_name = (isset($data['appraisal_name'])) ? $data['appraisal_name'] : null;
   }

   public function transformRecordToAppraisal() {
      $create_apprisal = new CreateNewAppraisalRecord($this->transformed_module_name, $this->appraisal_name);
      $create_apprisal_item = new CreateNewAppraisalItemRecord($this->transformed_module_name);

      $transformed_record_bean = $this->getTransformedBean();
      $appraisal_bean = $create_apprisal->newAppraisal($transformed_record_bean);
      $create_apprisal_item->newAppraisalItem($transformed_record_bean, $appraisal_bean);
   }

   public function runCronJob() {
      $this->transformRecordToAppraisal();
      $this->setNotification();
   }

   protected function getTransformedBean() {
      return BeanFactory::getBean($this->transformed_module_name, $this->transformed_record_id);
   }

   protected function setNotification() {
      global $app_strings, $current_user;
      $notification = new Notification();
      $notification->setAssignedUserId($current_user->id);
      $notification->setDescription(translate($app_strings['LBL_GENERATE_APPRAISAL_APPRAISAL_ITEMS_NOTIFICATION_DESCRIPTION']));
      $notification->disableUniqueValidation();
      $notification->saveAsAlert();
   }

}
