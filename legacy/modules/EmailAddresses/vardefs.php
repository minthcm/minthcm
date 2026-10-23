<?php
if (!defined('sugarEntry') || !sugarEntry) {
    die('Not A Valid Entry Point');
}
/**
 *
 * SugarCRM Community Edition is a customer relationship management program developed by
 * SugarCRM, Inc. Copyright (C) 2004-2013 SugarCRM Inc.
 *
 * SuiteCRM is an extension to SugarCRM Community Edition developed by SalesAgility Ltd.
 * Copyright (C) 2011 - 2018 SalesAgility Ltd.
 *
 * MintHCM is a Human Capital Management software based on SuiteCRM developed by MintHCM, 
 * Copyright (C) 2018-2024 MintHCM
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
 * Stub class to allow Link class easily use SugarEmailAddress
 */
global $dictionary;
if (file_exists('custom/metadata/email_addressesMetaData.php')) {
    include('custom/metadata/email_addressesMetaData.php');
} else {
    include('metadata/email_addressesMetaData.php');
}

if (file_exists('custom/metadata/emails_beansMetaData.php')) {
    include('custom/metadata/emails_beansMetaData.php');
} else {
    include('metadata/emails_beansMetaData.php');
}

if (isset($dictionary['EmailAddress']['fields']['id'])) {
    $dictionary['EmailAddress']['fields']['id']['comment'] = 'Record unique identifier';
}
if (isset($dictionary['EmailAddress']['fields']['email_address'])) {
    $dictionary['EmailAddress']['fields']['email_address']['comment'] = 'Email Address description';
}
if (isset($dictionary['EmailAddress']['fields']['email_address_caps'])) {
    $dictionary['EmailAddress']['fields']['email_address_caps']['comment'] = 'Email Address Caps description';
}
if (isset($dictionary['EmailAddress']['fields']['invalid_email'])) {
    $dictionary['EmailAddress']['fields']['invalid_email']['comment'] = 'Invalid Email description';
}
if (isset($dictionary['EmailAddress']['fields']['opt_out'])) {
    $dictionary['EmailAddress']['fields']['opt_out']['comment'] = 'Opt Out description';
}
if (isset($dictionary['EmailAddress']['fields']['confirm_opt_in'])) {
    $dictionary['EmailAddress']['fields']['confirm_opt_in']['comment'] = 'Confirm Opt In description';
}
if (isset($dictionary['EmailAddress']['fields']['confirm_opt_in_date'])) {
    $dictionary['EmailAddress']['fields']['confirm_opt_in_date']['comment'] = 'Confirm Opt In Date description';
}
if (isset($dictionary['EmailAddress']['fields']['confirm_opt_in_sent_date'])) {
    $dictionary['EmailAddress']['fields']['confirm_opt_in_sent_date']['comment'] = 'Confirm Opt In Sent Date description';
}
if (isset($dictionary['EmailAddress']['fields']['confirm_opt_in_fail_date'])) {
    $dictionary['EmailAddress']['fields']['confirm_opt_in_fail_date']['comment'] = 'Confirm Opt In Fail Date description';
}
if (isset($dictionary['EmailAddress']['fields']['confirm_opt_in_token'])) {
    $dictionary['EmailAddress']['fields']['confirm_opt_in_token']['comment'] = 'Confirm Opt In Token description';
}
if (isset($dictionary['EmailAddress']['fields']['date_created'])) {
    $dictionary['EmailAddress']['fields']['date_created']['comment'] = 'Date Created description';
}
if (isset($dictionary['EmailAddress']['fields']['date_modified'])) {
    $dictionary['EmailAddress']['fields']['date_modified']['comment'] = 'The date when the record was last modified';
}
if (isset($dictionary['EmailAddress']['fields']['deleted'])) {
    $dictionary['EmailAddress']['fields']['deleted']['comment'] = 'A flag indicating whether the record has been deleted';
}

