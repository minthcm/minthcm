<?php

/**
 * Checks if entered arrays are equal. First param is compared to rest of params.
 * EOU:
 * "arrayEquals($sales_stage, 'Completed', 'In Progress')"
 */
class VTExpression_arrayEquals extends VTExpression
{

    /**
     * Variable used for creating documentation. As value,
     * please set array of functions, which this formula can be used
     */
    public $availability = array('vt_calculated', 'vt_dependency', 'vt_required', 'vt_readonly', 'vt_duplicate', 'vt_validation');

    /**
     * Warning! if backend is not set, return false
     * @param Array
     * @return boolean
     * Please set input params as Array
     */
    public function backend($arguments = array())
    {
        $first_array = array();
        if (substr($arguments[0], -1) === '^' && substr($arguments[0], 0, 1) === '^') {
            $decoded = unencodeMultienum($arguments[0]);
            foreach ($decoded as $value) {
                $first_array[] = $value;
            }
        }
        $second_array = array();
        for ($i = 1; $i < count($arguments); $i++) {
            $second_array[] = $arguments[$i];
        }
        $is_diff = false;
        foreach ($first_array as $value) {
            if (!in_array($value, $second_array)) {
                $is_diff = true;
                break;
            }
        }
        if (!$is_diff) {
            foreach ($second_array as $value) {
                if (!in_array($value, $first_array)) {
                    $is_diff = true;
                    break;
                }
            }
        }
        return !$is_diff;
    }

    /**
     * Warning! if frontend is not set, return false
     * @return type
     */
    public function frontend()
    {
        return <<<EOQ
      var first_array = arguments[0];
      var second_array = [];
      for(var i = 1; i<arguments.length; i++){
         second_array.push(arguments[i]);
      }
      var is_diff = false;
      if(Array.isArray(first_array) && Array.isArray(second_array)){
         first_array.forEach(function(a){
            if($.inArray(a, second_array) < 0){
               is_diff = true;
            }
         });
         if(!is_diff){
            second_array.forEach(function(a){
               if($.inArray(a, first_array) < 0){
                  is_diff = true;
               }
            });
         }
      } else {
         is_diff = true;
      }
      return !is_diff;
EOQ;
    }

    /**
     * Warning! if duplicate section is not set, return false
     */
    public function sqlbackend($arguments = array())
    {
        $column = substr($this->interpreted_columns[0], 1);
        $parsed_string = substr(substr($arguments[0], 0, -1), 1);
        $first_array = explode("','", $parsed_string);
        $return = "( LENGTH( " . $column . " ) = LENGTH( '" . encodeMultienumValue($first_array) . "') AND {$column} LIKE '%^";
        $return .= implode("^%' AND {$column} LIKE '%^", $first_array) . "^%')";
        return $return;
    }

}
