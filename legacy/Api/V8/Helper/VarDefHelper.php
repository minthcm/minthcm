<?php
namespace Api\V8\Helper;

class VarDefHelper
{
    /**
     * @param \SugarBean $bean
     *
     * @return array
     */
    public function getAllRelationships(\SugarBean $bean)
    {
        $relations = [];
        $linkedFields = $bean->get_linked_fields();

        foreach ($linkedFields as $relation => $varDef) {
            if (isset($varDef['module']) && $bean->load_relationship($relation)) {
                $relations[$relation] = $varDef['module'];
            }
        }

        return $relations;
    }
    /**
     * @param $bean
     *
     * @return array of modules vardefs
     */
    // MintHCM Start #84951
    public function getModuleVardefs($bean)
    {
        $arr = [];
        if (!empty($bean) && $bean instanceof \SugarBean) {
            $arr = $bean->getFieldDefinitions();
        }
        return $arr;
    }
    // MintHCM End #84951
}
