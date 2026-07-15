<?php
namespace MintHCM\AI\Tools\Enums;

if (!defined('sugarEntry') || !sugarEntry) {
    die('Not A Valid Entry Point');
}

enum LogicalOperator: string
{
    case And = 'and';
    case Or  = 'or';
}
