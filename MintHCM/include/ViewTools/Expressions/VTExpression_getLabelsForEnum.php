<?php

/**
 * Translate enum and multienum values to labels.
 * EOU:
 * "ifElse(inArray('In Progress',$accounting_subtype),getLabelsForEnum($sales_stage, 'sales_stage_dom'),'--')"
 */
class VTExpression_getLabelsForEnum extends VTExpression
{

    /**
     * Variable used for creating documentation. As value,
     * please set array of functions, which this formula can be used
     */
    public $availability = array('vt_calculated');
    /**
     * if $inputParams are not set, return false
     * Definition of input params
     */
    public $inputParams = array('field', 'app_list_strings');

    /**
     * Warning! if backend is not set, return false
     * @param Array
     * @return boolean
     * Please set input params as Array
     */
    public function backend($arguments = array())
    {
        global $app_list_strings;
        $string_values = $arguments['field'];
        $values = unencodeMultienum($string_values);
        foreach ($values as $key => $value) {
            $values[$key] = $app_list_strings[$arguments['app_list_strings']][$value];
        }
        return implode(', ', $values);
    }

    /**
     * Warning! if frontend is not set, return false
     * @return type
     */
    public function frontend()
    {
        return <<<EOQ
      var lang = SUGAR.language.languages['app_list_strings'][arguments['app_list_strings']];
      var return_array = [];
      if(typeof arguments['field'] === "string"){
         return_array.push(lang[arguments['field']]);
      } else {
         for(var i = 0; i < arguments['field'].length; i++){
            return_array.push(lang[arguments['field'][i]]);
         }
      }
      return return_array;
EOQ;
    }

}
