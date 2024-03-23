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

require_once 'modules/AppraisalItems/AppraisalItems.php';
require_once 'modules/Appraisals/SugarFeeds/AppraisalsFeed.php';
require_once 'modules/Appraisals/AppraisalTokenController.php';

class Appraisals extends Basic
{

    public $new_schema = true;
    public $module_dir = 'Appraisals';
    public $object_name = 'Appraisals';
    public $table_name = 'appraisals';
    public $importable = true;
    public $id;
    public $name;
    public $date_entered;
    public $date_modified;
    public $modified_user_id;
    public $modified_by_name;
    public $created_by;
    public $created_by_name;
    public $description;
    public $deleted;
    public $created_by_link;
    public $modified_user_link;
    public $assigned_user_id;
    public $assigned_user_name;
    public $assigned_user_link;
    public $SecurityGroups;

    public function bean_implements($interface)
    {
        $result = false;
        if ($interface === 'ACL') {
            $result = true;
        }
        return $result;
    }

    public function save($check_notify = false)
    {
        $new_evaluator = false;
        if ($this->fetched_row['evaluator_id'] != $this->evaluator_id) {
            $new_evaluator = true;
        }
        $id = parent::save($check_notify);
        if ($new_evaluator) {
            $appraisal_token_controller = new AppraisalTokenController();
            if (!empty($this->evaluator_id)) {

                $appraisal_token_controller->deactivateToken($this->evaluator_id, $this->id);

            }
            $appraisal_token_controller->generateToken($this->evaluator_id, $this->id);
        }

        return $id;
    }

    protected function postSave()
    {
        $af = new AppraisalsFeed();
        $af->pushFeed($this, null, null);
        AppraisalItems::parentSave($this);
    }

    public function mark_deleted($id)
    {
        AppraisalItems::parentDelete($this);
        parent::mark_deleted($id);
    }

}
