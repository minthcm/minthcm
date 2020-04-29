<?php



/**
 * Checks if PESEL is in proper format.
 * EOU:
 * "validatePESEL( '87121703434' )" will give us "true"
 */
class VTExpression_validatePESEL extends VTExpression {

   /**
    * Variable used for creating documentation. As value, 
    * please set array of functions, which this formula can be used
    */
   public $availability = array( 'vt_validation' );
   /**
    * if $inputParams are not set, return false
    * Definition of input params
    */
   public $inputParams = array( 'pesel' );

   /**
    * Warning! if backend is not set, return false
    * @param Array 
    * @return boolean
    * Please set input params as Array
    */
   public function backend($arguments = array()) {
      if ( isset($arguments['pesel']) && !empty($arguments['pesel']) ) {
         $pesel = preg_replace("/[^0-9]/", "", $arguments['pesel']);
         if ( strlen($pesel) == 11 ) {
            $pesel_array = str_split($pesel);
            $checksum = $this->countChecksum($pesel_array);
            return ( $checksum == $pesel_array[10] );
         }
      }
      return false;
   }

   protected function countChecksum($pesel_array) {
      $sum = 0;
      $weight_array = array( '1', '3', '7', '9', '1', '3', '7', '9', '1', '3' );
      for ( $i = 0; $i < 10; $i++ ) {
         $sum += $pesel_array[$i] * $weight_array[$i];
      }
      $checksum = $sum % 10;
      return (($checksum % 10 == 0) ? 0 : 10 - $checksum);
   }

   /**
    * Warning! if frontend is not set, return false
    * @return type
    */
   public function frontend() {
      return <<<EOQ
      var pesel = arguments['pesel'];
      pesel = pesel.replace(/\D/g,'');
      if (pesel.length == 11 ){
         var pesel_array = pesel.split("");
         var weight_array = ['1', '3', '7', '9', '1', '3', '7', '9', '1', '3'];
         var checksum = 0;
         for(i=0;i<10;i++) {
            checksum += pesel_array[i] * weight_array[i];
         }
         checksum = checksum % 10;
         checksum = (checksum == 0 ? 0: 10 - checksum);
         return (checksum == pesel_array[10]);
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
