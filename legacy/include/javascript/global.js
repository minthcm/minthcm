function parseTimeValue( el ) {
   var precision = 2,
           formatedNumber;
   try {
      this.duration = new viewTools.Duration( el.value, '.,' + dec_sep );
      formatedNumber = formatNumber( this.duration.toFloat( precision ),
              num_grp_sep, dec_sep, precision, precision );
      if ( formatedNumber === dec_sep + '00' ) {
         formatedNumber = 0 + dec_sep + '00';
      }
      el.value = formatedNumber;
   } catch ( e ) {
      console.warn( e );
      el.value = 0 + dec_sep + '00';
   }
}