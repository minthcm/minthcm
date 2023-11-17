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

require_once('include/MVC/Controller/SugarController.php');
require_once('include/database/DBManagerFactory.php');

class PDFTemplatesController extends SugarController {

   var $parse_errors;
   var $db;
   
   function __construct() {
      $this->db = &DBManagerFactory::getInstance();
      parent::__construct();
   }

   function action_wizard() {
      $this->view = "wizard";
   }

   function action_templates() {
      global $moduleList;
      if ( isset($_POST['rmodule']) && (in_array($_POST['rmodule'], $moduleList) || $_POST['rmodule'] == 'Employees') ) {
         $rmodule = $_POST['rmodule'];
      }
      $query = "SELECT id AS value, name AS text, is_default FROM pdftemplates "
              . " WHERE pdftemplates.type='standard' AND pdftemplates.relatedmodule = '" . $rmodule . "' AND pdftemplates.deleted = 0 ORDER BY is_default DESC, name";

      $result = $this->db->query($query);
      $i = 0;
      while ( $row = $this->db->fetchByAssoc($result) ) {
         $rows[$i++] = $row;
      }
      $json = new JSON(JSON_LOOSE_TYPE);

      echo $json->encode($rows);
   }

   function action_saveTempTemplate() {
      if ( isset($_REQUEST['html_data']) ) {
         $guid = null;
         if ( isset($_REQUEST['save_to_id']) && $_REQUEST['save_to_id'] != '' ) {
            $guid = $_REQUEST['save_to_id'];
         } else {
            $guid = create_guid();
         }
         header('Content-type: text/html; charset=utf-8');
         mb_internal_encoding('UTF-8');
         //TODO delete file_put_contents('modules/PDFTemplates/templates/temp/copy', html_entity_decode(str_replace('&nbsp;','',$_REQUEST['html_data'])));
         sugar_file_put_contents('modules/PDFTemplates/templates/temp/template-' . $guid, html_entity_decode(str_replace('&nbsp;', '', $_REQUEST['html_data']),ENT_COMPAT | ENT_HTML401,'UTF-8'));

         $response['template_id'] = $guid;
         $json = new JSON(JSON_LOOSE_TYPE);
         echo $json->encode($response);
      }
   }

   function action_checkSyntax() {
      $response['error'] = 0;
      $response['message'] = '';

      if ( isset($_REQUEST['page_content']) && $_REQUEST['page_content'] != '' ) {
         $tpl = $_REQUEST['page_content'];
         $tpl = html_entity_decode($tpl);
         $tpl = str_replace('&nbsp;', ' ', $tpl);

         // For backwards compatibility, loops from relations declared as a comment
         // TODO: In future preg_replace won't handle /e mode (deprecated from php 5.5)
         // and we will have to use preg_replace_callback

         $tpl = preg_replace(array( '/<!--repeat[="_ A-Za-z0-9]+-->/e', '/<!--endrepeat-->/' ), array( 'preg_replace(array("/<!--repeat/", "/-->/"), array("<repeat", ">"), "$0")', '</repeat>' ), $tpl);

         require_once('modules/PDFGenerator/lib/simple_html_dom.php');//TODO olka fixed? lib

         $this->parse_errors = array();

         $variables = $this->parseSytnax($tpl);

         $this->checkVariablePosition($variables, $_REQUEST['for_module']);

         if ( count($this->parse_errors) > 0 ) {
            $response['error'] = 1;
            $response['message'] = translate("LBL_ERRORS_FIND", "PDFTemplates");
            $response['errors'] = $this->parse_errors;
         } else {
            $response['error'] = 0;
            $response['message'] = translate("LBL_ERRORS_NO_FIND", "PDFTemplates");
         }
      } else {
         $response['error'] = 1;
         $response['message'] = 'LBL_SYNTAX_NO_INPUT_DATA';
      }

      $json = new JSON(JSON_LOOSE_TYPE);
      echo $json->encode($response);
   }

   protected function checkVariablePosition($variables, $bean, $relation = '') {
      // List of standard relations
      $standard_relationships = array( 'assigned_user_link', 'created_by_link', 'modified_user_link' );

      // Actual level bean
      $mBean = BeanFactory::getBean($bean);

      /*
       * Loop traverses previously aquired structure
       * $key - relation path leading to variable
       * $element - certain variable from template 
       * */
      foreach ( $variables as $key => $element ) {

         if ( is_array($element) ) {
            $relationship_name = ($relation == '') ? $key : str_replace($relation . '__', '', $key);

            $this->checkVariablePosition($element, $this->getRelatedModule($mBean, $relationship_name), $key);
         } else {
            $this->checkVariablePositionElementNoArray($relation, $element, $bean, $standard_relationships);
         }
      }
   }

   function checkVariablePositionElementNoArray($relation, $element_src, $bean, $standard_relationships) {
      // Remove $ sign - for easier checking later
      $element = str_replace('$', '', $element_src);

      // Split the name of variable to rate its proper position in template and to compare it with real one
      $table = explode('__', $element);

      if ( count($table) > 1 ) {
         if ( $relation != '' ) {
            $this->checkVariablePositionForNoEmptyRelation($relation, $element, $table, $bean, $standard_relationships);
         } else {
            $this->checkVariablePositionForEmptyRelation($element, $table[0], $bean, $standard_relationships);
         }
      }
   }

   function checkVariablePositionForNoEmptyRelation($relation, $element, $table, $bean, $standard_relationships) {
      // get field name from array
      $field_name = array_pop($table);

      // join all other repeats
      $imploded = implode('__', $table);

      // get last element to chck if it's one-to-many relation
      $possible_one_relation = array_pop($table);
      // if it is - check path
      $secondary_impode = implode('__', $table);

      // Get info about probable relation
      $rel_info = $this->relationshipInfo($possible_one_relation);

      if ( $relation == $imploded ) {
         
      } elseif ( $secondary_impode == $relation && (in_array($possible_one_relation, $standard_relationships) || ($rel_info != null && $rel_info['relationship_type'] == 'one-to-many' && $rel_info['rhs_module'] == $bean)) ) {
         // our module is on many side
      } else {
         //problem
         //$this->parse_errors[] = array('FULL_ELEMENT_NAME' => $element, 'FIELD_NAME' => $field_name,'RELATIONSHIP_ROUTE' => $relation, 'POSSIBLE_O2M_RELATIONSHIP_NAME' => $possible_one_relation);
         $this->parse_errors[] = $element;
      }
   }

   function checkVariablePositionForEmptyRelation($element, $table, $bean, $standard_relationships) {
      // first nesting level, field is one-to-many on the many side
      // Get info about probable relation
      $rel_info = $this->relationshipInfo($table);

      // is one-to-many or standard one
      if ( ($rel_info['relationship_type'] == 'one-to-many' && $rel_info['rhs_module'] == $bean) || in_array($table, $standard_relationships) ) {
         // one-to-many
      } else {
         // Field is not in proper position
         $this->parse_errors[] = $element;
      }
   }

   protected function getRelatedModule($bean, $relationship) {
      $query = "SELECT * FROM relationships WHERE relationship_name = '" . $relationship . "' AND deleted = 0";
      $row = $this->bean->db->fetchByAssoc($this->bean->db->query($query));
      $module_name = $bean->module_name;
      if ( $module_name == null ) {//BEFORE 6.4
         $module_name = $bean->module_dir;
      }
      return ($row['lhs_module'] == $module_name) ? $row['rhs_module'] : $row['lhs_module'];
   }

   protected function relationshipInfo($name) {
      $query = "SELECT * FROM relationships WHERE relationship_name = '" . $name . "' AND deleted = 0";
      $row = $this->bean->db->fetchByAssoc($this->bean->db->query($query));

      return $row;
   }

   /*
    * Function analizes position of individual variables and returns an array 
    */

   function parseSytnax($tpl) {
      $html = str_get_html($tpl);
      $block = $html->find('repeat', 0);
      $var_array = array();

      while ( $block != null ) {
         if ( isset($block->relationship) && isset($block->type) && $block->type == "link" ) {
            $var_array[$block->relationship] = $this->parseSytnax($block->innertext);
            $block->outertext = '';
         }
         $html = str_get_html($html->save());
         $block = $html->find('repeat', 0);
      }

      $tpl_tmp = $html;
      $matches = array();
      preg_match_all('/\$([a-z_0-9A-Z]+)?/', $tpl_tmp, $matches);

      // Clear duplicates
      $variables = array_unique($matches[0]);
      if ( count($matches[0]) > 0 ) {
         foreach ( $matches[0] as $key => $variable ) {
            $tpl = str_replace($variable, $matches[1][$key], $tpl);
            $var_array[] = $variable;
         }
      }
      return $var_array;
   }

}

function getModules() {
   global $moduleList;
   $a = Array();
   foreach ( $moduleList as $item ) {
      $a[$item] = translate("LBL_MODULE_NAME", $item);
   }
   return $a;
}

?>
