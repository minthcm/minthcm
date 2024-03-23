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

use Elasticsearch\Common\Exceptions\BadRequest400Exception;
use MintHCM\Lib\Search\ElasticSearch\Operators\Equals;
use MintHCM\Lib\Search\ElasticSearch\Operators\Exists;
use MintHCM\Lib\Search\ElasticSearch\Operators\MatchOperator;
use MintHCM\Lib\Search\ElasticSearch\Operators\Range;
use MintHCM\Lib\Search\ElasticSearch\Operators\Wildcard;

class ElasticQueryOperatorsManager
{
    const OPERATORS_MAPPER = array(
        'equals' => Equals::class,
        'exists' => Exists::class,
        'match' => MatchOperator::class,
        'range' => Range::class,
        'wildcard' => Wildcard::class,
    );

    protected $query, $filters;

    public function __construct(array $filters)
    {
        $this->filters = $filters;
        $this->setQuery();
    }

    public function getQuery()
    {
        return $this->query;
    }

    protected function setQuery()
    {
        $this->query = array(
            'bool' => array(
                'filter' => array(),
                'must_not' => array(),
            ),
        );

        foreach ($this->filters as $filter) {
            if (empty($filter['type']) || !in_array($filter['type'], array_keys($this::OPERATORS_MAPPER))) {
                throw new BadRequest400Exception();
            }
            $class = $this::OPERATORS_MAPPER[$filter['type']];
            $operator = new $class($filter);
            $data = $operator->getData();
            $this->query['bool'][$operator->getArrayKey()][] = $data;
        }
    }
}
