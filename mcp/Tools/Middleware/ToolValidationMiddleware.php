<?php

namespace MintMCP\Tools\Middleware;

class ToolValidationMiddleware
{


    private $value;
    private $field;
    private $errors = [];

    public function __construct($value, $field = '')
    {
        $this->value = $value;
        $this->field = $field;
    }

    public static function make($value, $field = ''): self
    {
        return new self($value, $field);
    }

    /**
     * Validates that the current field exists in the module's field definitions.
     * @param array $fieldDefs
     * @param string $moduleName
     * @return self
     */
    public function fieldModule(array $fieldDefs, string $moduleName): self
    {
        $availableFields = array_keys($fieldDefs);
        if (!in_array($this->field, $availableFields)) {
            $this->errors[] = "Field '{$this->field}' is not available in the '{$moduleName}' module. Use get_module_fields to get list of fields available in the module.";
        }
        return $this;
    }

    /**
     * Validates the value of the current field based on its definition.
     * @param array $fieldDefs
     * @return self
     */
    public function filterField(array $fieldDefs): self
    {
        if (empty($fieldDefs[$this->field])) {
            $this->errors[] = "Field '{$this->field}' is not defined in the module.";
            return $this;
        }

        $fieldType = $fieldDefs[$this->field]['type'] ?? '';
        if (in_array($fieldType, ['link', 'relate'], true)) {
            $this->errors[] = "Field '{$this->field}' of type '{$fieldType}' cannot be used in filters. Use the field of type 'id' and ID of the related record instead.";
            return $this;
        }

        if (preg_match('/enum/i', $fieldType)) {
            $language = $GLOBALS['current_language'] ?? 'en_us';
            $appListStrings = function_exists('return_app_list_strings_language') ? return_app_list_strings_language($language) : [];
            $optionsKey = $fieldDefs[$this->field]['options'] ?? null;
            if ($optionsKey && !empty($appListStrings[$optionsKey])) {
                $enumValues = array_keys($appListStrings[$optionsKey]);
                $values = (array)$this->value;
                foreach ($values as $v) {
                    if (!in_array($v, $enumValues, true)) {
                        $this->errors[] = "Value '{$v}' is not valid for enum field '{$this->field}'.";
                    }
                }
            }
        }
        return $this;
    }
    /**
     * Validate a value based on its type definition (date, integer, string, etc.)
     * @param mixed $value
     * @param string $field
     * @param string $type
     * @return self
     */
    public static function validateByType($value, $field, $type): self
    {
        $validator = self::make($value, $field);
        switch (strtolower($type)) {
            case 'date':
            case 'datetime':
                $validator->date();
                break;
            case 'int':
            case 'integer':
                $validator->integer();
                break;
            case 'string':
            case 'text':
            case 'varchar':
            case 'char':
            case 'url':
                $validator->string();
                break;
        }
        return $validator;
    }
    public function greaterThanOrEquals($min): self
    {
        if ($this->value !== null && $this->value < $min) {
            $this->errors[] = "Field '{$this->field}' must be greater than or equal to {$min}.";
        }
        return $this;
    }

    public function isBefore($compareToValue, string $compareToField): self
    {
        if ($this->value !== null && $compareToValue !== null) {
            $thisTime = strtotime($this->value);
            $compareTime = strtotime($compareToValue);
            if ($thisTime === false || $compareTime === false) {
                $this->errors[] = "Invalid date format for comparison in '{$this->field}'.";
            } elseif ($thisTime >= $compareTime) {
                $this->errors[] = "Field '{$this->field}' ({$this->value}) must be before field {$compareToField} ({$compareToValue}).";
            }
        }
        return $this;
    }

    public function isAfter($compareTo, string $compareToField): self
    {
        if ($this->value !== null && $compareTo !== null) {
            $thisTime = strtotime($this->value);
            $compareTime = strtotime($compareTo);
            if ($thisTime === false || $compareTime === false) {
                $this->errors[] = "Invalid date format for comparison in '{$this->field}'.";
            } elseif ($thisTime <= $compareTime) {
                $this->errors[] = "Field '{$this->field}' ({$this->value}) must be after field {$compareToField} ({$compareTo}).";
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

    /**
     * Validate multiple fields and throw InvalidArgumentException if any errors.
     * @param ToolValidationMiddleware[] $validators
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
            throw new \InvalidArgumentException(implode("\n", $errors));
        }
    }

    /**
     * Validate a single field and throw InvalidArgumentException if any errors.
     * @param ToolValidationMiddleware $validator
     * @throws \InvalidArgumentException
     */
    public static function validateOne(self $validator): void
    {
        if (!$validator->isValid()) {
            throw new \InvalidArgumentException(implode("\n", $validator->getErrors()));
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
