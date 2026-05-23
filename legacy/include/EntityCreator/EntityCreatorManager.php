<?php

require_once 'include/EntityCreator/EntityCreator.php';
require_once 'include/EntityCreator/CustomEntityCreator.php';

class EntityCreatorManager
{
    static $dictionary = [];

    public static function getSkipingFieldTypes(): array
    {
        return EntityCreatorDataGenerator::SKIP_TYPES;
    }

    public static function getORMMappingTypes(): array
    {
        return EntityCreatorDataGenerator::ORM_TYPE_MAP;
    }

    public static function createEntities(): void
    {
        $GLOBALS['entityCreator'] = [];
        
        self::loadDictionary();
        foreach(self::$dictionary as $key => $module_vardefs) {
            if(isset($module_vardefs['doctrineEntity'])) {
                $GLOBALS['entityCreator']['CreatingEntities'][] = $key;
                (new EntityCreator($key, $module_vardefs))->run();
                (new CustomEntityCreator($key, $module_vardefs))->run();
            }
        }
    }

    protected static function loadDictionary(): void
    {
        global $beanList, $dictionary;
        
        self::$dictionary = $dictionary;
        
        foreach($beanList as $module => $bean) {
            if ($module !== $bean) {
                self::$dictionary[$module] = $dictionary[$bean];
                unset(self::$dictionary[$bean]);
            }

            if(!empty(self::$dictionary[$module]) && !empty(self::$dictionary[$module]['relationships'])) {
                foreach(self::$dictionary[$module]['relationships'] as $rel_name => $rel_def) {
                    if(empty(self::$dictionary[$rel_name]['relationships'][$rel_name])) {
                        self::$dictionary[$rel_name]['relationships'][$rel_name] = $rel_def;
                    }
                }
            }
        }
    }
}