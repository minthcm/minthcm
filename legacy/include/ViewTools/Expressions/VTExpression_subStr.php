<?php



/**
 * Cuts substring from entered string.
 * EOU:
 * "subStr( 'Ala ma kota' , 8 , 3 )" will give us "kot"
 * "strToUpper( subStr( 'Ala ma kota' , 1 ,abs( -3 ) ) )" will give us "ALA"
 */
class VTExpression_subStr extends VTExpression {

   /**
    * Variable used for creating documentation. As value, 
    * please set array of functions, which this formula can be used
    */
   public $availability = array( 'vt_calculated', 'vt_dependency', 'vt_required', 'vt_readonly', 'vt_duplicate', 'vt_validation', 'related' );
   /**
    * if $inputParams are not set, return false
    * Definition of input params
    */
   public $inputParams = array( 'string', 'from', 'len' );

   /**
    * Warning! if backend is not set, return false
    * @param Array 
    * @return boolean
    * Please set input params as Array
    */
   public function backend($arguments = array()) {
      $arguments['from'] = intval(unformat_number($arguments['from']));
      $arguments['len'] = intval(unformat_number($arguments['len']));
      if ( is_numeric($arguments['from']) && is_numeric($arguments['len']) ) {
         return substr($arguments['string'], ($arguments['from'] - 1), $arguments['len']);
      }
      return '';
   }

   /**
    * Warning! if frontend is not set, return false
    * @return type
    */
   public function frontend() {
      return <<<EOQ
      arguments['from'] = unformatNumber( arguments['from'], num_grp_sep, dec_sep );
      arguments['len'] = unformatNumber( arguments['len'], num_grp_sep, dec_sep );
      if ( arguments['from'] == "" || isNaN( arguments['from'] ) || arguments['len'] == "" || isNaN( arguments['len'] ) ) {
         return '';
      }
      return arguments['string'].substr( (arguments['from'] - 1), arguments['len'] );
EOQ;
   }

   /**
    * Warning! if duplicate section is not set, return false
    */
   public function sqlbackend($arguments = array()) {
      return "SUBSTRING({$arguments['string']},{$arguments['charpos']},{$arguments['len']})";
   }

}
