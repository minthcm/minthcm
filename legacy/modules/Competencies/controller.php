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

class CompetenciesController extends SugarController
{
    const TEMPLATE = 'Competencies';
    public function action_save()
    {
        parent::action_save();
        if (isset($_REQUEST['duplicateSave']) && $_REQUEST['duplicateSave'] === "true") {
            $template_id = $_REQUEST['duplicateId'];
            $duplicate_bean = BeanFactory::getBean(static::TEMPLATE, $template_id);
            $duplicate_bean->load_relationship('knowledge');
            $duplicate_bean->load_relationship('skills');
            $duplicate_bean->load_relationship('attitudes');
            $this_bean = BeanFactory::getBean(static::TEMPLATE, $this->bean->id);
            $this_bean->load_relationship('knowledge');
            $this_bean->load_relationship('skills');
            $this_bean->load_relationship('attitudes');
            $linked_beans_knowledge = $duplicate_bean->get_linked_beans('knowledge');
            $linked_beans_skills = $duplicate_bean->get_linked_beans('skills');
            $linked_beans_attitudes = $duplicate_bean->get_linked_beans('attitudes');
            foreach ($linked_beans_knowledge as $linked_bean) {
                $this_bean->knowledge->add($linked_bean);
            }
            foreach ($linked_beans_skills as $linked_bean) {
                $this_bean->skills->add($linked_bean);
            }
            foreach ($linked_beans_attitudes as $linked_bean) {
                $this_bean->attitudes->add($linked_bean);
            }
        }
    }
}
