<?php

/**
 * if(condition){....}else{...}
 * EOU:
 * "ifElse(equals($name,'John'),'smith','nowak')" will give us "smith" if $name is 'John'
 */
class VTExpression_ifElse extends VTExpression
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
    public $inputParams = array('condition', 'if_true', 'if_false');

    /**
     * Warning! if backend is not set, return false
     * @param Array
     * @return boolean
     * Please set input params as Array
     */
    public function backend($arguments = array())
    {
        if (!is_null($arguments['condition']) && !is_null($arguments['if_true'])) {
            //If true
            if ($arguments['condition'] === true || $arguments['condition'] === 'true') {
                return $arguments['if_true'];
            }
            //If false
            else if (!is_null($arguments['if_false'])) {
                return $arguments['if_false'];
            }
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
      if( arguments['condition'] !== undefined && arguments['if_true'] !== undefined ){
         if ( arguments['condition'] === true || arguments['condition'] === 'true' ) {
            return arguments['if_true'];
         }
         else if ( arguments['if_false'] !== undefined ){
            return arguments['if_false'];
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
        return "IF({$arguments['condition']},{$arguments['if_true']},{$arguments['if_false']})";
    }

}
