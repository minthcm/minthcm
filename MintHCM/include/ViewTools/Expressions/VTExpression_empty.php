<?php

/**
 * Determine whether a variable is empty.
 * EOU:
 * "empty($name)" will give us false if name exists and has a non-empty, non-zero value. Otherwise returns true.
 */
class VTExpression_empty extends VTExpression {

   /**
    * Variable used for creating documentation. As value, 
    * please set array of functions, which this formula can be used
    */
   public $availability = array( 'vt_calculated', 'vt_dependency', 'vt_required', 'vt_readonly', 'vt_duplicate', 'vt_validation', 'related' );
   /**
    * if $inputParams are not set, return false
    * Definition of input params
    */
   public $inputParams = array( 'mixed' );

   /**
    * Warning! if backend is not set, return false
    * @param Array 
    * @return boolean
    * Please set input params as Array
    */
   public function backend($arguments = array()) {
      return empty($arguments['mixed']);
   }

   /**
    * Warning! if frontend is not set, return false
    * @return type
    */
   public function frontend() {
      return <<<EOQ
        return _.isEmpty(arguments['mixed']);
EOQ;
   }

   /**
    * Warning! if duplicate section is not set, return false
    */
   public function sqlbackend($arguments = array()) {
      return "({$arguments['mixed']} IS NULL OR {$arguments['mixed']} = '')";
   }

}
