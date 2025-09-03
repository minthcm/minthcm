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

/*********************************************************************************

 * Description:  Business Card Wizard
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________..
 ********************************************************************************/
 
global $app_strings;
global $app_list_strings;
global $locale;

global $theme;
$error_msg = '';
global $current_language;
$mod_strings = return_module_language($current_language, 'Contacts');
echo getClassicModuleTitle($mod_strings['LBL_MODULE_NAME'], array($mod_strings['LBL_MODULE_NAME'],$mod_strings['LBL_BUSINESSCARD']), true); 
$xtpl=new XTemplate ('modules/Contacts/BusinessCard.html');
$xtpl->assign("MOD", $mod_strings);
$xtpl->assign("APP", $app_strings);
$xtpl->assign("PRINT_URL", "index.php?".$GLOBALS['request_string']);

$xtpl->assign("HEADER", $mod_strings['LBL_ADD_BUSINESSCARD']);

$xtpl->assign("MODULE", $_REQUEST['module']);
if ($error_msg != '')
{
	$xtpl->assign("ERROR", $error_msg);
	$xtpl->parse("main.error");
}

if(isset($_POST['handle']) && $_POST['handle'] == 'Save'){
	
	require_once('modules/Contacts/ContactFormBase.php');
	$contactForm = new ContactFormBase();
	
	if(!isset($_POST['selectedContact']) && !isset($_POST['ContinueContact'])){
		$duplicateContacts = $contactForm->checkForDuplicates('Contacts');
		if(isset($duplicateContacts)){
			$formBody = $contactForm->buildTableForm($duplicateContacts);
			$xtpl->assign('FORMBODY', $formBody);
			$xtpl->parse('main.formnoborder');
			$xtpl->parse('main');
			$xtpl->out('main');
			return;
		}
	}
	
	if(!empty($_POST['selectedContact'])){
		$contact = new Contact();
		$contact->retrieve($_POST['selectedContact']);	
	}else{
		$contact= $contactForm->handleSave('Contacts',false, false);
	}

	require_once('modules/Notes/NoteFormBase.php');

	$noteForm = new NoteFormBase();
	if(isset($contact))
		$_POST['ContactNotesparent_type'] = "Contacts";
		$_POST['ContactNotesparent_id'] = $contact->id;
		$contactnote= $noteForm->handleSave('ContactNotes',false, false);
	if(isset($_POST['newappointment']) && $_POST['newappointment']=='on' ){	
	if(isset($_POST['appointment']) && $_POST['appointment'] == 'Meeting'){
		require_once('modules/Meetings/MeetingFormBase.php');
		$meetingForm = new MeetingFormBase();
		$meeting= $meetingForm->handleSave('Appointments',false, false);
	}else{
		require_once('modules/Calls/CallFormBase.php');
		$callForm = new CallFormBase();
		$call= $callForm->handleSave('Appointments',false, false);	
	}
	}
	
	if(isset($call)){
		if(isset($contact)) {
			$call->load_relationship('contacts');
			$call->contacts->add($contact->id);
		}
	}
	if(isset($meeting)){
		if(isset($contact)) {
			$meeting->load_relationship('contacts');
			$meeting->contacts->add($contact->id);
		}
	}
		if(isset($contact)) {
		if(isset($contactnote)){
			$contact->load_relationship('notes');
			$contact->notes->add($contactnote->id);
		}				
	}
	
	if(isset($contact)){
		$contact->track_view($current_user->id, 'Contacts');
		if(isset($_POST['selectedContact']) && $_POST['selectedContact'] == $contact->id){
			$xtpl->assign('ROWVALUE', "<LI>".$mod_strings['LBL_EXISTING_CONTACT']." - <a href='index.php?action=DetailView&module=Contacts&record=".$contact->id."'>".$locale->getLocaleFormattedName($contact->first_name, $contact->last_name)."</a>" );
			$xtpl->parse('main.row');
		}else{
			
			$xtpl->assign('ROWVALUE', "<LI>".$mod_strings['LBL_CREATED_CONTACT']." - <a href='index.php?action=DetailView&module=Contacts&record=".$contact->id."'>".$locale->getLocaleFormattedName($contact->first_name, $contact->last_name)."</a>" );
			$xtpl->parse('main.row');
		}
	}
		
	if(isset($call)){
		$call->track_view($current_user->id, 'Calls');
		$xtpl->assign('ROWVALUE', "<LI>".$mod_strings['LBL_CREATED_CALL']. " - <a href='index.php?action=DetailView&module=Calls&record=".$call->id."'>".$call->name."</a>");	
		$xtpl->parse('main.row');
		}
	if(isset($meeting)){
		$meeting->track_view($current_user->id, 'Meetings');
		$xtpl->assign('ROWVALUE', "<LI>".$mod_strings['LBL_CREATED_MEETING']. " - <a href='index.php?action=DetailView&module=Calls&record=".$meeting->id."'>".$meeting->name."</a>");	
		$xtpl->parse('main.row');
		}
		$xtpl->assign('ROWVALUE',"&nbsp;");	
		$xtpl->parse('main.row');
		$xtpl->assign('ROWVALUE',"<a href='index.php?module=Contacts&action=BusinessCard'>{$mod_strings['LBL_ADDMORE_BUSINESSCARD']}</a>");	
	$xtpl->parse('main.row');
	$xtpl->parse('main');
	$xtpl->out('main');	
}
	
else{

//CONTACT
$xtpl->assign('FORMHEADER',$mod_strings['LNK_NEW_CONTACT']);
$xtpl->parse("main.startform");
$xtpl->parse("main.savebegin");
require_once('modules/Contacts/ContactFormBase.php');
$contactForm = new ContactFormBase();
$xtpl->assign('FORMBODY',$contactForm->getWideFormBody('Contacts', 'Contacts', 'BusinessCard', '', false));
$xtpl->assign('TABLECLASS', 'edit view');
$xtpl->assign('CLASS', 'dataLabel');
require_once('modules/Notes/NoteFormBase.php');
$noteForm = new NoteFormBase();
$postform = "<h5 class='dataLabel'><input class='checkbox' type='checkbox' name='newcontactnote' onclick='toggleDisplay(\"contactnote\");'> ${mod_strings['LNK_NEW_NOTE']}</h5>";
$postform .= '<div id="contactnote" style="display:none">'.$noteForm->getFormBody('ContactNotes','Notes','BusinessCard', 85).'</div>';

$xtpl->assign('POSTFORM',$postform);
$xtpl->parse("main.form");


$xtpl->assign('HEADER', $app_strings['LBL_RELATED_RECORDS']);
$xtpl->parse("main.hrrow");
$popup_request_data = array(
	'call_back_function' => 'set_return',
	'form_name' => 'BusinessCard',
	'field_to_name_array' => array(
		),
	);
	
$json = getJSONobj();
$encoded_contact_popup_request_data = $json->encode($popup_request_data);

//Appointment
$xtpl->assign('FORMHEADER',$mod_strings['LNK_NEW_APPOINTMENT']);
require_once('modules/Calls/CallFormBase.php');
$callForm = new CallFormBase();
$xtpl->assign('FORMBODY', "<input class='checkbox' type='checkbox' name='newappointment' onclick='toggleDisplay(\"newappointmentdiv\");'>&nbsp;".$mod_strings['LNK_NEW_APPOINTMENT']."<div id='newappointmentdiv' style='display:none'>".$callForm->getWideFormBody('Appointments', 'Calls',85));
$xtpl->assign('POSTFORM','');
$xtpl->parse("main.headlessform");
$xtpl->parse("main.saveend");
$xtpl->parse("main.endform");
$xtpl->parse("main");

$xtpl->out("main");

}
