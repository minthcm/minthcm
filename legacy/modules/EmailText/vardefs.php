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
 * Class for separate storage of Email texts
 */
global $dictionary;
if (file_exists('custom/metadata/emails_beansMetaData.php')) {
    require_once('custom/metadata/emails_beansMetaData.php');
} else {
    require_once('metadata/emails_beansMetaData.php');
}
$dictionary['EmailText'] = $dictionary['emails_text'];

if (isset($dictionary['EmailText']['fields']['email_id'])) {
    $dictionary['EmailText']['fields']['email_id']['comment'] = 'Foriegn key to emails table';
}
if (isset($dictionary['EmailText']['fields']['from_addr'])) {
    $dictionary['EmailText']['fields']['from_addr']['comment'] = 'Email address of person who send the email';
}
if (isset($dictionary['EmailText']['fields']['reply_to_addr'])) {
    $dictionary['EmailText']['fields']['reply_to_addr']['comment'] = 'reply to email address';
}
if (isset($dictionary['EmailText']['fields']['to_addrs'])) {
    $dictionary['EmailText']['fields']['to_addrs']['comment'] = 'Email address(es) of person(s) to receive the email';
}
if (isset($dictionary['EmailText']['fields']['cc_addrs'])) {
    $dictionary['EmailText']['fields']['cc_addrs']['comment'] = 'Email address(es) of person(s) to receive a carbon copy of the email';
}
if (isset($dictionary['EmailText']['fields']['bcc_addrs'])) {
    $dictionary['EmailText']['fields']['bcc_addrs']['comment'] = 'Email address(es) of person(s) to receive a blind carbon copy of the email';
}
if (isset($dictionary['EmailText']['fields']['description'])) {
    $dictionary['EmailText']['fields']['description']['comment'] = 'Email body in plain text';
}
if (isset($dictionary['EmailText']['fields']['description_html'])) {
    $dictionary['EmailText']['fields']['description_html']['comment'] = 'Email body in HTML format';
}
if (isset($dictionary['EmailText']['fields']['raw_source'])) {
    $dictionary['EmailText']['fields']['raw_source']['comment'] = 'Full raw source of email';
}
if (isset($dictionary['EmailText']['fields']['deleted'])) {
    $dictionary['EmailText']['fields']['deleted']['comment'] = '';
}

