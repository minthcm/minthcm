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

if (!defined('sugarEntry') || !sugarEntry) {
    die('Not A Valid Entry Point');
}


// the types/methods defined in this file are deprecated -- please see SoapSugarUsers.php, SoapPortalUsers.php, SoapStudio.php, etc.

$server->wsdl->addComplexType(
    'contact_detail',
    'complexType',
    'struct',
    'all',
    '',
    array(
        'email_address' => array('name' => 'email_address', 'type' => 'xsd:string'),
        'name1' => array('name' => 'name1', 'type' => 'xsd:string'),
        'name2' => array('name' => 'name2', 'type' => 'xsd:string'),
        'association' => array('name' => 'association', 'type' => 'xsd:string'),
        'id' => array('name' => 'id', 'type' => 'xsd:string'),
        'msi_id' => array('name' => 'id', 'type' => 'xsd:string'),
        'type' => array('name' => 'type', 'type' => 'xsd:string'),
    )
);

$server->wsdl->addComplexType(
    'contact_detail_array',
    'complexType',
    'array',
    '',
    'SOAP-ENC:Array',
    array(),
    array(
        array('ref' => 'SOAP-ENC:arrayType', 'wsdl:arrayType' => 'tns:contact_detail[]')
    ),
    'tns:contact_detail'
);

$server->wsdl->addComplexType(
    'user_detail',
    'complexType',
    'struct',
    'all',
    '',
    array(
        'email_address' => array('name' => 'email_address', 'type' => 'xsd:string'),
        'user_name' => array('name' => 'user_name', 'type' => 'xsd:string'),
        'first_name' => array('name' => 'first_name', 'type' => 'xsd:string'),
        'last_name' => array('name' => 'last_name', 'type' => 'xsd:string'),
        'department' => array('name' => 'department', 'type' => 'xsd:string'),
        'id' => array('name' => 'id', 'type' => 'xsd:string'),
        'title' => array('name' => 'title', 'type' => 'xsd:string'),
    )
);

$server->wsdl->addComplexType(
    'user_detail_array',
    'complexType',
    'array',
    '',
    'SOAP-ENC:Array',
    array(),
    array(
        array('ref' => 'SOAP-ENC:arrayType', 'wsdl:arrayType' => 'tns:user_detail[]')
    ),
    'tns:user_detail'
);


$server->register(
    'create_session',
    array('user_name' => 'xsd:string', 'password' => 'xsd:string'),
    array('return' => 'xsd:string'),
    $NAMESPACE
);

$server->register(
    'end_session',
    array('user_name' => 'xsd:string'),
    array('return' => 'xsd:string'),
    $NAMESPACE
);

$server->register(
    'contact_by_email',
    array('user_name' => 'xsd:string', 'password' => 'xsd:string', 'email_address' => 'xsd:string'),
    array('return' => 'tns:contact_detail_array'),
    $NAMESPACE
);

$server->register(
    'get_contact_relationships',
    array('user_name' => 'xsd:string', 'password' => 'xsd:string', 'id' => 'xsd:string'),
    array('return' => 'tns:contact_detail_array'),
    $NAMESPACE
);

$server->register(
    'user_list',
    array('user_name' => 'xsd:string', 'password' => 'xsd:string'),
    array('return' => 'tns:user_detail_array'),
    $NAMESPACE
);

$server->register(
    'search',
    array('user_name' => 'xsd:string', 'password' => 'xsd:string', 'name' => 'xsd:string'),
    array('return' => 'tns:contact_detail_array'),
    $NAMESPACE
);

$server->register(
    'track_email',
    array(
        'user_name' => 'xsd:string',
        'password' => 'xsd:string',
        'parent_id' => 'xsd:string',
        'contact_ids' => 'xsd:string',
        'date_sent_received' => 'xsd:date',
        'email_subject' => 'xsd:string',
        'email_body' => 'xsd:string'
    ),
    array('return' => 'xsd:string'),
    $NAMESPACE
);

$server->register(
    'create_contact',
    array(
        'user_name' => 'xsd:string',
        'password' => 'xsd:string',
        'first_name' => 'xsd:string',
        'last_name' => 'xsd:string',
        'email_address' => 'xsd:string'
    ),
    array('return' => 'xsd:string'),
    $NAMESPACE
);
$server->register(
    'create_lead',
    array(
        'user_name' => 'xsd:string',
        'password' => 'xsd:string',
        'first_name' => 'xsd:string',
        'last_name' => 'xsd:string',
        'email_address' => 'xsd:string'
    ),
    array('return' => 'xsd:string'),
    $NAMESPACE
);
$server->register(
    'create_account',
    array(
        'user_name' => 'xsd:string',
        'password' => 'xsd:string',
        'name' => 'xsd:string',
        'phone' => 'xsd:string',
        'website' => 'xsd:string'
    ),
    array('return' => 'xsd:string'),
    $NAMESPACE
);


$server->register(
    'create_case',
    array('user_name' => 'xsd:string', 'password' => 'xsd:string', 'name' => 'xsd:string'),
    array('return' => 'xsd:string'),
    $NAMESPACE
);
/**
 * Create a new session.  This method is required before calling any other functions.
 *
 * @param string $user_name -- the user name for the session
 * @param string $password -- MD5 of user password
 * @return "Success" if the session is created
 * @return "Failed" if the session creation failed.
 */
function create_session($user_name, $password)
{
    if (validate_user($user_name, $password)) {
        return "Success";
    }

    return "Failed";
}

/**
 * End a session.  This method will end the SOAP session.
 *
 * @param string $user_name -- the user name for the session
 * @return "Success" if the session is destroyed
 * @return "Failed" if the session destruction failed.
 */
function end_session($user_name)
{
    // get around optimizer warning
    $user_name = $user_name;

    return "Success";
}

/**
 * Validate the user session based on user name and password hash.
 *
 * @param string $user_name -- The user name to create a session for
 * @param string $password -- The MD5 sum of the user's password
 * @return true -- If the session is created
 * @return false -- If the session is not created
 */
function validate_user($user_name, $password)
{
    global $server, $current_user, $sugar_config, $system_config;
    $user = BeanFactory::newBean('Users');
    $user->user_name = $user_name;
    $system_config = BeanFactory::newBean('Administration');
    $system_config->retrieveSettings('system');
    $authController = new AuthenticationController();
    // Check to see if the user name and password are consistent.
    if ($user->authenticate_user($password)) {
        // we also need to set the current_user.
        $user->retrieve($user->id);
        $current_user = $user;
        login_success();

        return true;
    } else {
        if (function_exists('openssl_decrypt')) {
            $password = decrypt_string($password);
            if ($authController->login($user_name, $password) && isset($_SESSION['authenticated_user_id'])) {
                $user->retrieve($_SESSION['authenticated_user_id']);
                $current_user = $user;
                login_success();

                return true;
            }
        } else {
            $GLOBALS['log']->fatal("SECURITY: failed attempted login for $user_name using SOAP api");
            $server->setError("Invalid username and/or password");

            return false;
        }
    }
}

/**
 * Internal: When building a response to the plug-in for Microsoft Outlook, find
 * all contacts that match the email address that was provided.
 *
 * @param array by ref $output_list -- The list of matching beans.  New contacts that match
 *   the email address are appended to the $output_list
 * @param string $email_address -- an email address to search for
 * @param Contact $seed_contact -- A template SugarBean.  This is a blank Contact
 * @param ID $msi_id -- Index Count
 */
function add_contacts_matching_email_address(&$output_list, $email_address, &$seed_contact, &$msi_id)
{
    // escape the email address
    $safe_email_address = addslashes($email_address);
    global $current_user;

    // Verify that the user has permission to see Contact list views
    if (!$seed_contact->ACLAccess('ListView')) {
        return;
    }

    $contactList = $seed_contact->emailAddress->getBeansByEmailAddress($safe_email_address);
    // create a return array of names and email addresses.
    foreach ($contactList as $contact) {
        if (!is_a($contact, 'Contact')) {
            continue;
        }

        $output_list[] = array(
            "name1" => $contact->first_name,
            "name2" => $contact->last_name,
            "association" => $contact->account_name,
            "type" => 'Contact',
            "id" => $contact->id,
            "msi_id" => $msi_id,
            "email_address" => $contact->email1
        );

        $accounts = $contact->get_linked_beans('accounts', 'Account');
        foreach ($accounts as $account) {
            $output_list[] = get_account_array($account, $msi_id);
        }


        $cases = $contact->get_linked_beans('cases', 'aCase');
        foreach ($cases as $case) {
            $output_list[] = get_case_array($case, $msi_id);
        }

        $bugs = $contact->get_linked_beans('bugs', 'Bug');
        foreach ($bugs as $bug) {
            $output_list[] = get_bean_array($bug, $msi_id, 'Bug');
        }

        $projects = $contact->get_linked_beans('project', 'Project');
        foreach ($projects as $project) {
            $output_list[] = get_bean_array($project, $msi_id, 'Project');
        }

        $msi_id = $msi_id + 1;
    }
}

/**
 * Internal: Add Leads that match the specified email address to the result array
 *
 * @param Array $output_list -- List of matching detail records
 * @param String $email_address -- Email address
 * @param Bean $seed_lead -- Seed Lead Bean
 * @param int $msi_id -- output array offset.
 */
function add_leads_matching_email_address(&$output_list, $email_address, &$seed_lead, &$msi_id)
{
    $safe_email_address = DBManagerFactory::getInstance()->quote($email_address);
    if (!$seed_lead->ACLAccess('ListView')) {
        return;
    }

    $leadList = $seed_lead->emailAddress->getBeansByEmailAddress($safe_email_address);

    // create a return array of names and email addresses.
    foreach ($leadList as $lead) {
        if (!is_a($lead, 'Lead')) {
            continue;
        }

        $output_list[] = array(
            "name1" => $lead->first_name,
            "name2" => $lead->last_name,
            "association" => $lead->account_name,
            "type" => 'Lead',
            "id" => $lead->id,
            "msi_id" => $msi_id,
            "email_address" => $lead->email1
        );

        $msi_id = $msi_id + 1;
    }
}

/**
 * Return a list of modules related to the specified contact record
 *
 * This function does not require a session be created first.
 *
 * @param string $user_name -- User name to authenticate with
 * @param string $password -- MD5 of the user password
 * @param string $id -- the id of the record
 * @return contact detail array along with associated objects.
 */
function get_contact_relationships($user_name, $password, $id)
{
    if (!validate_user($user_name, $password)) {
        return array();
    }

    $seed_contact = BeanFactory::newBean('Contacts');
    // Verify that the user has permission to see Contact list views
    if (!$seed_contact->ACLAccess('ListView')) {
        return;
    }

    $msi_id = 1;
    $seed_contact->retrieve($id);

    $output_list[] = array(
        "name1" => $seed_contact->first_name,
        "name2" => $seed_contact->last_name,
        "association" => $seed_contact->account_name,
        "type" => 'Contact',
        "id" => $seed_contact->id,
        "msi_id" => $msi_id,
        "email_address" => $seed_contact->email1
    );

    $accounts = $seed_contact->get_linked_beans('accounts', 'Account');
    foreach ($accounts as $account) {
        $output_list[] = get_account_array($account, $msi_id);
    }


    $cases = $seed_contact->get_linked_beans('cases', 'aCase');
    foreach ($cases as $case) {
        $output_list[] = get_case_array($case, $msi_id);
    }

    $bugs = $seed_contact->get_linked_beans('bugs', 'Bug');
    foreach ($bugs as $bug) {
        $output_list[] = get_bean_array($bug, $msi_id, 'Bug');
    }

    $projects = $seed_contact->get_linked_beans('project', 'Project');
    foreach ($projects as $project) {
        $output_list[] = get_bean_array($project, $msi_id, 'Project');
    }

    return $output_list;
}

// Define a global current user
$current_user = null;

/**
 * Return a list of contact and lead detail records based on a single email
 * address or a  list of email addresses separated by '; '.
 *
 * This function does not require a session be created first.
 *
 * @param string $user_name -- User name to authenticate with
 * @param string $password -- MD5 of the user password
 * @param string $email_address -- Single email address or '; ' separated list of email addresses (e.x "test@example.com; test2@example.com"
 * @return contact detail array along with associated objects.
 */
function contact_by_email($user_name, $password, $email_address)
{
    if (!validate_user($user_name, $password)) {
        return array();
    }

    $seed_contact = BeanFactory::newBean('Contacts');
    $seed_lead = BeanFactory::newBean('Leads');
    $output_list = array();
    $email_address_list = explode("; ", $email_address);

    // remove duplicate email addresses
    $non_duplicate_email_address_list = array();
    foreach ($email_address_list as $single_address) {
        // Check to see if the current address is a match of an existing address
        $found_match = false;
        foreach ($non_duplicate_email_address_list as $non_dupe_single) {
            if (strtolower($single_address) == $non_dupe_single) {
                $found_match = true;
                break;
            }
        }

        if ($found_match == false) {
            $non_duplicate_email_address_list[] = strtolower($single_address);
        }
    }

    // now copy over the non-duplicated list as the original list.
    $email_address_list = $non_duplicate_email_address_list;

    // Track the msi_id
    $msi_id = 1;

    foreach ($email_address_list as $single_address) {
        // verify that contacts can be listed
        if ($seed_contact->ACLAccess('ListView')) {
            add_contacts_matching_email_address($output_list, $single_address, $seed_contact, $msi_id);
        }
        // verify that leads can be listed
        if ($seed_lead->ACLAccess('ListView')) {
            add_leads_matching_email_address($output_list, $single_address, $seed_lead, $msi_id);
        }
    }

    return $output_list;
}

/**
 * Internal: convert a bean into an array
 *
 * @param Bean $bean -- The bean to convert
 * @param int $msi_id -- Russult array index
 * @return An associated array containing the detail fields.
 */
function get_contact_array($contact, $msi_id = '0')
{
    $contact->emailAddress->handleLegacyRetrieve($contact);

    return array(
        "name1" => $contact->first_name,
        "name2" => $contact->last_name,
        "association" => $contact->account_name,
        "type" => 'Contact',
        "id" => $contact->id,
        "msi_id" => $msi_id,
        "email_address" => $contact->email1
    );
}

/**
 * Internal: Convert a user into an array
 *
 * @param User $user -- The user to convert
 * @return An associated array containing the detail fields.
 */
function get_user_list_array($user)
{
    return array(
        'email_address' => $user->email1,
        'user_name' => $user->user_name,
        'first_name' => $user->first_name,
        'last_name' => $user->last_name,
        'department' => $user->department,
        'id' => $user->id,
        'title' => $user->title
    );
}

/**
 * Get a full user list.
 *
 * This function does not require a session be created first.
 *
 * @param string $user -- user name for validation
 * @param password $password -- MD5 hash of the user password for validation
 * @return User Array -- An array of user detail records
 */
function user_list($user, $password)
{
    if (!validate_user($user, $password)) {
        return array();
    }

    $seed_user = BeanFactory::newBean('Users');
    $output_list = array();
    if (!$seed_user->ACLAccess('ListView')) {
        return $output_list;
    }
    $userList = $seed_user->get_full_list();


    foreach ($userList as $user) {
        $output_list[] = get_user_list_array($user);
    }

    return $output_list;
}

/**
 * Internal: Search for contacts based on the specified name and where clause.
 * Currently only the name is used.
 *
 * @param string $name -- Name to search for.
 * @param string $where -- Where clause defaults to ''
 * @param int $msi_id -- Response array index
 * @return array -- Returns a list of contacts that have the provided name.
 */
function contact_by_search($name, $where = '', $msi_id = '0')
{
    $seed_contact = BeanFactory::newBean('Contacts');
    if ($where == '') {
        $where = $seed_contact->build_generic_where_clause($name);
    }
    if (!$seed_contact->ACLAccess('ListView')) {
        return array();
    }
    $response = $seed_contact->get_list("last_name, first_name", $where, 0);
    $contactList = $response['list'];

    $output_list = array();

    // create a return array of names and email addresses.
    foreach ($contactList as $contact) {
        $output_list[] = get_contact_array($contact, $msi_id);
    }

    return $output_list;
}

/**
 * Internal: convert a bean into an array
 *
 * @param Bean $bean -- The bean to convert
 * @param int $msi_id -- Russult array index
 * @return An associated array containing the detail fields.
 */
function get_lead_array($lead, $msi_id = '0')
{
    $lead->emailAddress->handleLegacyRetrieve($lead);

    return array(
        "name1" => $lead->first_name,
        "name2" => $lead->last_name,
        "association" => $lead->account_name,
        "type" => 'Lead',
        "id" => $lead->id,
        "msi_id" => $msi_id,
        "email_address" => $lead->email1
    );
}

function lead_by_search($name, $where = '', $msi_id = '0')
{
    $seed_lead = BeanFactory::newBean('Leads');
    if ($where == '') {
        $where = $seed_lead->build_generic_where_clause($name);
    }
    if (!$seed_lead->ACLAccess('ListView')) {
        return array();
    }
    $response = $seed_lead->get_list("last_name, first_name", $where, 0);
    $lead_list = $response['list'];

    $output_list = array();

    // create a return array of names and email addresses.
    foreach ($lead_list as $lead) {
        $output_list[] = get_lead_array($lead, $msi_id);
    }

    return $output_list;
}

/**
 * Internal: convert a bean into an array
 *
 * @param Bean $bean -- The bean to convert
 * @param int $msi_id -- Russult array index
 * @return An associated array containing the detail fields.
 */
function get_account_array($account, $msi_id)
{
    return array(
        "name1" => '',
        "name2" => $account->name,
        "association" => $account->billing_address_city,
        "type" => 'Account',
        "id" => $account->id,
        "msi_id" => $msi_id,
        "email_address" => $account->email1
    );
}

function account_by_search($name, $where = '', $msi_id = '0')
{
    $seed_account = BeanFactory::newBean('Accounts');
    if (!$seed_account->ACLAccess('ListView')) {
        return array();
    }
    if ($where == '') {
        $where = $seed_account->build_generic_where_clause($name);
    }
    $response = $seed_account->get_list("name", $where, 0);
    $accountList = $response['list'];

    $output_list = array();

    // create a return array of names and email addresses.
    foreach ($accountList as $account) {
        $output_list[] = get_account_array($account, $msi_id);
    }

    return $output_list;
}



/**
 * Internal: convert a bean into an array
 *
 * @param Bean $bean -- The bean to convert
 * @param int $msi_id -- Russult array index
 * @return An associated array containing the detail fields.
 */
function get_bean_array($value, $msi_id, $type)
{
    return array(
        "name1" => '',
        "name2" => $value->get_summary_text(),
        "association" => '',
        "type" => $type,
        "id" => $value->id,
        "msi_id" => $msi_id,
        "email_address" => ''
    );
}

/**
 * Internal: convert a bean into an array
 *
 * @param Bean $bean -- The bean to convert
 * @param int $msi_id -- Russult array index
 * @return An associated array containing the detail fields.
 */
function get_case_array($value, $msi_id)
{
    return array(
        "name1" => '',
        "name2" => $value->get_summary_text(),
        "association" => $value->account_name,
        "type" => 'Case',
        "id" => $value->id,
        "msi_id" => $msi_id,
        "email_address" => ''
    );
}

function bug_by_search($name, $where = '', $msi_id = '0')
{
    $seed = BeanFactory::newBean('Bugs');
    if (!$seed->ACLAccess('ListView')) {
        return array();
    }
    if ($where == '') {
        $where = $seed->build_generic_where_clause($name);
    }
    $response = $seed->get_list("name", $where, 0);
    $list = $response['list'];

    $output_list = array();

    // create a return array of names and email addresses.
    foreach ($list as $value) {
        $output_list[] = get_bean_array($value, $msi_id, 'Bug');
    }

    return $output_list;
}

function case_by_search($name, $where = '', $msi_id = '0')
{
    $seed = BeanFactory::newBean('Cases');
    if (!$seed->ACLAccess('ListView')) {
        return array();
    }
    if ($where == '') {
        $where = $seed->build_generic_where_clause($name);
    }
    $response = $seed->get_list("name", $where, 0);
    $list = $response['list'];

    $output_list = array();

    // create a return array of names and email addresses.
    foreach ($list as $value) {
        $output_list[] = get_case_array($value, $msi_id);
    }

    return $output_list;
}

/**
 * Record and email message and associated it with the specified parent bean and contact ids.
 *
 * This function does not require a session be created first.
 *
 * @param string $user_name -- Name of the user to authenticate
 * @param string $password -- MD5 hash of the user password for authentication
 * @param id $parent_id -- [optional] The parent record to link the email to.
 * @param unknown_type $contact_ids
 * @param string $date_sent_received -- Date/time the email was sent in Visual Basic Date format. (e.g. '7/22/2004 9:36:31 AM')
 * @param string $email_subject -- The subject of the email
 * @param string $email_body -- The body of the email
 * @return "Invalid username and/or password"
 * @return -1 If the authenticated user does not have ACL access to save Email.
 */
function track_email($user_name, $password, $parent_id, $contact_ids, $date_sent_received, $email_subject, $email_body)
{
    if (!validate_user($user_name, $password)) {
        return "Invalid username and/or password";
    }
    global $current_user;

    $GLOBALS['log']->info("In track email: username: $user_name contacts: $contact_ids date_sent_received: $date_sent_received");

    // translate date sent from VB format 7/22/2004 9:36:31 AM
    // to yyyy-mm-dd 9:36:31 AM

    $date_sent_received = preg_replace("@([0-9]*)/([0-9]*)/([0-9]*)( .*$)@", "\\3-\\1-\\2\\4", $date_sent_received);


    $seed_user = BeanFactory::newBean('Users');

    $user_id = $seed_user->retrieve_user_id($user_name);
    $seed_user->retrieve($user_id);
    $current_user = $seed_user;


    $email = BeanFactory::newBean('Emails');
    if (!$email->ACLAccess('Save')) {
        return -1;
    }
    $email->description = $email_body;
    $email->name = $email_subject;
    $email->user_id = $user_id;
    $email->assigned_user_id = $user_id;
    $email->assigned_user_name = $user_name;
    $email->date_start = $date_sent_received;

    // Save one copy of the email message
    $parent_id_list = explode(";", $parent_id);
    $parent_id = explode(':', $parent_id_list[0]);

    // Having a parent object is optional.  If it is set, then associate it.
    if (isset($parent_id[0]) && isset($parent_id[1])) {
        $email->parent_type = $parent_id[0];
        $email->parent_id = $parent_id[1];
    }

    $email->save();
    // for each contact, add a link between the contact and the email message
    $id_list = explode(";", $contact_ids);

    foreach ($id_list as $id) {
        if (!empty($id)) {
            $email->set_emails_contact_invitee_relationship($email->id, DBManagerFactory::getInstance()->quote($id));
        }
    }

    return "Succeeded";
}

function create_contact($user_name, $password, $first_name, $last_name, $email_address)
{
    if (!validate_user($user_name, $password)) {
        return 0;
    }


    $seed_user = BeanFactory::newBean('Users');
    $user_id = $seed_user->retrieve_user_id($user_name);
    $seed_user->retrieve($user_id);


    $contact = BeanFactory::newBean('Contacts');
    if (!$contact->ACLAccess('Save')) {
        return -1;
    }
    $contact->first_name = $first_name;
    $contact->last_name = $last_name;
    $contact->email1 = $email_address;
    $contact->assigned_user_id = $user_id;
    $contact->assigned_user_name = $user_name;

    return $contact->save();
}

function create_lead($user_name, $password, $first_name, $last_name, $email_address)
{
    if (!validate_user($user_name, $password)) {
        return 0;
    }

    //todo make the activity body not be html encoded


    $seed_user = BeanFactory::newBean('Users');
    $user_id = $seed_user->retrieve_user_id($user_name);


    $lead = BeanFactory::newBean('Leads');
    if (!$lead->ACLAccess('Save')) {
        return -1;
    }
    $lead->first_name = $first_name;
    $lead->last_name = $last_name;
    $lead->email1 = $email_address;
    $lead->assigned_user_id = $user_id;
    $lead->assigned_user_name = $user_name;

    return $lead->save();
}

function create_account($user_name, $password, $name, $phone, $website)
{
    if (!validate_user($user_name, $password)) {
        return 0;
    }

    //todo make the activity body not be html encoded


    $seed_user = BeanFactory::newBean('Users');
    $user_id = $seed_user->retrieve_user_id($user_name);
    $account = BeanFactory::newBean('Accounts');
    if (!$account->ACLAccess('Save')) {
        return -1;
    }
    $account->name = $name;
    $account->phone_office = $phone;
    $account->website = $website;
    $account->assigned_user_id = $user_id;
    $account->assigned_user_name = $user_name;
    $account->save();

    return $account->id;
}

function create_case($user_name, $password, $name)
{
    if (!validate_user($user_name, $password)) {
        return 0;
    }

    //todo make the activity body not be html encoded


    $seed_user = BeanFactory::newBean('Users');
    $user_id = $seed_user->retrieve_user_id($user_name);
    $case = BeanFactory::newBean('Cases');
    if (!$case->ACLAccess('Save')) {
        return -1;
    }
    $case->assigned_user_id = $user_id;
    $case->assigned_user_name = $user_name;
    $case->name = $name;

    return $case->save();
}


function search($user_name, $password, $name)
{
    if (!validate_user($user_name, $password)) {
        return array();
    }
    $name_list = explode("; ", $name);
    $list = array();
    foreach ($name_list as $single_name) {
        $list = array_merge($list, contact_by_search($single_name));
        $list = array_merge($list, lead_by_search($single_name));
        $list = array_merge($list, account_by_search($single_name));
        $list = array_merge($list, case_by_search($single_name));
        $list = array_merge($list, bug_by_search($single_name));
    }

    return $list;
}
