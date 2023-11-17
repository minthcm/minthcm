<?php
/**
 *
 * SugarCRM Community Edition is a customer relationship management program developed by
 * SugarCRM, Inc. Copyright (C) 2004-2013 SugarCRM Inc.
 *
 * SuiteCRM is an extension to SugarCRM Community Edition developed by SalesAgility Ltd.
 * Copyright (C) 2011 - 2018 SalesAgility Ltd.
 *
 * MintHCM is a Human Capital Management software based on SuiteCRM developed by MintHCM,
 * Copyright (C) 2018-2023 MintHCM
 *
 * This program is free software; you can redistribute it and/or modify it under
 * the terms of the GNU Affero General Public License version 3 as published by the
 * Free Software Foundation with the addition of the following permission added
 * to Section 15 as permitted in Section 7(a): FOR ANY PART OF THE COVERED WORK
 * IN WHICH THE COPYRIGHT IS OWNED BY SUGARCRM, SUGARCRM DISCLAIMS THE WARRANTY
 * OF NON INFRINGEMENT OF THIRD PARTY RIGHTS.
 *
 * This program is distributed in the hope that it will be useful, but WITHOUT
 * ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS
 * FOR A PARTICULAR PURPOSE. See the GNU Affero General Public License for more
 * details.
 *
 * You should have received a copy of the GNU Affero General Public License along with
 * this program; if not, see http://www.gnu.org/licenses or write to the Free
 * Software Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA
 * 02110-1301 USA.
 *
 * You can contact SugarCRM, Inc. headquarters at 10050 North Wolfe Road,
 * SW2-130, Cupertino, CA 95014, USA. or at email address contact@sugarcrm.com.
 *
 * The interactive user interfaces in modified source and object code versions
 * of this program must display Appropriate Legal Notices, as required under
 * Section 5 of the GNU Affero General Public License version 3.
 *
 * In accordance with Section 7(b) of the GNU Affero General Public License version 3,
 * these Appropriate Legal Notices must retain the display of the "Powered by SugarCRM"
 * logo and "Supercharged by SuiteCRM" logo and "Reinvented by MintHCM" logo.
 * If the display of the logos is not reasonably feasible for technical reasons, the
 * Appropriate Legal Notices must display the words "Powered by SugarCRM" and
 * "Supercharged by SuiteCRM" and "Reinvented by MintHCM".
 */
/**
 * This file is used to rebuild js View Tools files.
 * Each change in include/ViewTools/Expressions shuld be ended by execute This file.
 *
 * Warning!
 * Do not edit/format/delete this file, otherwise ViewTools will not
 * work properly.
 */
if (!defined('sugarEntry') || !sugarEntry) {
    die('Not A Valid Entry Point');
}
try {
    /*
     * Build cache files
     */
    chmod("include/ViewTools/javascript/viewTools.cache.js", 0777);
    chmod("include/ViewTools/Expressions/cache.php", 0777);

    $jscache = fopen('include/ViewTools/javascript/viewTools.cache.js', 'w');
    fwrite($jscache,
        '/**
 * Warning!
 * This file is generated automatically.
 * Edit only on your own responsibility
*/' . "\n");

    $phpcache = fopen('include/ViewTools/Expressions/cache.php', 'w');
    fwrite($phpcache,
        "<?php\n".'
/**
 *
 * SugarCRM Community Edition is a customer relationship management program developed by
 * SugarCRM, Inc. Copyright (C) 2004-2013 SugarCRM Inc.
 *
 * SuiteCRM is an extension to SugarCRM Community Edition developed by SalesAgility Ltd.
 * Copyright (C) 2011 - 2018 SalesAgility Ltd.
 *
 * MintHCM is a Human Capital Management software based on SuiteCRM developed by MintHCM, 
 * Copyright (C) 2018-2023 MintHCM
 *
 * This program is free software; you can redistribute it and/or modify it under
 * the terms of the GNU Affero General Public License version 3 as published by the
 * Free Software Foundation with the addition of the following permission added
 * to Section 15 as permitted in Section 7(a): FOR ANY PART OF THE COVERED WORK
 * IN WHICH THE COPYRIGHT IS OWNED BY SUGARCRM, SUGARCRM DISCLAIMS THE WARRANTY
 * OF NON INFRINGEMENT OF THIRD PARTY RIGHTS.
 *
 * This program is distributed in the hope that it will be useful, but WITHOUT
 * ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS
 * FOR A PARTICULAR PURPOSE. See the GNU Affero General Public License for more
 * details.
 *
 * You should have received a copy of the GNU Affero General Public License along with
 * this program; if not, see http://www.gnu.org/licenses or write to the Free
 * Software Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA
 * 02110-1301 USA.
 *
 * You can contact SugarCRM, Inc. headquarters at 10050 North Wolfe Road,
 * SW2-130, Cupertino, CA 95014, USA. or at email address contact@sugarcrm.com.
 *
 * The interactive user interfaces in modified source and object code versions
 * of this program must display Appropriate Legal Notices, as required under
 * Section 5 of the GNU Affero General Public License version 3.
 *
 * In accordance with Section 7(b) of the GNU Affero General Public License version 3,
 * these Appropriate Legal Notices must retain the display of the "Powered by SugarCRM" 
 * logo and "Supercharged by SuiteCRM" logo and "Reinvented by MintHCM" logo. 
 * If the display of the logos is not reasonably feasible for technical reasons, the 
 * Appropriate Legal Notices must display the words "Powered by SugarCRM" and 
 * "Supercharged by SuiteCRM" and "Reinvented by MintHCM".
 */
/* Warning!
 * This file is generated automatically.
 * Edit only on your own responsibility
*/'."\n");
    $tmp_expression_list = array();
    //At first scan main Expressions folder
    $Expressionlist = scandir('include/ViewTools/Expressions/');
    foreach ($Expressionlist as $key => $filename) {
        if (strpos($filename, 'VTExpression_') !== false) {
            $tmp_expression_list[substr($filename, 13, (strlen($filename) - 17))]
            = 'include/ViewTools/Expressions/' . $filename;
        }
    }
    //Then check ext folder (and eventually overwrite standard functions)
    if (is_dir('include/ViewTools/Expressions/ext/')) {
        $Expressionlist = scandir('include/ViewTools/Expressions/ext/');
        foreach ($Expressionlist as $key => $filename) {
            if (strpos($filename, 'VTExpression_') !== false) {
                $tmp_expression_list[substr($filename, 13,
                    (strlen($filename) - 17))] = 'include/ViewTools/Expressions/ext/' . $filename;
            }
        }
    }
    $vt_expression_list = [];
    //Write formula definitions to file
    foreach ($tmp_expression_list as $fname => $src) {
        $vt_expression_list[$fname] = $src;
        fwrite($phpcache,
            '$vt_expression_list[\'' . $fname . '\'] = \'' . $src . '\';' . "\n");
    }
    fclose($phpcache);

    /**
     * Define serversideFrontend functions
     */
    require_once 'include/ViewTools/Expressions/cache.php';
    require_once 'include/ViewTools/Expressions/VTExpression.php';
    $formula_list = array();
    foreach ($vt_expression_list as $formula => $source) {
        require_once $source;
        $validationClass = 'VTExpression_' . $formula;
        $validationObj = new $validationClass();
        if ($validationObj->serversideFrontend === true) {
            $formula_list[] = $formula;
        }
    }
    fwrite($jscache,
        '
window.viewTools.cache.serversideFrontend=' . json_encode($formula_list) . ';');

    /**
     * Build View Tools frontend functions
     */
    $returnFormulas = '';
    $validationFields = array();
    if(!empty($vt_expression_list) && is_array($vt_expression_list)){
        ksort($vt_expression_list);
    }
    foreach ($vt_expression_list as $functionName => $fileSource) {
        $validationClass = 'VTExpression_' . $functionName;
        $validationObj = new $validationClass();
        if ($validationObj->frontend() !== false || $validationObj->serversideFrontend
            === true) {
            $returnFormulas = $returnFormulas . '
window.viewTools.formula.' . $functionName . '=function(){
   try {';
            if ($validationObj->serversideFrontend === true) {
                $returnFormulas .= <<<EOT
      var values = viewTools.form.getFormValues();
      values['eval_formula'] = arguments[0];
      values['formula_name'] = '{$functionName}';
      if($('#ConvertLead').length > 0){
         var submodule = '';
         var elem = $('[data-validation*="'+arguments[0]+'"], [data-required*="'+arguments[0]+'"], [data-readonly*="'+arguments[0]+'"], [data-calculated*="'+arguments[0]+'"], [data-dependency*="'+arguments[0]+'"]');
         var module_elem = elem.closest('div[id^="create"]');
         if(!module_elem.length) {
            module_elem = elem.parent();
            var elem_id = module_elem.attr('id');
            if(typeof elem_id === 'string' && elem_id.indexOf('select') === 0) {
               submodule = elem_id.replace('select', '');
            }
         } else {
            submodule = module_elem.attr('id').replace('create', '');
         }

         values['convert_lead'] = true;
         values['convert_lead_module'] = submodule;
      }
      var ret = false;
      viewTools.api.callCustomApi({
         module:'Home',
         action:'evalServersideFrontend',
         async: false,
         dataPOST:values,
         callback:function(response){
            if(response!==undefined){
               ret = response;
            }
            else{
               ret = false;
            }
         }
      });
      return ret;
EOT;
            } else {
                $returnFormulas = $returnFormulas . '//Get arguments values
      for ( key in arguments ) {
         arguments[key] = viewTools.formula.valueOf( arguments[key] );
      }';
                if ($validationObj->inputParams !== false) {
                    $returnFormulas = $returnFormulas . '
      //set argument names
      var tmpArgumentsKeys = ' . json_encode($validationObj->inputParams) . ';
      var tmpArguments = [ ];
      var x = 0;
      for ( key in tmpArgumentsKeys ) {
         if ( arguments[x] !== undefined ) {
            tmpArguments[tmpArgumentsKeys[key]] = (typeof arguments[x] == "string")?$.trim(arguments[x]):arguments[x];
         }
         x++;
      }
      arguments = tmpArguments;
';
                }
                $returnFormulas = $returnFormulas . '
' . $validationObj->frontend();
            }
            $returnFormulas = $returnFormulas . '
   } catch ( e ) {
      viewTools.console.log( e );
      return false;
   }
}';
        }
        $sqlformulas = array();
        foreach ($vt_expression_list as $functionName => $fileSource) {
            $validationClass = 'VTExpression_' . $functionName;
            $validationObj = new $validationClass();
            if ($validationObj->sqlBackendFormula === true) {
                $sqlformulas[] = "'{$functionName}'";
            }
        }

        //declare frontend function as validation-formula
        if ($validationObj->inputParams !== false) {
            $paramCounter = 1;
            $frontendValidationParams = '';
            foreach ($validationObj->inputParams as $param) {
                $frontendValidationParams = $frontendValidationParams . '
         ' . $param . ':{' . 'order:' . $paramCounter . '},';
                $paramCounter++;
            }
            $validationFields[$functionName] = '   ' .
                $functionName . ':{
      formulaName:\'' . $functionName . '\',
      params:{' . $frontendValidationParams . '
      }
   },
';
        }
    }
    fwrite($jscache,
        '
   window.viewTools.formulaParser = {
' . implode("", $validationFields) . '}');

    $phpcache = fopen("include/ViewTools/Expressions/cache.php", "a") or die("Unable to open 'cache.php' file!");
    fwrite($phpcache, '
$sql_formula=array(' . implode(",", $sqlformulas) . ');
');
    fclose($phpcache);

    fwrite($jscache, '
' . $returnFormulas);

    /*
     * Declare availability filters
     */
    $tmp_availablilities = array('All' => 'vt_formula');
    foreach ($vt_expression_list as $functionName => $fileSource) {
        $validationClass = 'VTExpression_' . $functionName;
        $validationObj = new $validationClass();
        foreach ($validationObj->availability as $available_in) {
            $tmp_availablilities[$available_in] = $available_in;
        }
    }
    $formula_availablilities = '';
    foreach ($tmp_availablilities as $name => $class) {
        $formula_availablilities = $formula_availablilities . ' | <a style="cursor:pointer;" class="filterButton ' . $class . '" onclick="setFilter(\'' . $class . '\')">' . $name . '</a>';
    }

    //Build ExpressionList documentation
    $documentationFileHandler = fopen('include/ViewTools/Expressions/documentation.html',
        'w');
    fwrite($documentationFileHandler,
        file_get_contents('include/ViewTools/Expressions/documentationHeader.html'));
    fwrite($documentationFileHandler,
        "<b style=\"color:#aaa;\">Please put custom formula classes in ' custom / Expressions / ext / ' directory. </b><br/>\n");
    fwrite($documentationFileHandler,
        "<i style=\"color:#aaa;\">[Documentation generated : " . date('Y-m-d h:i:s') . "]</i><br/>\n");

    fwrite($documentationFileHandler,
        "<div style=\"border-top: 1px solid #cccccc;margin-top:5px; padding-top:5px;\">Filter:{$formula_availablilities}</div>\n");

    foreach ($vt_expression_list as $functionName => $fileSource) {
        $validationClass = 'VTExpression_' . $functionName;
        $validationObj = new $validationClass();
        fwrite($documentationFileHandler,
            "<div style=\"border-top: 1px solid #cccccc;margin-top:5px; padding-top:5px;\" class=\"vt_formula " . implode(" ",
                $validationObj->availability) . "\">\n");
        fwrite($documentationFileHandler,
            "<div><h1><b><a id=\"{$functionName}\">{$functionName}</a></b>");

        if ($validationObj->inputParams != false) {
            fwrite($documentationFileHandler,
                "<span style=\"color:grey;\"> (" . implode(' , ',
                    $validationObj->inputParams) . ")</span>");
        }
        fwrite($documentationFileHandler, "</h1></div>\n");
        $fileHandler = fopen($fileSource, "r");
        /*
         * Field used for reading Expression class description
         * null - searching for desctiption
         * false - reading description
         * true - end of description
         */
        $docEndFlag = null;
        while (($buffer = fgets($fileHandler, 4096)) !== false && $docEndFlag
            !== true) {
            //Search for description
            if ($docEndFlag === null) {
                //Found start of description
                if (preg_match("/\/\*\*/", $buffer) > 0) {
                    $docEndFlag = false;
                }
            }
            //Read description
            else if ($docEndFlag === false) {
                //End of description
                if (preg_match("/\*\//", $buffer) > 0) {
                    $docEndFlag = true;
                }
                //Read description
                else {
                    //Predefined tags
                    $buffer = preg_replace("/EOU:/", "<i>Example of use:</i>",
                        $buffer);
                    //Replace function names as links to formula descriptions
                    foreach ($vt_expression_list as $fname => $null) {
                        $buffer = preg_replace("/{$fname}\(/",
                            "<a href=\"#{$fname}\">{$fname}</a>(", $buffer);
                    }
                    //set $field_name as bold
                    $buffer = preg_replace('/\$(\w+)/',
                        '<b style="color:black;">\$$1</b>', $buffer);
                    //set color to strings
                    $buffer = preg_replace('/\'(.+)\'/',
                        '<span style="color:green;">\'$1\'</span>', $buffer);
                    //set color to numbers
                    $buffer = preg_replace('/\ (\d+)\ /',
                        ' <b style="color:orange;">$1</b> ', $buffer);
                    $buffer = preg_replace('/\ -(\d+)\ /',
                        ' <b style="color:orange;">-$1</b> ', $buffer);
                    //
                    $docLine = trim(preg_replace("/\ \*/", "", $buffer));
                    if ($docLine != "") {
                        fwrite($documentationFileHandler, $docLine . "<br/>\n");
                    }
                }
            }
        }
        fclose($fileHandler);
        if ($validationObj->availability != false) {
            $tmpWhereVavailable = 'only as formula';
            if ($validationObj->inputParams != false) {
                $tmpWhereVavailable = 'as formula and array';
            }
            fwrite($documentationFileHandler,
                "<i style=\"color:grey;\">Available in: " . implode(' , ',
                    $validationObj->availability) . " / {$tmpWhereVavailable}</i><br/>\n");
        }
        fwrite($documentationFileHandler, "</div>\n");
    }
    fclose($documentationFileHandler);

    /*
     * Require all module vardefs
     */
    foreach (scandir('cache/modules') as $folder) {
        if ($folder != '.' && $folder != '..' && file_exists('cache/modules/' . $folder)
            && is_dir('cache/modules/' . $folder)) {
            foreach (scandir('cache/modules/' . $folder) as $file) {
                if ($file !== '.' && $file != '..' && strpos($file,
                    'vardefs.php') !== false) {
                    require_once 'cache/modules/' . $folder . '/' . $file;
                }
            }
        }
    }
    /*
     * Get all vt_calculated, vt_dependency, vt_required, vt_readonly vt_ definitions
     */
    include_once 'cache/Relationships/relationships.cache.php';
    $initArray = array();
    foreach ($GLOBALS["dictionary"] as $moduleName => $moduleData) {
        if (is_array($moduleData['fields'])) {
            foreach ($moduleData['fields'] as $fieldName => $params) {
                foreach ($params as $paramName => $paramData) {
                    //Found vt formula
                    if (in_array($paramName,
                        array('vt_calculated', 'vt_dependency', 'vt_required',
                            'vt_readonly'))) {
                        $module_lowercase = vtr_getModuleLowercase($moduleName, $moduleData);
                        if (is_array($paramData)) {
                            foreach ($paramData as $paramDataField => $paramDataValue) {
                                if (substr($paramDataValue, 0, 1) == '$') {
                                    $initArray[$module_lowercase][substr($paramDataValue,
                                        1)][$fieldName] = $fieldName;
                                }
                            }
                        } else if (is_string($paramData)) {
                            $variables = array();
                            //Find all form fields
                            $paramData = preg_replace('/\ /', '', $paramData);
                            preg_match_all('/\$(\w+)/i', $paramData, $variables);
                            foreach ($variables[1] as $matchedVariable) {
                                $initArray[$module_lowercase][$matchedVariable][$fieldName]
                                = $fieldName;
                            }
                            //check all relation definitions
                            $paramData = preg_replace('/\ /', '', $paramData);
                            preg_match_all('/\#(\w+)/i', $paramData, $variables);
                            foreach ($variables[1] as $matchedVariable) {
                                $focus = new $moduleName();
                                if ($focus->load_relationship($matchedVariable)) {
                                    $def = $focus->$matchedVariable->relationship->def;
                                    //if additional relation table is used
                                    if (isset($def['join_table'])) {
                                        if ($focus->module_name != $def['lhs_module']) {
                                            $get_foreign_key_from = 'lhs';
                                        } else {
                                            $get_foreign_key_from = 'rhs';
                                        }
                                        foreach ($focus->field_defs as $field) {
                                            if ($field['id_name'] == $def["join_key_" . $get_foreign_key_from]
                                                && $field['link'] == $matchedVariable) {
                                                $initArray[$module_lowercase][$field['name']][$fieldName]
                                                = $fieldName;
                                                break;
                                            }
                                        }
                                    } else {
                                        //lhs_key
                                        if (isset($def['lhs_key'])) {
                                            $initArray[$module_lowercase][$def['lhs_key']][$fieldName]
                                            = $fieldName;
                                        }
                                        //rhs_key
                                        if (isset($defs[$matchedVariable]['rhs_key'])) {
                                            $initArray[$module_lowercase][$def['rhs_key']][$fieldName]
                                            = $fieldName;
                                        }
                                    }
                                    /*
                                if ( isset($def['relationships']) ) {
                                $add_table = $def['relationships'];
                                //join_key_lhs
                                if ( isset($add_table['join_key_lhs']) ) {
                                $initArray[$module_lowercase][$add_table['join_key_lhs']][$fieldName] = $fieldName;
                                }
                                //join_key_rhs
                                if ( isset($add_table['join_key_rhs']) ) {
                                $initArray[$module_lowercase][$add_table['join_key_rhs']][$fieldName] = $fieldName;
                                }
                                }
                                 */
                                }
                                if (isset($relationships[$matchedVariable])) {
                                    $relationship = $relationships[$matchedVariable];
                                    //lhs_key
                                    if (isset($relationship['lhs_key'])) {
                                        $initArray[$module_lowercase][$relationship['lhs_key']][$fieldName]
                                        = $fieldName;
                                    }
                                    //rhs_key
                                    if (isset($relationships[$matchedVariable]['rhs_key'])) {
                                        $initArray[$module_lowercase][$relationship['rhs_key']][$fieldName]
                                        = $fieldName;
                                    }
                                    if (isset($relationship['relationships'])) {
                                        $add_table = $relationship['relationships'];
                                        //join_key_lhs
                                        if (isset($add_table['join_key_lhs'])) {
                                            $initArray[$module_lowercase][$add_table['join_key_lhs']][$fieldName]
                                            = $fieldName;
                                        }
                                        //join_key_rhs
                                        if (isset($add_table['join_key_rhs'])) {
                                            $initArray[$module_lowercase][$add_table['join_key_rhs']][$fieldName]
                                            = $fieldName;
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }
    }

    require_once 'modules/Administration/LeadConversionViewToolsRebuild/ViewToolsLeadConverRebuild.php';

    $viewTools_lead_converter_rebuild = new ViewToolsLeadConverRebuild();
    $parsedInitArray = $viewTools_lead_converter_rebuild->addLeadConversionDependency($initArray);

    fwrite($jscache,
        ';
window.viewTools.cache.initMappings = ' . json_encode($parsedInitArray) . ';');

    /*
     * Get all field requirements
     */
    $field_requirements = array();
    foreach ($GLOBALS["dictionary"] as $key => $module) {
        if (is_array($module['fields'])) {
            foreach ($module['fields'] as $params) {
                if (isset($params['required'])) {
                    $module_lowercase = vtr_getModuleLowercase($key, $module);
                    $field_requirements[$module_lowercase][$params['name']] = (bool) $params['required'];
                }
            }
        }
    }

    $parsed_field_requirements = $viewTools_lead_converter_rebuild->addLeadConversionRequirements($field_requirements);

    fwrite($jscache,
        '
window.viewTools.cache.formulaRequirements = ' . json_encode($parsed_field_requirements) . ';');

    /*
     * Get duplicate fields from duplicate definitions
     */
    $duplicate_fields = array();
    foreach ($GLOBALS["dictionary"] as $key => $module) {
        $module_lowercase = vtr_getModuleLowercase($key, $module);
        if (isset($module['vt_duplicate']) && is_string($module['vt_duplicate'])) {
            $variables = array();
            $module['vt_duplicate'] = preg_replace('/\ /', '',
                $module['vt_duplicate']);
            preg_match_all('/\$(\w+)/i', $module['vt_duplicate'], $variables);
            foreach ($variables[1] as $matchedVariable) {
                $duplicate_fields[$module_lowercase][$matchedVariable] = $matchedVariable;
            }
            preg_match_all('/\@(\w+)/i', $module['vt_duplicate'], $variables);
            foreach ($variables[1] as $matchedVariable) {
                $duplicate_fields[$module_lowercase][$matchedVariable] = $matchedVariable;
            }
        }
    }
    fwrite($jscache,
        '
window.viewTools.cache.formulaDuplicateFields = ' . json_encode($duplicate_fields) . ';');

    /*
     * Get duplicate formulas from duplicate definitions
     */
    $duplicate_formulas = '';
    foreach ($GLOBALS["dictionary"] as $key => $module) {
        $module_lowercase = vtr_getModuleLowercase($key, $module);
        if (isset($module['vt_duplicate'])) {
            $duplicate_formulas .= '$duplicate[\'' . $module_lowercase . '\'][\'formula\']=\'' . str_replace("'",
                "\'", $module['vt_duplicate']) . '\';' . "\n";

            $variables = array();
            $module['vt_duplicate'] = preg_replace('/\ /', '',
                $module['vt_duplicate']);
            preg_match_all('/[\$@](\w+)/i', $module['vt_duplicate'], $variables);
            foreach ($variables[1] as $matchedVariable) {
                $duplicate_formulas .= '$duplicate[\'' . $module_lowercase . '\'][\'fields\'][\'' . $matchedVariable . '\']=\'' . $matchedVariable . '\';' . "\n";
                $duplicate_formulas .= '$label[\'' . $module_lowercase . '\'][\'' . $matchedVariable . '\']=\'' . $module['fields'][$matchedVariable]['vname'] . '\';' . "\n";
            }
            if (isset($module['vt_duplicateColumns'])) {
                $module['vt_duplicateColumns'] = preg_replace('/\ /', '',
                    $module['vt_duplicateColumns']);
                preg_match_all('/(\w+)/i', $module['vt_duplicateColumns'],
                    $columns);
                foreach ($columns[1] as $matchedColumn) {
                    $duplicate_formulas .= '$duplicate[\'' . $module_lowercase . '\'][\'duplicateColumns\'][\'' . $matchedColumn . '\']=\'' . $matchedColumn . '\';' . "\n";
                    $duplicate_formulas .= '$label[\'' . $module_lowercase . '\'][\'duplicateColumns\'][\'' . $matchedColumn . '\']=\'' . $module['fields'][$matchedColumn]['vname'] . '\';' . "\n";
                }
            }
        }
    }
    $phpcache = fopen("include/ViewTools/Expressions/cache.php", "a") or die("Unable to open 'cache.php' file!");
    fwrite($phpcache, '
' . $duplicate_formulas);

    /*
     * check for relate
     */
    require 'cache/Relationships/relationships.cache.php';
    $related_tmp = array();
    foreach ($GLOBALS["dictionary"] as $module_name => $module) {
        if (isset($module['fields']) && is_array($module['fields'])) {
            foreach ($module['fields'] as $module_field) {
                if (isset($module_field['vt_calculated']) && is_string($module_field['vt_calculated'])) {
                    preg_match_all('/\#(\w+)/i', $module_field['vt_calculated'],
                        $vt_relationsip_tmp);
                    if (isset($vt_relationsip_tmp[1]) && count($vt_relationsip_tmp[1])
                        > 0) {
                        foreach ($vt_relationsip_tmp[1] as $relationship_name) {
                            $relationship_field = $module['fields'][$relationship_name];
                            $relationship = $relationships[$relationship_field['relationship']];
                            if ($relationship['lhs_table'] == $module['table']) {
                                $related_tmp[$relationship['rhs_table']][] = "'{$relationship_field['name']}'";
                            } else if ($relationship['rhs_table'] == $module['table']) {
                                $focus = BeanFactory::newBean($relationship['lhs_module']);
                                foreach ($focus->field_defs as $field) {
                                    if ($field['type'] == 'link' && $field['relationship']
                                        == $relationship['name']) {
                                        $related_tmp[$relationship['lhs_table']][]
                                        = "'{$field['name']}'";
                                        break;
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }
    }
    foreach ($related_tmp as $key => $module) {
        fwrite($phpcache,
            "\n\$related_recalculation['{$key}']=array(" . implode(',',
                array_unique($module)) . ");");
    }
    fclose($phpcache);

    //js cache closes
    fclose($jscache);
} catch (Exception $e) {
    echo $e->getMessage();
}

function vtr_getModuleLowercase($key, $module) {
    global $beanList;
    $flipped = array_flip($beanList);
    $module_lowercase = isset($flipped[$key]) ? strtolower($flipped[$key]) : '';
    if(empty($module_lowercase)){
        $module_lowercase = isset($module['table']) ? $module['table'] : '';
    }
    return $module_lowercase;
}
