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

/* * *******************************************************************************
 * This file is part of KReporter. KReporter is an enhancement developed
 * by aac services k.s.. All rights are (c) 2016 by aac services k.s.
 *
 * This Version of the KReporter is licensed software and may only be used in
 * alignment with the License Agreement received with this Software.
 * This Software is copyrighted and may not be further distributed without
 * witten consent of aac services k.s.
 *
 * You can contact us at info@kreporter.org
 * ****************************************************************************** */

/**
 * ref #50666 KREPORTER: Upgrade dla Suite 7.10
 *    zmiana nazwy pliku wynikowego (kreporter->report)
 */
if ( !defined('sugarEntry') || !sugarEntry )
   die('Not A Valid Entry Point');

class pluginkcsvexportcontroller {

   public function action_export($requestParams) {
      global $sugar_config;

      // 2014-02-24 add config option for memory limit see if we should set the runtime and memory limit
      if ( !empty($sugar_config['KReports']['csvmemorylimit']) )
         ini_set('memory_limit', $sugar_config['KReports']['csvmemorylimit']);
      if ( !empty($sugar_config['KReports']['csvmaxruntime']) )
         ini_set('max_execution_time', $sugar_config['KReports']['csvmaxruntime']);


      require_once('modules/KReports/KReport.php');
      $thisReport = BeanFactory::getBean('KReports', $requestParams['record']);


      // check if we have set dynamic Options
      if ( isset($requestParams['dynamicoptions']) )
         $thisReport->whereOverride = json_decode(html_entity_decode($requestParams['dynamicoptions']), true);


      $dynamicolsOverride = '';
      if ( isset($requestParams['dynamicols']) && $requestParams['dynamicols'] != '' )
         $dynamicolsOverride = html_entity_decode($requestParams['dynamicols'], ENT_QUOTES, 'UTF-8');

      // force Download
      // Mint start #50666 KREPORTER: Upgrade dla Suite 7.10
      //$filename = "kreporter.csv";
      $filename = "report.csv";
      // Mint end #50666 KREPORTER: Upgrade dla Suite 7.10
      header('Content-type: application/ms-excel');
      header('Content-Disposition: attachment; filename=' . $filename);

      $output = $thisReport->createCSV($dynamicolsOverride);

      if ( $requestParams['rawResult'] )
         return $output;
      else
         echo $output;
   }

}
