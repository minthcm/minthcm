<?php


/**
 * Returns the length of the given string.
 * EOU:
 * "strlen ( 'cat' )" will give us 3
 */
class VTExpression_strlen extends VTExpression {

   /**
    * Variable used for creating documentation. As value, 
    * please set array of functions, which this formula can be used
    */
   public $availability = array( 'vt_calculated', 'vt_dependency', 'vt_required', 'vt_readonly', 'vt_duplicate', 'vt_validation', 'related' );
   /**
    * if $inputParams are not set, return false
    * Definition of input params
    */
   public $inputParams = array( 'string' );

   /**
    * Warning! if backend is not set, return false
    * @param Array 
    * @return boolean
    * Please set input params as Array
    */
   public function backend($arguments = array()) {
      if ( !is_string($arguments['string']) ) {
         return '';
      }
      $result = strlen($arguments['string']); 
      if ( $result != null ) {
         return $result;
      }
      return '';
   }

   /**
    * Warning! if frontend is not set, return false
    * @return type
    */
   public function frontend() {
      return <<<EOQ
      if ( typeof arguments['string'] !== 'string' ) {
         return '';
      }
      var result = arguments['string'].length;
      if ( typeof result !== 'undefined' ) {
         return result;
      }
      return '';
EOQ;
   }

   /**
    * Warning! if duplicate section is not set, return false
    */
   public function sqlbackend($arguments = array()) {
      return "LENGTH({$arguments['string']})";
   }

}
