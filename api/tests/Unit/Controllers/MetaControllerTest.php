<?php

namespace {
    if (!defined('sugarEntry')) {
        define('sugarEntry', true);
    }
}

namespace MintHCM\Tests\Unit\Controllers {

    use MintHCM\Api\Controllers\MetaController;
    use PHPUnit\Framework\TestCase;

    final class MetaControllerTest extends TestCase
    {
        private ExposedMetaController $controller;

        protected function setUp(): void
        {
            $this->controller = new ExposedMetaController();
        }

        // --- Branch 1: field is a plain string (field name) ---

        public function testReplacesStringFieldWithModuleFieldData(): void
        {
            $result = $this->controller->mergeFields(
                ['panel0' => ['first_name']],
                ['first_name' => ['type' => 'varchar', 'vname' => 'LBL_FIRST_NAME']]
            );

            self::assertSame(['type' => 'varchar', 'vname' => 'LBL_FIRST_NAME'], $result['panel0'][0]);
        }

        // --- Branch 2: field is an array, value is a string mapping to module field ---

        public function testReplacesStringValueWithModuleFieldDataAndRenamesVnameToLabel(): void
        {
            $result = $this->controller->mergeFields(
                ['panel0' => [['left' => 'first_name']]],
                ['first_name' => ['type' => 'varchar', 'vname' => 'LBL_FIRST']]
            );

            self::assertSame('LBL_FIRST', $result['panel0'][0]['left']['label']);
            self::assertArrayNotHasKey('vname', $result['panel0'][0]['left']);
        }

        public function testSkipsStringValueWhenItDoesNotMapToAnyModuleField(): void
        {
            $result = $this->controller->mergeFields(
                ['panel0' => [['left' => 'unknown_field']]],
                ['first_name' => ['type' => 'varchar', 'vname' => 'LBL_FIRST']]
            );

            self::assertSame('unknown_field', $result['panel0'][0]['left']);
        }

        // --- Branch 3a: fieldset with string field names ---

        public function testExpandsFieldsetWithStringFieldNames(): void
        {
            $result = $this->controller->mergeFields(
                ['panel0' => [[0 => [
                    'type'       => 'fieldset',
                    'properties' => ['fields' => ['first_name', 'last_name']],
                ]]]],
                [
                    'first_name' => ['type' => 'varchar', 'vname' => 'LBL_FIRST'],
                    'last_name'  => ['type' => 'varchar', 'vname' => 'LBL_LAST'],
                ]
            );

            $fields = $result['panel0'][0][0]['properties']['fields'];
            self::assertSame('LBL_FIRST', $fields[0]['label']);
            self::assertSame('LBL_LAST',  $fields[1]['label']);
            self::assertArrayNotHasKey('vname', $fields[0]);
            self::assertArrayNotHasKey('vname', $fields[1]);
        }

        // --- Branch 3b: fieldset with array field entries (explicit overrides) ---

        public function testExpandsFieldsetWithArrayFieldEntriesMergingOverrides(): void
        {
            $result = $this->controller->mergeFields(
                ['panel0' => [[0 => [
                    'type'       => 'fieldset',
                    'properties' => ['fields' => [['name' => 'first_name', 'required' => true]]],
                ]]]],
                ['first_name' => ['type' => 'varchar', 'vname' => 'LBL_FIRST']]
            );

            $field = $result['panel0'][0][0]['properties']['fields'][0];
            self::assertSame('LBL_FIRST', $field['label']);
            self::assertTrue($field['required']);
            self::assertSame('varchar', $field['type']);
            self::assertArrayNotHasKey('vname', $field);
        }

        // --- Branch 4: field is an array with 'name' pointing to module field ---

        public function testMergesModuleFieldDataIntoFieldWithNameProperty(): void
        {
            $result = $this->controller->mergeFields(
                ['panel0' => [[0 => ['name' => 'first_name']]]],
                ['first_name' => ['type' => 'varchar', 'vname' => 'LBL_FIRST']]
            );

            $field = $result['panel0'][0][0];
            self::assertSame('first_name', $field['name']);
            self::assertSame('varchar',    $field['type']);
            self::assertSame('LBL_FIRST',  $field['label']);
            self::assertArrayNotHasKey('vname', $field);
        }

        public function testDoesNotOverwriteExistingLabelWhenMergingModuleFieldData(): void
        {
            $result = $this->controller->mergeFields(
                ['panel0' => [[0 => ['name' => 'first_name', 'label' => 'Custom Label']]]],
                ['first_name' => ['type' => 'varchar', 'vname' => 'LBL_FIRST']]
            );

            self::assertSame('Custom Label', $result['panel0'][0][0]['label']);
        }

        public function testSkipsFieldWhenNameDoesNotMapToAnyModuleField(): void
        {
            $input = ['panel0' => [[0 => ['name' => 'unknown_field']]]];

            $result = $this->controller->mergeFields($input, []);

            self::assertSame($input, $result);
        }

        public function testSkipsFieldWithNoNameProperty(): void
        {
            $input = ['panel0' => [[0 => ['type' => 'spacer']]]];

            $result = $this->controller->mergeFields($input, []);

            self::assertSame($input, $result);
        }
    }

    class ExposedMetaController extends MetaController
    {
        public function mergeFields(array $array, array $module_fields): array
        {
            return $this->mergeModuleFields($array, $module_fields);
        }
    }
}
