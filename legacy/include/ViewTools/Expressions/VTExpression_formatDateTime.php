<?php

/**
 * Converts string to datetime with entered format
 * EOU:
 * "formatDateTime(10.01.2010,'d.m.Y')" will give us date converted to date with user-selected format (for example '2010-01-10')
 */
class VTExpression_formatDateTime extends VTExpression
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
    public $inputParams = array('date_string', 'date_format');

    /**
     * Warning! if backend is not set, return false
     * @param Array
     * @return boolean
     * Please set input params as Array
     */
    public function backend($arguments = array())
    {
        global $timedate;
        global $current_user;
        $date_time = DateTime::createFromFormat($arguments['date_format'], $arguments['date_string']);
        $db_date_time = $date_time->format("{$timedate->dbDayFormat} {$timedate->dbTimeFormat}");
        return $timedate->to_display_date_time($db_date_time, true, true, $current_user);
    }

    /**
     * Warning! if frontend is not set, return false
     * @return type
     */
    public function frontend()
    {
        return <<<EOQ
      var date = viewTools.date.get(arguments['date_string'],arguments['date_format']);
      return date.format(viewTools.date.getDateTimeFormat());
EOQ;
    }

    /**
     * Warning! if duplicate section is not set, return false
     */
    public function sqlbackend($arguments = array())
    {
        $arguments['date_format'] = trim(str_replace(array('/', '.'), array('-', '-'), $arguments['date_format']));
        $arguments['date_format'] = str_replace(array('m', 'd', 'Y', 'h', 'i'), array('%m', '%d', '%Y', '%H', '%i'), $arguments['date_format']);
        return "STR_TO_DATE('{$arguments['date_string']}','{$arguments['date_format']}')";
    }

}
