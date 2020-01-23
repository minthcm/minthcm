<?php

/**
 * @author Michał "phpion" Płonka
 */
class saynumber {

   /**
    * return words representation of number
    *
    * Supports numbers from 0 to 9999999.
    *
    * @param int $number numbers.
    * @param string $imploder glue A string which will be connected to each individual segments numbers.
    * @return string Verbal representation of a number.
    */
   public static function say($number, $imploder = ' ') {
      $number = ( int ) $number;

      if ( $number == 0 ) {
         return 'zero';
      }

      $levels = array(
         '0' => array( '', 'jeden', 'dwa', 'trzy', 'cztery', 'pięć', 'sześć', 'siedem', 'osiem', 'dziewięć' ),
         '0a' => array( '', 'jedenaście', 'dwanaście', 'trzynaście', 'czternaście', 'piętnaście', 'szesnaście', 'siedemnaście', 'osiemnaście', 'dziewiętnaście' ),
         '1' => array( '', 'dziesięć', 'dwadzieścia', 'trzydzieści', 'czterdzieści', 'pięćdziesiąt', 'sześćdziesiąt', 'siedemdziesiąt', 'osiemdziesiąt', 'dziewięćdziesiąt' ),
         '2' => array( '', 'sto', 'dwieście', 'trzysta', 'czterysta', 'pięćset', 'sześćset', 'siedemset', 'osiemset', 'dziewięćset' ),
         '3' => array( '', 'jeden tysiąc', 'dwa tysiące', 'trzy tysiące', 'cztery tysiące', 'pięć tysięcy', 'sześć tysięcy', 'siedem tysięcy', 'osiem tysięcy', 'dziewięć tysięcy' ),
         '3a' => array( '', 'jedenaście tysięcy', 'dwanaście tysięcy', 'trzynaście tysięcy', 'czternaście tysięcy', 'piętnaście tysięcy', 'szesnaście tysięcy', 'siedemnaście tysięcy', 'osiemnaście tysięcy', 'dziewiętnaście tysięcy' ),
         '4' => array( '', 'dziesięć tysięcy', 'dwadzieścia tysięcy', 'trzydzieści tysięcy', 'czterdzieści tysięcy', 'pięćdziesiąt tysięcy', 'sześćdziesiąt tysięcy', 'siedemdziesiąt tysięcy', 'osiemdziesiąt tysięcy', 'dziewięćdziesiąt tysięcy' ),
         '5' => array( '', 'sto tysięcy', 'dwieście tysięcy', 'trzysta tysięcy', 'czterysta tysięcy', 'pięćset tysięcy', 'sześćset tysięcy', 'siedemset tysięcy', 'osiemset tysięcy', 'dziewięćset tysięcy' ),
         '6' => array( '', 'jeden milion', 'dwa miliony', 'trzy miliony', 'cztery miliony', 'pięć milionów', 'sześć milionów', 'siedem milionów', 'osiem milionów', 'dziewięć milionów' )
      );

      $return = array();
      $skip_next_level = FALSE;

      $number = ( string ) $number;

      // Loops assigned words counterparts numbers on specified position
      for ( $i = 0, $strlen = strlen($number); $i < $strlen; $i++ ) {
         if ( $skip_next_level === TRUE ) {
            $return[] = '';
            $skip_next_level = FALSE;

            continue;
         }

         $l = $strlen - $i - 1;
         $n = ( int ) $number[$i];

         if ( $n == 1 && isset($levels[($l - 1) . 'a']) ) {
            $next_number = ( int ) $number[$i + 1];

            if ( $next_number != 0 ) {
               $l = ($l - 1) . 'a';
               $n = ( int ) $number[$i + 1];
            }

            $return[] = $levels[$l][( string ) $n];

            $skip_next_level = TRUE;
         } else {
            $return[] = $levels[$l][( string ) $n];
         }
      }
      //
      // Remove reps type of "sto tysięcy jedenaście tysięcy" to "sto jedenaście tysięcy".
      $c = 0;
      $remove_after_space = FALSE;

      for ( $i = $strlen - 1; $i >= 0; $i-- ) {
         $strpos = strpos($return[$i], ' ');

         if ( $strpos > 0 ) {
            if ( $remove_after_space === TRUE ) {
               $return[$i] = substr($return[$i], 0, $strpos);
            } else {
               $remove_after_space = TRUE;
            }
         }

         if ( $c == 2 ) {
            $remove_after_space = FALSE;
            $c = 0;
         } else {
            $c++;
         }
      }
      //

      return implode($imploder, $return);
   }

}
?>

?>