<?php

/**
 * Checks if param1 is greater than param2.
 * EOU:
 * "greaterThan( 5 , 4 )" will give us "true"
 * "greaterThan( 5.4 , '5.9' )" will give us "false"
 * "greaterThan( 5.4 , floor( 5.9 ) )" will give us "true"
 * Within range example:
 * $value = 10
 * "and( greaterThan( $value , 3 ) , greaterThan( 15 , $value ) )" will give us "true"
 */
class VTExpression_greaterThan extends VTExpression
{

    /**
     * Variable used for creating documentation. As value,
     * please set array of functions, which this formula can be used
     */
    public $availability = array('vt_calculated', 'vt_dependency', 'vt_required', 'vt_readonly', 'vt_duplicate', 'vt_validation', 'related');
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
        $arguments['param1'] = floatval(unformat_number($arguments['param1']));
        $arguments['param2'] = floatval(unformat_number($arguments['param2']));
        if (is_numeric($arguments['param1']) && is_numeric($arguments['param2']) && $arguments['param1'] > $arguments['param2']) {
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

      arguments['param1'] = unformatNumber( arguments['param1'], num_grp_sep, dec_sep );
      arguments['param2'] = unformatNumber( arguments['param2'], num_grp_sep, dec_sep );
      if(arguments['param1'] === ""){
         arguments['param1'] = 0;
      }
      if(arguments['param2'] === ""){
         arguments['param2'] = 0;
      }

      if ( arguments['param1'] === "" || isNaN( arguments['param1'] ) || arguments['param2'] === "" || isNaN( arguments['param2'] ) ) {
         return false;
      }
      if ( parseFloat( arguments['param1'] ) > parseFloat( arguments['param2'] ) ) {
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
        return "{$arguments['param1']} > {$arguments['param1']}";
    }

}
