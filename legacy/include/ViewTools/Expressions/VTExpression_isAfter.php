<?php

/**
 * Checks if date1 is after date2.
 * EOU:
 * "isAfter( '2015/01/16' , '2015/01/15' )" will give us "true"
 * Between example:
 * $date = '2015/01/16 12:00'
 * "and( isAfter( '2015/01/16 08:00' , $date ) , isAfter( $date , '2015/01/16 16:00' ) )" will give us "true"
 */
class VTExpression_isAfter extends VTExpression
{

    /**
     * Variable used for creating documentation. As value,
     * please set array of functions, which this formula can be used
     */
    public $availability = array('vt_calculated', 'vt_dependency', 'vt_required', 'vt_readonly', 'vt_duplicate', 'vt_validation', 'related');
    /**
     * if $inputParams are not set, return false
     * Definition of input params
     */
    public $inputParams = array('date1', 'date2');

    /**
     * Warning! if backend is not set, return false
     * @param Array
     * @return boolean
     * Please set input params as Array
     */
    public function backend($arguments = array())
    {
        if (!is_null($arguments['date1']) && $arguments['date1'] != null && !is_null($arguments['date2']) && $arguments['date2'] != null) {
            $date1 = new DateTime($arguments['date1']);
            $date2 = new DateTime($arguments['date2']);
            return $date1 > $date2;
        }
        return false;
    }

    /**
     * Warning! if frontend is not set, return false
     * @return type
     */
    public function frontend()
    {
        return <<<EOQ
      var date1 = viewTools.date.get(arguments['date1']);
      var date2 = viewTools.date.get(arguments['date2']);
      return moment( date1 ).isAfter( date2 );
EOQ;
    }

    /**
     * Warning! if duplicate section is not set, return false
     */
    public function sqlbackend($arguments = array())
    {
        return "TIMEDIFF({$arguments['date1']},{$arguments['date2']})>0";
    }

}
