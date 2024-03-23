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

require_once 'include/MVC/View/views/view.edit.php';

class PDFTemplatesViewWizard extends ViewEdit {

   var $ev;
   var $type = 'wizard';
   var $useForSubpanel = false;  //boolean variable to determine whether view can be used for subpanel creates
   var $useModuleQuickCreateTemplate = false; //boolean variable to determine whether or not SubpanelQuickCreate has a separate display function
   var $showTitle = true;

   function PDFTemplatesViewWizard() {
      parent::getEditView();
   }

   function preDisplay() {
      parent::preDisplay();
      $metadataFile = $this->getMetaDataFile();
      $this->ev = new EditView();
      $this->ev->view = "WizardView";
      $this->ev->ss = & $this->ss;
      $this->ev->setup($this->module, $this->bean, $metadataFile);
   }

   function display() {
      $this->ev->process();
      echo $this->ev->display($this->showTitle, false);
   }

   function getMetaDataFile() {
      $metadataFile = null;
      $foundViewDefs = false;
      if ( file_exists('custom/modules/' . $this->module . '/metadata/wizarddefs.php') ) {
         $metadataFile = 'custom/modules/' . $this->module . '/metadata/wizarddefs.php';
         $foundViewDefs = true;
      } else {
         if ( file_exists('custom/modules/' . $this->module . '/metadata/metafiles.php') ) {
            require_once('custom/modules/' . $this->module . '/metadata/metafiles.php');
            if ( !empty($metafiles[$this->module]['wizarddefs.php']) ) {
               $metadataFile = $metafiles[$this->module]['wizarddefs.php'];
               $foundViewDefs = true;
            }
         } elseif ( file_exists('modules/' . $this->module . '/metadata/metafiles.php') ) {
            require_once('modules/' . $this->module . '/metadata/metafiles.php');
            if ( !empty($metafiles[$this->module]['wizarddefs.php']) ) {
               $metadataFile = $metafiles[$this->module]['wizarddefs.php'];
               $foundViewDefs = true;
            }
         }
      }
      $GLOBALS['log']->debug("metadatafile=" . $metadataFile);
      if ( !$foundViewDefs && file_exists('modules/' . $this->module . '/metadata/wizarddefs.php') ) {
         $metadataFile = 'modules/' . $this->module . '/metadata/wizarddefs.php';
      }
      return $metadataFile;
   }

}
