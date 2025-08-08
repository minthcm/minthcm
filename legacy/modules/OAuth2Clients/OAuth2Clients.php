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

/**
 * Class OAuth2Clients
 */
class OAuth2Clients extends SugarBean
{
    /**
     * @var string
     */
    public $secret;

    /**
     * @var string
     */
    public $redirect_uri;

    /**
     * @var string
     */
    public $allowed_grant_type;

    /**
     * @var string
     */
    public $table_name = 'oauth2clients';

    /**
     * @var string
     */
    public $object_name = 'OAuth2Clients';

    /**
     * @var string
     */
    public $module_dir = 'OAuth2Clients';

    /**
     * @var bool
     */
    public $disable_row_level_security = true;

    /**
     * @see SugarBean::get_summary_text()
     */
    public function get_summary_text()
    {
        return (string)$this->name;
    }

    /**
     * @see SugarBean::save()
     *
     * @param bool $check_notify
     * @return string ID
     */
    public function save($check_notify = false)
    {
        if (!empty($_REQUEST['new_secret'])) {
            $this->secret = hash('sha256', $_REQUEST['new_secret']);
        }
        if (empty($this->duration_value)) { // MintHCM #159328
            $this->setDurationValue();
        }
        return parent::save();
    }

    private function setDurationValue()
    {
        if (empty($_REQUEST['duration_amount']) || empty($_REQUEST['duration_unit'])) {
            $this->duration_value = 60;
            $this->duration_amount = 1;
            $this->duration_unit = 'minute';
            return;
        }
        $amount = $_REQUEST['duration_amount'];
        $value = 1;
        switch ($_REQUEST['duration_unit']) {
            case 'month': $value = $amount * 30 * 24 * 60 * 60; break;
            case 'week': $value = $amount * 7 * 24 * 60 * 60; break;
            case 'day': $value = $amount * 24 * 60 * 60; break;
            case 'hour': $value = $amount * 60 * 60; break;
            case 'minute': $value = $amount * 60; break;
        }
        $this->duration_value = $value;
    }
}
