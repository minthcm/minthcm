<?php

/**
 * Checks if string has substring.
 * EOU:
 * "contains( 'ala ma kota' , 'kota' )" will give us "true"
 * "contains( 'ala ma kota' , strToLower( $name ) )" - if $name is "KOTA", formula will give us "true"
 * "contains( 'ala ma kota' , subStr( 'ala ma kota' , 7 , 4 ) )" - also will give us "true"
 */
class VTExpression_contains extends VTExpression
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
    public $inputParams = array('string', 'substring');

    /**
     * Warning! if backend is not set, return false
     * @param Array
     * @return boolean
     * Please set input params as Array
     */
    public function backend($arguments = array())
    {
        if (strstr($arguments['string'], $arguments['substring']) !== false) {
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
      if ( arguments['string'].indexOf( arguments['substring'] ) >= 0 ) {
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
        return "{$arguments['string']} LIKE '%{$arguments['substring']}%'";
    }

}
