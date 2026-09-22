<?php

namespace MintHCM\Lib\DuplicateDetection\Factory;

use InvalidArgumentException;
use LogicException;
use MintHCM\Lib\DuplicateDetection\DuplicateQueryInterface;

class DuplicateQueryFactory
{
    public static function get(string $module, string $query_class): DuplicateQueryInterface
    {
        if (!class_exists($query_class)) {
            throw new InvalidArgumentException("DuplicateQuery class for module $module not found ($query_class)");
        }

        $instance = new $query_class();

        if (!$instance instanceof DuplicateQueryInterface) {
            throw new LogicException("$query_class must implement DuplicateQueryInterface");
        }

        return $instance;
    }
}
