<?php
/**
 * Singleton 
 */

 require_once 'modules/Import/ImportFileSplitter.php';

class LastNextContactsConfig
{
    const BASE_PATH = 'include/config/last_next_contact_config_base.php';
    const CUSTOM_PATH = 'custom/include/config/last_next_contact_config.php';
    
    protected static $cfg;
    protected $last_next_modules;
    protected $related_modules;
    protected $last_next_activities_std_modules;
    protected $relationship_map;
    protected $only_owner_activities_enabled;
    protected $last_next_trigger_fields;

    public static function getConfigPath()
    {
        $custom_file = new ImportFileSplitter(self::CUSTOM_PATH);
        if ( $custom_file->fileExists() ) {
            return self::CUSTOM_PATH;
        }
        $base_file = new ImportFileSplitter(self::BASE_PATH);
        if ( $base_file->fileExists() ) {
            return self::BASE_PATH;
        }else{
            throw new Exception("Missing file ".self::BASE_PATH);
        }
        
    }
    
    public static function cisset($key, $k = false)
    {
        if (!isset(static::$cfg)) {
            static::$cfg = new LastNextContactsConfig();
        }
        return static::$cfg->isSetValue($key, $k);
    }
    public static function get($key, $k = false)
    {
        if (!isset(static::$cfg)) {
            static::$cfg = new LastNextContactsConfig();
        }
        $val = static::$cfg->getValue($key);
        if (false !== $k) {
            if (isset($val[$k])) {
            return $val[$k];
        }
            return '';
        }
        return $val;
    }
    public function isSetValue($key, $k = false)
    {
        if (false !== $k) {
            return isset($this->$key);
        } else {
            return isset($this->$key) && is_array($this->$key) && isset($this->$key[$k]);
        }

    }
    public function getValue($key)
    {
        return $this->$key;
    }

    public function __construct()
    {
        //$this->debug = 'fatal';
        $conf_path = self::getConfigPath();
        include $conf_path;
        $config = new Administration();
        $config->retrieveSettings('DLNC');
        $this->only_owner_activities_enabled = $config->settings['DLNC_flag'] ?? false;

        if (isset($last_next_modules)) {
            $this->last_next_modules = $last_next_modules;
        }
        if (isset($related_modules)) {
            $this->related_modules = $related_modules;
        }
        if (isset($last_next_activities_std_modules)) {
            $this->last_next_activities_std_modules = $last_next_activities_std_modules;
        }
        if (isset($relationship_map)) {
            $this->relationship_map = $relationship_map;
        }
        $this->filterTriggerFields($last_next_trigger_fields ?? [], $only_owner_activities_enabled_config ?? []);

    }

    private function filterTriggerFields(array $last_next_trigger_fields, array $only_owner_activities_enabled_config)
    {
        if (!empty($last_next_trigger_fields)) {
            $this->last_next_trigger_fields = $last_next_trigger_fields;
            if ($this->only_owner_activities_enabled && !empty($only_owner_activities_enabled_config)) {
                $keys = array_keys($only_owner_activities_enabled_config);
                foreach ($keys as $key) {
                    if (array_key_exists($key, $this->last_next_trigger_fields)) {
                        $this->last_next_trigger_fields[$key] = array_merge($this->last_next_trigger_fields[$key], $only_owner_activities_enabled_config);
                    }
                }
            }
        }
    }

}
