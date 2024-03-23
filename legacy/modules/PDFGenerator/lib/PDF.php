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

require_once 'modules/PDFGenerator/lib/BasePDFGenerator.php';

class PDF extends BasePDFGenerator
{

    protected function getNewBlockForBlockLink($block, $parent_bean, $relation_field_name, $depth, $block_after)
    {
        $module_name = $parent_bean->$relation_field_name->getRelatedModuleName();
        $bean = BeanFactory::newBean($module_name);
        // $table = $bean->getTableName();
        if ($parent_bean->load_relationship($relation_field_name)) {
            $relatedBeans = $parent_bean->$relation_field_name->getBeans();
        }
        $new_block = '';
        $counter = 0;
        foreach ($relatedBeans as $id => $bean) {
            $new_block .= $this->setNewBlockLink($module_name, $id, $depth, ++$counter, $block_after, $parent_bean);
        }

        // $q = $parent_bean->{$relation_field_name}->getQuery();
        // $query = "SELECT id FROM {$table} WHERE id IN({$q})";
        // //check ordering
        // if (isset($block->orderby)) {

        //     if ($bean->getFieldDefinition($block->orderby)) {
        //         $dir = $this->getBlockDir($block);
        //         $query .= "ORDER BY {$block->orderby} {$dir}";
        //     }
        // }
        // $result = $parent_bean->db->query($query);
        // $count = $parent_bean->db->getAffectedRowCount($result);
        // $new_block = '';
        // $counter = 0;
        // if ($count > 0) {
        //     while (($row = $parent_bean->db->fetchByAssoc($result)) != null) {
        //         $new_block .= $this->setNewBlockLink($module_name, $row['id'], $depth, ++$counter, $block_after, $parent_bean);
        //     }
        // }
        return $this->setNewBlockForBlockLink($block, $counter, $new_block);
    }

    public function setNewBlockLink($module_name, $bean_id, $depth, $counter, $block_after, $parent_bean = null)
    {
        $newbean = $this->getBean($module_name, $bean_id, $parent_bean);
        return $this->parse($block_after->innertext, $newbean, $block_after->relationship, $counter, $depth + 1);
    }

    public function getBlockDir($block)
    {
        if (isset($block->dir) && in_array(strtoupper($block->dir), array("ASC", "DESC"))) {
            $dir = $block->dir;
        } else {
            $dir = "ASC";
        }
        return $dir;
    }

}
