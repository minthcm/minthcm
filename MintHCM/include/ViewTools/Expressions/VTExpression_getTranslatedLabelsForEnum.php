<?php


/**
 * Translate enum and multienum values to labels in selected language.
 * If language key isn't provided, the expression will return the label in current user's language.
 * EOU:
 * "ifElse(inArray('In Progress',$accounting_subtype),getTranslatedLabelsForEnum($sales_stage, 'sales_stage_dom', 'en_us'),'--')"
 */
class VTExpression_getTranslatedLabelsForEnum extends VTExpression {

   /**
    * Variable used for creating documentation. As value, 
    * please set array of functions, which this formula can be used
    */
   public $availability = array( 'vt_calculated', 'vt_dependency' );
   /**
    * if $inputParams are not set, return false
    * Definition of input params
    */
   public $inputParams = array( 'field', 'app_list_strings', 'language_key' );

   /**
    * Warning! if backend is not set, return false
    * @param Array 
    * @return boolean
    * Please set input params as Array
    */
   public function backend($arguments = array()) {
      $string_values = $arguments['field'];
      if ( isset($arguments['language_key']) ) {
         $lang = $arguments['language_key'];
      } else {
         $lang = $_GLOBALS['current_language'];
      }
      $strings = return_app_list_strings_language($lang);
      $values = unencodeMultienum($string_values);
      foreach ( $values as $key => $value ) {
         $values[$key] = $strings[$arguments['app_list_strings']][$value];
      }
      return implode(', ', $values);
   }

   /**
    * Warning! if frontend is not set, return false
    * @return type
    */
   public function frontend() {
      return <<<EOQ
      var list = SUGAR.language.languages['app_list_strings'][arguments['app_list_strings']];
      var return_array = [];
      if(typeof arguments['field'] === "string"){
         return_array.push(list[arguments['field']]);
      } else {
         for(var i = 0; i < arguments['field'].length; i++){
            return_array.push(list[arguments['field'][i]]);
         }
      }      
      return return_array;
EOQ;
   }

}
