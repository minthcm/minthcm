# MintLogic - Dynamic Form Logic

## Introduction

**MintLogic** is a system for managing dynamic form logic in MintHCM. It allows you to define field behavior in forms based on other field values or business conditions, without modifying frontend code.

The system is located in `api/lib/MintLogic/` and enables:

- Conditional field visibility
- Dynamic required field marking
- Automatic read-only field setting
- Field value updates based on other fields
- Bean (record) or individual field validation
- Dynamic option management in enum fields

## Basic Structure

### The logicdefs.php Files

Each module can have one or more logic definition files. The system loads all files matching the pattern `*logicdefs.php` from the following directories:

**Main module directory:**
```
api/lib/MintLogic/Modules/{ModuleName}/*logicdefs.php
```

**Custom directory (for extensions and customizations):**
```
api/custom/lib/MintLogic/{ModuleName}/*logicdefs.php
```

The system automatically merges all found definitions, allowing you to split logic into multiple files and extend standard logic with custom definitions without modifying core files.

Examples of valid filenames:
- `logicdefs.php`
- `validation.logicdefs.php`
- `visibility.logicdefs.php`
- `custom.logicdefs.php`

Basic file structure:

```php
<?php
use MintHCM\Lib\MintLogic\Hook;
use MintHCM\Lib\MintLogic\Formula;

return [
    'bean' => [
        'validation' => [
            // Record-level validators
        ],
    ],
    'rules' => [
        // Logic rule definitions
    ],
];
```

## System Components

### 1. Hooks (Hook Points)

Determine when a rule should be executed. `Hook` is a backed enum (`lib/MintLogic/Hook.php`):

- `Hook::INIT` (`'init'`) - On form initialization (`getInitial()`)
- `Hook::CHANGE` (`'change'`) - On field value change (`getChanged()`)
- `Hook::ALL` (`'all'`) - Always (both init and change)

A rule with `'hooks' => []` never runs. `[Hook::ALL]` alone already covers init and change.

### 2. Rules

Each rule can contain:

#### `hooks` (required)
Array specifying when the rule is executed:
```php
'hooks' => [Hook::INIT, Hook::CHANGE]
```

#### `triggerFields` (optional)
List of fields whose change triggers the rule:
```php
'triggerFields' => ['status', 'type']
```

#### `trigger` (optional)
Logical condition determining if the rule should be applied:
```php
'trigger' => Formula::equals('$status', 'active')
```

#### `logic` (required)
Object defining the actual logic. Can contain the following keys:

##### `visible`
Determines field visibility:
```php
'visible' => [
    'field_name' => false,  // hides the field
]
```

##### `readonly`
Determines if a field is read-only:
```php
'readonly' => [
    'field_name' => true,  // read-only field
]
```

##### `required`
Determines if a field is required:
```php
'required' => [
    'field_name' => true,  // required field
]
```

##### `update`
Updates field values. Can be an array or a function:
```php
'update' => [
    'field_name' => 'new_value',
]

// or a function:
'update' => function ($bean) {
    return [
        'field_name' => $bean->other_field . '_suffix',
    ];
}
```

##### `validation`
Defines validators for specific fields. A value can be a `Validator` class, a `Formula::validate(...)` expression or a list of them:
```php
'validation' => [
    'field_name' => ValidationClass::class,
    'other_field' => [
        ValidationClass::class,
        Formula::validate(Formula::equals('$other_field', 'test'), 'ERR_CANNOT_BE_TEST'),
    ],
]
```
Field-level validation results are returned in `logic.errors` of the init/change response. They are **not** run again when the record is saved — only `bean.validation` is enforced on save (see [API Integration](#api-integration)).

##### `options`
Dynamically changes available options in enum fields. The value is either the name of an `app_list_strings` list or a function returning the options:
```php
'options' => [
    'field_name' => 'name_of_the_list',
    'other_field' => function ($bean) {
        return ['key1' => 'Label 1', 'key2' => 'Label 2'];
    },
]
```

`lib/MintLogic/Modules/_example/logicdefs.php` documents every rule key with comments and is the best reference.

## Formulas - Helper Functions

The MintLogic system provides a `Formula` class with ready-to-use logical functions:

### Comparisons

```php
// Checks equality
Formula::equals('$field1', 'value')
Formula::equals('$field1', '$field2')

// Checks inequality
Formula::notEquals('$field1', 'value')

// Checks if value is in array
Formula::inArray('$status', ['active', 'planned'])

// Checks if value is not in array
Formula::notInArray('$status', ['active', 'planned'])
```

### Empty Value Checks

```php
Formula::empty('$field1')
Formula::notEmpty('$field1')
```

### Logical Operators

```php
// All conditions must be met
Formula::and(
    Formula::equals('$status', 'active'),
    Formula::notEmpty('$assigned_user_id')
)

// At least one condition must be met
Formula::or(
    Formula::equals('$type', 'type1'),
    Formula::equals('$type', 'type2')
)

// Condition negation
Formula::not(Formula::equals('$field1', 'value'))
```

### Validation with Error Message

The first argument is the **error condition**: when it evaluates to `true`, a `ValidationException` with the given label is thrown (`lib/MintLogic/Formulas/Validate.php`).

```php
// Error when the field is empty
Formula::validate(
    Formula::empty('$required_field'),
    'ERR_FIELD_REQUIRED'
)
```

## Parser - Referencing Fields

In formulas, you can reference field values using special syntax:

- `$field_name` - current field value
- `$new.field_name` - new field value (after change)
- `$old.field_name` - old field value (before change, from `fetched_row`)

Only names that exist in the bean's `field_defs` are resolved (`lib/MintLogic/Parser.php`); any other string is used as a literal value.

## Usage Examples

### Example 1: Conditional Field Visibility

The `transport_type` field appears only when `has_transport` is checked:

```php
return [
    'rules' => [
        'transport_visibility' => [
            'hooks' => [Hook::ALL],
            'triggerFields' => ['has_transport'],
            'trigger' => Formula::equals('$has_transport', '1'),
            'logic' => [
                'visible' => [
                    'transport_type' => true,
                ],
            ],
        ],
    ],
];
```

### Example 2: Conditional Required Field

The `rejection_reason` field is required only when status is `rejected`:

```php
return [
    'rules' => [
        'rejection_required' => [
            'hooks' => [Hook::ALL],
            'triggerFields' => ['status'],
            'trigger' => Formula::equals('$status', 'rejected'),
            'logic' => [
                'required' => [
                    'rejection_reason' => true,
                ],
            ],
        ],
    ],
];
```

### Example 3: Automatic Value Update

Automatically copying data from a related record (based on `Modules/Delegations/logicdefs.php`):

```php
use MintHCM\Data\BeanFactory;

return [
    'rules' => [
        'copy_currency' => [
            'hooks' => [Hook::ALL],
            'triggerFields' => ['delegation_locale_name'],
            'logic' => [
                'update' => function ($bean) {
                    $locale = BeanFactory::getBean('DelegationsLocale', $bean->delegation_locale_id);
                    return [
                        'currency_id' => $locale->currency_id,
                    ];
                },
            ],
        ],
    ],
];
```

### Example 4: Read-only Fields

Setting fields as read-only on initialization:

```php
return [
    'rules' => [
        'init' => [
            'hooks' => [Hook::INIT],
            'logic' => [
                'readonly' => [
                    'total_amount_usdollar' => true,
                    'exchange_rate' => true,
                ],
            ],
        ],
    ],
];
```

### Example 5: Validation with Custom Validator

```php
use MintHCM\Lib\MintLogic\Validators\IsUnique;

return [
    'rules' => [
        'unique_name' => [
            'hooks' => [Hook::ALL],
            'logic' => [
                'validation' => [
                    'name' => IsUnique::class,
                ],
            ],
        ],
    ],
];
```

### Example 6: Complex Conditions

Combining multiple conditions:

```php
return [
    'rules' => [
        'complex_logic' => [
            'hooks' => [Hook::ALL],
            'triggerFields' => ['status', 'type', 'assigned_user_id'],
            'trigger' => Formula::and(
                Formula::equals('$status', 'active'),
                Formula::or(
                    Formula::equals('$type', 'internal'),
                    Formula::equals('$type', 'external')
                ),
                Formula::notEmpty('$assigned_user_id')
            ),
            'logic' => [
                'required' => [
                    'deadline' => true,
                    'priority' => true,
                ],
                'visible' => [
                    'internal_notes' => true,
                ],
            ],
        ],
    ],
];
```

### Example 7: Bean-Level Validation

Validating the entire record before saving (`Modules/Delegations/logicdefs.php`):

```php
use MintHCM\Lib\MintLogic\Modules\Delegations\Validators\DelegationValidator;

return [
    'bean' => [
        'validation' => [
            DelegationValidator::class,
        ],
    ],
];
```

Example validator (module validators live in `lib/MintLogic/Modules/{Module}/Validators/`):

```php
<?php
namespace MintHCM\Lib\MintLogic\Modules\Delegations\Validators;

use MintHCM\Lib\MintLogic\Exceptions\ValidationException;
use MintHCM\Lib\MintLogic\Validator;

class DelegationValidator extends Validator
{
    public function validate($bean, $field = null)
    {
        // Real implementation: rejects a delegation with the same name and dates
        if ($this->duplicateExists($bean)) {
            throw new ValidationException('ERR_DELEGATION_EXISTS');
        }
    }
}
```

Bean-level validators can also be `Formula::validate(...)` expressions.

### Example 8: Default Value Initialization

Setting a default value when creating a new record. (The assigned user itself does not need a rule: the built-in BasicSet `assigned_user_init` already does this for every module, see [Basic Sets](#basic-sets).)

```php
return [
    'rules' => [
        'default_start_date' => [
            'hooks' => [Hook::INIT],
            'trigger' => Formula::empty('$id'),   // new records only
            'logic' => [
                'update' => function ($bean) {
                    if (empty($bean->date_start)) {
                        return ['date_start' => date('Y-m-d')];
                    }
                    return [];
                },
            ],
        ],
    ],
];
```

## Basic Sets

Rules in `lib/MintLogic/BasicSets/*.php` are applied to **every** module before the module's own rules:

| Rule key | Hook | Purpose |
|----------|------|---------|
| `required_from_vardefs` | INIT | Marks fields with `required` in vardefs as required |
| `readonly_from_vardefs` | INIT | Marks fields with `'readonly' => true` in vardefs as read-only |
| `assigned_user_init` | INIT | Sets the current user as assigned user on new records |
| `init_parent_fields` | INIT | Limits `parent` field module options to modules the user can list; read-only when the current parent module is not accessible |
| `init_function_options` | INIT | Loads options of enum/multienum fields defined by a vardefs `function` |
| `check_relate_module_access` | INIT | Makes relate fields read-only when the user has no access to the related module |

A module (or custom) rule with the **same key** replaces the basic set rule for that module, e.g. define `'assigned_user_init' => []` to switch it off.

## Custom Validators

You can create your own validators by extending the `Validator` class. Core module validators live in `lib/MintLogic/Modules/{Module}/Validators/` (namespace `MintHCM\Lib\MintLogic\Modules\{Module}\Validators`); generic ones in `lib/MintLogic/Validators/` (`IsUnique`, `IsInRange`, ...).

For customizations put the class in an autoloadable custom namespace — `custom/lib/` is **not** autoloaded — e.g. `custom/modules/{Module}/Validators/` (namespace `MintHCM\Custom\Modules\{Module}\Validators`):

```php
<?php
namespace MintHCM\Custom\Modules\Candidates\Validators;

use MintHCM\Lib\MintLogic\Exceptions\ValidationException;
use MintHCM\Lib\MintLogic\Validator;

class CustomValidator extends Validator
{
    public function validate($bean, $field = null)
    {
        // Your validation logic
        if (empty($bean->{$field})) {
            throw new ValidationException('ERR_CUSTOM_VALIDATION');
        }
    }
}
```

The validator is instantiated with the bean (`new $class($bean)`) and `validate($bean, $field)` is called with the field name for field-level validation, or without it for bean-level validation. Throw `ValidationException` with a label; its default HTTP code is 422.

## API Integration

MintLogic works on the legacy bean (`$entity->getMintBean()`) and is called by the API at the following points:

1. **When fetching a record** (`POST /api/{Module}/Get[/{id}]`) - the record response contains the initial logic (`getInitial()`, rules with `INIT` or `ALL` hooks). Global search results also carry it.
2. **When changing values** - the frontend calls `POST /api/{Module}/Logic[/{id}]` with body `attributes` (current form values) and `triggerFields` (changed fields); the response is `getChanged($triggerFields)` (rules with `CHANGE` or `ALL` hooks whose `triggerFields` intersect, or that have no `triggerFields`).
3. **Before saving** (`ModuleController::create()`/`update()`) - `validateBean()` runs the `bean.validation` validators. On failure the API answers **422**:

```json
{
  "isValid": false,
  "error": "ERR_DELEGATION_EXISTS"
}
```

Example record response with logic (`ModuleController::mergeRecordData()`):

```json
{
  "id": "123",
  "module": "Candidates",
  "attributes": {
    "name": "Example",
    "status": "active"
  },
  "acl_access": { "edit": true, "delete": true, "view": true, "admin": false },
  "logic": {
    "rules": [
      {
        "key": "rule_name",
        "triggerFields": ["status"],
        "trigger": true,
        "logic": {
          "errors": {},
          "visible": {
            "special_field": true
          },
          "readonly": {
            "readonly_field": true
          },
          "required": {
            "required_field": true
          },
          "update": {
            "calculated_field": "new_value"
          },
          "options": {}
        },
        "hooks": ["init", "change"]
      }
    ]
  },
  "related_records": []
}
```

When `trigger` is `false`, `logic` is an empty array. Fields hidden with `visible => false` are also returned in `update` with `null`.

## Best Practices

1. **Rule organization** - Give rules meaningful names as keys in the `rules` array
2. **Optimization** - Use `triggerFields` to limit rule execution to necessary cases only
3. **Trigger conditions** - Use `trigger` to conditionally apply entire rules
4. **Functions vs arrays** - Use functions in `update` when the value depends on complex logic or other fields
5. **Validators** - Create separate validator classes for complex validation logic
6. **Documentation** - Add comments explaining the business logic of rules
7. **Testing** - Test different form behavior scenarios

## Extending via Custom

The MintLogic system supports extending and customizing logic through the `custom/` directory:

```
api/custom/lib/MintLogic/{ModuleName}/*logicdefs.php
```

### How It Works

1. The system first loads all `*logicdefs.php` files from the main module directory
2. Then loads all `*logicdefs.php` files from the custom directory
3. Definitions are merged recursively using `array_merge_recursive()`

⚠️ Because of `array_merge_recursive()`, a custom rule with the **same key** as a core rule does **not** replace it: the two definitions are merged into one (lists such as `hooks` and `triggerFields` are concatenated, scalar values like `trigger` become arrays, and closures are collected into lists). Use new, unique rule keys in custom files. To change a core rule, add a separate rule that sets the opposite state, or adjust the core file in a project branch. (Only [Basic Sets](#basic-sets) can be replaced by key.)

### Benefits

- **Non-invasive customization** - You can add or extend logic without modifying core files
- **Multiple files** - Split logic into multiple thematic files (e.g., `validation.logicdefs.php`, `visibility.logicdefs.php`)
- **Safe upgrades** - Custom definitions remain intact during system upgrades

### Example

Standard file in `api/lib/MintLogic/Modules/Candidates/logicdefs.php` (simplified):
```php
return [
    'rules' => [
        'standard_rule' => [
            'hooks' => [Hook::INIT],
            'logic' => [
                'readonly' => ['field1' => true],
            ],
        ],
    ],
];
```

Custom extension in `api/custom/lib/MintLogic/Candidates/custom.logicdefs.php`:
```php
return [
    'rules' => [
        'custom_rule' => [
            'hooks' => [Hook::CHANGE],
            'triggerFields' => ['status'],
            'logic' => [
                'required' => ['custom_field' => true],
            ],
        ],
    ],
];
```

Both rules (with different keys) are active in the system.

## Migrating from View Tools to MintLogic

When migrating legacy modules from old views to new recordviewdefs-based views, View Tools (vt_*) must be converted to MintLogic rules:

### View Tools Mapping

| View Tool | MintLogic Equivalent | Notes |
|-----------|---------------------|-------|
| `vt_dependency` | `visible` logic | Requires BOTH show and hide rules |
| `vt_calculated` | `update` logic | Use function with BeanFactory |
| `vt_required` | `required` logic | Requires BOTH true and false rules |
| `vt_readonly` | `readonly` logic | Single rule often sufficient |

### Example: vt_dependency Migration

**Old (View Tools)**:
```php
'field_name' => [
    'vt_dependency' => "inArray('other', $type)",
]
```

**New (MintLogic)**:
```php
return [
    'rules' => [
        'field_show' => [
            'hooks' => [Hook::ALL],
            'triggerFields' => ['type'],
            'trigger' => Formula::equals('$type', 'other'),
            'logic' => ['visible' => ['field_name' => true]],
        ],
        'field_hide' => [
            'hooks' => [Hook::ALL],
            'triggerFields' => ['type'],
            'trigger' => Formula::notEquals('$type', 'other'),
            'logic' => ['visible' => ['field_name' => false]],
        ],
    ],
];
```

**Important**: Visibility rules require BOTH show and hide rules for proper functionality!

### Example: vt_calculated Migration

**Old (View Tools)**:
```php
'assigned_user_id' => [
    'vt_calculated' => 'related(@assigned_user_id, #delegations)',
]
```

**New (MintLogic)**:
```php
'assigned_user_calculated' => [
    'hooks' => [Hook::INIT, Hook::CHANGE],
    'triggerFields' => ['delegation_id'],
    'logic' => [
        'update' => function ($bean) {
            if (!empty($bean->delegation_id)) {
                $delegation = \BeanFactory::getBean('Delegations', $bean->delegation_id);
                if ($delegation && !empty($delegation->assigned_user_id)) {
                    return ['assigned_user_id' => $delegation->assigned_user_id];
                }
            }
            return [];
        },
    ],
],
```

**Note**: Update functions must always return an array, even if empty.

For complete recordview structure documentation, see: `vue/documentation/12-recordviewdefs.md`

## Debugging

To debug MintLogic:

1. Check the API response - `logic` section of the record response or of `POST /api/{Module}/Logic`
2. Log from update/options functions with `$GLOBALS['log']->fatal(...)` or `error_log()` — do not `var_dump()`, it corrupts the JSON response
3. Check the MintHCM log for exceptions thrown by validators or functions
4. Test formulas separately using `Formula::executeOperator()`
5. Remember that [Basic Sets](#basic-sets) also contribute rules (e.g. required/readonly flags from vardefs)

## Limitations

- Formulas do not support complex mathematical calculations (use functions in `update`)
- No direct database access in formulas (use functions or validators)
- Logic is executed server-side, so it requires API communication

## Summary

MintLogic is a powerful tool for defining dynamic form logic without modifying frontend code. Thanks to the system of hooks, formulas, and validators, you can flexibly manage field behavior based on various business conditions.
