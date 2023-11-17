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

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class ButtonParser {

   /**
    * Repairs detailviews in modules related to existing templates by adding or removing pdf generating button
    */
   public function rebuildAll() {
      global $app_list_strings;
      //Find modules related to existing templates
      $relatedmodules = $this->getRelatedModules();
      $relatedall = array_flip($app_list_strings['pdf_module_list']);
      $overmodules = array_diff($relatedall, $relatedmodules);
      if ( !empty($app_list_strings['pdf_module_list_no_button']) ) {
         $to_remove = array_flip($app_list_strings['pdf_module_list_no_button']);
         $overmodules = array_merge($overmodules, $to_remove);
      }
      $change_add = $this->addButtons($relatedmodules); //add buttons to related modules
      $change_del = $this->removeButtons($overmodules); //remove buttons from not related modules

      if ( $change_del || $change_add ) {
         $relatedall = array_merge($relatedmodules, $overmodules);
         $this->clearTpls($relatedall);
      }
   }

   public function rebuild($module) {
      $change = $this->addButtons(array( $module ));
      if ( $change ) {
         $this->clearTpls(array( $module ));
      }
   }

   protected function clearTpls($module_list) {
      if ( is_array($module_list) && !empty($module_list) ) {
         $clear_cache = new RepairAndClear();
         $clear_cache->module_list = $module_list;
         $clear_cache->clearTpls();
      }
   }

   protected function addButtons($modules) {

      global $sugar_config;
      if ( isset($sugar_config['suitecrm_version']) && !empty($sugar_config['suitecrm_version']) ) {
         return $this->addButtonsSuite($modules);
      } else {
         global $bwcModules;
         $bwc_targets = array_intersect($modules, $bwcModules);
         $sidecar_targets = array_diff($modules, $bwc_targets);
         return $this->addButtonsSugar($sidecar_targets) || $this->addButtonsSuite($bwc_targets);
      }
   }

   protected function addButtonsSugar($modules) {
      
      $change_return = false;
      foreach ( $modules as $module ) {
         $parser = ParserFactory::getParser('recordview', $module);
         $defs = $parser->_viewdefs;
         $button_exist = false;

         //check if link exist
         if ( isset($defs['buttons']) ) {
            $button_exist = $this->checkIfButtonExist($defs['buttons']);
         }
         if ( !$button_exist ) {
            $this->add_code_sugar($parser); //add code with button
            $change_return = true;
         }
      }
      return $change_return;
   }

   protected function addButtonsSuite($modules) {
      
      $change_return = false;
      foreach ( $modules as $module ) {
         $detailview_path = $this->getDetailViewPath($module);
         include $detailview_path;
         $defs = $viewdefs[$module]['DetailView']['templateMeta']['form'];
         $button_exist = false;

         //Fix old way
         foreach ( $defs['links'] as $key => $button ) {
            if ( is_array($button) ) {
               list($change_return, $defs) = $this->fixButtonAddInDetailView($button, $key, $module, $viewdefs, $defs, $change_return);
            }
         }
         //check if link exist
         if ( isset($defs['links']) ) {
            $button_exist = $this->checkIfLinkExist($defs['links']);
         }
         if ( !$button_exist ) {
            $this->add_code_suite($detailview_path, $module); //add code with button
            $change_return = true;
         }
      }
      return $change_return;
   }

   protected function fixButtonAddInDetailView($button, $key, $module, $viewdefs, $defs, $change_return) {
      if ( isset($button['customCode']) ) {
         if ( preg_match('/id="pdftemplatewidget"/', $button['customCode']) ) {
            unset($defs['buttons'][$key]);
            write_array_to_file('viewdefs', $viewdefs, 'custom/modules/' . $module . '/metadata/detailviewdefs.php');
            $change_return = true;
         }
      }
      return array( $change_return, $defs );
   }

   protected function checkIfLinkExist($defs_links) {
      $button_exist = false;
      if ( is_array($defs_links) ) {
         for ( $i = 0; $i < count($defs_links); $i++ ) {
            if ( preg_match('/id="pdf_generator"/', $defs_links[$i]) ) {
               $button_exist = true;
               break;
            }
         }
      } else {
         if ( preg_match('/id="pdf_generator"/', $defs_links) ) {
            $button_exist = true;
         }
      }
      return $button_exist;
   }

   protected function checkIfButtonExist($defs_buttons) {
      $button_exist = false;
      if ( is_array($defs_buttons) ) {
         foreach ( $defs_buttons as $button ) {
            if ( $button['type'] == 'actiondropdown' && $button['name'] == 'main_dropdown' ) {
               foreach ( $button['buttons'] as $dropdown_item ) {
                  if ( $dropdown_item['type'] == 'pdfaction' && $dropdown_item['name'] == 'pdf' ) {
                     $button_exist = true;
                     break;
                  }
               }
            }
         }
      }
      return $button_exist;
   }

   protected function removeButtons($modules) {
      global $sugar_config;
      if ( isset($sugar_config['suitecrm_version']) && !empty($sugar_config['suitecrm_version']) ) {
         return $this->removeButtonsSuite($modules);
      } else {
         global $bwcModules;
         $bwc_targets = array_intersect($modules, $bwcModules);
         $sidecar_targets = array_diff($modules, $bwc_targets);
         return $this->removeButtonsSugar($sidecar_targets) || $this->removeButtonsSuite($bwc_targets);
      }
   }

   protected function removeButtonsSugar($modules) {
      $change_return = false;
      foreach ( $modules as $module ) {
         $parser = ParserFactory::getParser('recordview', $module);
         $defs = $parser->_viewdefs;
         $button_exist = false;

         //check if link exist
         if ( isset($defs['buttons']) ) {
            $button_exist = $this->checkIfButtonExist($defs['buttons']);
         }
         if ( $button_exist ) {
            $this->remove_code_sugar($parser);
            $change_return = true;
         }
      }
      return $change_return;
   }

   protected function remove_code_sugar($parser) {
      $deleted = false;
      foreach ( $parser->_viewdefs['buttons'] as $key1 => $button ) {
         if ( $button['type'] == 'actiondropdown' && $button['name'] == 'main_dropdown' ) {
            foreach ( $button['buttons'] as $key2 => $dropdown_item ) {
               if ( $dropdown_item['type'] == 'pdfaction' && $dropdown_item['name'] == 'pdf' ) {
                  array_splice($parser->_viewdefs['buttons'][$key1]['buttons'], $key2, 1);
                  $deleted = true;
               }
            }
         }
      }
      if ( $deleted ) {
         $parser->handleSave(false);
      }
   }

   protected function removeButtonsSuite($modules) {
      $change_return = false;
      foreach ( $modules as $module ) {
         $change = false;
         $detailview_path = $this->getDetailViewPath($module);
         include $detailview_path;
         $defs = &$viewdefs[$module]['DetailView']['templateMeta'];
         //find if exist button to unset
         list($defs, $change) = $this->findIfExistButtonToUnset($defs, $change);
         if ( isset($defs['form']['links']) ) {
            list($defs, $change) = $this->checkIfLinkExistForRemove($defs, $change, $module);
         }
         //find if exist include to unset
         list($defs, $change) = $this->checkfExistOpenPdfJsInIncludes($defs, $change);
         if ( $change ) {
            write_array_to_file('viewdefs', $viewdefs, 'custom/modules/' . $module . '/metadata/detailviewdefs.php');
            $change_return = true;
         }
      }
      return $change_return;
   }

   protected function checkfExistOpenPdfJsInIncludes($defs, $change) {
      for ( $i = 0; $i < count($defs['includes']); $i++ ) {
         if ( PDFTEMPLATES_PATH_OPENPDF_JS == $defs['includes'][$i]['file'] ) {
            unset($defs['includes'][$i]);
            $change = true;
            break;
         }
      }
      return array( $defs, $change );
   }

   protected function checkIfLinkExistForRemove($defs, $change, $module) {
      if ( is_array($defs['form']['links']) ) {
         for ( $i = 0; $i < count($defs['form']['links']); $i++ ) {
            if ( preg_match('/id="pdf_generator"/', $defs['form']['links'][$i]) ) {
               $change = true;
               unset($defs['form']['links'][$i]);
               break;
            }
         }
      } else {
         if ( preg_match('/id="pdf_generator"/', $defs['form']['links']) ) {
            $defs['form']['links'] = str_replace($this->genPDFButton($module), "", $defs['form']['links']);
            $change = true;
         }
      }
      return array( $defs, $change );
   }

   protected function findIfExistButtonToUnset($defs, $change) {
      for ( $i = 0; $i < count($defs['form']['buttons']); $i++ ) {
         $button = $defs['form']['buttons'][$i];
         list($change, $defs) = $this->fixButtonDelInDetailView($button, $i, $defs, $change);
         if ( $change ) {
            break;
         }
      }
      return array( $defs, $change );
   }

   protected function fixButtonDelInDetailView($button, $i, $defs, $change_return) {
      if ( is_array($button) && isset($button['customCode']) && preg_match('/id="pdftemplatewidget"/', $button['customCode']) ) { //if is set button
         unset($defs['form']['buttons'][$i]);
         $change_return = true;
      }
      return array( $change_return, $defs );
   }

   /**
    * Adds code with button and includes javascript
    */
   protected function add_code_suite($detailview_path, $module) {
      include $detailview_path;
      $defs = &$viewdefs[$module]['DetailView']['templateMeta'];
      if ( isset($defs['form']['links']) ) {
         if ( !is_array($defs['form']['links']) ) {
            $defs['form']['links'] = array( $defs['form']['links'] );
         }
         $defs['form']['links'][] = $this->genPDFButton($module);
      } else {
         $defs['form']['links'] = array( $this->genPDFButton($module) );
      }
      $defs['includes'][] = array(
         'file' => 'modules/PDFTemplates/js/openpdf.js',
      );
      if ( isset($defs['javascript']) ) {
         $defs['javascript'] .= '<script> var currentModule="' . $module . '"</script>';
      } else {
         $defs['javascript'] = '<script> var currentModule="' . $module . '"</script>';
      }
      $this->checkAndCreateCustomMetadataDir($module);
      write_array_to_file('viewdefs', $viewdefs, 'custom/modules/' . $module . '/metadata/detailviewdefs.php');
   }

   protected function add_code_sugar($parser) {
      foreach ( $parser->_viewdefs['buttons'] as $button ) {
         if ( $button['type'] == 'actiondropdown' && $button['name'] == 'main_dropdown' ) {
            array_splice($button['buttons'], 2, 0, array( array(
                  'name' => 'pdf',
                  'type' => 'pdfaction',
                  'label' => 'LBL_PDF_TEMPLATE',
                  'showOn' => 'view',
                  'action' => 'download'
               )
            ));
            array_splice($parser->_viewdefs['buttons'], -2, 1, array( $button ));
            break;
         }
      }
      $parser->handleSave(false);
   }

   protected function genPDFButton($module) {
      return '<span id="pdf_generator"><span id="templateselect_span"></span>' .
              '<input title="PDF" onclick="openPDF(\'{$fields.id.value}\', \'' . $module . '\');" type="button" name="button" value="PDF"></span>';
   }

   protected function checkAndCreateCustomMetadataDir($module) {
      if ( !is_dir('custom/modules/' . $module) ) {
         mkdir('custom/modules/' . $module);
      }
      if ( !is_dir('custom/modules/' . $module . '/metadata') ) {
         mkdir('custom/modules/' . $module . '/metadata');
      }
   }

   protected function getRelatedModules() {
      $db = &DBManagerFactory::getInstance();
      $sql = 'SELECT distinct relatedmodule FROM pdftemplates WHERE deleted=0 and type="standard"';
      $r = $db->query($sql);
      while ( $row = $db->fetchByAssoc($r) ) {
         $relatedmodules[] = $row['relatedmodule'];
      }
      if ( empty($relatedmodules) ) {
         return array();
      }
      $relatedmodules = array_unique($relatedmodules);
      return $relatedmodules;
   }

   protected function getDetailViewPath($module) {
      $detailview_path = 'custom/modules/' . $module . '/metadata/detailviewdefs.php';
      if ( !file_exists($detailview_path) ) {
         $detailview_path = 'modules/' . $module . '/metadata/detailviewdefs.php';
      }
      return $detailview_path;
   }

   protected function getRecordViewPath($module) {
      $recordview_path = 'custom/modules/' . $module . '/clients/base/views/record/record.php';
      if ( !file_exists($recordview_path) ) {
         $recordview_path = 'modules/' . $module . '/clients/base/views/record/record.php';
      }
      return $recordview_path;
   }

}

