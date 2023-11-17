<?php

/**
 * Counts number of selected elements
 * EOU:
 * "related(count(1),#relate_field)"
 * "ifElse(greaterThan(2,related(count(@name),#relate_field,equals(@status,'accepted'))),'mote than 2 related records','less than 2')"
 */
class VTExpression_count extends VTExpression
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
        return "COUNT({$arguments['field_selector']})";
    }

}
