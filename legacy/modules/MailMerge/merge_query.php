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


/**
 * Create merge query depending on the modules being merged
 * @param SugarBean $seed Object being queried
 * @param string $merge_module Module being merged
 * @param string $key ID of the record in module being merged
 */
function get_merge_query($seed, $merge_module, $key)
{
    $selQuery = array(
'Contacts'=>array(
    'Cases' => 'SELECT contacts.first_name, contacts.last_name, contacts.id, contacts.date_entered FROM contacts LEFT JOIN contacts_cases ON contacts.id=contacts_cases.contact_id AND (contacts_cases.deleted is NULL or contacts_cases.deleted=0)',
    'Bugs' => 'SELECT contacts.first_name, contacts.last_name, contacts.id, contacts.date_entered FROM contacts LEFT JOIN contacts_bugs ON contacts.id=contacts_bugs.contact_id AND (contacts_bugs.deleted is NULL or contacts_bugs.deleted=0)',
    'Quotes' => 'SELECT contacts.first_name, contacts.last_name, contacts.id, contacts.date_entered FROM contacts LEFT JOIN quotes_contacts ON contacts.id=quotes_contacts.contact_id AND (quotes_contacts.deleted is NULL or quotes_contacts.deleted=0)'
),
);

    $whereQuery = array(
'Contacts' =>
    array(
    'Cases' => 'contacts_cases.contact_id = contacts.id AND contacts_cases.case_id = ',
    'Bugs' => 'contacts_bugs.contact_id = contacts.id AND contacts_bugs.bug_id = ',
    'Quotes' => 'quotes_contacts.contact_id = contacts.id AND quotes_contacts.quote_id = '
    ),
);

    $relModule = $seed->module_dir;

    $select = "";
    if (!empty($selQuery[$relModule][$merge_module])) {
        $select = $selQuery[$relModule][$merge_module];
    } else {
        $lowerRelModule = strtolower($relModule);
        if ($seed->load_relationship($lowerRelModule)) {
            $params = array('join_table_alias' => 'r1', 'join_table_link_alias' => 'r2', 'join_type' => 'LEFT JOIN');
            $join = $seed->$lowerRelModule->getJoin($params);
            $select = "SELECT {$seed->table_name}.* FROM {$seed->table_name} $join";
        }
    }

    if (empty($select)) {
        $select = "SELECT contacts.first_name, contacts.last_name, contacts.id, contacts.date_entered FROM contacts";
    }

    if (empty($whereQuery[$relModule][$merge_module])) {
        $select .= " WHERE {$seed->table_name}.id = '{$seed->db->quote($key)}'";
    } else {
        $select .= " WHERE ". $whereQuery[$relModule][$merge_module] . "'{$seed->db->quote($key)}'";
    }
    $select .=  " ORDER BY {$seed->table_name}.date_entered";
    return $select;
}
