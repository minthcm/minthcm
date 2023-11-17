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

namespace MintHCM\Lib\Search\Base;

use Doctrine\DBAL\Connection;
use MintHCM\Data\BeanFactory;
use MintHCM\Utils\LegacyConnector;

abstract class SearchResult
{
    protected $result, $grouped_ids, $beans, $hits;

    protected $handle_acl,$indice_module_map;

    protected $size, $current_offset, $total;

    public function __construct($result, $current_offset, $size, $handle_acl = false,$indice_module_map = [])
    {
        $this->total = $result["hits"]["total"]['value'];
        $this->size = $size;
        $this->handle_acl = $handle_acl;
        $this->indice_module_map = $indice_module_map;
        $this->setData($result, $current_offset);
    }

    abstract protected function setResultGroupedIds(): void;

    abstract protected function setHits(): void;

    abstract protected function setBeans(): void;

    public function getTotal(): int
    {
        return (int) $this->total;
    }

    public function getHits($group_by_module = false): array
    {
        $response = $group_by_module ? $this->getGroupedHits() : $this->beans;
        return array_slice($response, 0, $this->size);
    }

    public function getBeans($group_by_module = false): array
    {
        if (!isset($this->beans)) {
            $this->setBeans();
        }

        $response = $group_by_module ? $this->getGroupedBeans() : $this->beans;
        return array_slice($response, 0, $this->size);
    }

    public function getBeansAsJsonArray($group_by_module = false): array
    {
        if (!isset($this->beans)) {
            $this->setBeans();
        }

        $beans = array();
        foreach (array_slice($this->beans, 0, $this->size) as $bean) {
            $columns = $bean->column_fields;
            $row = [];
            foreach ($columns as $column) {
                if ($bean->$column instanceof Link2) {
                    $id_key = strtolower($bean->$column->getSide()) . "_key";
                    $id_name = $bean->$column->relationship->def[$id_key];
                    $id = $bean->{$id_name};
                    if (!empty($id) && 'id' !== $id_name) {
                        $row[$column] = "/" . $bean->$column->getRelatedModuleName() . "/DetailView/" . $id;
                        continue;
                    }
                }
                $row[$column] = $bean->$column;
            }
            $row['module_name'] = $bean->module_name;
            if ($this->add_acl_info) {
                $row['acl_access'] = $bean->acl_access;
            }
            if ($group_by_module) {
                $beans[$bean->module_name][] = $row;
            } else {
                $beans[] = $row;
            }
        }
        return $beans;
    }

    protected function setData($result, $current_offset): void
    {
        $this->result = $result;
        $this->current_offset = $current_offset;
        $this->setResultGroupedIds();
        if (true === $this->handle_acl) {
            $this->handleSG();
            $this->handleOwner();
        }
        $this->setHits();
    }

    protected function getGroupedBeans(): array
    {
        $response = array();
        foreach ($this->beans as $bean) {
            $respone[$bean->module_name] = $bean;
        }
        return $response;
    }

    protected function getGroupedHits(): array
    {
        $response = array();
        foreach ($this->hits as $hit) {
            $respone[$hit['module']] = $hit;
        }
        return $response;
    }

    protected function handleOwner()
    {
        if (!isset($this->beans)) {
            $this->setBeans();
        }

        global $current_user;

        $acl_controller = new LegacyConnector('ACLController');

        $beans_after_check = array();
        $new_grouped = array();

        foreach ($this->beans as $bean) {
            if (!$bean->bean_implements('ACL') || !$acl_controller::requireOwner($bean->module_dir, 'list')) {
                $beans_after_check[] = $bean;
                $new_grouped[$bean->module_name][] = $bean->id;
                continue;
            }

            if ($bean->isOwner($current_user->id)) {
                $beans_after_check[] = $bean;
                $new_grouped[$bean->module_name][] = $bean->id;
                continue;
            }
        }

        $this->grouped_ids = $new_grouped;
        $this->beans = $beans_after_check;
    }

    protected function handleSG()
    {
        if (empty($this->grouped_ids)) {
            return;
        }

        $new_grouped = array();
        foreach ($this->grouped_ids as $module => $ids) {
            global $current_user;

            $bean = BeanFactory::newBean($module);
            $acl_controller = new LegacyConnector('ACLController');

            if (!$bean->bean_implements('ACL') || !$acl_controller::requireSecurityGroup($bean->module_dir, 'list')) {
                $new_grouped[$module] = $ids;
                continue;
            }

            $security_group = new LegacyConnector('SecurityGroup', 'modules/SecurityGroups/SecurityGroup.php');
            $group_where = $security_group::getGroupWhere($bean->table_name, $bean->module_dir, $current_user->id);
            $owner_where = $bean->getOwnerWhere($current_user->id);
            if (!empty($owner_where)) {
                $where = empty($group_where) ? $owner_where : " ({$owner_where} OR {$group_where}) ";
            }

            global $entityManager;
            $query = "SELECT id
                FROM {$bean->table_name}
                WHERE {$where}
                    AND id IN (:ids)
            ";
            $connection = $entityManager->getConnection();
            $rows = $connection->executeQuery(
                $query,
                ['ids' => $ids],
                ['ids' => Connection::PARAM_STR_ARRAY]
            )->fetchAllAssociative();
            $parsed_ids = array_map(function ($row) { return $row['id']; }, $rows);

            $new_ids = array();

            foreach ($ids as $id) {
                if (in_array($id, $parsed_ids)) {
                    $new_ids[] = $id;
                }
            }

            $new_grouped[$module] = $new_ids;
        }

        $this->grouped_ids = $new_grouped;
    }
}
