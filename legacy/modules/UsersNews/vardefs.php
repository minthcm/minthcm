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
$dictionary['UsersNews'] = array(
   'table' => 'usersnews',
   'audited' => true,
   'inline_edit' => true,
   'duplicate_merge' => false,
   'fields' => array(
      'news_read' =>
      array(
         'required' => false,
         'name' => 'news_read',
         'vname' => 'LBL_NEWS_READ',
         'type' => 'bool',
         'massupdate' => 0,
         'default' => '0',
         'no_default' => false,
         'comments' => '',
         'help' => '',
         'importable' => 'true',
         'duplicate_merge' => 'disabled',
         'duplicate_merge_dom_value' => '0',
         'audited' => true,
         'inline_edit' => '',
         'reportable' => true,
         'unified_search' => false,
         'merge_filter' => 'disabled',
         'len' => '255',
         'size' => '20',
      ),
      'not_display' =>
      array(
         'required' => false,
         'name' => 'not_display',
         'vname' => 'LBL_NOT_DISPLAY',
         'type' => 'date',
         'massupdate' => 0,
         'no_default' => false,
         'comments' => '',
         'help' => '',
         'importable' => 'true',
         'duplicate_merge' => 'disabled',
         'duplicate_merge_dom_value' => '0',
         'audited' => true,
         'inline_edit' => '',
         'reportable' => true,
         'unified_search' => false,
         'merge_filter' => 'disabled',
         'size' => '20',
         'enable_range_search' => true,
         'options' => 'date_range_search_dom',
      ),
      'news' =>
      array(
         'name' => 'news',
         'type' => 'link',
         'relationship' => 'usersnews_news',
         'source' => 'non-db',
         'module' => 'News',
         'bean_name' => 'News',
         'vname' => 'LBL_NEWS',
         'id_name' => 'news_id',
      ),
      'news_name' =>
      array(
         'name' => 'news_name',
         'type' => 'relate',
         'source' => 'non-db',
         'vname' => 'LBL_NEWS_NAME',
         'save' => true,
         'id_name' => 'news_id',
         'link' => 'news',
         'table' => 'news',
         'module' => 'News',
         'rname' => 'name',
         'audited' => false,
         'required' => true,
      ),
      'news_id' =>
      array(
         'name' => 'news_id',
         'type' => 'link',
         'relationship' => 'usersnews_news',
         'reportable' => false,
         'vname' => 'LBL_NEWS_ID',
         'rname' => 'id',
         'isnull' => 'true',
         'dbType' => 'id',
      ),
   ),
   'relationships' => array(
      'usersnews_news' =>
      array(
         'lhs_module' => 'News',
         'lhs_table' => 'news',
         'lhs_key' => 'id',
         'rhs_module' => 'UsersNews',
         'rhs_table' => 'usersnews',
         'rhs_key' => 'news_id',
         'relationship_type' => 'one-to-many',
      ),
   ),
   'optimistic_locking' => true,
   'unified_search' => true,
);
if ( !class_exists('VardefManager') ) {
   require_once('include/SugarObjects/VardefManager.php');
}
VardefManager::createVardef('UsersNews', 'UsersNews', array( 'basic', 'assignable', 'security_groups' ));

$dictionary['UsersNews']['fields']['name']["vt_calculated"] = "concat(\$news_name,' - ',\$assigned_user_name)";