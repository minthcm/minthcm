<?php

namespace MintHCM\AI\Tools\Utils;

if (!defined('sugarEntry') || !sugarEntry) {
    die('Not A Valid Entry Point');
}

class ToolValidation
{
    private mixed $value;
    private string $field;
    private array $errors = [];

    public function __construct(mixed $value, string $field = '')
    {
        $this->value = $value;
        $this->field = $field;
    }

    public static function make(mixed $value, string $field = ''): self
    {
        return new self($value, $field);
    }

    /**
     * Validates that the current field exists in the module's field definitions.
     * @param array $field_defs
     * @param string $module_name
     * @return self
     */
    public function fieldModule(array $field_defs, string $module_name): self
    {
        $available_fields = array_keys($field_defs);
        if (!in_array($this->field, $available_fields)) {
            $this->errors[] = "Field '{$this->field}' is not available in the '{$module_name}' module. Use get_module_fields to get list of fields available in the module.";
        }
        return $this;
    }

    /**
     * Email fields (email1–email10) are non-db but writable via SugarCRM's
     * SugarEmailAddress mechanism on bean save.
     */
    private static function isEmailField(string $field_name): bool
    {
        return (bool) preg_match('/^email\d+$/', $field_name);
    }

    /**
     * Validates that a field can be written (create/update).
     * Allows email fields despite being non-db (handled by SugarEmailAddress on save).
     * @param array $field_defs
     * @return self
     */
    public function writableField(array $field_defs): self
    {
        if (empty($field_defs[$this->field])) {
            $this->errors[] = "Field '{$this->field}' is not defined in the module. Use get_module_fields to get list of fields available in the module.";
            return $this;
        }

        $field_source = $field_defs[$this->field]['source'] ?? null;
        if ($field_source === 'non-db' && !self::isEmailField($this->field)) {
            $this->errors[] = "Field '{$this->field}' is of source 'non-db' and cannot be set directly. Use the underlying *_id (and *_type for polymorphic) column instead.";
            return $this;
        }

        $this->validateEnum($field_defs);

        return $this;
    }

    /**
     * Validates the value of the current field based on its definition.
     * @param array $field_defs
     * @return self
     */
    public function filterField(array $field_defs): self
    {
        if (empty($field_defs[$this->field])) {
            $this->errors[] = "Field '{$this->field}' is not defined in the module. Use get_module_fields to get list of fields available in the module.";
            return $this;
        }

        // Non-writable virtual fields (relate/link/parent display fields) are flagged with
        // source=non-db. We can't filter on type=relate alone, since writable foreign-key
        // columns like assigned_user_id / employee_id are also declared as type=relate
        // (with dbType=id and no source override).
        // Email fields (email1–email10) are non-db but filterable via subquery on email_addresses table.
        $field_source = $field_defs[$this->field]['source'] ?? null;
        if ($field_source === 'non-db' && !self::isEmailField($this->field)) {
            $this->errors[] = "Field '{$this->field}' is of source 'non-db' and cannot be used in filters. Use the underlying *_id (and *_type for polymorphic) column instead.";
            return $this;
        }

        $this->validateEnum($field_defs);

        return $this;
    }

    private function validateEnum(array $field_defs): void
    {
        $field_type = $field_defs[$this->field]['type'] ?? '';
        if (!preg_match('/enum/i', $field_type)) {
            return;
        }

        $language = $GLOBALS['current_language'] ?? 'en_us';
        $app_list_strings = function_exists('return_app_list_strings_language') ? return_app_list_strings_language($language) : [];
        $enum_values = self::resolveEnumKeys($field_defs[$this->field] ?? [], $app_list_strings);
        if ($enum_values === null) {
            return;
        }

        if ($field_type === 'multienum' && is_string($this->value) && $this->value !== '') {
            $values = function_exists('unencodeMultienum')
                ? unencodeMultienum($this->value)
                : array_map(static fn($v) => trim((string) $v, '^'), explode(',', $this->value));
        } else {
            $values = (array) $this->value;
        }
        foreach ($values as $v) {
            if (!in_array($v, $enum_values, true)) {
                $this->errors[] = "Value '{$v}' is not valid for enum field '{$this->field}'.";
            }
        }
    }

    /**
     * Resolves the valid enum keys for a field definition. First tries the static `options` key
     * against `app_list_strings`; falls back to invoking a `function` callback for vardefs whose
     * options are populated dynamically (e.g. `getDictionary` reading from the `dictionaries`
     * table), mirroring GetModuleFieldsTool::resolveEnumValues() so read and write agree on
     * what values are valid.
     *
     * Callbacks declaring `returns: 'html'` are skipped — those produce widget markup, not
     * key/value option maps.
     *
     * @param array<string, mixed>                 $field_def
     * @param array<string, array<string, string>> $app_list_strings
     *
     * @return list<string>|null
     */
    private static function resolveEnumKeys(array $field_def, array $app_list_strings): ?array
    {
        $options_key = isset($field_def['options']) ? (string) $field_def['options'] : '';
        if ($options_key !== '' && !empty($app_list_strings[$options_key])) {
            return array_keys($app_list_strings[$options_key]);
        }

        if (empty($field_def['function'])) {
            return null;
        }

        $function = $field_def['function'];
        if (is_array($function)) {
            $function_name = (string) ($function['name'] ?? '');
            $include = (string) ($function['include'] ?? '');
            $additional_params = $function['additional_params'] ?? '';
            $returns = (string) ($function['returns'] ?? '');
        } else {
            $function_name = (string) $function;
            $include = '';
            $additional_params = '';
            $returns = '';
        }

        if ($function_name === '' || $returns === 'html') {
            return null;
        }

        if ($include !== '' && file_exists($include)) {
            require_once $include;
        }

        if (!function_exists($function_name)) {
            return null;
        }

        // Standard SugarCRM signature for option-providing callbacks:
        // ($focus, $name, $value, $view, $additional_params).
        $values = $function_name(null, '', '', 'MCP', $additional_params);
        if (!is_array($values) || $values === []) {
            return null;
        }

        return array_map('strval', array_keys($values));
    }

    /**
     * Validate a value based on its type definition (date, integer, string, etc.)
     * @param mixed $value
     * @param string $field
     * @param string $type
     * @return self
     */
    public function fieldType($type): self
    {
        switch (strtolower($type)) {
            case 'date':
            case 'datetime':
            case 'datetimecombo':
                $this->date();
                break;
            case 'int':
            case 'integer':
                $this->integer();
                break;
            case 'string':
            case 'text':
            case 'varchar':
            case 'char':
            case 'url':
                $this->string();
                break;
        }
        return $this;
    }
    public function greaterThanOrEquals($min): self
    {
        if ($this->value !== null && $this->value < $min) {
            $this->errors[] = "Field '{$this->field}' must be greater than or equal to {$min}.";
        }
        return $this;
    }

    public function isBefore($compare_to_value, string $compare_to_field): self
    {
        if ($this->value !== null && $compare_to_value !== null) {
            $this_time = strtotime($this->value);
            $compare_time = strtotime($compare_to_value);
            if ($this_time === false || $compare_time === false) {
                $this->errors[] = "Invalid date format for comparison in '{$this->field}'.";
            } elseif ($this_time >= $compare_time) {
                $this->errors[] = "Field '{$this->field}' ({$this->value}) must be before field {$compare_to_field} ({$compare_to_value}).";
            }
        }
        return $this;
    }

    public function isAfter($compare_to, string $compare_to_field): self
    {
        if ($this->value !== null && $compare_to !== null) {
            $this_time = strtotime($this->value);
            $compare_time = strtotime($compare_to);
            if ($this_time === false || $compare_time === false) {
                $this->errors[] = "Invalid date format for comparison in '{$this->field}'.";
            } elseif ($this_time <= $compare_time) {
                $this->errors[] = "Field '{$this->field}' ({$this->value}) must be after field {$compare_to_field} ({$compare_to}).";
            }
        }
        return $this;
    }

    public function required(): self
    {
        if ($this->value === null || $this->value === '' || (is_array($this->value) && empty($this->value))) {
            $this->errors[] = "Field '{$this->field}' is required.";
        }
        return $this;
    }

    public function string(): self
    {
        if ($this->value !== null && !is_string($this->value)) {
            $this->errors[] = "Field '{$this->field}' must be a string.";
        }
        return $this;
    }

    public function integer(): self
    {
        if ($this->value !== null && !is_int($this->value)) {
            $this->errors[] = "Field '{$this->field}' must be an integer.";
        }
        return $this;
    }

    public function greaterThan($min): self
    {
        if ($this->value !== null && $this->value <= $min) {
            $this->errors[] = "Field '{$this->field}' must be greater than {$min}.";
        }
        return $this;
    }

    public function lessThan($max): self
    {
        if ($this->value !== null && $this->value >= $max) {
            $this->errors[] = "Field '{$this->field}' must be less than {$max}.";
        }
        return $this;
    }

    public function date($format = 'Y-m-d H:i:s'): self
    {
        if ($this->value !== null && strtotime($this->value) === false) {
            $this->errors[] = "Field '{$this->field}' must be a valid date in format {$format}.";
        }
        return $this;
    }

    public function array(): self
    {
        if ($this->value !== null && !is_array($this->value)) {
            $this->errors[] = "Field '{$this->field}' must be an array.";
        }
        return $this;
    }

    public function enum(array $allowed): self
    {
        if ($this->value !== null && !in_array($this->value, $allowed, true)) {
            $this->errors[] = "Field '{$this->field}' must be one of: " . implode(', ', $allowed) . ".";
        }
        return $this;
    }

    public function numeric(): self
    {
        if ($this->value !== null && $this->value !== '' && !is_numeric($this->value)) {
            $this->errors[] = "Field '{$this->field}' must be numeric. Got: '{$this->value}'.";
        }
        return $this;
    }

    /**
     * Validates that the value matches a strict date format using DateTime::createFromFormat.
     * More precise than date() which uses strtotime.
     * @param string $format e.g. 'Y-m-d' or 'Y-m-d H:i:s'
     */
    public function dateFormat(string $format): self
    {
        if ($this->value !== null && $this->value !== '') {
            $str = (string) $this->value;
            if (\DateTime::createFromFormat($format, $str) === false) {
                $this->errors[] = "Field '{$this->field}' must be a valid date in format {$format}. Got: '{$str}'.";
            }
        }
        return $this;
    }

    /**
     * Run a custom validation callable against the current value.
     * The callable receives the value and must return true (valid) or false (invalid).
     * @param callable $validator fn($value): bool
     * @param string $error_message Error message added when the callable returns false
     */
    public function customValidation(callable $validator, string $error_message): self
    {
        if (!$validator($this->value)) {
            $this->errors[] = $error_message;
        }
        return $this;
    }

    /**
     * Validate multiple fields and throw InvalidArgumentException if any errors.
     * @param ToolValidation[] $validators
     * @throws \InvalidArgumentException
     */
    public static function validateMany(array $validators): void
    {
        $errors = [];
        foreach ($validators as $validator) {
            if (!$validator->isValid()) {
                $errors = array_merge($errors, $validator->getErrors());
            }
        }
        if (!empty($errors)) {
            throw new \InvalidArgumentException(json_encode($errors, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
        }
    }

    /**
     * Validate a single field and throw InvalidArgumentException if any errors.
     * @param ToolValidation $validator
     * @throws \InvalidArgumentException
     */
    public static function validateOne(self $validator): void
    {
        if (!$validator->isValid()) {
            throw new \InvalidArgumentException(json_encode($validator->getErrors(), JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
        }
    }

    public function getErrors(): array
    {
        return $this->errors;
    }

    public function isValid(): bool
    {
        return empty($this->errors);
    }
}
