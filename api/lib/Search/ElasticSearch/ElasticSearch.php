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

use Elasticsearch\ClientBuilder;
use Elasticsearch\Common\Exceptions\InvalidArgumentException;
use MintHCM\Lib\Search\Base\SearchManager;
use MintHCM\Lib\Search\Base\SearchResult;
use MintHCM\Lib\Search\ElasticSearch\ElasticQuery;
use MintHCM\Lib\Search\ElasticSearch\ElasticResult;

class ElasticSearch extends SearchManager
{

    protected $client, $result, $indice_module_map,$queryClass;

    public function __construct(array $params)
    {
        parent::__construct($params);
        $this->setClient();
    }
    
    public function setElasticACL($elastic_acl)
    {
        $this->elastic_acl = $elastic_acl;
    }
    
    public function search($handle_acl = false): SearchResult
    {
        if (empty($this->query)) {
            throw new InvalidArgumentException();
        }
        $result = $this->client->search($this->query);
        $this->setResultManager($result, $handle_acl);
        return $this->result_manager;
    }

    public function setQuery(array $params): void
    {
        $this->params = $params;
        $this->queryClass =(new ElasticQuery($params))->setACLFilters($this->elastic_acl);
        $this->query = $this->queryClass->getQuery();
        $this->indice_module_map = $this->queryClass->getIndiceToModuleMapping();
    }

    protected function setResultManager($result, $handle_acl): void
    {
        $this->result_manager = new ElasticResult($result, $this->params['from'], $this->params['items'], $handle_acl,$this->indice_module_map);
        if ($this->result_manager->shouldSearchAgain()) {
            $this->params['from'] = $this->result_manager->getNextOffset();
            $this->setQuery($this->params);
            $result = $this->client->search($this->query);
            $this->result_manager->nextSearch($result);
        }
    }

    protected function setClient()
    {
        global $mint_config;

        $hosts = $mint_config['search']['engines']['ElasticSearch'] ?? array();
        if (!is_array($hosts)) {
            throw new InvalidArgumentException();
        }

        foreach ($hosts as $host) {
            if (empty($host['host'])) {
                throw new InvalidArgumentException();
            }
        }

        $this->client = ClientBuilder::create()->setHosts($hosts)->build();
    }

}
