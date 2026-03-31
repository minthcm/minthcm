<?php

#[\AllowDynamicProperties]
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

    protected function log(string $message)
    {
        if ($this->debug) {
            $GLOBALS['log']->fatal($message);
        }
    }

}
