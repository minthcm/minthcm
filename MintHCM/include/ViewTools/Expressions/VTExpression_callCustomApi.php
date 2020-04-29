<?php

/**
 * Returns result of custom Module Api method
 * EOU:
 * "callCustomApi( Accounts , getSomeInfo , param_1, param_2, ..., param_N )" where:
 * - Accounts - module name to find his Api class (here: AccountsApi) / this param is required
 * - getSomeInfo - method name to call in this Api class / this param is required
 * - param_1, param_2, ..., param_N - parameters given for method / this params are optional
 * Important info:
 * - location of Api: (custom/)modules/ModuleName/api/ModuleNameApi.php
 * -- example: modules/vt_Orders/api/vt_OrdersApi.php or custom/modules/Accounts/api/AccountsApi.php
 * - method called in this class should returns: boolean, integer, float, string, array
 * - if callCustomApi will not have first and second params (eg. Accounts, getSomeInfo) then callCustomApi returns false
 * - if called method doesn't exists or gives Fatal error then callCustomApi returns false
 */
class VTExpression_callCustomApi extends VTExpression
{

    public $availability = array('vt_calculated', 'vt_dependency', 'vt_duplicate', 'vt_validation', 'related');
    public $serversideFrontend = true;
    public $sqlBackendFormula = true;

    public function backend($arguments = array())
    {
        $result = false;

        if (count($arguments) >= 2) {
            $module_name = $this->cleanValue($arguments[0]);
            $method_name = $this->cleanValue($arguments[1]);
            array_shift($arguments);
            array_shift($arguments);
            $method_params = $this->cleanValuesFromArray($arguments);
            if (!empty($_POST['is_frontend'])) {
                if (is_array($method_params[0])) {
                    $method_params[0]['is_frontend'] = true;
                } elseif ($method_params[0] == "") {
                    $method_params[0] = ['is_frontend' => true];
                }
            }

            if (!empty($module_name) && !empty($method_name)) {
                $api_class = $module_name . 'Api';
                $api_path = 'custom/modules/' . $module_name . '/api/' . $api_class . '.php';
                if (!file_exists($api_path)) {
                    $api_path = 'modules/' . $module_name . '/api/' . $api_class . '.php';
                }
                if (file_exists($api_path)) {
                    include_once $api_path;
                    $api_instance = new $api_class();
                    $result = call_user_func_array(array($api_instance, $method_name), $method_params);
                }
            }
        }
        return $result;
    }

    protected function cleanValuesFromArray($array)
    {
        $return_array = [];
        foreach ($array as $key => $value) {
            if (is_array($value)) {
                $return_array[$key] = $this->cleanValuesFromArray($value);
            } else {
                $return_array[$key] = $this->cleanValue($value);
            }
        }
        return $return_array;
    }

    protected function cleanValue($value)
    {
        $characters_to_clean = array(' ', '\'', '"');
        $old_value = '';
        while ($old_value != $value) {
            $old_value = $value;
            foreach ($characters_to_clean as $character) {
                $value = trim($value, $character);
            }
        }
        return $value;
    }

}
