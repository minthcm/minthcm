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

namespace MintHCM\Lib\Search\ElasticSearch;

use Elasticsearch\Common\Exceptions\InvalidArgumentException;
use MintHCM\Data\BeanFactory;
use MintHCM\Lib\Search\Base\SearchQuery;
use MintHCM\Lib\Search\ElasticSearch\ElasticMapperParser;
use MintHCM\Lib\Search\ElasticSearch\ElasticQueryOperatorsManager;
use MintHCM\Lib\Search\ElasticSearch\ModulePrefixer;
use MintHCM\Utils\CustomLoader;
use MintHCM\Utils\LegacyConnector;

class ElasticQuery extends SearchQuery
{
    const DEFAULT_SORT_FIELD = "_score";
    const DEFAULT_SORT_ORDER = "asc";
    const SORT_KEYWORD = ".keyword";

    const DEFAULT_TYPE = null;

    const ALL_FIELDS = "*";

    protected $exclude_modules = [];
    protected $search_modules = [];

    protected $add_acl_filters = false;
    protected $indice_module_map;
    protected $parsed = [];

    protected function setQuery()
    {
        $this->query = array(
            "body" => $this->getBody(),
            "index" => $this->getIndex(),
        );
    }

    public function setACLFilters($add_acl_filters)
    {
        $this->add_acl_filters = $add_acl_filters;
        return $this;
    }

    protected function setSort()
    {
        $field = !empty($this->params["sort_by"]) ? $this->params['sort_by']: static::DEFAULT_SORT_FIELD;
        if (static::DEFAULT_SORT_FIELD !== $field) {
            $parser = ElasticMapperParser::getInstance();
            $module_name = $this->params['type'] ?? '';
            $field = $parser->getFieldAttributePath($module_name, $field);
        }
        $this->sort = array(
            $field => array(
                "order" => !empty($this->params["sort_order"]) ? $this->params['sort_order'] : 'asc',
            ),
        );
        return;
    }

    private function getIndex()
    {
        $prefix = static::getIndexPrefix();
        if (isset($prefix)) {
            if (isset($this->params['search']) && 'list' == $this->params['search'] && !empty($this->params['type'])) {
                $search_modules = [$this->params['type']];
            } else {
                $search_modules = $this->getGlobalSearchModuleList();
            }
            $searchModules = array_map('strtolower', $search_modules);
            $searchModules = substr_replace($searchModules, $prefix . '_', 0, 0);
            $indexes = implode(',', $searchModules);
            $this->indice_module_map = array_combine($searchModules, $search_modules);

            return $indexes;
        }
        return null;
    }

    private function getBody()
    {
        return array(
            "query" => $this->getBodyQuery(),
            "size" => $this->size,
            "from" => $this->from,
            "sort" => $this->sort,
        );
    }

    private function getBodyQuery()
    {
        switch (strtolower($this->params['search'])) {
            case "global":
                return $this->getGlobalQuery();
                break;
            case "list":
                $body = $this->getListQuery();
                $module_to_search = $this->params['type'] ?? '';
                if ($this->add_acl_filters && $module_to_search) {
                    $bean = BeanFactory::newBean($module_to_search);
                    $acl_controller = new LegacyConnector('ACLController');
                    if ($bean->bean_implements('ACL') && !$acl_controller::checkAccess($bean->module_dir, 'list', true)) {
                        return [];
                    }
                    $module_filters = $this->getACLForSingleModule($module_to_search);
                    if (is_array($module_filters)) {
                        $add_filter = [
                            "bool" => [
                                "should" => $module_filters
                            ],
                        ];
                        $body["bool"]["filter"][] = $add_filter;
                    }
                }
                return $body;
                break;
            default:
                throw new InvalidArgumentException();
        }
    }

    private function getGlobalQuery()
    {

        if ($this->add_acl_filters) {
            $uniq = static::getIndexPrefix();
            $main_acl["bool"]["must"] = $this->noAclGlobalQuery();
            $main_acl["bool"]["filter"]["bool"]["should"] = [];
            $search_modules = $this->getGlobalSearchModuleList();

            foreach ($search_modules as $module_to_search) {
                $bean = BeanFactory::newBean($module_to_search);
                /** @var \ACLController $acl_controller */
                $acl_controller = new LegacyConnector('ACLController');
                if ($bean->bean_implements('ACL') && !$acl_controller::checkAccess($bean->module_dir, 'list', true)) {
                    continue;
                }
                if ($bean->bean_implements('ACL') && ($acl_controller::requireOwner($bean->module_dir, 'list') || $acl_controller::requireSecurityGroup($bean->module_dir, 'list'))) {
                    $module_filters = $this->getACLForModule($module_to_search);
                } else {
                    $module_filters['bool']['must']['term']['_index'] = $uniq . '_' . strtolower($module_to_search);
                }

                if (is_array($module_filters)) {
                    $main_acl["bool"]["filter"]["bool"]["should"][] = $module_filters;
                    $module_filters = [];
                }
            }

            if (count($this->exclude_modules)) {
                $main_acl["bool"]["filter"]["bool"]['must_not'] = $this->getExcludeModules();
            }

            return $main_acl;
        } else {
            return $this->noAclGlobalQuery();
        }
    }

    private function noAclGlobalQuery()
    {
        $fields = !empty($this->params['fields']) ? $this->params['fields'] : array(static::ALL_FIELDS);
        return array(
            'simple_query_string' => array(
                'query' => $this->params['query'],
                'fields' => $fields,
                'analyzer' => 'standard',
                'default_operator' => 'OR',
                'minimum_should_match' => '66%',
            ),
        );
    }

    private function getListQuery()
    {
        return (CustomLoader::getObject(ElasticQueryOperatorsManager::class, $this->params['filters'] ?? [], $this->params['type'] ?? null))->getQuery();
    }

    protected function getExcludeModules()
    {
        $uniq = static::getIndexPrefix();
        $excluded_queries = [];
        foreach ($this->exclude_modules as $module) {
            $excluded_queries[]['term']['_index'] = $uniq . '_' . strtolower($module);
        }
        return $excluded_queries;
    }

    protected function getACLForModule($module)
    {
        global $current_user;
        $uniq = static::getIndexPrefix();
        $acl = $this->getACLClassForModule($module);
        $restriction_filter = $acl->getAccessRestrictionFilter($current_user->id);

        $single_module['bool']['must'][]['term']['_index'] = $uniq . '_' . strtolower($module);
        if (!empty($restriction_filter[0]['bool']['should'])) {
            $single_module['bool']['must'][]['bool']['should'] = $restriction_filter[0]['bool']['should'];
        }
        return $single_module;
    }

    protected function getACLForSingleModule($module)
    {
        global $current_user;
        $acl = $this->getACLClassForModule($module);
        $restriction_filter = $acl->getAccessRestrictionFilter($current_user->id);
        if (!empty($restriction_filter[0]['bool']['should'])) {
            return $restriction_filter[0]['bool']['should'];
        }
        return [];
    }

    protected function getACLClassForModule(string $module)
    {
        $variants = [
            ['className' => 'MintHCM\Custom\Modules\\' . $module . '\\' . $module . 'ListACL', 'path' => "custom/modules/{$module}/{$module}ListACL.php"],
            ['className' => 'MintHCM\Modules\\' . $module . '\\' . $module . 'ListACL', 'path' => "modules/{$module}/{$module}ListACL.php"],
            ['className' => 'MintHCM\Custom\Lib\Search\ElasticSearch\CustomBaseListACL', 'path' => "lib/Search/ElasticSearch/CustomBaseListACL.php"],
            ['className' => 'MintHCM\Lib\Search\ElasticSearch\BaseListACL', 'path' => "lib/Search/ElasticSearch/BaseListACL.php"],
        ];

        foreach ($variants as $variant) {
            if (file_exists($variant['path'])) {
                require_once $variant['path'];
                if (class_exists($variant['className'])) {
                    return new $variant['className']($module);
                }
            }
        }
        throw new InvalidArgumentException("ACL class not found for module: {$module}");
    }
    public function getIndiceToModuleMapping()
    {
        return $this->indice_module_map;
    }

    protected function getGlobalSearchModuleList()
    {
        if (!empty($this->search_modules)) {
            return $this->search_modules;
        }
        include '../legacy/custom/modules/unified_search_modules_display.php';

        $search_modules = [];
        $exclude_hardcode = ["Connectors", "Currencies", "OAuthTokens", "OAuthKeys", "ACLRoles", "ACLActions", "EmailMan", "Schedulers", "SchedulersJobs", "CampaignLog", "EmailMarketing", "AOW_WorkFlow"];
        global $beanList;
        if (!empty($unified_search_modules_display)) {
            $search_modules = array_filter(array_map(function ($row) {
                return true === $row['visible'];
            }, $unified_search_modules_display));
            foreach (array_keys($search_modules) as $module_name) {
                if (!isset($beanList[$module_name])) {
                    unset($search_modules[$module_name]);
                }
            }
            $exclude = array_filter(array_map(function ($row) {
                return false === $row['visible'];
            }, $unified_search_modules_display));
            $this->exclude_modules = array_merge($exclude_hardcode, array_keys($exclude));
            return array_diff(array_keys($search_modules), $this->exclude_modules);
        }
        $this->search_modules = $search_modules;
        return $search_modules;
    }

    protected function setIndicesBoost()
    {
        if (empty($this->search_modules)) {
            $search_modules = $this->getGlobalSearchModuleList();
        } else {
            $search_modules = $this->search_modules;
        }
        if (isset($this->query['body']['query']['simple_query_string']['fields'])) {
            $boost_array = &$this->query['body']['query']['simple_query_string']['fields'];
        } else if (isset($this->query['body']['query']['bool']['must']['simple_query_string']['fields'])) {
            $boost_array = &$this->query['body']['query']['bool']['must']['simple_query_string']['fields'];
        } else {
            return;
        }

        foreach ($search_modules as $module_name) {
            $module_bean = BeanFactory::getBean($module_name);

            $parser = ElasticMapperParser::getInstance();
            $mappings = $parser->getDefaultMapParams($module_name);

            if (isset($module_bean->search_boost)) {
                $boost_array[] = $module_name . '*^' . $module_bean->search_boost;
            }

            foreach ($module_bean->field_defs as $field_key => $value) {
                $prefixer = new ModulePrefixer($module_name);
                if (isset($value['search_boost'])) {
                    $field_name = $prefixer->modify($field_key);

                    if (is_array($mappings['mappings']['properties'][$field_name]['properties'])) {
                        $boost_array[] = $field_name . '.*^' . $value['search_boost'];
                    } else {
                        $boost_array[] = $field_name . '^' . $value['search_boost'];
                    }
                }
            }
        }
    }

    public static function getIndexPrefix(): string
    {
        return $GLOBALS['sugar_config']['elasticsearch_index_prefix'] ?? $GLOBALS['sugar_config']['unique_key'];
    }
}
