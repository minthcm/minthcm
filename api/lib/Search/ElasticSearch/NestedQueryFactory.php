<?php

namespace MintHCM\Lib\Search\ElasticSearch;

class NestedQueryFactory
{
    const NAMESPACE_PREFIX = "MintHCM\\Modules\\";
    const CLASS_SUFIX = "NestedQuery";
    const BASE_CLASS = "MintHCM\\Lib\\Search\\ElasticSearch\\BaseNestedQuery";

    public static function getModuleNestedQueryObject(string $module): BaseNestedQuery
    {
        $className = static::NAMESPACE_PREFIX . "{$module}\\{$module}" . static::CLASS_SUFIX;
        if(class_exists($className)){
            $moduleNestedQueryObject = new $className();
            if($moduleNestedQueryObject instanceof BaseNestedQuery){
                return $moduleNestedQueryObject;
            }
        }
        return new (static::BASE_CLASS)();
    }
}
