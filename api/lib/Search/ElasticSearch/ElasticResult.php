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

namespace MintHCM\Lib\Search\ElasticSearch;

use MintHCM\Data\BeanFactory;
use MintHCM\Data\MintBean;
use MintHCM\Lib\Search\Base\SearchResult;

class ElasticResult extends SearchResult
{
    protected $next_offset, $next_page_exists;

    public function __construct($result, $current_offset, $size, $handle_acl = false,$indice_module_map =[] )
    {
        parent::__construct($result, $current_offset, $size, $handle_acl,$indice_module_map);
        $this->setNextData();
    }

    public function shouldSearchAgain(): bool
    {
        return $this->next_offset < $this->total && count($this->hits) <= $this->size;
    }

    public function getNextOffset()
    {
        return $this->next_offset;
    }

    public function getNextPageExists()
    {
        return $this->next_page_exists;
    }

    public function nextSearch($result)
    {
        $beans = $this->beans;
        $hits = $this->hits;

        $this->beans = null;

        $this->setData($result, $this->next_offset);

        $this->hits = array_merge($hits, $this->hits);
        $this->beans = isset($beans) ? array_merge($beans, $this->beans) : $this->beans;

        $this->setNextData();
    }

    protected function setNextOffset()
    {
        $last_id = count($this->hits) > $this->size ? $this->hits[$this->size - 1]['id'] : end($this->hits)['id'];
        $next_offset = $this->current_offset;
        if (null !== $last_id && count($this->hits) > $this->size) {
            foreach ($this->result["hits"]["hits"] as $key => $hit) {
                $next_offset += 1;
                if ($hit['_id'] === $last_id) {
                    break;
                }
            }
        } else {
            $next_offset += $this->size;
        }
        $this->next_offset = $next_offset;
    }

    protected function setNextData(): void
    {
        $this->setNextOffset();
        $this->next_page_exists = count($this->hits) > $this->size;
    }

    protected function setResultGroupedIds(): void
    {
        $this->grouped_ids = array();
        if (empty($this->result)) {
            return;
        }
        
        foreach ($this->result["hits"]["hits"] as $hit) {
            $module = $this->indice_module_map[$hit["_index"]];
            $this->grouped_ids[$module][] = $hit['_id'];
        }
    }

    protected function setHits(): void
    {
        $this->hits = array();
        if (empty($this->result)) {
            return;
        }

        foreach ($this->result["hits"]["hits"] as $hit) {
            $module = $this->indice_module_map[$hit["_index"]];
            if (!in_array($hit['_id'], $this->grouped_ids[$module] ?? array())) {
                continue;
            }
            $this->hits[] = array(
                'id' => $hit['_id'],
                'module' => $module,
            );
        }
    }

    protected function setBeans(): void
    {
        $this->beans = array();

        $beans_unsorted = array();
        foreach ($this->grouped_ids as $module => $ids) {
            $focus = BeanFactory::newBean($module);
            $beans = $focus->get_full_list('', " {$focus->table_name}.id IN ('" . implode("','", $ids) . "')");

            foreach ($beans as $bean) {
                $bean = new MintBean($bean);
                if (empty($bean->id)) {
                    continue;
                }

                $bean->load_relationships();
                $bean->acl_access = [
                    'edit' => $bean->ACLAccess('edit'),
                    'view' => $bean->ACLAccess('view'),
                    'delete' => $bean->ACLAccess('delete'),
                ];

                $beans_unsorted[] = $bean;
            }
        }

        foreach ($this->result["hits"]["hits"] as $hit) {
            $module = $this->indice_module_map[$hit["_index"]];
            if (!in_array($hit['_id'], $this->grouped_ids[$module] ?? array())) {
                continue;
            }
            $id = $hit['_id'];
            $bean = array_filter($beans_unsorted, function ($bean) use ($id) {
                return $bean->id === $id;
            });

            $bean = reset($bean) ?? null;
            if (empty($bean)) {
                continue;
            }

            $this->beans[] = $bean;
        }

    }

}
