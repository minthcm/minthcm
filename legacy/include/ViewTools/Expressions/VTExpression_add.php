<?php

/**
 * Return sum (float) of mixed values or <b>false</b> if any mixed value is not a number. Empty ("") mixed means zero.
 * EOU:
 * "add( 10 , "2" , "-4" , -5.5 )" will give us 2.5
 * "add( 10 , "2" , "foo12" , -5 )" will give us "false"
 */
class VTExpression_add extends VTExpression
{

    /**
     * Variable used for creating documentation. As value,
     * please set array of functions, which this formula can be used
     */
    public $availability = array('vt_calculated');

    /**
     * Warning! if backend is not set, return false
     * @param Array
     * @return float or false
     * Please set input params as Array
     */
    public function backend($arguments = array())
    {
        $result = 0.0;
        foreach ($arguments as $argument) {
            $argument = floatval(unformat_number($argument));
            if (is_numeric($argument)) {
                $result += $argument;
            } else {
                return false;
            }
        }
        return format_number($result);
    }

    /**
     * Warning! if frontend is not set, return false
     * @return float or false
     */
    public function frontend()
    {
        return <<<EOQ
      var result = 0.0;
      for ( key in arguments ) {
         arguments[key] = unformatNumber( arguments[key], num_grp_sep, dec_sep );
         if ( arguments[key] === "" || isNaN( arguments[key] ) ) {
            return false;
         }
         result += parseFloat( arguments[key] );
      }
      return formatNumber( result, num_grp_sep, dec_sep );
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
                $tmpRet = $tmpRet . ' + ';
            }
            $tmpRet = $tmpRet . $argument;
        }
        return "({$tmpRet})";
    }

}
