<?php

if ( !defined('sugarEntry') || !sugarEntry )
   die('Not A Valid Entry Point');
/* * *******************************************************************************
 * SugarCRM Community Edition is a customer relationship management program developed by
 * SugarCRM, Inc. Copyright (C) 2004-2011 SugarCRM Inc.
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
 * FOR A PARTICULAR PURPOSE.  See the GNU Affero General Public License for more
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
 * these Appropriate Legal Notices must retain the display of the "Powered by
 * SugarCRM" logo. If the display of the logo is not reasonably feasible for
 * technical reasons, the Appropriate Legal Notices must display the words
 * "Powered by SugarCRM".
 * ****************************************************************************** */

/* * *******************************************************************************

 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________..
 * ****************************************************************************** */

if ( file_exists('include/ytree/Tree.php') ) { // SuiteCRM - SugarCRM
   require_once('include/ytree/Tree.php');
   require_once('include/ytree/Node.php');
} else {
   require_once('vendor/ytree/Tree.php');
   require_once('vendor/ytree/Node.php');
}

require_once('modules/KTemplates/TreeData.php');
require_once('include/MVC/View/views/view.edit.php');

class KTemplatesViewEdit extends ViewEdit {

   function preDisplay() {
      if ( !isset($_POST['relatedmodule']) )
         $_POST['relatedmodule'] = $this->bean->relatedmodule;
      $_POST['relatedmodule2'] = $_POST['relatedmodule'];
      $metadataFile = $this->getMetaDataFile();
      $treefield = new Tree('treefield');
      $treefield->set_param('module', 'KTemplates');
      $nodes = get_nodes();
      foreach ( $nodes as $node ) {
         $treefield->add_node($node);
      }
      $tree_head = '';
      $tree_head .= $treefield->generate_header();
      $tree_head = str_replace(array( '{', '}' ), array( 'openc', 'closec' ), $tree_head);

      $instance = $treefield->generate_nodes_array();
      $instance = str_replace(array( '{', '}' ), array( 'openc', 'closec' ), $instance);

      $this->bean->field_defs['fields']['tree'] = $instance;
      $this->bean->field_defs['fields']['treehead'] = $tree_head;
      $this->bean->field_defs['fields']['template_value'] = $this->bean->template;
      $this->ev = new EditView();
      $this->ev->ss = & $this->ss;
      $this->ev->setup($this->module, $this->bean, $metadataFile, 'include/EditView/EditView.tpl');
   }

   public function display() {
      $this->ev->process();
      $tpl = $this->ev->display($this->showTitle);
      $tpl = str_replace(array( 'openc', 'closec' ), array( '{', '}' ), $tpl);
      echo $tpl;
   }

   public function getModuleTitle(
           $show_help = true
   ) {
      global $sugar_version, $sugar_flavor, $server_unique_key, $current_language, $action;

      $theTitle = "<div class='moduleTitle'>\n";

      $module = preg_replace("/ /", "", $this->module);

      $params = $this->_getModuleTitleParams();
      $index = 0;

      if ( SugarThemeRegistry::current()->directionality == "rtl" ) {
         $params = array_reverse($params);
      }
      if ( count($params) > 1 ) {
         array_shift($params);
      }
      $count = count($params);
      $paramString = '';
      foreach ( $params as $parm ) {
         $index++;
         $paramString .= $parm;
         if ( $index < $count ) {
            $paramString .= $this->getBreadCrumbSymbol();
         }
      }

      if ( !empty($paramString) ) {
         $theTitle .= "<h2> $paramString </h2>";

         if ( $this->type == "detail" ) {
            $theTitle .= "<div class='favorite' record_id='" . $this->bean->id . "' module='" . $this->bean->module_dir . "'><div class='favorite_icon_outline'>" . SugarThemeRegistry::current()->getImage('favorite-star-outline', 'title="' . translate('LBL_DASHLET_EDIT', 'Home') . '" border="0"  align="absmiddle"', null, null, '.gif', translate('LBL_DASHLET_EDIT', 'Home')) . "</div>
                                                    <div class='favorite_icon_fill'>" . SugarThemeRegistry::current()->getImage('favorite-star', 'title="' . translate('LBL_DASHLET_EDIT', 'Home') . '" border="0"  align="absmiddle"', null, null, '.gif', translate('LBL_DASHLET_EDIT', 'Home')) . "</div></div>";
         }
      }

      // bug 56131 - restore conditional so that link doesn't appear where it shouldn't
      if ( $show_help || $this->type == 'list' ) {
         $theTitle .= "<span class='utils'>";
         $createImageURL = SugarThemeRegistry::current()->getImageURL('create-record.gif');
         if ( $this->type == 'list' )
            $theTitle .= '<a href="#" class="btn btn-success showsearch"><span class=" glyphicon glyphicon-search" aria-hidden="true"></span></a>';$url = "index.php?module=$module&action=wizard&return_module=$module&return_action=DetailView";
         if ( $show_help ) {
            $theTitle .= <<<EOHTML
&nbsp;
<a id="create_image" href="{$url}" class="utilsLink">
<img src='{$createImageURL}' alt='{$GLOBALS['app_strings']['LNK_CREATE']}'></a>
<a id="create_link" href="{$url}" class="utilsLink">
{$GLOBALS['app_strings']['LNK_CREATE']}
</a>
EOHTML;
         }
         $theTitle .= "</span>";
      }

      $theTitle .= "<div class='clear'></div></div>\n";
      return $theTitle;
   }

}

?>
