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
use Api\V8\Helper\ModuleListProvider;
use Api\V8\Helper\VarDefHelper;
use Api\V8\JsonApi\Response\AttributeResponse;
use Api\V8\JsonApi\Response\DataResponse;
use Api\V8\JsonApi\Response\DocumentResponse;
use Api\V8\Param\GetModuleMetaParams;
use Api\V8\Param\GetFieldListParams;
use Slim\Http\Request;
use SuiteCRM\Exception\Exception;
use SuiteCRM\Exception\NotAllowedException;
use SuiteCRM\Exception\NotFoundException;

/**
 * Class MetaService
 * @package Api\V8\Service
 */
class MetaService
{
    /**
     * @var BeanManager
     */
    private $beanManager;

    /**
     * @var ModuleListProvider
     */
    private $moduleListProvider;
    private $varDefHelper;

    private static $allowedVardefFields = [
        'type',
        'dbType',
        'source',
        'relationship',
        'default',
        'len',
        'precision',
        'comments',
        'required',
        'vname', // MintHCM #84318
    ];

    /**
     * UserService constructor.
     * @param BeanManager $beanManager
     */
    public function __construct(
        BeanManager $beanManager,
        ModuleListProvider $moduleListProvider,
        VarDefHelper $varDefHelper
    ) {
        $this->beanManager = $beanManager;
        $this->moduleListProvider = $moduleListProvider;
        $this->varDefHelper = $varDefHelper;
    }

    /**
     * Build the response with a list of modules to return.
     *
     * @param Request $request
     * @return DocumentResponse
     */
    public function getModuleList(Request $request)
    {
        $modules = $this->moduleListProvider->getModuleList();

        $dataResponse = new DataResponse('modules', '');
        $attributeResponse = new AttributeResponse($modules);
        $dataResponse->setAttributes($attributeResponse);

        $response = new DocumentResponse();
        $response->setData($dataResponse);

        return $response;
    }

    /**
     * Build the response with a list of fields to return.
     *
     * @param Request $request
     * @param GetFieldListParams $fieldListParams
     * @return DocumentResponse
     * @throws NotAllowedException
     */
    public function getFieldList(Request $request, GetFieldListParams $fieldListParams)
    {
        $fieldList = $this->buildFieldList($fieldListParams->getModule());

        $dataResponse = new DataResponse('fields', '');
        $attributeResponse = new AttributeResponse($fieldList);
        $dataResponse->setAttributes($attributeResponse);

        $response = new DocumentResponse();
        $response->setData($dataResponse);

        return $response;
    }

    /**
     * @param string $module
     * @throws NotAllowedException
     */
    private function checkIfUserHasModuleAccess($module)
    {
        global $current_user;

        $modules = query_module_access_list($current_user);
        \ACLController::filterModuleList($modules, false);

        if (!in_array($module, $modules, true)) {
            throw new NotAllowedException('The API user does not have access to this module.');
        }
    }

    /**
     * Build the list of fields for a given module.
     *
     * @param string $module
     * @return array
     * @throws NotAllowedException
     */
    private function buildFieldList($module)
    {
        $this->checkIfUserHasModuleAccess($module);
        $bean = $this->beanManager->newBeanSafe($module);
        $fieldList = [];
        foreach ($bean->field_defs as $fieldName => $fieldDef) {
            $fieldList[$fieldName] = $this->pruneVardef($fieldDef);
        }

        return $fieldList;
    }

    /**
     * We only allow certain fields from the vardefs to be returned in the field list.
     *
     * @param array $def
     * @return array
     */
    private function pruneVardef($def)
    {
        $pruned = [];
        foreach ($def as $var => $val) {
            if (in_array($var, static::$allowedVardefFields, true)) {
                $pruned[$var] = $val;
            }
        }
        if (!isset($def['required'])) {
            $pruned['required'] = false;
        }
        if (!isset($def['dbType'])) {
            $pruned['dbType'] = $def['type'];
        }

        return $pruned;
    }

    /**
     * Build the response with the swagger schema.
     *
     * @return DocumentResponse
     * @throws NotFoundException
     * @throws Exception
     */
    public function getSwaggerSchema()
    {
        $path = __DIR__ . '/../../docs/swagger/swagger.json';
        if (!file_exists($path)) {
            throw new NotFoundException(
                'Unable to find JSON Api Schema file: ' . $path
            );
        }

        $swaggerFile = file_get_contents($path);

        if (!$swaggerFile) {
            throw new Exception(
                'Unable to read JSON Api Schema file: ' . $path
            );
        }

        return json_decode($swaggerFile, true);
    }

    public function getEditViewMeta(Request $request, GetModuleMetaParams $moduleMetaParams)
    {
        $module = $moduleMetaParams->getModuleName();
        
        $ve = new \ViewEdit;
        $ve->module = $module;
        require_once $ve->getMetaDataFile();

        $bean = \BeanFactory::newBean($module);
        $module_fields = $this->varDefHelper->getModuleVardefs($bean);

        $response = new DocumentResponse();
        $response->setData($this->mergeModuleFields($viewdefs[$module]['EditView']['panels'],$module_fields));
        return $response;
    }

    public function getDetailViewMeta(Request $request, GetModuleMetaParams $moduleMetaParams)
    {
        $module = $moduleMetaParams->getModuleName();

        $ve = new \ViewDetail;
        $ve->module = $module;
        require_once $ve->getMetaDataFile();
        require_once 'include/SubPanel/SubPanelDefinitions.php';

        $bean = $this->beanManager->newBeanSafe($module);
        $module_fields = $this->varDefHelper->getModuleVardefs($bean);

        $data = [];
        $data['detailview'] = $this->mergeModuleFields($viewdefs[$module]['DetailView']['panels'],$module_fields);
        $data['subpanels'] = $this->getSubpanelSetup(new \SubPanelDefinitions($bean, $module));

        $response = new DocumentResponse();
        $response->setData($data);
        return $response;
    }

    protected function mergeModuleFields($array, $module_fields)
    {
        foreach ($array as $panel => $fields) {
            foreach ($fields as $arr_key => $field) {
                foreach ($field as $k => $v) {
                    if (!is_array($v) && !empty($module_fields[$v])) {
                        $array[$panel][$arr_key][$k] = $module_fields[$v]; 
                        $array[$panel][$arr_key][$k]['label'] = $array[$panel][$arr_key][$k]['vname'];
                        unset($array[$panel][$arr_key][$k]['vname']);
                        continue;
                    }
                    if(empty($module_fields[$v['name']])){
                        continue;
                    }
                    if(empty($array[$panel][$arr_key][$k]['label'])){
                        $array[$panel][$arr_key][$k]['label'] = $module_fields[$v['name']]['vname'];
                    } 
                    unset($module_fields[$v['name']]['vname']);
                    $array[$panel][$arr_key][$k] += $module_fields[$v['name']];
                }
            }
        }
        return $array;
    }

    protected function mergeSubpanelFields($array, $module_fields)
    {
        foreach ($array as $k => $v) {
            if (!is_array($v) && !empty($module_fields[$v])) {
                $array[$k] = $module_fields[$v];
                $array[$k]['label'] = $array[$k]['vname'];
                unset($array[$k]['vname']);
                continue;
            }
            if (in_array($k, ['edit_button', 'remove_button'])) {
                continue;
            }
            if(empty($module_fields[$k])){
                unset($array[$k]);
                continue;
            }
            if(!empty($array[$k]['vname'])){
                $array[$k]['label'] = $array[$k]['vname'];
                unset($array[$k]['vname']);
            } else {
                if(!empty($module_fields[$k]['vname'])){
                    $array[$k]['label'] = $module_fields[$k]['vname'];
                }
            }
            unset($module_fields[$k]['vname']);
            $array[$k] += $module_fields[$k];
        }                    
        return $array;
    }

    protected function getSubpanelSetup($sb)
    {
        $array = [];
        foreach ($sb->layout_defs['subpanel_setup'] as $name => $defs) {
            $module_bean = \BeanFactory::newBean($defs['module']);
            $array[$name]['properties'] = $defs;
            if(!empty($module_bean) && $module_bean instanceof \SugarBean){
                $array[$name]['columns'] = $this->mergeSubpanelFields(($sb->load_subpanel($name))->panel_definition['list_fields'], $this->varDefHelper->getModuleVardefs($module_bean));
            } else {
                $array[$name]['columns'] = ($sb->load_subpanel($name))->panel_definition['list_fields'];    
            }
        }
        return $array;
    }
}
