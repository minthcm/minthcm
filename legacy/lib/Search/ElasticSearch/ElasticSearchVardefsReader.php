<?php

class ElasticSearchVardefsReader
{
    public function getModuleNestedProperties(string $module_name): array
    {
        $es_module_config = $GLOBALS['dictionary'][$module_name]['elasticsearch'];
        if (empty($es_module_config)) {
            return [];
        }

        return $es_module_config['nested'] ?? [];
    }

    public function getLinkFieldName(string $property_name, array $nested_config): string
    {
        return is_array($nested_config['link']) ? $nested_config['link'][0] : $nested_config['link'] ?? $property_name;
    }

    public function getRelatedModuleName(SugarBean $bean, string $link_field_name): string
    {
        $link = $bean->$link_field_name;
        $related_module_name = $link->def['module'] ?? null;

        if (empty($related_module_name)) {
            $lhs_module_name = $link->relationship->def['lhs_module'];
            $rhs_module_name = $link->relationship->def['rhs_module'];
            $related_module_name = $lhs_module_name === $bean->module_name
                ? $rhs_module_name
                : $lhs_module_name;
        }

        return $related_module_name;
    }
}
