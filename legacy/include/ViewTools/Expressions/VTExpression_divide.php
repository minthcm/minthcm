<?php

/**
 * Return divide (float) of mixed values or <b>false</b> if any mixed value is not a number or second mixed value is zero. Empty ("") mixed means zero.
 * EOU:
 * "divide( 5 , "2" )" will give us 2.5
 * "divide( 0 , 5 )" will give us 0
 * "divide( 5 , 0 )" will give us "false"
 * "divide( "foo5" , "2" )" will give us "false"
 * "divide( "foo5" , "2" )" will give us "false"
 */
class VTExpression_divide extends VTExpression
{

    /**
     * Variable used for creating documentation. As value,
     * please set array of functions, which this formula can be used
     */
    public $availability = array('vt_calculated');
    public $inputParams = array('mixed_1', 'mixed_2');

    /**
     * Warning! if backend is not set, return false
     * @param Array
     * @return float or false
     * Please set input params as Array
     */
    public function backend($arguments = array())
    {
        $arguments['mixed_1'] = floatval(unformat_number($arguments['mixed_1']));
        $arguments['mixed_2'] = floatval(unformat_number($arguments['mixed_2']));
        if (is_numeric($arguments['mixed_1']) && is_numeric($arguments['mixed_2']) && $arguments['mixed_2'] != 0.0) {
            return format_number(($arguments['mixed_1'] / $arguments['mixed_2']));
        }
        return false;
    }

    /**
     * Warning! if frontend is not set, return false
     * @return float or false
     */
    public function frontend()
    {
        return <<<EOQ
      arguments['mixed_1'] = unformatNumber( arguments['mixed_1'], num_grp_sep, dec_sep );
      arguments['mixed_2'] = unformatNumber( arguments['mixed_2'], num_grp_sep, dec_sep );
      if ( arguments['mixed_1'] == "" || isNaN( arguments['mixed_1'] ) || arguments['mixed_2'] == "" || isNaN( arguments['mixed_2'] || arguments['mixed_2'] == 0 ) ) {
         return false;
      }
      return formatNumber( (parseFloat( arguments['mixed_1'] ) / parseFloat( arguments['mixed_2'] )), num_grp_sep, dec_sep );
EOQ;
    }

    /**
     * Warning! if duplicate section is not set, return false
     */
    public function sqlbackend($arguments = array())
    {
        return "({$arguments['mixed_1']} / {$arguments['mixed_2']})";
    }

}
