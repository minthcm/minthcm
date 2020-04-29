<?php

/**
 * EOU:
 * "and( true , true , false )" will give us "false"
 * "and( true , true )" will give us "true"
 * "and( or( true , false ) )" will give us "true"
 */
class VTExpression_and extends VTExpression
{

    /**
     * Variable used for creating documentation. As value,
     * please set array of functions, which this formula can be used
     */
    public $availability = array('vt_calculated', 'vt_dependency', 'vt_required', 'vt_readonly', 'vt_duplicate', 'vt_validation', 'related');

    /**
     * Warning! if backend is not set, return false
     * @param Array
     * @return boolean
     * Please set input params as Array
     */
    public function backend($arguments = array())
    {
        foreach ($arguments as $argument) {
            $andCheckValue = $argument;
            if ($andCheckValue !== true && $andCheckValue !== 'true') {
                return false;
            }
        }
        return true;
    }

    /**
     * Warning! if frontend is not set, return false
     * @return type
     */
    public function frontend()
    {
        return <<<EOQ
      for ( key in arguments ) {
         var andCheckValue = arguments[key];
         if ( andCheckValue !== true && andCheckValue !== 'true' ) {
            return false;
         }
      }
      return true;
EOQ;
    }

    /**
     * Warning! if duplicate section is not set, return false
     */
    public function sqlbackend($arguments = array())
    {
        $tmpRet = '';
        foreach ($arguments as $argument) {
            if ($tmpRet != '') {
                $tmpRet = $tmpRet . ' AND ';
            }
            $tmpRet = $tmpRet . $argument;
        }
        return "({$tmpRet})";
    }

}
