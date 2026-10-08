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

require_once('include/MVC/Controller/SugarController.php');

#[\AllowDynamicProperties]
class FilesController extends SugarController
{
    /**
     * Files-specific attachment authorization for #192537 / GHSA-hgmw-4wvp-mjq2.
     *
     * The base SugarController::action_deleteattachment() performs no ACL check of its own.
     * For Files this action is reachable two ways: (a) the "Remove" button on the uploadfile
     * field in Files' own EditView (legacy/include/SugarFields/Fields/File/EditView.tpl via
     * SugarFieldFile.js), used to swap out the attached file while editing the record, and
     * (b) a direct forged POST bypassing the UI entirely. Either way it only clears/unlinks
     * the attachment and saves the bean — unlike FilesController::deleteFile() (Slim) and
     * FilesApi::removeFile() (legacy dropzone), it never calls mark_deleted() on the record.
     * That makes it the same "replace attachment while editing" operation that Notes/
     * AOS_Products gate on ACLAccess('edit') (see Notes/controller.php action_editview()),
     * not the record-destroying "delete" those two endpoints perform — so it is gated here on
     * 'edit', consistent with that precedent, rather than 'delete' like the other two paths.
     */
    protected function action_deleteattachment()
    {
        if (!empty($this->bean->id) && !$this->bean->ACLAccess('edit')) {
            ob_clean();
            echo json_encode(false);
            sugar_cleanup(true);

            return;
        }

        parent::action_deleteattachment();
    }
}
