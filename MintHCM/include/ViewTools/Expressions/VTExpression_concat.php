<?php

/**
 * Joins entered strings (or references to string fields) to one string.
 * EOU:
 * "concat( 'John' , '&nbsp;&nbsp;' , 'Smith' )" will give us "John Smith"
 * "concat( strToUpper( 'John' ) , '&nbsp;&nbsp;' , strToLower( 'Smith' ) )" will give us "John smith"
 */
class VTExpression_concat extends VTExpression {

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
      $ret = '';
      foreach ( $arguments as $argument ) {
         $tmpValue = $argument;
         if ( $tmpValue != null ) {
            $ret = $ret . $tmpValue;
         }
      }
      return $ret;
   }

   /**
    * Warning! if frontend is not set, return false
    * @return type
    */
   public function frontend() {
      return <<<EOQ
      var ret = '';
      for ( key in arguments ) {
      var tmpValue = arguments[key];
         if ( tmpValue !== undefined ) {
            ret = ret + tmpValue;
         }
      }
      return ret;
EOQ;
   }

   /**
    * Warning! if duplicate section is not set, return false
    */
   public function sqlbackend($arguments = array()) {
      $tmpVar = '';
      foreach ( $arguments as $argument ) {
         if ( $tmpVar != '' ) {
            $tmpVar = $tmpVar . ',';
         }
         $tmpVar = $tmpVar . "ISNULL({$argument},'')";
      }
      return "CONCAT({$tmpVar})";
   }

}
