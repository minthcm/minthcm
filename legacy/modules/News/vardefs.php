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
$dictionary['News'] = array(
    'table' => 'news',
    'audited' => true,
    'inline_edit' => true,
    'duplicate_merge' => true,
    'fields' => array(
        'content_of_announcement' => array(
            'required' => false,
            'name' => 'content_of_announcement',
            'vname' => 'LBL_CONTENT_OF_ANNOUNCEMENT',
            'type' => 'text',
            'massupdate' => 0,
            'no_default' => false,
            'comments' => '',
            'help' => '',
            'importable' => 'false',
            'duplicate_merge' => 'disabled',
            'duplicate_merge_dom_value' => '0',
            'audited' => false,
            'inline_edit' => '',
            'reportable' => false,
            'unified_search' => false,
            'merge_filter' => 'disabled',
            'size' => '20',
            'studio' => 'visible',
        ),
        'display_date' => array(
            'required' => false,
            'name' => 'display_date',
            'vname' => 'LBL_DISPLAY_DATE',
            'type' => 'date',
            'massupdate' => 0,
            'no_default' => false,
            'comments' => '',
            'help' => '',
            'importable' => 'false',
            'duplicate_merge' => 'disabled',
            'duplicate_merge_dom_value' => '0',
            'audited' => false,
            'inline_edit' => '',
            'reportable' => true,
            'unified_search' => false,
            'merge_filter' => 'disabled',
            'size' => '20',
            'enable_range_search' => false,
        ),
        'news_type' => array(
            'required' => true,
            'name' => 'news_type',
            'vname' => 'LBL_NEWS_TYPE',
            'type' => 'enum',
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
            'len' => 100,
            'size' => '20',
            'options' => 'news_type_list',
            'studio' => 'visible',
            'dependency' => false,
            'default' => 'announcement',
        ),
        'news_status' => array(
            'required' => false,
            'name' => 'news_status',
            'vname' => 'LBL_NEWS_STATUS',
            'type' => 'enum',
            'massupdate' => 0,
            'default' => 'draft',
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
            'len' => 100,
            'size' => '20',
            'options' => 'news_status_list',
            'studio' => 'visible',
            'dependency' => false,
        ),
        'usersnews' => array(
            'name' => 'usersnews',
            'type' => 'link',
            'relationship' => 'usersnews_news',
            'source' => 'non-db',
            'module' => 'UsersNews',
            'bean_name' => false,
            'side' => 'right',
            'vname' => 'LBL_USERSNEWS',
        ),
        'publication_date' => array(
            'required' => false,
            'name' => 'publication_date',
            'vname' => 'LBL_PUBLICATION_DATE',
            'type' => 'date',
            'massupdate' => 0,
            'no_default' => false,
            'comments' => '',
            'help' => '',
            'importable' => 'false',
            'duplicate_merge' => 'disabled',
            'duplicate_merge_dom_value' => '0',
            'audited' => true,
            'inline_edit' => '',
            'reportable' => true,
            'required' => true,
            'unified_search' => false,
            'merge_filter' => 'disabled',
            'size' => '20',
            'enable_range_search' => true,
            'options' => 'date_range_search_dom',
            'vt_dependency' => 'equals($news_type,\'announcement\')',
        ),
        'comments' => array(
            'name' => 'comments',
            'type' => 'link',
            'relationship' => 'news_comments',
            'module' => 'Comments',
            'bean_name' => 'Comments',
            'source' => 'non-db',
            'vname' => 'LBL_COMMENTS',
        ),
        'comments_widget' => array(
            'name' => 'comments_widget',
            'vname' => 'LBL_COMMENTS_WIDGET',
            'type' => 'function',
            'source' => 'non-db',
            'massupdate' => 0,
            'studio' => 'visible',
            'importable' => 'false',
            'duplicate_merge' => 'disabled',
            'duplicate_merge_dom_value' => 0,
            'audited' => false,
            'reportable' => false,
            'inline_edit' => 0,
            'function' => array(
                'name' => 'display_comments',
                'returns' => 'html',
                'include' => 'modules/Comments/RelatedComments.php',
            ),
        ),
        'reactions' => array(
            'name' => 'reactions',
            'type' => 'link',
            'relationship' => 'news_reactions',
            'module' => 'Reactions',
            'bean_name' => 'Reactions',
            'source' => 'non-db',
            'vname' => 'LBL_REACTIONS',
        ),
    ),
    'relationships' => array(
        'news_comments' => array(
            'lhs_module' => 'News',
            'lhs_table' => 'news',
            'lhs_key' => 'id',
            'rhs_module' => 'Comments',
            'rhs_table' => 'comments',
            'rhs_key' => 'parent_id',
            'relationship_type' => 'one-to-many',
            'relationship_role_column' => 'parent_type',
            'relationship_role_column_value' => 'News',
        ),
        'news_reactions' => array(
            'lhs_module' => 'News',
            'lhs_table' => 'news',
            'lhs_key' => 'id',
            'rhs_module' => 'Reactions',
            'rhs_table' => 'reactions',
            'rhs_key' => 'parent_id',
            'relationship_type' => 'one-to-many',
            'relationship_role_column' => 'parent_type',
            'relationship_role_column_value' => 'News',
        ),
    ),
    'optimistic_locking' => true,
    'unified_search' => true,
);
if (!class_exists('VardefManager')) {
    require_once 'include/SugarObjects/VardefManager.php';
}
VardefManager::createVardef('News', 'News', array('basic', 'assignable', 'security_groups'));
