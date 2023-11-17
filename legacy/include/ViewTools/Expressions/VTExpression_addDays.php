<?php

/**
 * Adds number of days to defined date (or reference to date-type field).
 * EOU:
 * "addDays( $date_modified , 0 )" will give us ex 2010-01-21 + 0 = "2010-01-21"
 * "addDays( $date_modified , 2 )" will give us ex 2010-01-21 + 2 = "2010-01-23"
 * "addDays( $date_modified , -2 )" will give us ex 2010-01-21 + ( -2 ) = "2010-01-19"
 */
class VTExpression_addDays extends VTExpression
{

    /**
     * Variable used for creating documentation. As value,
     * please set array of functions, which this formula can be used
     */
    public $availability = array('vt_calculated', 'vt_dependency', 'vt_required', 'vt_readonly', 'vt_duplicate',
        'vt_validation', 'related');
    /**
     * if $inputParams are not set, return false
     * Definition of input params
     */
    public $inputParams = array('date', 'days');

    /**
     * Warning! if backend is not set, return false
     * @param Array
     * @return boolean
     * Please set input params as Array
     */
    public function backend($arguments = array())
    {
        if (!is_null($arguments['date']) && $arguments['date'] != null && !is_null($arguments['days']) && $arguments['days'] != null) {
            $date = new DateTime($arguments['date']);
            $date->add(new DateInterval("P{$arguments['days']}D"));
            return $date->format(TimeDate::DB_DATETIME_FORMAT);
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
      var tmpDate = moment( arguments['date'], viewTools.date.getDateFormat() );
      tmpDate.add( 'days', arguments['days'] );
      return (tmpDate.format( viewTools.date.getDateFormat() ));
EOQ;
    }

    /**
     * Warning! if duplicate section is not set, return false
     */
    public function sqlbackend($arguments = array())
    {
        return "DATE_ADD({$arguments['date']},INTERVAL " . trim($arguments['days'], "'") . " DAY)";
    }

}
