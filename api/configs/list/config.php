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

global $list_config;

$list_config = array(
    "config" => array(
        "actions" => ["edit", "view", "delete"],
        "itemsPerPageOptions" => [5, 10, 20, 50, 100, 200, 500, 1000],
        "defaultMaxItemsPerPage" => 20,
        "otherElementsFixedHeight" => 360,
    ),
    'variables' => array(
        "font" => array(
            "primary" => "Catamaran",
            "secondary" => "Montserrat",
        ),
        "color" => array(
            "primary--lighter" => "#73bba2",
            "primary--light" => "#4aaa8b",
            "primary" => "#009976",
            "primary--dark" => "#117e62",
            "primary--darker" => "#17644e",
            "secondary--lighter" => "#73bba2",
            "secondary--light" => "#4aaa8b",
            "secondary" => "#009976",
            "secondary--dark" => "#117e62",
            "secondary--darker" => "#17644e",
            "light" => "#FFFFFF",
            "dark" => "rgba(0, 0, 0, .87)",
            "gray--light" => "#DDDDDD",
            "gray" => "#999999",
            "disabled" => "#00000033",
            "error" => "#FF0000",
        ),
    ),
    "theme" => array(
        "font" => array(
            "body" => "primary",
            "btn" => "secondary",
            "popup-title" => "secondary",
        ),
        "color" => array(
            "btn-primary--bg" => "secondary",
            "btn-primary--text" => "light",
            "btn-secondary--bg" => "light",
            "btn-secondary--text" => "secondary",
            "btn-secondary--outline" => "disabled",
            "popup-column-visible--bg" => "primary",
            "popup-column-visible--text" => "light",
            "popup-column-hidden--bg" => "gray--light",
            "popup-column-hidden--text" => "dark",
            "switch" => "primary",
            "action-icon" => "primary--dark",
            "boolean-icon" => "gray",
            "loader" => "primary--lighter",
            "text--disabled" => "disabled",
            "text--link" => "primary",
            "text--error" => "error",
            "scroll--bg" => "light",
            "scroll--fg" => "primary",
        ),
    ),
    "fields_mappigs" => array(
        'name' => 'name.name',
        'first_name' => 'name.first',
        'last_name' => 'name.last',
        'date_entered' => 'meta.created.date',
        'created_by' => 'meta.created.user_id',
        'date_modified' => 'meta.modified.date',
        'modified_user_id' => 'meta.modified.user_id',
        'assigned_user_id' => 'meta.assigned.user_id',
        'modified_by_name' => 'meta.modified.user_name',
        'created_by_name' => 'meta.created.user_name',
        'assigned_user_name' => 'meta.assigned.user_name',
        'phone_mobile' => 'phone.mobile',
        'primary_address_city' => 'address.primary.city',
        'primary_address_state' => 'address.primary.state',
        'primary_address_postalcode' => 'address.primary.postalcode',
        'primary_address_street' => 'address.primary.street',
        'primary_address_country' => 'address.primary.country',
    ),
    "sort_mappings" => array(
        "name" => "name.name.keyword",
        "first_name" => "name.first.keyword",
        "last_name" => "name.last.keyword",
        "date_entered" => "meta.created.date",
        "created_by" => "meta.created.user_id.keyword",
        "date_modified" => "meta.modified.date",
        "modified_user_id" => "meta.modified.user_id.keyword",
        "assigned_user_id" => "meta.assigned.user_id.keyword",
        "modified_by_name" => "meta.modified.user_name.keyword",
        "created_by_name" => "meta.created.user_name.keyword",
        "assigned_user_name" => "meta.assigned.user_name.keyword",
        "phone_mobile" => "phone.mobile.keyword",
        "primary_address_city" => "address.primary.city.keyword",
        "primary_address_state" => "address.primary.state.keyword",
        "primary_address_postalcode" => "address.primary.postalcode.keyword",
        "primary_address_street" => "address.primary.street.keyword",
        "primary_address_country" => "address.primary.country.keyword",
    ),
);

$files = scandir(__DIR__);
if (is_array($files)) {
    $files = array_diff($files, array('.', '..', 'config.php'));
    foreach ($files as $file) {
        include __DIR__ . '/' . $file;
    }
}
unset($files);
