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
use Api\V8\Param\ListViewColumnsParams;
use ListViewFacade;
use SuiteCRM\LangText;

if (!defined('sugarEntry') || !sugarEntry) {
    die('Not A Valid Entry Point');
}

include_once __DIR__ . '/../../../include/ListView/ListViewFacade.php';

/**
 * ListViewService
 *
 * @author gyula
 */
class ListViewService
{
    
    /**
     * an exact match to ListViewColumnInterface struct of Angular front-end
     *
     * @var array
     */
    protected static $listViewColumnInterface = [
        'fieldName' => '',
        'width' => '',
        'label' => '',
        'link' => false,
        'default' => false,
        'module' => '',
        'id' => '',
        // 'sortable' => false,
        'customCode' => '', // deprecated from legacy (using only on PHP front-end)
    ];
    
    /**
     * @var BeanManager
     */
    protected $beanManager;

    /**
     * @var AttributeObjectHelper
     */
    protected $attributeHelper;

    /**
     * @var RelationshipObjectHelper
     */
    protected $relationshipHelper;

    /**
     * @var PaginationObjectHelper
     */
    protected $paginationHelper;

    private $varDefHelper;

    /**
     * @param BeanManager $beanManager
     * @param AttributeObjectHelper $attributeHelper
     * @param RelationshipObjectHelper $relationshipHelper
     * @param PaginationObjectHelper $paginationHelper
     */
    public function __construct(
        BeanManager $beanManager,
        AttributeObjectHelper $attributeHelper,
        RelationshipObjectHelper $relationshipHelper,
        PaginationObjectHelper $paginationHelper,
        VarDefHelper $varDefHelper
    ) {
        $this->beanManager = $beanManager;
        $this->attributeHelper = $attributeHelper;
        $this->relationshipHelper = $relationshipHelper;
        $this->paginationHelper = $paginationHelper;
        $this->varDefHelper = $varDefHelper;
    }

    /**
     * @param ListViewColumnsParams $params
     *
     * @return JsonSerializable
     */
    public function getListViewDefs(ListViewColumnsParams $params)
    {
        $moduleName = $params->getModuleName();
        /** @var SugarBean */
        // MintHCM Start #84951
        $bean = $this->beanManager->newBeanSafe($moduleName);
        $fields = $this->varDefHelper->getModuleVardefs($bean);
        // MintHCM End #84951
        /* MintHCM Start #84318
        $text = new LangText(null, null, LangText::USING_ALL_STRINGS, true, false, $moduleName);
        MintHCM End #84318 */
        $displayColumns = ListViewFacade::getDisplayColumns($moduleName);
        $data = [];
        foreach ($displayColumns as $key => $column) {
            $column = array_merge(self::$listViewColumnInterface, $column);
            /* MintHCM Start #84318
            $column['fieldName'] = $key; // get the vardef instead this "intuitive fieldName"
            $translated = $text->getText($column['label']);
            if (!$translated) {
                $translated = $text->getText($bean->field_name_map[strtolower($key)]['vname']);
            }
            $column['label'] = $translated ? $translated : $column['label'];
            MintHCM End #84318 */ 
            
            // TODO: validate the column name (for e.g label and name should be requered etc...) also check the ListViewColumnInterface keys are match..
            // MintHCM Start #84951
            if (!empty($fields)) {
                $field = $fields[strtolower($key)];
                if(empty($field) || empty($field['name'])){
                    continue;
                }
                unset($column['fieldName']);
                $column['name'] = $field['name'];
                $column['type'] = $field['type'];
                if (!empty($field['options'])) {
                    $column['options'] = $field['options'];
                }
                if (empty($column['label'])) {
                    $column['label'] = $field['vname'];
                }
                if ($field['type'] === 'relate' && !empty($field['module']) && !empty($field['id_name'])) {
                    $column['module'] = $field['module'] ?? '';
                    $column['id_name'] = $field['id_name'] ?? '';
                }
            }
            //MintHCM End #84951
            $data[] = $column;
        }
        $response = new AttributeResponse($data);
        return $response;
    }
}
