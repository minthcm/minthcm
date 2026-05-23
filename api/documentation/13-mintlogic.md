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

Determine when a rule should be executed:

- `Hook::INIT` - On form initialization
- `Hook::CHANGE` - On field value change
- `Hook::ALL` - Always (both init and change)

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
Defines validators for specific fields:
```php
'validation' => [
    'field_name' => ValidationClass::class,
]
```

##### `options`
Dynamically changes available options in enum fields:
```php
'options' => [
    'field_name' => function ($bean) {
        return ['key1' => 'Label 1', 'key2' => 'Label 2'];
    },
]
```

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

```php
Formula::validate(
    Formula::notEmpty('$required_field'),
    'ERR_FIELD_REQUIRED'
)
```

## Parser - Referencing Fields

In formulas, you can reference field values using special syntax:

- `$field_name` - current field value
- `$new.field_name` - new field value (after change)
- `$old.field_name` - old field value (before change)

## Usage Examples

### Example 1: Conditional Field Visibility

The `transport_type` field appears only when `has_transport` is checked:

```php
return [
    'rules' => [
        'transport_visibility' => [
            'hooks' => [Hook::ALL, Hook::CHANGE],
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
            'hooks' => [Hook::ALL, Hook::CHANGE],
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

Automatically copying data from a related record:

```php
return [
    'rules' => [
        'copy_currency' => [
            'hooks' => [Hook::ALL, Hook::CHANGE],
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
            'hooks' => [Hook::ALL, Hook::CHANGE],
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

Validating the entire record before saving:

```php
use MintHCM\Lib\MintLogic\Validators\DelegationValidator;

return [
    'bean' => [
        'validation' => [
            DelegationValidator::class,
        ],
    ],
];
```

Example validator:

```php
<?php
namespace MintHCM\Lib\MintLogic\Validators;

use MintHCM\Lib\MintLogic\Exceptions\ValidationException;
use MintHCM\Lib\MintLogic\Validator;

class DelegationValidator extends Validator
{
    public function validate($bean, $field = null)
    {
        if ($bean->date_from > $bean->date_to) {
            throw new ValidationException('ERR_INVALID_DATE_RANGE');
        }
    }
}
```

### Example 8: Default Value Initialization

Setting the default user when creating a new record:

```php
return [
    'rules' => [
        'init' => [
            'hooks' => [Hook::INIT],
            'logic' => [
                'update' => function ($bean) {
                    global $current_user;
                    if (empty($bean->assigned_user_id)) {
                        return [
                            'assigned_user_id' => $current_user->id,
                            'assigned_user_name' => $current_user->name,
                        ];
                    }
                    return [];
                },
            ],
        ],
    ],
];
```

## Custom Validators

You can create your own validators by extending the `Validator` class:

```php
<?php
namespace MintHCM\Lib\MintLogic\Validators;

use MintHCM\Lib\MintLogic\Exceptions\ValidationException;
use MintHCM\Lib\MintLogic\Validator;

class CustomValidator extends Validator
{
    public function validate($bean, $field = null)
    {
        // Your validation logic
        if (/* condition not met */) {
            throw new ValidationException('ERR_CUSTOM_VALIDATION');
        }
    }
}
```

## API Integration

MintLogic is automatically called by the API at the following points:

1. **When fetching a record** - returns initial logic (`getInitial()`)
2. **When changing values** - returns updated logic for changed fields (`getChanged()`)
3. **Before saving** - validates the entire bean (`validateBean()`)

Example API response with logic:

```json
{
  "data": {
    "id": "123",
    "name": "Example",
    "status": "active"
  },
  "logic": {
    "rules": [
      {
        "key": "rule_name",
        "trigger": true,
        "triggerFields": ["status"],
        "logic": {
          "visible": {
            "special_field": true
          },
          "required": {
            "required_field": true
          },
          "readonly": {
            "readonly_field": true
          },
          "update": {
            "calculated_field": "new_value"
          },
          "errors": {},
          "options": {}
        }
      }
    ]
  }
}
```

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

### Benefits

- **Non-invasive customization** - You can add or extend logic without modifying core files
- **Multiple files** - Split logic into multiple thematic files (e.g., `validation.logicdefs.php`, `visibility.logicdefs.php`)
- **Safe upgrades** - Custom definitions remain intact during system upgrades

### Example

Standard file in `api/lib/MintLogic/Modules/Candidates/logicdefs.php`:
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

Both rules will be merged and active in the system.

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
            'hooks' => [Hook::ALL, Hook::CHANGE],
            'triggerFields' => ['type'],
            'trigger' => Formula::equals('$type', 'other'),
            'logic' => ['visible' => ['field_name' => true]],
        ],
        'field_hide' => [
            'hooks' => [Hook::ALL, Hook::CHANGE],
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

1. Check the API response - `logic` section in JSON
2. Use `var_dump()` or `error_log()` in update functions
3. Check validation logs in case of errors
4. Test formulas separately using `Formula::executeOperator()`

## Limitations

- Formulas do not support complex mathematical calculations (use functions in `update`)
- No direct database access in formulas (use functions or validators)
- Logic is executed server-side, so it requires API communication

## Summary

MintLogic is a powerful tool for defining dynamic form logic without modifying frontend code. Thanks to the system of hooks, formulas, and validators, you can flexibly manage field behavior based on various business conditions.
