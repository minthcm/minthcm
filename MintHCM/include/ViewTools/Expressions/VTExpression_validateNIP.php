<?php



/**
 * Checks if NIP is in proper format.
 * EOU:
 * "validateNIP( '1111111111' )" will give us "true"
 */
class VTExpression_validateNIP extends VTExpression {

   /**
    * Variable used for creating documentation. As value, 
    * please set array of functions, which this formula can be used
    */
   public $availability = array( 'vt_validation' );
   /**
    * if $inputParams are not set, return false
    * Definition of input params
    */
   public $inputParams = array( 'nip' );

   /**
    * Warning! if backend is not set, return false
    * @param Array 
    * @return boolean
    * Please set input params as Array
    */
   public function backend($arguments = array()) {
      if ( isset($arguments['nip']) && !empty($arguments['nip']) ) {
         $nip = preg_replace("/[^0-9]/", "", $arguments['nip']);
         if ( strlen($nip) == 10 ) {
            $nip_array = str_split($nip);
            $checksum = $this->countChecksum($nip_array);
            return ( $checksum == $nip_array[9] );
         }
      }
      return false;
   }

   protected function countChecksum($nip_array) {
      $sum = 0;
      $weight_array = array( '6', '5', '7', '2', '3', '4', '5', '6', '7' );
      for ( $i = 0; $i < 9; $i++ ) {
         $sum += $nip_array[$i] * $weight_array[$i];
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
      var nip = arguments['nip'];
      nip = nip.replace(/\D/g,'');
      if (nip.length == 10 ){
         var nip_array = nip.split("");
         var weight_array = ['6', '5', '7', '2', '3', '4', '5', '6', '7'];
         var checksum = 0;
         for(i=0;i<9;i++) {
            checksum += nip_array[i] * weight_array[i];
         }
         checksum = checksum % 11;
         checksum = checksum % 10;
         return (checksum == nip_array[9]);
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
