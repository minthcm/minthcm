<?php

namespace MintHCM\Lib\MintLogic;

class Parser
{
    public static function parse($arg, $bean)
    {
        if (empty($arg) || !is_string($arg)) {
            return $arg;
        }
        if (str_starts_with($arg, '$old.')) {
            $fieldName = substr($arg, 5);
            if (!empty($bean->field_defs[$fieldName])) {
                return $bean->fetched_row[$fieldName] ?? null;
            }
        }
        if (str_starts_with($arg, '$new.')) {
            $fieldName = substr($arg, 5);
            if (!empty($bean->field_defs[$fieldName])) {
                return $bean->{$fieldName};
            }
        }
        if (str_starts_with($arg, '$')) {
            $fieldName = substr($arg, 1);
            if (!empty($bean->field_defs[$fieldName])) {
                return $bean->{$fieldName};
            }
        }
        return $arg;
    }
}
