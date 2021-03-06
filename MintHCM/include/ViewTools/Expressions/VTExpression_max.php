<?php

/**
 * Returns max value from related records
 * EOU:
 * "related(max(@value),#relate_field)"
 */
class VTExpression_max extends VTExpression
{

    /**
     * Variable used for creating documentation. As value,
     * please set array of functions, which this formula can be used
     */
    public $availability = array('related');
    /**
     * if $inputParams are not set, return false
     * Definition of input params
     */
    public $inputParams = array('field_selector');

    /**
     * Warning! if duplicate section is not set, return false
     */
    public function sqlbackend($arguments = array())
    {
        return "MAX({$arguments['field_selector']})";
    }

}
