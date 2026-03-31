<?php

use MintHCM\Lib\MintLogic\Exceptions\ValidationException;
use MintHCM\Lib\MintLogic\Formula;
use MintHCM\Lib\MintLogic\Hook;
use MintHCM\Lib\MintLogic\Validators\IsUnique;
use MintHCM\Lib\MintLogic\Modules\_example\Validators\ExampleValidator;

return [
    'bean' => [
        'validation' => [
            IsUnique::class, // Runs Validator without field context. Executed on save
        ],
    ],
    'rules' => [
        // rule description
        [
            'hooks' => [], // Required. The rule will be checked on these hooks, e.g. on init, change
            'triggerFields' => [], // Optional. The rule will be tested when one of these fields changes
            'trigger' => true, // Optional. The rule condition. If true or not set, the rule will be triggered and the logic will be applied
            'logic' => [ // Required. The logic to be applied when the rule is triggered
                'visible' => [], // Fields to be shown or hidden
                'readonly' => [], // Fields to be set as readonly or editable
                'required' => [], // Fields to be set as required or not required
                'update' => [], // Fields to be updated with new values
                'validation' => [], // Fields to be validated with validators
                'options' => [], // Fields to have their options updated, e.g. a list for enum field
            ]
        ],

        // example body of the rule:
        [
            'hooks' => [
                Hook::INIT, // check if the rule should be triggered on init
                Hook::CHANGE, // check if the rule should be triggered when one of the triggerFields changes
            ], 
            'triggerFields' => ['field1', 'field2'], // rule is checked when value of field1 or field2 changes
            'trigger' => Formula::or(
                Formula::equals('$field1', 'value1'),
                Formula::equals('$field2', 'value2')
            ), // logic is applied when field1 equals 'value1' or field2 equals 'value2'
            'logic' => [
                'visible' => [
                    'field3' => false, // field3 will be hidden when the rule is triggered
                    'field4' => true, // field4 will be shown when the rule is triggered
                ],
                'readonly' => [
                    'field5' => true, // field5 will be readonly when the rule is triggered
                    'field6' => false, // field6 will be editable when the rule is triggered
                ],
                'required' => [
                    'field7' => true, // field7 will be required when the rule is triggered
                    'field8' => false, // field8 will not be required when the rule is triggered
                ],
                'update' => [
                    'field9' => 'value', // field9 will be updated to 'value' when the rule is triggered
                    'field10' => '', // field10 will be updated to '' when the rule is triggered
                ],
                'validation' => [
                    'field11' => [
                        ExampleValidator::class, // field11 will be validated with ExampleValidator when the rule is triggered
                    ],
                ],
                'options' => [
                    'enum1' => 'name_of_the_list', // enum1 will have its options updated to 'name_of_the_list' from app_list_strings when the rule is triggered
                    'enum2' => function ($bean) {
                        global $app_list_strings;
                        $list = $app_list_strings['name_of_the_list'];
                        unset($list['list_value']);
                        return $list; // enum2 will have its options updated to the modified list from app_list_strings when the rule is triggered
                    },
                ],
            ],
        ],

        // Validation example
        [
            'hooks' => [Hook::ALL],
            'triggerFields' => ['name'],
            'trigger' => Formula::notEmpty('$name'),
            'logic' => [
                'validation' => [
                    // Field may have multiple validators
                    // First validator that throws ValidationException stops the validation and returns the error
                    'name' => [
                        IsUnique::class, // Global Validator class
                        ExampleValidator::class, // Module Validator class
                        function ($bean) { // Anonymous function that throws ValidationException with error message
                            if ($bean->name = 'test') {
                                throw new ValidationException('ERR_NAME_CANNOT_BE_TEST');
                            }
                        },
                        Formula::validate( // Formula validation
                            Formula::equals('$name', 'test2'), // error condition
                            'ERR_NAME_CANNOT_BE_TEST2' // error message
                        ),
                    ],
                    // 'another_field' => [...],
                    // ...
                ],
            ],
        ],
    ],
];
