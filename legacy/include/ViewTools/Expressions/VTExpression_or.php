<?php


/**
 * At least one of entered arguments has to be 'true'
 * EOU:
 * "or( true )" will give us "true"
 * "or( true , false )" will give us "true"
 * "or( false , and( true , true ) )" will give us "true"
 */
class VTExpression_or extends VTExpression {

   /**
    * Variable used for creating documentation. As value, 
    * please set array of functions, which this formula can be used
    */
   public $availability = array( 'vt_calculated', 'vt_dependency', 'vt_required', 'vt_readonly', 'vt_duplicate', 'vt_validation', 'related' );

   /**
    * Warning! if backend is not set, return false
    * @param Array 
    * @return boolean
    * Please set input params as Array
    */
   public function backend($arguments = array()) {
      foreach ( $arguments as $argument ) {
         $andCheckValue = $this->valueOf($argument);
         if ( $andCheckValue === true || $andCheckValue === 'true' ) {
            return true;
         }
      }
      return false;
   }

   /**
    * Warning! if frontend is not set, return false
    * @return type
    */
   public function frontend() {
      return <<<EOQ
      for ( key in arguments ) {
         var andCheckValue = viewTools.formula.valueOf(arguments[key]);
         if ( andCheckValue === true || andCheckValue === 'true' ) {
            return true;
         }
      }
      return false;
EOQ;
   }

   /**
    * Warning! if duplicate section is not set, return false
    */
   public function sqlbackend($arguments = array()) {
      $tmpVar = '';
      foreach ( $arguments as $argument ) {
         if ( $tmpVar != '' ) {
            $tmpVar = $tmpVar . ' OR ';
         }
         $tmpVar = $tmpVar . $argument;
      }
      return "({$tmpVar})";
   }

}
