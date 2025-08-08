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

use SuiteCRM\Utility\SuiteValidator;

require_once('include/formbase.php');
require_once('modules/Leads/LeadFormBase.php');



global $app_strings, $app_list_strings, $sugar_config, $timedate, $current_user;

$mod_strings = return_module_language($sugar_config['default_language'], 'Leads');

$app_list_strings['record_type_module'] = array('Contact'=>'Contacts', 'Account'=>'Accounts', 'Case'=>'Cases', 'Note'=>'Notes', 'Call'=>'Calls', 'Email'=>'Emails', 'Meeting'=>'Meetings', 'Task'=>'Tasks', 'Lead'=>'Leads','Bug'=>'Bugs',

);

/**
 * To make your changes upgrade safe create a file called leadCapture_override.php and place the changes there
 */
$users = array(
    'PUT A RANDOM KEY FROM THE WEBSITE HERE' => array('name'=>'PUT THE USER_NAME HERE', 'pass'=>'PUT THE USER_HASH FOR THE RESPECTIVE USER HERE'),
);

if (isset($_POST['campaign_id']) && !empty($_POST['campaign_id'])) {
    //adding the client ip address
    $_POST['client_id_address'] = query_client_ip();
    $campaign_id=$_POST['campaign_id'];
    $campaign = BeanFactory::newBean('Campaigns');
    $campaign_id = $campaign->db->quote($_POST['campaign_id']);
    $isValidator = new SuiteValidator();
    if (!$isValidator->isValidId($campaign_id)) {
        throw new RuntimeException('Invalid ID requested in Lead Capture');
    }
    $camp_query  = "select name,id from campaigns where id='$campaign_id'";
    $camp_query .= " and deleted=0";
    $camp_result=$campaign->db->query($camp_query);
    $camp_data = $campaign->db->fetchByAssoc($camp_result);
    // Bug 41292 - have to select marketing_id for new lead
    $db = DBManagerFactory::getInstance();
    $marketing = BeanFactory::newBean('EmailMarketing');
    $marketing_query = $marketing->create_new_list_query(
        'date_start desc, date_modified desc',
        "campaign_id = '{$campaign_id}' and status = 'active' and date_start < " . $db->convert('', 'today'),
        array('id')
        );
    $marketing_result = $db->limitQuery($marketing_query, 0, 1, true);
    $marketing_data = $db->fetchByAssoc($marketing_result);
    // .Bug 41292
    if (isset($_REQUEST['assigned_user_id']) && !empty($_REQUEST['assigned_user_id'])) {
        $current_user = BeanFactory::newBean('Users');
        $current_user->retrieve($_REQUEST['assigned_user_id']);
    }

    if (isset($camp_data) && $camp_data != null) {
        $leadForm = new LeadFormBase();
        $lead = BeanFactory::newBean('Leads');
        $prefix = '';
        if (!empty($_POST['prefix'])) {
            $prefix = $_POST['prefix'];
        }

        if (empty($lead->id)) {
            $lead->id = create_guid();
            $lead->new_with_id = true;
        }
        $GLOBALS['check_notify'] = true;

        //bug: 47574 - make sure, that webtolead_email1 field has same required attribute as email1 field
        if (isset($lead->required_fields['email1'])) {
            $lead->required_fields['webtolead_email1'] = $lead->required_fields['email1'];
        }
            
        //bug: 42398 - have to unset the id from the required_fields since it is not populated in the $_POST
        unset($lead->required_fields['id']);
        unset($lead->required_fields['team_name']);
        unset($lead->required_fields['team_count']);

        // Bug #52563 : Web to Lead form redirects to Sugar when duplicate detected
        // prevent duplicates check
        $_POST['dup_checked'] = true;

        // checkRequired needs a major overhaul before it works for web to lead forms.
        $lead = $leadForm->handleSave($prefix, false, false, false, $lead);
            
        if (!empty($lead)) {
                
                //create campaign log
            $camplog = BeanFactory::newBean('CampaignLog');
            $camplog->campaign_id  = $campaign_id;
            $camplog->related_id   = $lead->id;
            $camplog->related_type = $lead->module_dir;
            $camplog->activity_type = "lead";
            $camplog->target_type = $lead->module_dir;
            $campaign_log->activity_date=$timedate->now();
            $camplog->target_id    = $lead->id;
            if (isset($marketing_data['id'])) {
                $camplog->marketing_id = $marketing_data['id'];
            }
            $camplog->save();

            //link campaignlog and lead

            if (isset($_POST['email1']) && $_POST['email1'] != null) {
                $lead->email1 = $_POST['email1'];
            }
            //in case there are old forms used webtolead_email1
            elseif (isset($_POST['webtolead_email1']) && $_POST['webtolead_email1'] != null) {
                $lead->email1 = $_POST['webtolead_email1'];
            }
                
            if (isset($_POST['email2']) && $_POST['email2'] != null) {
                $lead->email2 = $_POST['email2'];
            }
            //in case there are old forms used webtolead_email2
            elseif (isset($_POST['webtolead_email2']) && $_POST['webtolead_email2'] != null) {
                $lead->email2 = $_POST['webtolead_email2'];
            }
                
            $lead->load_relationship('campaigns');
            $lead->campaigns->add($camplog->id);
            if (!empty($GLOBALS['check_notify'])) {
                $lead->save($GLOBALS['check_notify']);
            } else {
                $lead->save(false);
            }
        }

        //in case there are forms out there still using email_opt_out
        if (isset($_POST['webtolead_email_opt_out']) || isset($_POST['email_opt_out'])) {
            if (isset($lead->email1) && !empty($lead->email1)) {
                $sea = new SugarEmailAddress();
                $sea->AddUpdateEmailAddress($lead->email1, 0, 1);
            }
            if (isset($lead->email2) && !empty($lead->email2)) {
                $sea = new SugarEmailAddress();
                $sea->AddUpdateEmailAddress($lead->email2, 0, 1);
            }
        }
        if (isset($_POST['redirect_url']) && !empty($_POST['redirect_url'])) {
            // Get the redirect url, and make sure the query string is not too long
            $redirect_url = $_POST['redirect_url'];
            $query_string = '';
            $first_char = '&';
            if (strpos($redirect_url, '?') === false) {
                $first_char = '?';
            }
            $first_iteration = true;
            $get_and_post = array_merge($_GET, $_POST);
            foreach ($get_and_post as $param => $value) {
                if ($param == 'redirect_url' && $param == 'submit') {
                    continue;
                }
                    
                if ($first_iteration) {
                    $first_iteration = false;
                    $query_string .= $first_char;
                } else {
                    $query_string .= "&";
                }
                $query_string .= "{$param}=".urlencode($value);
            }
            if (empty($lead)) {
                if ($first_iteration) {
                    $query_string .= $first_char;
                } else {
                    $query_string .= "&";
                }
                $query_string .= "error=1";
            }
                
            $redirect_url = $redirect_url.$query_string;


            // Check if the headers have been sent, or if the redirect url is greater than 2083 characters (IE max URL length)
            //   and use a javascript form submission if that is the case.
            if (headers_sent() || strlen($redirect_url) > 2083) {
                echo '<html ' . get_language_header() . '><head><title>SugarCRM</title></head><body>';
                echo '<form name="redirect" action="' .$_POST['redirect_url']. '" method="GET">';
    
                foreach ($_POST as $param => $value) {
                    if ($param != 'redirect_url' ||$param != 'submit') {
                        echo '<input type="hidden" name="'.$param.'" value="'.$value.'">';
                    }
                }
                if (empty($lead)) {
                    echo '<input type="hidden" name="error" value="1">';
                }
                echo '</form><script language="javascript" type="text/javascript">document.redirect.submit();</script>';
                echo '</body></html>';
            } else {
                $header_URL = "Location: {$redirect_url}";

                SugarApplication::headerRedirect($header_URL);

                die();
            }
        } else {
            echo $mod_strings['LBL_THANKS_FOR_SUBMITTING'];
        }
        sugar_cleanup();
        // die to keep code from running into redirect case below
        die();
    } else {
        echo $mod_strings['LBL_SERVER_IS_CURRENTLY_UNAVAILABLE'];
    }
}

if (!empty($_POST['redirect'])) {
    if (headers_sent()) {
        echo '<html ' . get_language_header() . '><head><title>SugarCRM</title></head><body>';
        echo '<form name="redirect" action="' .$_POST['redirect']. '" method="GET">';
        echo '</form><script language="javascript" type="text/javascript">document.redirect.submit();</script>';
        echo '</body></html>';
    } else {
        $header_URL = "Location: {$_POST['redirect']}";
        SugarApplication::headerRedirect($header_URL);
        die();
    }
}

echo $mod_strings['LBL_SERVER_IS_CURRENTLY_UNAVAILABLE'];
