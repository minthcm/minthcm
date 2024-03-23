<?php

class LastNextContactsBase
{
    public $debug = false;
    public function __construct()
    {
    }

    protected function getDB()
    {
        return DBManagerFactory::getInstance();
    }

    protected function log()
    {
        if ($this->debug) {
            $GLOBALS['log']->{$this->debug}(print_r([ basename(__FILE__), __METHOD__, __FUNCTION__], 1));
        }
    }

}
