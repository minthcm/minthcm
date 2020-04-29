<?php



/**
 * Rounds entered value to floor.
 * EOU:
 * "floor( 4.29 )" will give us "4"
 * "floor( 4.9 )" also will give us "4"
 */
class VTExpression_floor extends VTExpression {

   /**
    * Variable used for creating documentation. As value, 
    * please set array of functions, which this formula can be used
    */
   public $availability = array( 'vt_calculated', 'vt_dependency', 'vt_required', 'vt_readonly', 'vt_duplicate', 'vt_validation', 'related' );
   /**
    * if $inputParams are not set, return false
    * Definition of input params
    */
   public $inputParams = array( 'value' );

   /**
    * Warning! if backend is not set, return false
    * @param Array 
    * @return boolean
    * Please set input params as Array
    */
   public function backend($arguments = array()) {
      $arguments['value'] = floatval(unformat_number($arguments['value']));
      if ( !is_numeric($arguments['value']) ) {
         return false;
      }
      return format_number(floor($arguments['value']));
   }

   /**
    * Warning! if frontend is not set, return false
    * @return type
    */
   public function frontend() {
      return <<<EOQ
      arguments['value'] = unformatNumber( arguments['value'], num_grp_sep, dec_sep );
      if ( arguments['value'] == "" || isNaN( arguments['value'] ) ) {
         return false;
      }
      return formatNumber( Math.floor( arguments['value'] ), num_grp_sep, dec_sep );
EOQ;
   }

   /**
    * Warning! if duplicate section is not set, return false
    */
   public function sqlbackend($arguments = array()) {
      return "FLOOR({$arguments['value']})";
   }

}
