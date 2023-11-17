<?php

/**
 * Checks if entered values are equal.
 * EOU:
 * "equals( 2 , 2 )" will give us "true"
 * "equals( charAt( 'kot' , 2 ), 'o' )" will give us "true"
 * "equals( $name, 'John' )" - if field $name has value "John", then formula also give us "true"
 */
class VTExpression_equals extends VTExpression
{

    /**
     * Variable used for creating documentation. As value,
     * please set array of functions, which this formula can be used
     */
    public $availability = array('vt_calculated', 'vt_dependency', 'vt_duplicate', 'vt_validation', 'vt_required', 'vt_readonly', 'related');
    /**
     * if $inputParams are not set, return false
     * Definition of input params
     */
    public $inputParams = array('param1', 'param2');

    /**
     * Warning! if backend is not set, return false
     * @param Array
     * @return boolean
     * Please set input params as Array
     */
    public function backend($arguments = array())
    {
        if (($arguments['param2'] === '' && strlen(trim($arguments['param1'])) > 0) || ($arguments['param1'] === '' && strlen(trim($arguments['param2'])) > 0)) {
            return false;
        }
        if ($arguments['param1'] == $arguments['param2']) {
            return true;
        }
        return false;
    }

    /**
     * Warning! if frontend is not set, return false
     * @return type
     */
    public function frontend()
    {
        return <<<EOQ
      if((arguments['param2'] === '' && trim(arguments['param1']).length > 0) || (arguments['param1'] === '' && trim(arguments['param2']).length > 0)){
         return false;
      }
      if ( arguments['param1'] == arguments['param2'] ) {
         return true;
      }
      return false;
EOQ;
    }

    /**
     * Warning! if duplicate section is not set, return false
     */
    public function sqlbackend($arguments = array())
    {
        return "{$arguments['param1']} = {$arguments['param2']}";
    }

}
