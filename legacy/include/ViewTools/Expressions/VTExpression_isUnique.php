<?php

/**
 * Checks if given field_value of field_name is unique among other records of the same module
 * EOU:
 * "isUnique( name,\$name )" returns true, when $name is unique value of name field among other records of the same module, otherwise returns false
 */
class VTExpression_isUnique extends VTExpression
{

    public $availability = array('vt_calculated', 'vt_dependency', 'vt_duplicate', 'vt_validation', 'related');
    public $inputParams = array('field_name', 'field_value');
    public $serversideFrontend = true;
    public $sqlBackendFormula = true;

    public function backend($arguments = array())
    {
        $db = DBManagerFactory::getInstance();
        $module_name = VTExpression::getModuleName();
        $module_id = VTExpression::getRecordId();
        $bean = BeanFactory::newBean($module_name);
        $table_name = $bean->table_name;
        $field_name = $arguments['field_name'];
        $field_value = $arguments['field_value'];
        $result = true;
        if ($field_name != '' && $field_value != '') {
            $sql = "SELECT id FROM $table_name WHERE $field_name = '$field_value' AND id != '$module_id' AND deleted = 0 LIMIT 1";
            $exist = $db->getOne($sql);
            if (!empty($exist)) {
                $result = false;
            }
        }
        return $result;
    }

}
