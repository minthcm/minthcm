<?php
class VTExpression_getUploadMaxsize extends VTExpression
{
    public $availability = array('vt_calculated', 'vt_dependency', 'vt_required',
        'vt_readonly', 'vt_duplicate', 'vt_validation', 'related');
    public $inputParams = array();

    public function backend($arguments = array())
    {
        global $sugar_config;
        return $sugar_config['upload_maxsize'] / (10**6);
    }

    public function frontend()
    {
        return <<<EOQ
        return SUGAR.config.uploadMaxsize;
EOQ;
    }
}