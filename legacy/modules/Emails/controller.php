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
    die('Not A Valid Entry Point');
}

include_once 'include/Exceptions/SugarControllerException.php';
require_once 'modules/Emails/EmailsController.php';

class CustomEmailsController extends EmailsController
{

    public function action_send()
    {

        global $current_user;
        global $app_strings;

        $request = $_REQUEST;

        $this->bean = $this->bean->populateBeanFromRequest($this->bean, $request);
        $inboundEmailAccount = new InboundEmail();
        $inboundEmailAccount->retrieve($_REQUEST['inbound_email_id']);

        if ($this->userIsAllowedToSendEmail($current_user, $inboundEmailAccount, $this->bean)) {
            $this->bean->save();

            $this->bean->handleMultipleFileAttachments();

            // parse and replace bean variables
            $this->bean = $this->replaceEmailVariables($this->bean, $request);

            if ($this->bean->send()) {
                $this->bean->status = 'sent';
                if (isset($this->bean->field_defs['date_sent_received'])) {
                    $this->bean->date_sent_received = gmdate('Y-m-d H:i:s');
                } elseif (isset($this->bean->field_defs['date_sent'])) {
                    $this->bean->date_sent = gmdate('Y-m-d H:i:s');
                }
                $this->bean->save();
            } else {
                // Don't save status if the email is a draft.
                // We need to ensure that drafts will still show
                // in the list view
                if ($this->bean->status !== 'draft') {
                    $this->bean->save();
                }
                $this->bean->status = 'send_error';
            }

            $this->view = 'sendemail';
        } else {
            $GLOBALS['log']->security(
                'User ' . $current_user->name .
                ' attempted to send an email using incorrect email account settings in' .
                ' which they do not have access to.'
            );

            $this->view = 'ajax';
            $response['errors'] = [
                'type' => get_class($this->bean),
                'id' => $this->bean->id,
                'title' => $app_strings['LBL_EMAIL_ERROR_SENDING'],
            ];
            echo json_encode($response);
        }
    }

}
