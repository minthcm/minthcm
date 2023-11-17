<?php


class VTFormulaParser {

   private static $vt_expression_list = null;
   private static $VTFormula = array();

   public static function getFormulaClassSource($formula_name) {
      if ( static::$vt_expression_list === null ) {
         $vt_expression_list = array();
         $cache_file = 'include/ViewTools/Expressions/cache.php';
         if (!file_exists($cache_file)) {
             include 'modules/Administration/viewToolsRebuild.php';
         }
         require $cache_file;
         static::$vt_expression_list = $vt_expression_list;
      }
      if ( !is_null(static::$vt_expression_list[$formula_name]) ) {
         return static::$vt_expression_list[$formula_name];
      }
      return false;
   }

   /**
    * require class / create and return object
    */
   public static function getFormulaObject($formula_name = null) {
      if ( is_array($formula_name) ) {
         $formula_name = $formula_name[0];
      }
      // Check if object is already defined in VTFormula array
      if ( isset(static::$VTFormula[$formula_name]) && !is_null(static::$VTFormula[$formula_name]) ) {
         return static::$VTFormula[$formula_name];
      }
      /*
       * If not, check if class exists.
       * If yes, define object in VTFormula array and return object
       */ else {
         $formula_source = static::getFormulaClassSource($formula_name);
         if ( $formula_source !== false ) {
            require_once($formula_source);
            $tmp_class_name = 'VTExpression_' . $formula_name;
            static::$VTFormula[$formula_name] = new $tmp_class_name();
            return static::$VTFormula[$formula_name];
         }
      }
      return false;
   }

   /**
    * Eval formula defined as 'eval(formula($as,\'Expression\'))'
    */
   public static function evalFormulaExpression($parsed_formula) {
      //Eval parsed formula
      if ( !is_null($parsed_formula) ) {
         $tmpFormulaRet = false;
         eval("\$tmpFormulaRet={$parsed_formula};");
         return $tmpFormulaRet;
      }
      return false;
   }

   /**
    * Build php code which can be eval'ed
    * @param type $formula
    * @return string
    */
   public static function buildFormulaExpression($formula) {
      /*
       * require file with $sql_formula definitions
       */
      $sql_positions = array();
      include('include/ViewTools/Expressions/cache.php');
      $index_of = 0;
      if ( is_array($sql_formula) ) {
         foreach ( $sql_formula as $f_key ) {
            while ( ($index_of = strpos($formula, $f_key . '(', ($index_of))) !== false ) {
               $sql_positions[$index_of] = strlen($f_key) + 1;
               $index_of++;
            }
         }
      }
      /*
       * If found at least one sql declaration
       */
      if ( count($sql_positions) > 0 ) {

         $tmp_formula_sector_counter = 0;
         $tmp_formula_type = 'normal';
         $tmp_bracket_counter = 0;

         $key = 0;
         while ( $key < strlen($formula) ) {
            //Set analised sign
            $tmp_sign = $formula[$key];

            foreach ( $sql_positions as $didx => $value ) {
               if ( $key == ( int ) $didx + ( int ) $value ) {
                  $tmp_formula_sector_counter++;
                  $tmp_formula_type = 'sql';
                  $tmp_bracket_counter = 1;
               }
            }

            if ( $tmp_sign == '(' ) {
               $tmp_bracket_counter++;
            }
            if ( !!is_null($tmp_formula[$tmp_formula_sector_counter]) ) {
               $tmp_formula[$tmp_formula_sector_counter] = array(
                  'type' => $tmp_formula_type,
                  'string' => ''
               );
            }
            $tmp_formula[$tmp_formula_sector_counter]['string'] = $tmp_formula[$tmp_formula_sector_counter]['string'] . $tmp_sign;
            //Check sql end of declaration (end of processing only)
            if ( $tmp_sign == ')' ) {
               $tmp_bracket_counter--;
               if ( $tmp_bracket_counter <= 0 ) {
                  $tmp_formula_sector_counter++;
                  $tmp_formula_type = 'normal';
               }
            }
            $key++;
         }
         $formula = '';
         //Parse splitted formulas and build right formula
         foreach ( $tmp_formula as $key => $value ) {
            if ( $value['type'] == 'normal' ) {
               $tmp_formula[$key]['string'] = VTFormulaParser::prepareFormulaExpression($value['string']);
            } else if ( $value['type'] == 'sql' ) {
               $tmp_formula[$key]['string'] = VTFormulaParser::prepareFormulaExpression($value['string'], 'sqlFormula');
            }
            //Implode converted formula declaration
            $formula = $formula . $tmp_formula[$key]['string'];
         }
         return $formula;
      } else {
         return VTFormulaParser::prepareFormulaExpression($formula);
      }
   }

   /**
    * build right php-formula definition names
    */
   public static function prepareFormulaExpression($formula_expression = null, $evalFormula = 'backendFormula') {

      //Preparing sqlFormula
      if ( $evalFormula == 'sqlFormula' ) {
         $tmp_string = $formula_expression;
         $formula_expression = '';
         $left_flag = true;
         $key = 0;
         //parse '' signs to achieve "function("'smith'")" definition (single quotes put between double quotes)
         while ( $key < strlen($tmp_string) ) {
            $tmp_sign = $tmp_string[$key];
            if ( $left_flag == false && $tmp_sign == " " ) {
               $tmp_sign = "&#32;";
            }
            if ( $tmp_sign == "'" ) {
               if ( $left_flag == true ) {
                  $tmp_sign = '"\'';
                  $left_flag = false;
               } else {
                  $tmp_sign = '\'"';
                  $left_flag = true;
               }
            }
            $formula_expression = $formula_expression . $tmp_sign;
            $key++;
         }
      }

      //prepare ( and ) signs to avoid 'array(' and '))' conversion
      $convert_specialchars = false;
      $tmp_string = $formula_expression;
      $formula_expression = '';
      $key = 0;
      while ( $key < strlen($tmp_string) ) {
         $sign = $tmp_string[$key];
         if ( $convert_specialchars === true ) {
            if ( $sign == '(' ) {
               $sign = '&#40;';
            } elseif ( $sign == ')' ) {
               $sign = '&#41;';
            }
         }
         if ( $sign == '\'' ) {
            $convert_specialchars = !$convert_specialchars;
         }
         $formula_expression = $formula_expression . $sign;
         $key++;
      }
      //Prepare field selectors
      $formula_expression = preg_replace('/\$(\w+)/', '\'\$$1\'', $formula_expression);
      //Prepare db columns
      $formula_expression = preg_replace('/@(\w+)/', '\'@$1\'', $formula_expression);
      //
      $formula_expression = preg_replace('/#(\w+)/', '\'#$1\'', $formula_expression);
      //build formulaObj structure
      $formula_expression = preg_replace('/(\w+)\(/', 'VTFormulaParser::getFormulaObject(\'$1\')->' . $evalFormula . '(', $formula_expression);
      //replace "(" and ")" to "array(" and "))"
      $formula_expression = preg_replace('/\(/', '(array(', $formula_expression);
      $formula_expression = preg_replace('/\)/', '))', $formula_expression);
      //unconvert ( and ) signs
      $formula_expression = preg_replace(array( '/&#40;/', '/&#41;/', '/&#32;/' ), array( '(', ')', ' ' ), $formula_expression);

      while ( preg_match('/\{(.*?)(?<![\w\'])(["\w]+)(?![\w\'])(.*?)\}/', $formula_expression) ) {
         $formula_expression = preg_replace('/\{(.*?)(?<![\w\'])(["\w]+)(?![\w\'])(.*?)\}/', '{$1\'$2\'$3}', $formula_expression);
      }
      while ( preg_match('/\{(.*?):(.*?)\}/', $formula_expression) ) {
         $formula_expression = preg_replace('/\{(.*?):(.*?)\}/', '{$1 => $2}', $formula_expression);
      }
      $formula_expression = str_replace([ '{', '}' ], [ 'array(', ')' ], $formula_expression);
      return $formula_expression;
   }

   /**
    * @global Array $dictionary
    * @global Array $mod_strings
    * @param String $message
    */
   public function appendErrorMessage($message = null, &$bean, $field_key) {
      global $dictionary;
      global $mod_strings;
      //Check if declared message is mod_string key
      if ( !is_null($mod_strings[$message]) ) {
         $abortMessage = $mod_strings[$message];
      } else {
         $abortMessage = $message;
      }
      //Find entered $field_handler and replace them with field names
      $abortMessage = explode(" ", $abortMessage);
      foreach ( $abortMessage as $workKey => $word ) {
         if ( !is_null($mod_strings[$dictionary[$bean->object_name]['fields'][substr($word, 1)]['vname']]) ) {
            $abortMessage[$workKey] = $mod_strings[$dictionary[$bean->object_name]['fields'][substr($word, 1)]['vname']];
         }
      }
      $abortMessage = implode(" ", $abortMessage);
      if ( $bean->skip_vt_validation === true && !empty($field_key) ) {
         $bean->vt_validation_error_array[$field_key][] = $abortMessage;
      } else {
         SugarApplication::appendErrorMessage('[!] ' . $abortMessage);
      }
   }

}
