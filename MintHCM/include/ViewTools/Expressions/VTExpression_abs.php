<?php



/**
 * Description of absolute value is available <a target="_blank" href="https://en.wikipedia.org/wiki/Absolute_value">here</a>.
 * EOU:
 * "abs( 10 )" will give us "10"
 * "abs( -10 )" will also give us "10"
 */
class VTExpression_abs extends VTExpression {

   /**
    * Variable used for creating documentation. As value, 
    * please set array of functions, which this formula can be used
    */
   public $availability = array( 'vt_calculated', 'vt_dependency', 'vt_required', 'vt_readonly', 'vt_duplicate', 'vt_validation', 'related' );
   /**
    * if $inputParams are not set, return false
    * Definition of input params
    */
   public $inputParams = array( 'param' );

   /**
    * Warning! if backend is not set, return false
    * @param Array 
    * @return boolean
    * Please set input params as Array
    */
   public function backend($arguments = array()) {
      $arguments['param'] = floatval(unformat_number($arguments['param']));
      if ( !is_numeric($arguments['param']) ) {
         return false;
      }
      return format_number(abs($arguments['param']));
   }

   /**
    * Warning! if frontend is not set, return false
    * @return type
    */
   public function frontend() {
      return <<<EOQ
      arguments['param'] = unformatNumber( arguments['param'], num_grp_sep, dec_sep );
      if ( arguments['param'] == "" || isNaN( arguments['param'] ) ) {
         return false;
      }
      return formatNumber( Math.abs( arguments['param'] ), num_grp_sep, dec_sep );
EOQ;
   }

   /**
    * Warning! if duplicate section is not set, return false
    */
   public function sqlbackend($arguments = array()) {
      return "ABS({$arguments['param']})";
   }

}
