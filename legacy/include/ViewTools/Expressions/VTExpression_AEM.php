<?php

/**
 * Append error message
 * EOU:
 * "AEM(equals( 2 , 2 ),'LBL_ERROR')" will give us "true" and no error message
 * "AEM(equals( charAt( 'kot' , 1 ), 'o' ),'LBL_ERROR')" will give us "false" and will print error message
 */
class VTExpression_AEM extends VTExpression
{

    /**
     * Variable used for creating documentation. As value,
     * please set array of functions, which this formula can be used
     */
    public $availability = array('vt_validation');
    /**
     * if $inputParams are not set, return false
     * Definition of input params
     */
    public $inputParams = array('formula', 'errorMessage');

    /**
     * Warning! if backend is not set, return false
     * @param Array
     * @return boolean
     * Please set input params as Array
     */
    public function backend($arguments = array())
    {

        $ret = $arguments['formula'];
        if ($ret === false || $ret === 'false') {
            SugarAutoLoader::requireWithCustom('include/ViewTools/Expressions/VTFormulaParser.php');
            $formula_parser = new VTFormulaParser();
            global $mod_strings;
            global $app_strings;

            $error_message = '';
            if (isset($mod_strings[$arguments['errorMessage']])) {
                $error_message = $mod_strings[$arguments['errorMessage']];
            } else if (isset($app_strings[$arguments['errorMessage']])) {
                $error_message = $app_strings[$arguments['errorMessage']];
            } elseif ($arguments['errorMessage'] != '') {
                $error_message = $arguments['errorMessage'];
            }
            if ($error_message != '') {
                SugarApplication::appendErrorMessage($error_message);
            }
        }
        return $ret;
    }

    /**
     * Warning! if frontend is not set, return false
     * @return type
     */
    public function frontend()
    {
        return <<<EOQ
      var ret = arguments['formula'];
      var error_message = '';

      if(ret==false||ret=='false'){
         var alert_msg = viewTools.language.get( window.viewTools.form.getModuleName($( 'form .vt_formulaSelector' ).last()), arguments['errorMessage'] );
         if( alert_msg!='undefined' && alert_msg!='' ){
            error_message = alert_msg;
         }
         else{
            alert_msg = viewTools.language.get( 'app_strings', arguments['errorMessage'] );
            if( alert_msg!='undefined' && alert_msg!='' ){
               error_message = alert_msg;
            }
            else if( arguments['errorMessage']!='undefined' && arguments['errorMessage']!='' ){
               error_message = arguments['errorMessage'];
            }
         }
         if( error_message!='' ){
            window.viewTools.cache.AEM.push(error_message);
         }
      }
      return ret;
EOQ;
    }

}
