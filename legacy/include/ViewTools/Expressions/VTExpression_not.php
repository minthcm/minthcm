<?php



/**
 * Negates logic value entered as value.
 * EOU:
 * "not( false )" will give us "true"
 * "not( and( true , false ) )" will give us "true"
 * "not( or( true , and( true , false ) ) )" will give us "false"
 */
class VTExpression_not extends VTExpression {

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
      if ( $arguments['value'] !== true && $arguments['value'] !== 'true' ) {
         return true;
      }
      return false;
   }

   /**
    * Warning! if frontend is not set, return false
    * @return type
    */
   public function frontend() {
      return <<<EOQ
      if ( arguments['value'] !== true && arguments['value'] !== 'true' ) {
         return true;
      }
      return false;
      
EOQ;
   }

   /**
    * Warning! if duplicate section is not set, return false
    */
   public function sqlbackend($arguments = array()) {
      return "NOT({$arguments['value']})";
   }

}
