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

class KTemplatesController extends SugarController {

   var $parse_errors;

   public function preProcess() {
      if ( isset($_REQUEST['action']) && $_REQUEST['action'] == 'EditView' && !isset($_POST['relatedmodule']) && !isset($_REQUEST['record']) ) {
         $this->set_redirect('index.php?module=KTemplates&action=wizard&return_module=KTemplates&return_action=DetailView');
         $this->redirect();
      }
   }

   function action_wizard() {
      $this->view = "wizard";
   }

   function action_templates() {
      global $moduleList;
      $db = &DBManagerFactory::getInstance();
      if ( isset($_POST['rmodule']) && in_array($_POST['rmodule'], $moduleList) ) {
         $rmodule = $_POST['rmodule'];
      }
      $query = "SELECT id AS value, name AS text, is_default
				FROM ktemplates
				WHERE ktemplates.relatedmodule = '" . $rmodule . "'
				AND ktemplates.deleted = 0
				ORDER BY is_default DESC, name";

      $result = $db->query($query);
      $i = 0;
      while ( $row = $db->fetchByAssoc($result) ) {
         $rows[$i++] = $row;
      }

      $json = new JSON(JSON_LOOSE_TYPE);

      echo $json->encode($rows);
   }

   function action_save_temp_template() {
      if ( isset($_REQUEST['html_data']) ) {
         $guid = null;

         if ( isset($_REQUEST['save_to_id']) && $_REQUEST['save_to_id'] != '' ) {
            $guid = $_REQUEST['save_to_id'];
         } else {
            $guid = create_guid();
         }
         //TODO delete file_put_contents('modules/KTemplates/templates/temp/kopia', html_entity_decode(str_replace('&nbsp;','',$_REQUEST['html_data'])));
         sugar_file_put_contents('modules/KTemplates/templates/temp/template-' . $guid, html_entity_decode(str_replace('&nbsp;', '', $_REQUEST['html_data'])));

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

         // W celu zachowania kompatybilności nadal uznajemy pętle relacji zadeklarowane jako komentarz
         // TODO: W przyszłości preg_replace nie będzie obsługiwał przełącznika /e (od php 5.5 jest deprecated)
         // i będzie trzeba użyć preg_replace_callback

         $tpl = preg_replace(array( '/<!--repeat[="_ A-Za-z0-9]+-->/e', '/<!--endrepeat-->/' ), array( 'preg_replace(array("/<!--repeat/", "/-->/"), array("<repeat", ">"), "$0")', '</repeat>' ), $tpl);
         include_once('include/tcpdf/simple_html_dom.php');

         // Wykryte błędy w ułożeniu zmiennych 
         $this->parse_errors = array();

         // Funkcja parseSyntax zwraca informacje o strukturze i położeniu zmiennych
         $variables = $this->parseSytnax($tpl);

         // Funkcjan checkVariablePosition analizuje położenie zmiennych i zapisuje ewentualne błędy do parse_errors
         $this->checkVariablePosition($variables, $_REQUEST['for_module']);
         if ( count($this->parse_errors) > 0 ) {
            $response['error'] = 1;
            $response['message'] = translate("LBL_ERRORS_FOUND", 'KTemplates');
            $response['errors'] = $this->parse_errors;
         } else {
            $response['error'] = 0;
            $response['message'] = translate("LBL_NO_ERRORS_FOUND", 'KTemplates');
         }
      } else {
         $response['error'] = 1;
         $response['message'] = 'LBL_SYNTAX_NO_INPUT_DATA';
      }

      $json = new JSON(JSON_LOOSE_TYPE);

      echo $json->encode($response);
   }

   protected function checkVariablePosition($variables, $bean, $relation = '') {
      // Lista standardowych relacji
      $standard_relationships = array( 'assigned_user_link', 'created_by_link', 'modified_user_link' );

      // Bean aktualnego poziomu
      $mBean = BeanFactory::getBean($bean);

      /*
       * Pętla przechodzi po wyciągnietej wczesniej strukturze
       * $key - scieżka relacji prowadząca do zmiennej
       * $element - konkretna zmienna z szablonu 
       * */
      foreach ( $variables as $key => $element ) {

         if ( is_array($element) ) {
            // Element jest tablica - odpowiada znacznikowi REPEAT dla danej relacji
            // Nazwa relacji łącząca ten moduł z podrzednym  - nie cała ścieżka relacji
            $relationship_name = ($relation == '') ? $key : str_replace($relation . '__', '', $key);

            // Przekazujemy do dalszego sprawdzenia sprawdzając jaki moduł jest po drugiej stronie 
            // i przekazując ścieżke relacji prowadzącą do tego modułu
            $this->checkVariablePosition($element, $this->get_related_module($mBean, $relationship_name), $key);
         } else {
            // Wycięcie znaku dolara - łatwiej porównywac później
            $element = str_replace('$', '', $element);

            // Dzielimy nazwę zmiennej aby ocenic jej prawidłową pozycję w szablonie i porównać z faktyczną
            $table = explode('__', $element);

            if ( count($table) > 1 ) {
               // Nazwa  pola zawiera co najmniej jedno __ sprawdzamy czy jesteśmy na pierwszym poziomie zagłębienia	
               if ( $relation != '' ) {
                  // Wyciynamy nazwę pola z tablicy
                  $field_name = array_pop($table);

                  // łaczymy wszystkie pozostałe relacje repeat aby porównać czy znajduje się w odpowiednim zagłębieniu
                  $imploded = implode('__', $table);

                  // Wycinamy dodatkowo ostatni element aby sprawdzić czy pole nie jest elementem relacji one to many 
                  $possible_one_relation = array_pop($table);
                  // Jeśli to jest relacja one 2 many to musimy sprawdzić czy prowadząca do niej ścieżka jest nadal prawidłowa
                  $secondary_impode = implode('__', $table);

                  // Pobieramy informacje o możliwej relacji
                  $rel_info = $this->relationship_info($possible_one_relation);

                  if ( $relation != $imploded ) {
                     $this->parse_errors[] = $element;
                  } elseif ( $secondary_impode != $relation ) {
                     $this->parse_errors[] = $element;
                  } elseif ( !in_array($possible_one_relation, $standard_relationships) ) {
                     if ( $rel_info == null ) {
                        $this->parse_errors[] = $element;
                     } elseif ( $rel_info['relationship_type'] != 'one-to-many' ) {
                        $this->parse_errors[] = $element;
                     } elseif ( $rel_info['rhs_module'] != $bean ) {
                        $this->parse_errors[] = $element;
                     }
                  }
               } else {
                  // Jesteśmy na pierwszym poziomie zagłębienia, pole musi być relacją jeden do wielu gdzie sprawdzany moduł jest po stronie n
                  // Wyciągamy informacje o relacji 
                  $rel_info = $this->relationship_info($table[0]);
                  // Sprawdzamy czy O2M lub jedna ze standardowych
                  if ( !in_array($table[0], $standard_relationships) ) {
                     if ( $rel_info['relationship_type'] != 'one-to-many' ) {
                        // Pole nie znajduje się w prawidłowym miejscu
                        $this->parse_errors[] = $element;
                     } elseif ( $rel_info['rhs_module'] != $bean ) {
                        // Pole nie znajduje się w prawidłowym miejscu
                        $this->parse_errors[] = $element;
                     }
                  }
               }
            }
         }
      }
   }

   protected function get_related_module($bean, $relationship) {
      $query = "SELECT * FROM relationships WHERE relationship_name = '" . $relationship . "' AND deleted = 0";
      $row = $this->bean->db->fetchByAssoc($this->bean->db->query($query));
      $module_name = $bean->module_name;
      if ( $module_name == null ) {//BEFORE 6.4
         $module_name = $bean->module_dir;
      }
      return ($row['lhs_module'] == $module_name) ? $row['rhs_module'] : $row['lhs_module'];
   }

   protected function relationship_info($name) {
      $query = "SELECT * FROM relationships WHERE relationship_name = '" . $name . "' AND deleted = 0";
      $row = $this->bean->db->fetchByAssoc($this->bean->db->query($query));

      return $row;
   }

   /*
    * Funkcja analizuje położenie poszczególnych zmiennych i zwraca tablicę 
    */

   function parseSytnax($tpl) {
      $html = $tpl;

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

      $tpl = $html;
      preg_match_all('/\$([a-z_0-9A-Z]+)?/', $tpl, $matches);

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

   function action_getKReportsRecords() {
      require_once ('include/JSON.php');
      $json = new JSON ( );
      $kreport = new KReport();
      $kreport_list = $kreport->get_full_list();
      foreach ( $kreport_list as $kreport_bean ) {
         $return_array[$kreport_bean->id] = $kreport_bean->name;
      }
      echo $json->encode($return_array);
   }

   function action_getReportPDFTemplates() {
      if ( !isset($_REQUEST['kreport_id']) ) {
         echo json_encode(false);
      } else {
         global $mod_strings;
         $return_data = array();
         array_push($return_data, array(
            'id' => 'Default',
            'name' => $mod_strings['LBL_DEFAULT_PDF_TEMPLATE'],
         ));
         if ( isset($_REQUEST['plugin']) && $_REQUEST['plugin'] == 'ktreeview' ) {
            $dir = new DirectoryIterator('modules/KReports/Plugins/Presentation/ktreeview/TreeViewPDFPrinter/tpls');
            foreach ( $dir as $fileinfo ) {
               if ( $fileinfo->isDir() && !$fileinfo->isDot() && $fileinfo->getFilename() != "lightblue1" ) {
                  array_push($return_data, array(
                     'id' => $fileinfo->getFilename(),
                     'name' => $fileinfo->getFilename(),
                  ));
               }
            }
         } else {
            global $db;
            $sql = "SELECT id,name FROM `ktemplates` WHERE `relatedmodule`='{$_REQUEST['kreport_id']}' AND `deleted`=0;";
            $result = $db->query($sql);
            while ( $row = $db->fetchByAssoc($result) ) {
               array_push($return_data, $row);
            }
         }
         echo json_encode($return_data);
      }
   }

}

function getModules() {
   global $moduleList;

   $a = Array();
   foreach ( $moduleList as $item )
      $a[$item] = translate("LBL_MODULE_NAME", $item);

   return $a;
}

?>