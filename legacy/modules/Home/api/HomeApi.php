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

class HomeApi {

   protected function loadRecordData($args) {
      if ( !is_null($args['record_id']) && !is_null($args['module_name']) ) {
         global $timedate;
         global $current_user;
         global $db;
         $query = "SELECT * FROM {$args['module_name']} WHERE id='{$args['record_id']}'";
         $rd = $db->fetchOne($query);
         if ( is_array($rd) ) {
            foreach ( $rd as $name => $value ) {
               //Change every date to user format
               if ( DateTime::createFromFormat("{$timedate->dbDayFormat} {$timedate->dbTimeFormat}", $value) !== false ) {
                  $row[$name] = $timedate->to_display_date_time($value, true, true, $current_user);
               }
               //if column is not date, rewrite without date-formatting
               else {
                  $row[$name] = htmlentities($value, ENT_QUOTES);
               }
            }
         }
         return $row;
      }
      return null;
   }

   public function getDateTimeFormat() {
      global $timedate;
      return json_encode(array(
         'dateFormat' => $timedate->get_date_format(),
         'timeFormat' => $timedate->get_time_format(),
      ));
   }

   public function getRecordValues($args) {
      return json_encode($this->loadRecordData($args));
   }

   public function getExpressionsDocumentation() {
      return json_encode(
              array(
                 'documentation' => file_get_contents('include/ViewTools/Expressions/documentation.html')
              )
      );
   }

   /*
    * For vt_duplicate declaration,funtion below will search for duplicates
    */

   public function checkForDuplicates($args) {

      global $db;
      global $timedate;
      global $current_user;
      global $current_language;
      $mod_strings = return_module_language($current_language, $args['modulename']);

      SugarAutoLoader::requireWithCustom('include/ViewTools/Expressions/cache.php');
      SugarAutoLoader::requireWithCustom('include/ViewTools/Expressions/VTExpression.php');
      //Check if any change was made
      $bean = BeanFactory::getBean($args['modulename'], $args['recordid']);
      $found_duplicatefield_changes = false;
      foreach ( $duplicate[strtolower($args['modulename'])]['fields'] as $field ) {
         if ( $bean->$field != $args['vt_' . $field] ) {
            $found_duplicatefield_changes = true;
         }
      }
      //If not, no not check for duplicates
      if ( $found_duplicatefield_changes == false ) {
         return json_encode(array(
            'duplicate_count' => 0,
            'duplicate_labels' => array(),
            'duplicate_positions' => array()
         ));
      }
      //Some changes was made - check if record is duplicate
      else {
         $record_values = array();
         foreach ( $args as $field_name => $field_value ) {
            if ( substr($field_name, 0, 3) == 'vt_' ) {
               $field_name_sugar = substr($field_name, 3);
               //Datetime field check
               if ( in_array($bean->field_defs[$field_name_sugar]['type'], [ 'datetime', 'datetimecombo', 'date', 'time' ]) ) {
                  $record_values[$field_name_sugar] = VTExpression::toSqlDateTime($field_value, $bean->field_defs[$field_name_sugar]['type']);
               } else {
                  $record_values[$field_name_sugar] = $field_value;
               }
            }
         }
         if ( count($record_values) > 0 ) {
            //Find module language file
            foreach ( scandir('cache/modules') as $dir ) {
               if ( strtolower($dir) == strtolower($args['modulename']) ) {
                  include_once ('cache/modules/' . $dir . '/language/' . $current_language . '.lang.php');
               }
            }
            $duplicateQuery = $this->buildDuplicateQuery($args, $record_values, $duplicate);
            //Get row names
            $row_names = array();
            $duplicateColumns = $duplicate[strtolower($args['modulename'])]['duplicateColumns'];
            if ( is_array($duplicateColumns) ) {
               foreach ( $duplicateColumns as $field_name ) {
                  $row_names[] = $mod_strings[$label[strtolower($args['modulename'])]['duplicateColumns'][$field_name]];
               }
            } else {
               $columns = $duplicate[strtolower($args['modulename'])]['fields'];
               foreach ( $columns as $field_name ) {
                  $row_names[] = $mod_strings[$label[strtolower($args['modulename'])][$field_name]];
               }
            }
         }
         $response = array();
         $res = $db->query($duplicateQuery);
         while ( ($row = $db->fetchByAssoc($res)) !== false ) {
            /*
              foreach ( $row as $field_name => $value ) {
              //Change every date to user format
              if ( SugarDateTime::createFromFormat(TimeDate::DB_DATETIME_FORMAT, $value) ) {
              $row[$field_name] = $timedate->to_display_date_time($value, true, true, $current_user);
              }
              if ( SugarDateTime::createFromFormat(TimeDate::DB_DATE_FORMAT, $value) ) {
              $row[$field_name] = $timedate->to_display_date($value);
              }
              }
             */
            $response[] = $row;
         }

         $i = 0;
         foreach ( $response as $id ) {
            $readed_bean = BeanFactory::getBean($args['modulename'], $id['id']);
            if ( $readed_bean->id && is_array($duplicateColumns) ) {
               foreach ( $duplicateColumns as $field_name ) {
                  $response[$i][$field_name] = $readed_bean->$field_name;
               }
               $i++;
            }
         }

         return json_encode(array(
            'duplicate_count' => count($response),
            'duplicate_labels' => $row_names,
            'duplicate_positions' => $response
         ));
      }
   }

   protected function buildDuplicateQuery($args, $record_values, $duplicate) {
      SugarAutoLoader::requireWithCustom('include/ViewTools/Expressions/VTFormulaParser.php');
      $duplicate_search = new VTFormulaParser();
      VTExpression::setValues($record_values);
      $duplicateQuery = 'SELECT id FROM ' . strtolower($args['modulename']);
      if ( !is_null($duplicate[strtolower($args['modulename'])]['formula']) ) {
         //Get where from formula definitions (build formula logic)
         $where = $duplicate_search->evalFormulaExpression($duplicate_search->prepareFormulaExpression($duplicate[strtolower($args['modulename'])]['formula'], 'sqlFormula'));
         $duplicateQuery .= ' WHERE ' . $where;

//If record is edited, exclude current record in duplicate check
         if ( !is_null($args['recordid']) && $args['recordid'] != '' ) {
            $duplicateQuery .= ' AND id != \'' . $args['recordid'] . '\'';
         }
         $duplicateQuery .= ' AND deleted = 0';
      }
      return $duplicateQuery;
   }

   /**
    * Each formula can be used in frontend without prepared frontend declaration 
    * by evaluating backend function and sending back result of calculations
    */
   public function evalServersideFrontend($args) {
      $record_values = array();
      $eval_formula = $args['eval_formula'];
      foreach ( $args as $field_name => $field_value ) {
         if ( substr($field_name, 0, 3) == 'vt_' ) {
            $record_values[substr($field_name, 3)] = $field_value;
         }
      }
      SugarAutoLoader::requireWithCustom('include/ViewTools/Expressions/VTExpression.php');
      SugarAutoLoader::requireWithCustom('include/ViewTools/Expressions/VTFormulaParser.php');

      VTExpression::setTableName($args['modulename']);
      VTExpression::setValues($record_values);
      VTExpression::setRecordId($args['recordid']);
      VTExpression::setModuleName($args['modulename']);
      VTExpression::setAction($args['action']);

      $args['eval_formula'] = $args['formula_name'] . '(' . str_replace('&#039;', '\'', $eval_formula) . ')';
      $formula = VTFormulaParser::buildFormulaExpression($args['eval_formula']);
      $response = VTFormulaParser::evalFormulaExpression($formula);
      return json_encode($response);
   }

   /**
    * Add job to schedulers
    * @return type
    */
   public function rebuildLock() {
      try {
         require_once 'modules/Administration/viewToolsRebuild.php';
         //Add rebuild to queue
         if ( $GLOBALS['sugar_config']['developerMode'] != true ) {
            $lock_status = json_decode($this->rebuildCheckLock());
            //Check if scheduler job is already added to SchedulerQueue
            if ( $lock_status->lock == '1' || $lock_status->lock === false ) {
               //Unlock rebuild
               $rebuild = fopen('include/ViewTools/Expressions/rebuild.lock', 'w');
               fwrite($rebuild, '');
               fclose($rebuild);
               //Add job to queue
               require_once('include/SugarQueue/SugarJobQueue.php');
               $job = new SchedulersJob();
               $job->name = "Rebuild View Tools";
               $job->data = "";
               $job->target = "function::rebuildViewTools";
               $job->assigned_user_id = 1;
               $jq = new SugarJobQueue();
               $jq->submitJob($job);
            }
         } else {
            require_once 'modules/Administration/viewToolsJSGroupings.php';
            $rebuild = fopen('include/ViewTools/Expressions/rebuild.lock', 'w');
            fwrite($rebuild, '1');
            fclose($rebuild);
         }
         //Return scheduler adding status
         return json_encode(array( 'status' => 'ok' ));
      } catch ( Exception $e ) {
         echo $e->getMessage();
      }
   }

   /**
    * 
    * @return type
    */
   public function rebuildCheckLock() {
      $rebuild = file_get_contents('include/ViewTools/Expressions/rebuild.lock');
      return json_encode(array( 'lock' => $rebuild ));
   }

   public function getLanguage($args) {
      $language = return_module_language($GLOBALS['current_language'], $args['target_module']);
      return json_encode($language);
   }

   public function getJSCalendarVariables()
    {
        global $timedate, $current_user;
        $cal_date_format = $timedate->get_cal_date_format();
        $user_date_format = $timedate->get_user_date_format();
        $user_time_format = $timedate->get_user_time_format();
        $calendar_fdow = $current_user->get_first_day_of_week();
        $time_separator = ':';
        if (preg_match('/\d+([^\d])\d+([^\d]*)/s', $user_time_format, $match)) {
            $time_separator = $match[1];
        }
        $t23 = strpos($user_time_format, '23') !== false ? '%H' : '%I';
        if (!isset($match[2]) || empty($match[2])) {
            $calendar_format = $cal_date_format . ' ' . $t23 . $time_separator . '%M';
        } else {
            $pm = $match[2] === 'pm' ? '%P' : '%p';
            $calendar_format = $cal_date_format . ' ' . $t23 . $time_separator . '%M' . $pm;
        }
        return [
            $cal_date_format,
            $user_date_format,
            $user_time_format,
            $calendar_format,
            $time_separator,
            $calendar_fdow,
        ];
    }

}
