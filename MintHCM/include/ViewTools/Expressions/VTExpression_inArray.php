<?php

/**
 * First param is compared to rest of params. If first param will duplicate
 * in given params, function will return "true". Otherwise wi will get "false".
 * Second param can be also array.
 * EOU:
 * "inArray( 'John' , 'Tom' , 'David' , 'John' )" will give us "true"
 * "inArray( 'John' , 'Harry' , 'David' , 'Tom' )" will give us "false"
 * "inArray( 'John' , ['Harry' , 'David' , 'Tom'] )" will give us "false"
 * "inArray( 'Completed' , $accounting_subtype )" $accounting_subtype is multienum
 */
class VTExpression_inArray extends VTExpression
{

    /**
     * Variable used for creating documentation. As value,
     * please set array of functions, which this formula can be used
     */
    public $availability = array('vt_calculated', 'vt_dependency', 'vt_required', 'vt_readonly', 'vt_duplicate', 'vt_validation', 'related');

    /**
     * Warning! if backend is not set, return false
     * @param Array
     * @return boolean
     * Please set input params as Array
     */
    public function backend($arguments = array())
    {
        $counter = 0;
        $compareArgument = false;
        if (substr($arguments[1], -1) === '^' && substr($arguments[1], 0, 1) === '^') {
            $decoded = unencodeMultienum($arguments[1]);
            $key = 1;
            foreach ($decoded as $value) {
                $arguments[$key++] = $value;
            }
        }
        foreach ($arguments as $argument) {
            if ($counter == 0) {
                $compareArgument = $argument;
            } else {
                if ($compareArgument == $argument) {
                    return true;
                }
            }
            $counter++;
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
      var compareArgument = '';
      var args = [].slice.call(arguments);
      if(Array.isArray(args[1])){
         $.map( args[1], function( val, i ) {
            args.push(val);
         });
         args.splice(1, 1);
      }
      for ( var i = 0; i <= args.length; i++) {
         if( i == 0 ){
            compareArgument = args[i];
         }
         else{
            if( compareArgument == args[i] ){
               return true;
            }
         }
      }
      return false;
EOQ;
    }

    /**
     * Warning! if duplicate section is not set, return false
     */
    public function sqlbackend($arguments = array())
    {
        $counter = 0;
        $field = false;
        $compare_fields = array();
        foreach ($arguments as $argument) {
            if ($counter == 0) {
                $field = $argument;
            } else {
                $compare_fields[] = $argument;
            }
            $counter++;
        }
        return "{$field} IN (" . implode(',', $compare_fields) . ")";
    }

}
