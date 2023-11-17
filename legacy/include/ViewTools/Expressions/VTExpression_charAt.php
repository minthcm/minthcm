<?php



/**
 * Returns char at declared position.
 * EOU:
 * "charAt( 'cat' , 2 )" will give us "a"
 * "charAt( 'dog' , 7 )" will give us ""
 */
class VTExpression_charAt extends VTExpression {

   /**
    * Variable used for creating documentation. As value, 
    * please set array of functions, which this formula can be used
    */
   public $availability = array( 'vt_calculated', 'vt_dependency', 'vt_required', 'vt_readonly', 'vt_duplicate', 'vt_validation', 'related' );
   /**
    * if $inputParams are not set, return false
    * Definition of input params
    */
   public $inputParams = array( 'string', 'charpos' );

   /**
    * Warning! if backend is not set, return false
    * @param Array 
    * @return boolean
    * Please set input params as Array
    */
   public function backend($arguments = array()) {
      if ( !is_numeric($arguments['charpos']) ) {
         return '';
      }
      $charAtPos = substr($arguments['string'], (( int ) $arguments['charpos'] - 1), 1);
      if ( $charAtPos != null ) {
         return $charAtPos;
      }
      return '';
   }

   /**
    * Warning! if frontend is not set, return false
    * @return type
    */
   public function frontend() {
      return <<<EOQ
      if ( !$.isNumeric( arguments['charpos'] ) ) {
         return '';
      }
      var charAtPos = String( arguments['string'] ).charAt( parseInt( arguments['charpos'] )-1 );
      if ( charAtPos !== undefined ) {
         return charAtPos;
      }
      return '';
EOQ;
   }

   /**
    * Warning! if duplicate section is not set, return false
    */
   public function sqlbackend($arguments = array()) {
      return "SUBSTRING({$arguments['string']},{$arguments['charpos']},1)";
   }

}
