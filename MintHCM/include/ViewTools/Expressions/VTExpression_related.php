<?php

/**
 * Gets value from related modules.
 * Part of funcions (like "count") can be used in select_definition clause (see second example below). You can find them by calculated filter
 * If <i>use_middle_table</i> param is set to <i>true</i> then returns <i>select_definition</i> from middle table of <i>relationship</i> at the assumed conditions in <i>additional_conditions</i>
 * EOU:
 * "related(@name,#opportunities)" will give us name of related opportunity
 * "related(count(@name),#contacts,equals(@name,$name))" will give us number of related contacts, which have name equal to module field $name
 * "related(@account_id,#accounts_opportunities,equals(@opportunity_id,$opportunity_id),<b>true</b>)" will give us account_id from accounts_opportunities relationship table where opportunity_id field in table equals opportunity_id field on View.
 */
class VTExpression_related extends VTExpression {

   /**
    * Variable used for creating documentation. As value, 
    * please set array of functions, which this formula can be used
    */
   public $availability = array( 'vt_calculated', 'vt_dependency', 'vt_required', 'vt_readonly', 'vt_duplicate', 'vt_validation' );
   /**
    * if $inputParams are not set, return false
    * Definition of input params
    */
   public $inputParams = array( 'select_definition', 'relationship_name', 'additional_conditions', 'use_middle_table' );
   /**
    * If $serversideFrontend is true, frontend script declaration 
    * will be replaced with backend execution.
    * Warning! using frontend as $serversideFrontend too frequently
    * can strongly slow down module form.
    * Please use it carefully and on your own responsibility
    */
   public $serversideFrontend = true;
   /**
    * If $sqlBackendFormula is set to "true", backend formula 
    * will get sql "WHERE" definition from formulas 
    * defined inside "duplicate(formula(defined(inside)))"
    */
   public $sqlBackendFormula = true;

   /**
    * Warning! if backend is not set, return false
    * @param Array 
    * @return boolean
    * Please set input params as Array
    */
   public function backend($arguments = array()) {
      $db = DBManagerFactory::getInstance();
      include('cache/Relationships/relationships.cache.php');
      SugarAutoLoader::requireWithCustom('include/ViewTools/Expressions/cache.php');
      SugarAutoLoader::requireWithCustom('include/ViewTools/Expressions/VTFormulaParser.php');
      SugarAutoLoader::requireWithCustom('include/ViewTools/Expressions/VTExpression.php');
      $action = VTExpression::getAction();
      if ( $arguments['select_definition'][0] == '@' ) {
         $arguments['select_definition'] = substr($arguments['select_definition'], 1);
      }
      if ( $arguments['relationship_name'][0] == '#' ) {
         $arguments['relationship_name'] = substr($arguments['relationship_name'], 1);
      }
      $focus = BeanFactory::newBean(VTExpression::getModuleName());
      if ( $arguments['select_definition'] != '' && $arguments['relationship_name'] != '' && isset($focus->field_defs[$arguments['relationship_name']]) ) {
         $name_of_relationship = $focus->field_defs[$arguments['relationship_name']]['relationship'];
         $relationship = $relationships[$name_of_relationship];
         //Select definition
         $related_query = "SELECT IFNULL({$arguments['select_definition']},'') AS response";
         if ( $action == "evalServersideFrontend" || empty(VTExpression::getRecordId()) ) {
            $focus->load_relationship($arguments['relationship_name']);
            $opposite = $focus->{$arguments['relationship_name']}->getOppositeModuleNameAndKey();
            $opposite_focus = BeanFactory::getBean($opposite['module']);
            $related_query .= " FROM {$opposite_focus->table_name} WHERE id='" . VTExpression::getValue($opposite['key']) . "'";
         } else {
            //declare default side of relation
            $left_side = 'lhs';
            $right_side = 'rhs';
            //If module is declared on right side, swap field places (our record module always on left)
            if ( $relationship['rhs_table'] == strtolower(VTExpression::getTableName()) ) {
               $left_side = 'rhs';
               $right_side = 'lhs';
            }
            //Without relation table
            if ( is_null($relationship['relationships']) ) {
               $related_query = " {$related_query} FROM {$relationship[$right_side . '_table']}"
                       . " WHERE id='" . VTExpression::getValue($relationship[$left_side . '_key']) . "'";
            }
            //if uses indirect table
            else if ( !is_null($relationship['relationships'][$name_of_relationship]) ) {
               $indirect_table = $relationship['relationships'][$name_of_relationship];
               if ( $arguments['use_middle_table'] == true || $arguments['use_middle_table'] == 'true' ) {
                  $related_query = " {$related_query} FROM {$relationship['table']}"
                          . " WHERE 1 ";
               } else {
                  $join_id = '\'' . VTExpression::getValue($indirect_table['join_key_' . $left_side]) . '\'';
                  if ( $join_id == '\'\'' ) {
                     $join_id = "SELECT {$indirect_table['join_key_' . $right_side]} FROM {$indirect_table['join_table']} WHERE {$indirect_table['join_key_' . $left_side]} = '" . VTExpression::getRecordId() . "' AND deleted=0";
                  }
                  $related_query = " {$related_query} FROM {$relationship[$right_side . '_table']}"
                          . " WHERE {$relationship[$right_side . '_key']} IN ({$join_id})";
               }
            }
            if ( $arguments['additional_conditions'] != '' ) {
               $related_query = "$related_query AND {$arguments['additional_conditions']}";
            }
            $related_query = "$related_query AND deleted=0";
         }
         //Execute prepared query
         $res = $db->fetchByAssoc($db->query($related_query));
         if ( $res != false ) {
            return $res['response'];
         } else {
            return '';
         }
      }
      return false;
   }

}
