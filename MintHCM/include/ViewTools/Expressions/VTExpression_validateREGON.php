<?php



/**
 * Checks if REGON is in proper format.
 * EOU:
 * "validateREGON( '123456785' )" will give us "true"
 * "validateREGON( '12345678512347' )" will give us "true"
 */
class VTExpression_validateREGON extends VTExpression {

   /**
    * Variable used for creating documentation. As value, 
    * please set array of functions, which this formula can be used
    */
   public $availability = array( 'vt_validation' );
   /**
    * if $inputParams are not set, return false
    * Definition of input params
    */
   public $inputParams = array( 'regon' );

   /**
    * Warning! if backend is not set, return false
    * @param Array 
    * @return boolean
    * Please set input params as Array
    */
   public function backend($arguments = array()) {
      if ( isset($arguments['regon']) && !empty($arguments['regon']) ) {
         $regon = preg_replace("/[^0-9]/", "", $arguments['regon']);
         $lenght = strlen($regon);
         if ( $lenght == 9 || $lenght == 14 ) {
            $regon_array = str_split($regon);
            $checksum = $this->countChecksum($regon_array);
            return ($lenght == 9 ? $checksum == $regon_array[8] : $checksum == $regon_array[13]);
         }
      }
      return false;
   }

   protected function countChecksum($regon_array) {
      $sum = 0;
      $lenght = count($regon_array);
      if ( $lenght == 9 ) {
         $weight_array = array( '8', '9', '2', '3', '4', '5', '6', '7' );
      } else {
         $weight_array = array( '2', '4', '8', '5', '0', '9', '7', '3', '6', '1', '2', '4', '8' );
      }
      for ( $i = 0; $i < $lenght - 1; $i++ ) {
         $sum += $regon_array[$i] * $weight_array[$i];
      }
      $sum = $sum % 11;
      $sum = $sum % 10;
      return $sum;
   }

   /**
    * Warning! if frontend is not set, return false
    * @return type
    */
   public function frontend() {
      return <<<EOQ
      var regon = arguments['regon'];
      regon = regon.replace(/\D/g,'');
      var length = regon.length;
      if (length == 9 || length == 14 ){
         var regon_array = regon.split("");
         if (length == 9) {
            var weight_array = ['8', '9', '2', '3', '4', '5', '6', '7'];
         }  
         else {
            var weight_array = ['2', '4', '8', '5', '0', '9', '7', '3', '6', '1', '2', '4', '8'];
         }
         var checksum = 0;
         for(i=0;i<length-1;i++) {
            checksum += regon_array[i] * weight_array[i];
         }
         checksum = checksum % 11;
         checksum = checksum % 10;
         return (length == 9 ? checksum == regon_array[8] : checksum == regon_array[13]);
      }
      return false;
EOQ;
   }

   /**
    * Warning! if duplicate section is not set, return false
    */
   public function sqlbackend($arguments = array()) {
      return false;
   }

}
