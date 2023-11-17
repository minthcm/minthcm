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

if ( file_exists('include/ytree/Tree.php') ) { // SuiteCRM - SugarCRM
   require_once('include/ytree/Tree.php');
   require_once('include/ytree/Node.php');
} else {
   require_once('vendor/ytree/Tree.php');
   require_once('vendor/ytree/Node.php');
}

function get_related_module($bean, $relationship) {
   $query = "SELECT * FROM relationships WHERE relationship_name = '" . $relationship . "' AND deleted = 0";
   $row = $bean->db->fetchByAssoc($bean->db->query($query));
   //$bean_module = $bean->module_dir;
   if ( isset($bean->module_name) ) {
      $bean_module = $bean->module_name;
   } else {
      $bean_module = $bean->module_dir;
   }
   return ($row['lhs_module'] == $bean_module) ? $row['rhs_module'] : $row['lhs_module'];
}

function get_node_data($params, $get_array = false) {
   $ret = array();
   $depth = $params['TREE']['depth'];
   $new_relation = '';
   while ( $i++ < $depth )
      $new_relation .= $params['NODES'][$i]['relationship'] . '__';

   $nodes = get_module_elements($params['NODES'][$depth]['related_module'], $new_relation);

   foreach ( $nodes as $node )
      $ret['nodes'][] = $node->get_definition();

   $json = new JSON(JSON_LOOSE_TYPE);
   $str = $json->encode($ret);
   return $str;
}

function get_nodes() {
   $nodes = array();
   global $beanList, $moduleList, $beanFiles, $pdftemplate, $app_strings, $mod_strings, $app_list_strings;

   $module = "KReports";

   $root = new Node($module, translate('LBL_MODULE_NAME', $module));
   $root->expanded = true;
   $root->dynamic_load = false;

   $nodes = get_module_elements($module);

   foreach ( $nodes as $node )
      $root->add_node($node);

   return array( $root );
}

/*
 * Generates nodes for given module including extendable nodes for its related modules
 */

function get_module_elements($module, $relationship = "") {
   global $beanList, $beanFiles, $pdftemplate, $app_strings, $mod_strings, $app_list_strings;
   $nodes = array();

   $bean = $beanList[$module];
   require_once($beanFiles[$bean]);
   $focus = new $bean();
   $focus->load_relationships();

   $field_defs = $focus->getFieldDefinitions();
   $link_defs = $focus->get_linked_fields();
   $key = '$';
   $rel = substr($relationship, 0, -2);
   $module_dir = $focus->module_dir;

   $kreport = new KReport();
   $kreport = $kreport->retrieve($_POST['relatedmodule']);
   require_once 'modules/KTemplates/KReports_utils.php';
   $kreport_utils = new KReports_utils();
   $variable = $kreport_utils->get_variable($kreport->id);
   //dodanie node, które będzie stawiało sekcję powtarzającą
   $sub_node = new Node("repeat", "[ " . "Wstaw tabelę powtarzającą" . " ]");
   $sub_node->expanded = true;
   $sub_node->dynamic_load = false;
   $href_string = "javascript:window.treeAddons.insertToken('" . ' <table border=1><thead><tr><th>Header</th><th>Header</th></tr></thead><tbody><!--repeat--><tr><td>&nbsp;</td><td>&nbsp;</td></tr><!--endrepeat--></tbody></table>' . "');";
   $sub_node->set_property("href", $href_string);
   $nodes[] = $sub_node;
   //end
   foreach ( $variable as $field ) {//dodanie nodów na podstawie każdego pola znajdującego się w KRaporcie
      $sub_node = new Node($field['name'], "[ " . $field['label'] . " ]");
      $sub_node->expanded = true;
      $sub_node->dynamic_load = false;
      $href_string = "javascript:window.treeAddons.insertToken('" . $key . $field['name'] . "');";
      $sub_node->set_property("href", $href_string);
      $nodes[] = $sub_node;
   }
   return $nodes;
}

?>