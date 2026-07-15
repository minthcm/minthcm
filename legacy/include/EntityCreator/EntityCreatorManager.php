<?php

require_once 'include/EntityCreator/EntityCreator.php';
require_once 'include/EntityCreator/CustomEntityCreator.php';

class EntityCreatorManager
{
    static $dictionary = [];
    static bool $errorShown = false;

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
        self::$errorShown = false;

        self::loadDictionary();
        foreach (self::$dictionary as $key => $module_vardefs) {
            if (isset($module_vardefs['doctrineEntity'])) {
                $GLOBALS['entityCreator']['CreatingEntities'][] = $key;
                try {
                    (new EntityCreator($key, $module_vardefs))->run();
                    (new CustomEntityCreator($key, $module_vardefs))->run();
                } catch (Throwable $e) {
                    $msg = "EntityCreator: failed to create entity '{$key}': " . $e->getMessage();
                    $GLOBALS['log']->fatal($msg);
                    if (!self::$errorShown) {
                        echo "EntityCreator: An error occurred while creating entities. Please check the admin logs.<br/>\n";
                        self::$errorShown = true;
                    }
                }
            }
        }
    }

    protected static function loadDictionary(): void
    {
        global $beanList, $dictionary;

        self::$dictionary = $dictionary;

        foreach ($beanList as $module => $bean) {
            if ($module !== $bean) {
                self::$dictionary[$module] = $dictionary[$bean];
                unset(self::$dictionary[$bean]);
            }

            if (!empty(self::$dictionary[$module]) && !empty(self::$dictionary[$module]['relationships'])) {
                foreach (self::$dictionary[$module]['relationships'] as $rel_name => $rel_def) {
                    if (empty(self::$dictionary[$rel_name]['relationships'][$rel_name])) {
                        self::$dictionary[$rel_name]['relationships'][$rel_name] = $rel_def;
                    }
                }
            }
        }

        self::normalizeMetadataKeys($beanList);
    }

    protected static function normalizeMetadataKeys(array $beanList): void
    {
        $bean_list_keys = array_merge(array_keys($beanList), array_values($beanList));

        foreach (self::$dictionary as $key => $vardefs) {
            if (in_array($key, $bean_list_keys, true)) {
                continue;
            }
            if (strpos($key, '_') === false) {
                continue;
            }
            if (!isset($vardefs['doctrineEntity'])) {
                continue;
            }

            $pascal_key = str_replace(' ', '', ucwords(str_replace('_', ' ', $key)));
            if ($pascal_key !== $key && !isset(self::$dictionary[$pascal_key])) {
                self::$dictionary[$pascal_key] = $vardefs;
                unset(self::$dictionary[$key]);
            }
        }
    }
}
