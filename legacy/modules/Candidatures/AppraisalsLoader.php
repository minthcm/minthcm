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

class AppraisalsLoader {

   const APPRAISAL_ITEMS_MODULE_NAME = 'AppraisalItems';
   const APPRAISAL_MODULE_NAME = 'Appraisals';

   public function getLatestAppraisalBean($candidature_bean) {
      global $db;

      $sql = "SELECT id FROM appraisals WHERE candidature_id='{$candidature_bean->id}' AND deleted=0 ORDER BY date_entered DESC LIMIT 1";
      $result_bean_id = $db->getOne($sql);

      $appraisals_related_to_converted_candidature = BeanFactory::getBean(self::APPRAISAL_MODULE_NAME, $result_bean_id);

      if ( empty($appraisals_related_to_converted_candidature) ) {
         return NULL;
      }

      return $appraisals_related_to_converted_candidature;
   }

   public function getLatestAppraisalItemsBeans($latest_appraisal_bean) {
      global $db;
      $fetched_appraisal_items_beans = array();

      if ( !is_null($latest_appraisal_bean) ) {
         $sql = "SELECT id FROM appraisalitems WHERE appraisal_id='{$latest_appraisal_bean->id}' AND deleted=0";
         $result = $db->query($sql);

         while ( $row = $db->fetchByAssoc($result) ) {
            array_push($fetched_appraisal_items_beans, BeanFactory::getBean(self::APPRAISAL_ITEMS_MODULE_NAME, $row['id']));
         }
      }

      return $fetched_appraisal_items_beans;
   }

}
