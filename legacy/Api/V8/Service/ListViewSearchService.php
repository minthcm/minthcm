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

namespace Api\V8\Service;

use Api\V8\BeanDecorator\BeanManager;
use Api\V8\Helper\VarDefHelper;
use Api\V8\JsonApi\Helper\AttributeObjectHelper;
use Api\V8\JsonApi\Helper\PaginationObjectHelper;
use Api\V8\JsonApi\Helper\RelationshipObjectHelper;
use Api\V8\JsonApi\Response\AttributeResponse;
use Api\V8\JsonApi\Response\DataResponse;
use Api\V8\JsonApi\Response\DocumentResponse;
use Api\V8\Param\ListViewSearchParams;
use JsonSerializable;
use SearchForm;
use SuiteCRM\LangText;
use ListViewFacade;

if (!defined('sugarEntry') || !sugarEntry) {
    die('Not A Valid Entry Point');
}

include_once __DIR__ . '/../../../include/SearchForm/SearchForm2.php';
include_once __DIR__ . '/../../../include/ListView/ListViewFacade.php';

/**
 * ListViewSearchService
 *
 * @author gyula
 */
class ListViewSearchService
{
    
    /**
     * @var BeanManager
     */
    protected $beanManager;

    private $varDefHelper;

    /**
     * @param BeanManager $beanManager
     * @param AttributeObjectHelper $attributeHelper
     * @param RelationshipObjectHelper $relationshipHelper
     * @param PaginationObjectHelper $paginationHelper
     */
    public function __construct(
        BeanManager $beanManager,
        VarDefHelper $varDefHelper
    ) {
        $this->beanManager = $beanManager;
        $this->varDefHelper = $varDefHelper;
    }
    
    /**
     *
     * @param LangText $trans
     * @param array $data
     * @param string $part
     * @param string $valueKey
     * @param array $displayColumns
     * @return array
     */
    protected function getDataTranslated($trans, $data, $part, $valueKey, $displayColumns)
    {
        foreach ($data[$part] as $key => $value) {
            $text = null;
            if (isset($value[$valueKey])) {
                $text = $value[$valueKey];
            } elseif (isset($value['name']) && isset($displayColumns[strtoupper($value['name'])]['label'])) {
                $text = $displayColumns[strtoupper($value['name'])]['label'];
            } else {
                \LoggerManager::getLogger()->warn("Not found translation text key for search defs for selected module field: $key");
            }
            
            $label = $text ? $trans->getText($text) : $text;
            $data[$part][$key][$valueKey] = $label;
        }
        
        return $data;
    }
    
    /**
     * @param ListViewSearchParams $params
     *
     * @return JsonSerializable
     */
    public function getListViewSearchDefs(ListViewSearchParams $params)
    {
        // retrieving search defs
        
        $moduleName = $params->getModuleName();
        $searchDefs = SearchForm::retrieveSearchDefs($moduleName);

        //MintHCM Start #84951
        $bean = $this->beanManager->newBeanSafe($moduleName);
        $module_fields = $this->varDefHelper->getModuleVardefs($bean);
         
        // get list view defs
        // $displayColumns = ListViewFacade::getDisplayColumns($moduleName); MintHCM #84318
         
        // simplified data struct
         
        $data = [
            'module' => $moduleName,
            'templateMeta' => $searchDefs['searchdefs'][$moduleName]['templateMeta'],
            'basic' => $this->mergeModuleFields(array_values($searchDefs['searchdefs'][$moduleName]['layout']['basic_search']),$module_fields),
            'advanced' => $this->mergeModuleFields(array_values($searchDefs['searchdefs'][$moduleName]['layout']['advanced_search']),$module_fields),
            'fields' => $searchDefs['searchFields'][$moduleName]
        ];
 
        //MintHCM End #84951
        
        /* MintHCM Start #84318
        // translations
        
        $trans = new LangText(null, null, LangText::USING_ALL_STRINGS, true, false, $moduleName);
        

        $data = $this->getDataTranslated($trans, $data, 'basic', 'label', $displayColumns);
        $data = $this->getDataTranslated($trans, $data, 'advanced', 'label', $displayColumns);
        $data = $this->getDataTranslated($trans, $data, 'fields', 'vname', $displayColumns);
        MintHCM End #84318 */
        
        // generate response
        
        $dataResponse = new DataResponse('SearchDefs', null);
        $attributeResponse = new AttributeResponse($data);
        $dataResponse->setAttributes($attributeResponse);
        
        $response = new DocumentResponse();
        $response->setData($dataResponse);
        return $response;
    }

    protected function mergeModuleFields($array, $module_fields)
    {
        foreach ($array as $k => $v) {
            if (!is_array($v) && !empty($module_fields[$v])) {
                $array[$k] = $module_fields[$v];
                $array[$k]['label'] = $array[$k]['vname'];
                unset($array[$k]['vname']);
                continue;
            }
            if(empty($module_fields[$v['name']])){
                unset($array[$k]);
                continue;
            }
            if(empty($array[$k]['label'])){
                $array[$k]['label'] = $module_fields[$v['name']]['vname'];
            } 
            unset($module_fields[$v['name']]['vname']);
            $array[$k] += $module_fields[$v['name']];
        }                    
        return $array;
    }
}
