<?php

/**
 * Checks if entered value is numeric
 * EOU:
 * "isNumeric( 10 )" will give us "true"
 * "isNumeric( '10' )" will give us "true"
 * "isNumeric( subStr( 'ala ma 10 kotów' , 7 , 2 ) )" will also give us "true"
 */
class VTExpression_isNumeric extends VTExpression
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
    public $inputParams = array('value');

    /**
     * Warning! if backend is not set, return false
     * @param Array
     * @return boolean
     * Please set input params as Array
     */
    public function backend($arguments = array())
    {
        $seps = get_number_seperators();
        $arguments['value'] = trim(str_replace(array($seps[0], $seps[1]), array('', '.'), $arguments['value']));
        if (is_numeric($arguments['value'])) {
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
      if ( dec_sep == "." ) {
         dec_sep_reg = /\./g;
      } else {
         dec_sep_reg = dec_sep;
      }
      if ( num_grp_sep == "." ) {
         num_grp_sep_reg = /\./g;
      } else {
         num_grp_sep_reg = num_grp_sep;
      }
      var dec_regex = new RegExp( dec_sep_reg, 'g' );
      var num_regex = new RegExp( num_grp_sep_reg, 'g' );
      var value = arguments['value'].toString().trim();
      var replaced_value = value.replace( num_regex, '' ).replace( dec_regex, '.' );
      if ( replaced_value != "" && !isNaN( replaced_value ) ) {
         if ( value.indexOf( dec_sep ) != -1 ) {
            var split_value = value.split( dec_sep );
            if ( split_value[1].indexOf( num_grp_sep ) != -1 ) {
               return false;
            }
         }
         if( dec_sep != "." && num_grp_sep != "." && value.indexOf(".") != -1 ) {
            return false;
         }
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
        return "TRIM({$arguments['value']}) REGEXP '^-?[0-9]+(\.|\,)?[0-9]?$'";
    }

}
