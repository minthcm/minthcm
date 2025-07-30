<?php

namespace MintHCM\Firebase;

class Config
{
    protected $config;

    public function __construct()
    {
        if (file_exists("custom/modules/Connectors/connectors/sources/ext/eapm/firebase/config.php")) {
            include 'custom/modules/Connectors/connectors/sources/ext/eapm/firebase/config.php';
            $config = $config['properties'] ?? [];
        } else {
            $GLOBALS['log']->debug("WARNING: Firebase - default_config used");
            if (file_exists("include/Integrations/Firebase/Config/default_config.php")) {
                include 'include/Integrations/Firebase/Config/default_config.php';
            }
        }
        if (!isset($config)) {
            $GLOBALS['log']->debug("FATAL: Firebase - config not found for minthcm mobile");
            return;
        }
        if (empty($config['project_id'])) {
            $GLOBALS['log']->debug("FATAL: Firebase - missing project_id for minthcm mobile");
        }
        $this->config = $config;
    }

    public function get($param, $default = '')
    {
        return $this->config[$param] ?? $default;
    }
}
