<?php
namespace MintHCM\AI\Tools\Enums;

if (!defined('sugarEntry') || !sugarEntry) {
    die('Not A Valid Entry Point');
}

enum FilterOperator: string
{
    case Equals              = '=';
    case NotEquals           = '<>';
    case GreaterThan         = '>';
    case LessThan            = '<';
    case GreaterThanOrEquals = '>=';
    case LessThanOrEquals    = '<=';
    case Like                = 'LIKE';
    case NotLike             = 'NOT LIKE';
    case In                  = 'IN';
    case NotIn               = 'NOT IN';
    case Between             = 'BETWEEN';

    /** @var array<int, string> */
    public const SCHEMA_VALUES = [
        self::Equals->value,
        self::NotEquals->value,
        self::GreaterThan->value,
        self::LessThan->value,
        self::GreaterThanOrEquals->value,
        self::LessThanOrEquals->value,
        self::Like->value,
        self::NotLike->value,
        self::In->value,
        self::NotIn->value,
        self::Between->value,
    ];
}
