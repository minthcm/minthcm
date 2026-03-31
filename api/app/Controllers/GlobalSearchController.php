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

namespace MintHCM\Api\Controllers;

use Doctrine\ORM\EntityManagerInterface;
use Slim\Exception\HttpBadRequestException;
use Slim\Exception\HttpInternalServerErrorException;
use Slim\Psr7\Response;
use MintHCM\Lib\Search\Search;
use Psr\Http\Message\ServerRequestInterface as Request;
use MintHCM\Utils\ConstantsLoader;


#[\AllowDynamicProperties]
class GlobalSearchController
{
    protected $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;

        // Workaround for api/lib/Search/Base/SearchResult.php
        // Passing EntityManager by constructor could make a mess with class structure
        // It should be replaced with a normal solution
        global $entityManager;
        $entityManager = $this->entityManager;
    }

    public function getData(Request $request, Response $response, array $args): Response
    {
        $response = $response->withHeader('Content-type', 'application/json');
        try {
            global $current_user;
            $query = $request->getAttribute('query');
            $itemsPerPage = $request->getAttribute('itemsPerPage') ?? 5;
            $page = $request->getAttribute('page') ?? 1;
            $is_unified_search = $request->getAttribute('isUnifiedSearch') ?? false;

            $search_manager = Search::getManager();
            $search_manager->setElasticACL(!is_admin($current_user));
            
            $normalizedPhone = $this->normalizePhoneQuery($query);
            $queryParams = array(
                "search" => 'global',
                "fields" => array("*__last^5", "*__first^4", "*__name.*^3", "*"),
                "items" => $itemsPerPage,
                "query" => $normalizedPhone ?? $query,
                "sort_order" => "desc",
                "from" => $itemsPerPage * ($page - 1),
            );
            if ($normalizedPhone !== null) {
                $queryParams['phone_query'] = true;
            }
            $search_manager->setQuery($queryParams);
            $search_result = $search_manager->search(false);


        } catch (BadRequest400Exception) {
            throw new HttpBadRequestException($this->request);
        } catch (InvalidArgumentException) {
            throw new HttpInternalServerErrorException($this->request);
        }
        
        $data = array(
            'query' => $query,
            'next_page_exists' => $search_result->getNextPageExists(),
            'results' => $this->getBeans($search_result->getBeans(), $is_unified_search),
            'total' => $search_result->getTotal() ?? 0,
        );

        $response->getBody()->write(json_encode($data));
        return $response;
    }

    /**
     * Detect phone-number queries and expand them into OR variants ( | ) so
     * that the search works regardless of whether the number is stored with or
     * without a country-code prefix — and without hard-coding any specific
     * country code.
     *
     * International format  "+48555888666":
     *   The standard analyser strips "+" so the indexed token is "48555888666".
     *   We also generate sub-strings by removing 1, 2, or 3 leading digits to
     *   cover all possible country-code lengths (1–3 digits), e.g.:
     *     "48555888666 | 8555888666 | 555888666 | 55888666"
     *   This finds the record whether the phone is stored as "+48555888666" or
     *   as bare "555888666".
     *
     * Local format  "555888666"  (7–12 digits, no prefix):
     *   We also emit a leading wildcard "*555888666" which matches any indexed
     *   token that ends with "555888666" — e.g. "48555888666". simple_query_string
     *   supports "*" wildcards and does not analyse them, so the wildcard is
     *   matched directly against the inverted-index terms.
     *     "555888666 | *555888666"
     *
     * Returns null when the query does not look like a phone number so the
     * caller falls back to the original query string unchanged.
     *
     * NOTE: callers must set minimum_should_match=1 when using the returned
     * string, because the default "66%" would require most variants to match
     * simultaneously, defeating the OR logic.
     */
    protected function normalizePhoneQuery(string $query): ?string
    {
        // The frontend appends "*" to every term for prefix search.
        // Strip it before analysis — phone numbers are matched exactly.
        $cleaned = rtrim(preg_replace('/[\s\-\(\).]/', '', $query), '*');

        // International format: "+" followed by 8–15 digits total
        if (preg_match('/^\+(\d{8,15})$/', $cleaned, $matches)) {
            $digits = $matches[1]; // e.g. "48555888666"
            $variants = [$digits];
            // Strip 1, 2, or 3 leading digits to cover all country-code lengths.
            for ($i = 1; $i <= 3; $i++) {
                $local = substr($digits, $i);
                if (strlen($local) >= 7) {
                    $variants[] = $local;
                }
            }
            return implode(' OR ', array_unique($variants));
        }

        // Local format: 7–12 digits only.
        // "OR *{digits}*" uses query_string contains-wildcard to catch numbers stored
        // with any country-code prefix, e.g. "906888767" finds token "48906888767".
        // simple_query_string only supports trailing wildcards, so we switch the caller
        // to query_string (via phone_query flag) which supports leading wildcards.
        if (preg_match('/^\d{7,12}$/', $cleaned)) {
            return "{$cleaned} OR *{$cleaned}*";
        }

        return null;
    }

    protected function getBeans($beans, $is_unified_search = false)
    {
        $response = array();
        foreach ($beans as $bean) {
            if(!$is_unified_search) {
                $response[] = array(
                    "id" => $bean->id,
                    "module" => $bean->module_name,
                    "name" => $bean->name,
                    "meta" => $this->getAdditionalMetaData($bean),
                );
                continue;
            }

            $response[] = array(
                "id" => $bean->id ?? '',
                "module" => $bean->module_name ?? '',
                "name" => $bean->name ?? $bean->document_name ?? '',
                "date_entered" => $bean->date_entered ?? '',
                "date_modified" => $bean->date_modified ?? '',
                "meta_array" => $this->getAdditionalMetaDataForUnifiedSearch($bean) ?? '',
            );
        }

        return $response;
    }

    protected function getAdditionalMetaData($bean)
    {
        $meta_field_name = $GLOBALS['dictionary'][$bean->object_name]['full_text_search_meta_field'] ?? null;
        if (!empty($meta_field_name) && isset($bean->field_defs[$meta_field_name])) {
            $field = $bean->field_defs[$meta_field_name];
        } else {
            $field = $bean->field_defs['date_entered'];
        }
        return [
            "def" => $field,
            "value" => $bean->{$field['name']},
        ];
    }

    protected function getAdditionalMetaDataForUnifiedSearch($bean)
    {
        $meta = $this->getAdditionalMetaData($bean);
        return [
            'value' => $meta['value'],
            'label' => $meta['def']['vname'] ?? $meta['def']['label'] ?? '',
        ];
    }
}
