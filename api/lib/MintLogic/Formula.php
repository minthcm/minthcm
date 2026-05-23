<?php

namespace MintHCM\Lib\MintLogic;

use MintHCM\Lib\MintLogic\Formulas;

abstract class Formula
{
    protected $bean;

    public function __construct($bean)
    {
        $this->bean = $bean;
    }

    public static function executeOperator(string $op, $bean, ...$args)
    {
        if (class_exists($op)) {
            $args = array_map(fn($arg) => Parser::parse($arg, $bean), $args);
            return (new $op($bean))->execute(...$args);
        }
        return false;
    }

    /**
     * Negates logic value entered as value
     * ```php
     * // Examples:
     * Formula::not(Formula::equals('$field1', 'value1'));
     * ```
     */
    public static function not($arg)
    {
        return [
            'op' => Formulas\_Not::class,
            'args' => [$arg],
        ];
    }

    /**
     * At least one of entered arguments has to be 'true'
     * ```php
     * // Examples:
     * Formula::or(
     *    Formula::equals('$field1', 'value1'),
     *    Formula::equals('$field1', '$field2'),
     * );
     * ```
     */
    public static function or(...$args)
    {
        return [
            'op' => Formulas\_Or::class,
            'args' => $args,
        ];
    }

    /**
     * All entered arguments have to be 'true'
     * ```php
     * // Examples:
     * Formula::and(
     *    Formula::equals('$field1', 'value1'),
     *    Formula::notEquals('$field2', 'value2'),
     * );
     * ```
     */
    public static function and(...$args)
    {
        return [
            'op' => Formulas\_And::class,
            'args' => $args,
        ];
    }

    /**
     * Checks if entered values are equal.
     * ```php
     * // Examples:
     * Formula::equals('$status', 'active');
     * Formula::equals('$field1', '$field2');
     * ```
     * @param string|int|bool $arg1
     * @param string|int|bool $arg2
     */
    public static function equals($arg1, $arg2)
    {
        return [
            'op' => Formulas\Equals::class,
            'args' => [$arg1, $arg2],
        ];
    }

    /**
     * Checks if entered values are not equal
     * ```php
     * // Examples:
     * Formula::notEquals('$status', 'active');
     * Formula::notEquals('$field1', '$field2');
     * ```
     */
    public static function notEquals($arg1, $arg2)
    {
        return self::not(self::equals($arg1, $arg2));
    }

    /**
     * Checks if entered value is in array
     * ```php
     * // Examples:
     * Formula::inArray('$status', ['active', 'planned']);
     * ```
     */
    public static function inArray($arg1, $arg2)
    {
        return self::or(
            ...array_map(
                fn($arg) => self::equals($arg, $arg1),
                $arg2
            )
        );
    }

    /**
     * Checks if entered value is not in array
     * ```php
     * // Examples:
     * Formula::notInArray('$status', ['active', 'planned']);
     * ```
     */
    public static function notInArray($arg1, $arg2)
    {
        return self::not(self::inArray($arg1, $arg2));
    }

    /**
     * Checks if entered value is empty
     * ```php
     * // Examples:
     * Formula::empty('$field1');
     * ```
     */
    public static function empty($arg)
    {
        return [
            'op' => Formulas\_Empty::class,
            'args' => [$arg],
        ];
    }

    /**
     * Checks if entered value is not empty
     * ```php
     * // Examples:
     * Formula::notEmpty('$field1');
     * ```
     */
    public static function notEmpty($arg)
    {
        return self::not(self::empty($arg));
    }

    /**
     * Validate expression and throw error message if expression is false
     * ```php
     * // Examples:
     * Formula::validate(
     *    Formula::equals('$field1', 'value1'),
     *   'ERR_FIELD1_NOT_EQUAL_VALUE1'
     * );
     * ```
     */
    public static function validate($expr, $message)
    {
        return [
            'op' => Formulas\Validate::class,
            'args' => [$expr, $message],
        ];
    }
}
