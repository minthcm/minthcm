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

class KTreeViewGenerator {

   const TREE_COLUMN_NAME = 'tree_column';

   public $nodes_history;
   public $values_history;
   public $parent_node;
   public $column_node;
   public $is_leaf;
   protected $bean;
   protected $columns;
   protected $records;
   protected $group_until;
   protected $presentation_params;
   protected $original_records;
   protected $parsed_records;

   public function __construct($report_bean, $nodes_history = '[&quot;root&quot;]', $values_history = '[&quot;Root&quot;]', $where_conditions = '') {
      $this->bean = $report_bean;
      $this->bean->whereOverride = json_decode(html_entity_decode($where_conditions), true);

      $this->columns = json_decode(html_entity_decode($this->bean->listfields, ENT_QUOTES), true);
      $this->original_records = $this->bean->getSelectionResults(array( 'grouping' => 'off', 'noFormat' => true ));
      $this->presentation_params = json_decode(html_entity_decode($this->bean->presentation_params, ENT_QUOTES), true);
      $this->group_until = $this->presentation_params['pluginData']['kTreeViewProperties']['groupUntil'];

      $history = array(
         'nodes_history' => json_decode(html_entity_decode($nodes_history), true),
         'values_history' => json_decode(html_entity_decode($values_history), true),
      );
      $this->init($history);
   }

   protected function init($history) {
      $this->records = $this->original_records;
      $this->nodes_history = $history['nodes_history'];
      $this->values_history = $history['values_history'];
      $this->parent_node = end($this->nodes_history);
      $this->column_node = null;
      $this->is_leaf = false;
      $this->parsed_records = array();
      return $this;
   }

   public function generateColumns() {
      $columns_array = array();

      $last_column_found = false;
      $group_column_title = '';
      $group_column_width = 0;

      foreach ( $this->columns as $column ) {
         if ( $column['display'] == 'no' )
            continue;

         if ( !$last_column_found ) {
            $group_column_title .= (empty($group_column_title) ? $column['name'] : ' / ' . $column['name']);
            $group_column_width += $column['width'];

            if ( $column['fieldid'] == $this->group_until ) {
               $last_column_found = true;

               array_push($columns_array, array(
                  'xtype' => 'treecolumn',
                  'text' => $group_column_title,
                  'dataIndex' => self::TREE_COLUMN_NAME,
                  'width' => $group_column_width,
                  'sortable' => false
               ));
            }
         } else {
            array_push($columns_array, array(
               'text' => $column['name'],
               'dataIndex' => $column['fieldid'],
               'width' => $column['width'],
               'sortable' => false
            ));
         }
      }

      return $columns_array;
   }

   public function generateRecords() {
      $records = array();

      $this->column_node = $this->getCurrentColumnNode();
      $this->records = $this->removeUnnecessaryRecords($this->records);

      if ( $this->column_node != null ) {
         $nodes_history = $this->nodes_history;
         array_push($nodes_history, $this->column_node);

         foreach ( $this->records as $record ) {
            $found_group_until_column = false;
            $values_history = $this->values_history;
            array_push($values_history, self::removeInvalidSymbols($record[$this->column_node]));

            $record_row = array(
               'leaf' => $this->is_leaf,
               'expanded' => false,
               'nodes_history' => $nodes_history,
               'values_history' => $values_history
            );

            foreach ( $this->columns as $column ) {
               if ( $column['fieldid'] == $this->column_node ) {
                  $record_row[self::TREE_COLUMN_NAME] = $record[$column['fieldid']];
               } else if ( $found_group_until_column ) {
                  $record_row[$column['fieldid']] = $record[$column['fieldid']];
               }

               if ( $column['fieldid'] == $this->group_until ) {
                  $found_group_until_column = true;
               }
            }

            array_push($this->parsed_records, $record_row);
         }

         $this->parsed_records = $this->mergeRecords();

         $this->applyRecordsSorting();
         $this->formatFields();
      }

      return $this->parsed_records;
   }

   public function generateChildrenRecordsForNode($node) {
      if ( $node !== 'root' ) {
         $this->init($node);
      }

      $records = $this->generateRecords();
      foreach ( $records as $index => $record ) {
         if ( $record['leaf'] ) {
            continue;
         }
         $records[$index]['children'] = $this->generateChildrenRecordsForNode($record);
      }
      return $records;
   }

   protected function getCurrentColumnNode() {
      $column_node = null;

      $found_parent_fieldid = ($this->parent_node == 'root');
      foreach ( $this->columns as $column ) {
         if ( $found_parent_fieldid ) {
            $column_node = $column['fieldid'];

            if ( $column['fieldid'] == $this->group_until ) {
               $this->is_leaf = true;
            }
            break;
         }

         if ( $column['fieldid'] == $this->parent_node ) {
            $found_parent_fieldid = true;
         }
      }

      return $column_node;
   }

   protected function removeUnnecessaryRecords($records_array) {
      $return_array = $records_array;

      foreach ( $records_array as $record_index => $record ) {
         foreach ( $this->nodes_history as $node_index => $node ) {
            if ( $node != 'root' && html_entity_decode(self::removeInvalidSymbols($record[$node])) != $this->values_history[$node_index] ) {
               unset($return_array[$record_index]);
               break;
            }
         }
      }

      return $return_array;
   }

   protected function mergeRecords() {
      $merged_records = array();

      if ( $this->is_leaf ) {
         $merged_records = $this->parsed_records;

         foreach ( $this->parsed_records as $record_index => $record ) {
            foreach ( $this->columns as $column ) {
               if ( array_key_exists($column['fieldid'], $record) && isset($column['function']) ) {
                  switch ( $column['function'] ) {
                     case 'count':
                        $merged_records[$record_index][$column['fieldid']] = 1;
                  }
               }
            }
         }
      } else {
         foreach ( $this->parsed_records as $record ) {
            $this->appendRecordToArray($merged_records, $record);
         }
      }
      return $merged_records;
   }

   protected function mergeRecord(&$records_array, $record, $existing_index) {
      foreach ( $this->columns as $column ) {
         if ( array_key_exists($column['fieldid'], $record) ) {
            $func = isset($column['function']) ? $column['function'] : '';
            switch ( $func ) {
               case 'sum':
                  $records_array[$existing_index][$column['fieldid']] += $record[$column['fieldid']];
                  break;
               case 'avg':
                  $fieldid_avg = $column['fieldid'] . '_avg';

                  $records_array[$existing_index][$column['fieldid']] *= $records_array[$existing_index][$fieldid_avg]['current_count'];
                  $records_array[$existing_index][$column['fieldid']] += $record[$column['fieldid']];
                  $records_array[$existing_index][$column['fieldid']] /= ++$records_array[$existing_index][$fieldid_avg]['current_count'];
                  break;
               case 'count':
                  $records_array[$existing_index][$column['fieldid']] += 1;
                  break;
               case 'min':
                  if ( $records_array[$existing_index][$column['fieldid']] > $record[$column['fieldid']] ) {
                     $records_array[$existing_index][$column['fieldid']] = $record[$column['fieldid']];
                  }
                  break;
               case 'max':
                  if ( $records_array[$existing_index][$column['fieldid']] < $record[$column['fieldid']] ) {
                     $records_array[$existing_index][$column['fieldid']] = $record[$column['fieldid']];
                  }
                  break;
               default:
                  $records_array[$existing_index][$column['fieldid']] .= ' ' . $record[$column['fieldid']];
            }
         }
      }
   }

   protected function appendRecordToArray(&$records_array, $record) {
      $existing_index = self::findExistingRecord($records_array, self::TREE_COLUMN_NAME, $record[self::TREE_COLUMN_NAME]);

      if ( $existing_index == -1 ) {
         foreach ( $this->columns as $column ) {
            if ( array_key_exists($column['fieldid'], $record) ) {
               $func = isset($column['function']) ? $column['function'] : '';
               switch ( $func ) {
                  case 'count':
                     $record[$column['fieldid']] = 1;
                     break;
                  case 'avg':
                     $record[$column['fieldid'] . '_avg'] = array(
                        'current_sum' => $record[$column['fieldid']],
                        'current_count' => 1,
                     );
                     break;
               }
            }
         }
         array_push($records_array, $record);
      } else {
         $this->mergeRecord($records_array, $record, $existing_index);
      }
   }

   protected function applyRecordsSorting() {
      foreach ( $this->columns as $column ) {
         if ( $column['fieldid'] == $this->column_node ) {
            $this->parsed_records = self::sortRecordsByField($this->parsed_records, self::TREE_COLUMN_NAME, $column['sort']);
         } else if ( !empty($column['function']) && $column['function'] != '-' && !empty($column['sort'] && $column['sort'] != '-') ) {
            $this->parsed_records = self::sortRecordsByField($this->parsed_records, $column['fieldid'], $column['sort']);
         }
      }
   }

   protected function formatFields() {
      foreach ( $this->parsed_records as $record_index => $record ) {
         foreach ( $this->columns as $column ) {
            if ( $column['display'] == 'no' ) {
               unset($this->parsed_records[$record_index][$column['fieldid']]);
               continue;
            }

            if ( isset($column['function']) && $column['function'] == 'count' )
               continue;
            switch ( $this->bean->fieldNameMap[$column['fieldid']]['type'] ) {
               case 'currency':
                  if ( isset($record[$column['fieldid']]) ) {
                     $this->parsed_records[$record_index][$column['fieldid']] = currency_format_number($record[$column['fieldid']]);
                  }
                  break;
            }
         }
      }
   }

   public static function findExistingRecord($records_array, $duplicate_field, $duplicate_value) {
      foreach ( $records_array as $index => $record ) {
         if ( $record[$duplicate_field] == $duplicate_value ) {
            return $index;
         }
      }
      return -1;
   }

   public static function sortRecordsByField($records_array, $field, $direction) {
      if ( $direction == 'asc' ) {
         usort($records_array, function($a, $b) use ($field) {
            if ( $a[$field] == $b[$field] ) {
               return 0;
            } else {
               return $a[$field] > $b[$field] ? 1 : -1;
            }
         });
      } else if ( $direction == 'desc' ) {
         usort($records_array, function($a, $b) use ($field) {
            if ( $a[$field] == $b[$field] ) {
               return 0;
            } else {
               return $a[$field] < $b[$field] ? 1 : -1;
            }
         });
      }
      return $records_array;
   }

   public static function removeInvalidSymbols($string) {
      $string = str_replace("&quot;", "", $string);
      return $string;
   }

}
