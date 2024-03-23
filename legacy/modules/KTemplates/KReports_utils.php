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

class KReports_utils {

   function get_variable($kreport_id) {
      require_once('modules/KReports/KReport.php');
      $thisReport = new KReport();
      if ( $kreport_id != null ) {
         $thisReport->retrieve($kreport_id);
      } else {
         return false;
         $GLOBALS['log']->debug = "kreport id is empty";
      }

      global $current_user;

      $results = $thisReport->getSelectionResults(array( 'toCSV' => true ), isset($_REQUEST ['snapshotid']) ? $_REQUEST ['snapshotid'] : '0');

      $arrayList = json_decode_kinamu(html_entity_decode($thisReport->listfields, ENT_QUOTES, 'UTF-8'));
      $fieldArray = [];
      $fieldIdArray = array();
      foreach ( $arrayList as $thisList ) {
         if ( $thisList ['display'] == 'yes' ) {
            $displaypath = explode("::", $thisList['path']);
            $patch_array = array();
            $name = '';
            for ( $i = 1; $i < count($displaypath); $i++ ) {
               if ( "link" == substr($displaypath[$i], 0, strpos($displaypath[$i], ":")) ) {
                  $patch_array[] = substr($displaypath[$i], strRpos($displaypath[$i], ":") + 1);
               }
            }
            foreach ( $patch_array as $patch ) {
               $name .= $patch . '__';
            }
            $name .= substr($thisList['path'], strpos($thisList['path'], "field:") + 6);
            $fieldArray [] = array(
               'label' => $thisList ['name'],
               'width' => (isset($thisList ['width']) && $thisList ['width'] != '' && $thisList ['width'] != '0') ? $thisList ['width'] : '100',
               'display' => $thisList ['display'],
               'name' => $name
            );
            $fieldIdArray [] = $thisList ['fieldid'];
         }
      }
      return $fieldArray;
   }

}
