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

class SpendTimeNotifier
{
    protected $db;
    protected $sql;
    protected $spent_times_ids;
    protected $subject;
    protected $body_header;

    public function __construct($sql, $type)
    {
        $mod_strings = return_module_language('pl-PL', 'Schedulers');

        $this->sql = $sql;
        switch ($type) {
            case "times_without_schedules":
                $this->subject     = $mod_strings['LBL_SPENT_TIMES_WITHOUT_WORK_SCHEDULE_NOTIFICATION_SUBJECT'];
                $this->body_header = $mod_strings['LBL_SPENT_TIMES_WITHOUT_WORK_SCHEDULE_NOTIFICATION_BODY'];
                break;
            case "invalid_times":
                $this->subject     = $mod_strings['LBL_INVALID_SPENT_TIMES_NOTIFICATION_SUBJECT'];
                $this->body_header = $mod_strings['LBL_INVALID_SPENT_TIMES_NOTIFICATION_BODY'];
                break;
            case "times_work_schedule_other_users":
                $this->subject     = $mod_strings['LBL_FIND_SPENT_TIMES_ASSIGN_TO_DIFFERENT_USER_WORK_SCHEDULE_SUBJECT'];
                $this->body_header = $mod_strings['LBL_FIND_SPENT_TIMES_ASSIGN_TO_DIFFERENT_USER_WORK_SCHEDULE_BODY'];
                break;
            default:
                $this->subject     = $mod_strings['LBL_NOTIFIER_DEFAULT_SUBJECT'];
                $this->body_header = $mod_strings['LBL_NOTIFIER_DEFAULT_BODY'];
                break;
        }
        $this->db = DBManagerFactory::getInstance();
    }

    public function setSubject($subject)
    {
        $this->subject = $subject;
    }

    public function setBodyHeader($body_header)
    {
        $this->body_header = $body_header;
    }

    public function notifyIfInvalidTimeFound()
    {
        $this->findInvalidSpendTimes();

        if (count($this->spent_times_ids) > 0) {
            $body = $this->buildBody();
            $this->sendReport($body);
        }
    }

    protected function findInvalidSpendTimes()
    {
        $result                = $this->db->query($this->sql);
        $this->spent_times_ids = array();

        while ($row = $this->db->fetchByAssoc($result)) {
            $this->spent_times_ids[] = $row['id'];
        }
    }

    protected function sendReport($body)
    {
        require_once("include/SugarPHPMailer.php");
        include("custom/config/admin_emails.php");

        $mail = new SugarPHPMailer();
        if (!empty($admin_emails)) {
            foreach ($admin_emails as $email) {
                $mail->AddAddress($email);
            }

            $mail->setMailerForSystem();
            $mail->Body        = $body;
            $mail->Subject     = $this->subject;
            $mail->ContentType = 'text/html';
            $mail->prepForOutbound();
            if (!$mail->Send()) {
                $GLOBALS['log']->fatal("Email Reminder: error sending e-mail (method: {$mail->Mailer}), (error: {$mail->ErrorInfo})");
            }
        }
    }

    protected function buildBody()
    {
        $spent_time_links = "";

        foreach ($this->spent_times_ids as $id) {
            $spent_time_links .= "<li>".$this->getURLBasedOnId($id)."</li>";
        }

        $body = "<div>".$this->body_header."Lista czasów: <br /><ul>".$spent_time_links."</ul></div>";

        return $body;
    }

    protected function getURLBasedOnId($id)
    {
        global $sugar_config;

        $url = $sugar_config['site_url']."?module=SpentTime&action=DetailView&record=".$id;
        return "<a href='".$url."' target='_blank'>".$url."</a>";
    }
}