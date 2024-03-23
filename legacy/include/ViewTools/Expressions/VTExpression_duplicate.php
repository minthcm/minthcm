<?php

/**
 * Searches for duplicates
 * EOU:
 * "duplicate(equals(@name,$name))" will give us true if found record with @name same as record $name
 */
class VTExpression_duplicate extends VTExpression
{

    /**
     * Variable used for creating documentation. As value,
     * please set array of functions, which this formula can be used
     */
    public $availability = array('vt_calculated', 'vt_dependency', 'vt_required', 'vt_validation');
    /**
     * if $inputParams are not set, return false
     * Definition of input params
     */
    public $inputParams = array('duplicate_formula');
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
    public function backend($arguments = array())
    {
        global $db;

        SugarAutoLoader::requireWithCustom('include/ViewTools/Expressions/cache.php');
        SugarAutoLoader::requireWithCustom('include/ViewTools/Expressions/VTFormulaParser.php');
        SugarAutoLoader::requireWithCustom('include/ViewTools/Expressions/VTExpression.php');

        $duplicateQuery = 'SELECT * FROM ' . strtolower(VTExpression::getTableName());
        //Get where from formula definitions (build formula logic)
        $duplicateQuery = $duplicateQuery . ' WHERE (' . $arguments['duplicate_formula'] . ')';
        //If record is edited, exclude current record in duplicate check
        if (VTExpression::getRecordId() != null && VTExpression::getRecordId() != '') {
            $duplicateQuery = $duplicateQuery . ' AND id != \'' . VTExpression::getRecordId() . '\'';
        }
        $duplicateQuery = $duplicateQuery . ' AND deleted = 0';
        $res = $db->query($duplicateQuery);
        //If found duplicate
        if ($db->fetchByAssoc($res) !== false) {
            return true;
        }
        return false;
    }

}
