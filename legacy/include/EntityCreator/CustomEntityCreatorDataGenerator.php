<?php

require_once 'include/EntityCreator/EntityCreatorDataGenerator.php';

class CustomEntityCreatorDataGenerator extends EntityCreatorDataGenerator
{

    const CUSTOM_SUFFIX = '_cstm';

    public function __construct(string $moduleName, array $vardefs)
    {
        parent::__construct($moduleName, $vardefs, true);
        $this->buildCustomFields();
    }

    protected function buildCustomFields()
    {
        if (!self::hasCustomTable($this->vardefs["table"])) {
            return;
        }
        $customFields = [];

        foreach ($this->vardefs['fields'] as $fieldName => $fieldDef) {
            if (empty($this->vardefs["fields"][$fieldName]["source"])
                || "custom_fields" != $this->vardefs["fields"][$fieldName]["source"]) {
                continue;
            }
            unset($fieldDef['source']);
            $customFields[$fieldName] = $this->getFieldData($fieldName, $fieldDef);
        }
        $this->setCustomData($customFields);
    }

    protected function setCustomData(array $customFields)
    {
        $this->data['table'] = $this->vardefs['table'] . self::CUSTOM_SUFFIX;
        $this->data['fields'] = $customFields;
        $this->data['relationships'] = [];
        $this->data['indexes'] = [
            [
                'name' => $this->vardefs['table'] . 'pk',
                'columns' => 'id_c',
            ],
        ];
        $this->data['doctrineEntity'] = [];
        $this->data['generate_custom_entity'] = !empty($customFields);
    }
}
