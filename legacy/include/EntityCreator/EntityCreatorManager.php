<?php

require_once 'include/EntityCreator/EntityCreator.php';

class EntityCreatorManager
{

    public static function createEntities(): void
    {
        global $dictionary;
        $GLOBALS['entityCreator'] = [];
        
        self::loadDictionary();
        foreach($dictionary as $key => $module_vardefs) {
            if(isset($module_vardefs['doctrineEntity'])) {
                $GLOBALS['entityCreator']['CreatingEntities'][] = $key;
                $entityCreator = new EntityCreator($key, $module_vardefs);
                $entityCreator->run();
            }
        }
    }

    protected static function loadDictionary(): void
    {
        global $beanList, $dictionary;
        foreach($beanList as $module => $bean) {
            if(!empty($dictionary[$bean]) && !empty($dictionary[$bean]['relationships'])) {
                foreach($dictionary[$bean]['relationships'] as $rel_name => $rel_def) {
                    if(empty($dictionary[$rel_name]['relationships'][$rel_name])) {
                        $dictionary[$rel_name]['relationships'][$rel_name] = $rel_def;
                    }
                }
            }
        }
    }
}