<?php

class EntityCreatorDataGenerator
{
    public const ORM_TYPE_MAP = [
        'id' => 'id',
        'varchar' => 'string',
        'int' => 'integer',
        'float' => 'float',
        'decimal' => 'decimal',  //COPY TO CORE MINTHCM
        'bool' => 'boolean',
        'date' => 'date',
        'datetime' => 'datetime',
        'text' => 'text',
        'enum' => 'string',
        'multienum' => 'multienum',
        'phone' => 'string',
        'url' => 'string',
        'file' => 'string',
        'fullname' => 'string',
        'double' => 'decimal',
        'relate' => 'string',
        'parent_type' => 'string',
        'parent_id' => 'string',
        'currency' => 'decimal',
        'datetimecombo' => 'datetime',
        'longtext' => 'text',
        'encrypt' => 'string',
        'long' => 'bigint',
        'mediumtext' => 'text',
        'dynamicenum' => 'string',
    ];

    public const SKIP_TYPES = [
        'link',
        'function',
        'time',
    ];

    protected const CUSTOM_SUFFIX = '_cstm';
    protected string $moduleName;
    protected array $vardefs;
    protected array $data = [];
    protected bool $is_custom = false;
    public const VIRTUAL_FIELDS = [
        'email1',
    ];

    public function __construct(string $moduleName, array $vardefs, bool $is_custom = false)
    {
        $this->moduleName = $moduleName;
        $this->vardefs = $vardefs;
        $this->data = [];
        $this->is_custom = $is_custom;
        $this->buildData();
        $this->buildFields();
        $this->buildRelationshipFields();
        $this->buildIndexes();
        $this->buildAdditionalUseStatements();
        $this->buildConstructorFields();
        $this->buildAdditionalMethods();
    }
    public function getData(): array
    {
        return $this->data;
    }

    protected function buildData()
    {
        $this->data = [
            'table' => $this->vardefs['table'],
            'className' => $this->moduleName,
            'entityNamespace' => EntityCreator::ENTITY_NAMESPACE,
            'fields' => [],
            'relationshipFields' => [],
            'additionalUseStatements' => [],
            'constructorFields' => [],
            'generate_custom_entity' => (new CustomEntityCreator($this->moduleName, $this->vardefs))->shouldGenerateCustomEntity(),
            'additionalMethods' => [],
        ];
        $repositorySet = !empty($this->vardefs['doctrineEntity']['repository']);
        if ($repositorySet) {
            $this->data['repositoryClassPath'] = EntityCreator::REPOSITORY_FOLDER_PATH . $this->vardefs['doctrineEntity']['repository'];
            $this->data['repositorySet'] = true;
        }
    }

    protected function buildFields()
    {
        foreach ($this->vardefs['fields'] as $fieldName => $fieldDef) {
            if (
                in_array($fieldDef['name'], self::VIRTUAL_FIELDS)
                && empty($fieldDef['dbType'])
                && 'non-db' === $fieldDef['source']) {
                $this->data['fields'][$fieldDef['name']] = [
                    'name' => $fieldDef['name'],
                    'columnAttributes' => null,
                    'isId' => false,
                    'virtual' => true,
                ];
                continue;
            }
            if ((! empty($fieldDef['source']) && 'non-db' === $fieldDef['source'])
                || (in_array($fieldDef['type'], self::SKIP_TYPES) && (empty($fieldDef['dbType']) || 'id' !== $fieldDef['dbType']))
                || 'custom_fields' === $fieldDef['source']
            ) {
                continue;
            }

            $this->data['fields'][$fieldName] = $this->getFieldData($fieldName, $fieldDef);
        }
    }

    protected function getFieldData(string $fieldName, array $fieldDef): array
    {
        $field = [
            'name' => $fieldName,
            'columnAttributes' => '',
            'isId' => false,
        ];

        $attributes = [];
        if (!empty($fieldDef['type']) || !empty($fieldDef['dbType'])) {
            $type = $fieldDef['dbType'] ?? $fieldDef['type'];
            $ORM_type = self::ORM_TYPE_MAP[$type] ?? null;

            if (!$ORM_type) {
                throw new \Exception("Unsupported field type: $type for field: $fieldName");
            }

            $attributes[] = 'type="' . $ORM_type . '"';

            if (!empty($fieldDef['len'])) {
                $attributes[] = 'length="' . $fieldDef['len'] . '"';
            } else if ('id' == $type || 'relate' == $type) {
                $attributes[] = 'length="36"';
            }

            if ((in_array($type, ['id', 'int']) && 'id' == $fieldName)) {
                $field['isId'] = true;
                if ('id' == $type) {
                    $field['CustomIdGenerator'] = 'class=UuidGenerator::class';
                }
            }
        }

        $field['columnAttributes'] = implode(', ', $attributes);
        return $field;
    }

    protected function buildRelationshipFields()
    {
        foreach ($this->vardefs['relationships'] as $relationshipName => $relationshipDef) {
            if (empty($relationshipDef['lhs_module']) || empty($relationshipDef['rhs_module'])) {
                continue;
            }
            $this->createRelationshipField($relationshipDef, $relationshipName);
        }

        foreach ($this->vardefs['fields'] as $fieldName => $fieldDef) {
            if ('link' === $fieldDef['type'] && !empty($fieldDef['relationship'])) {
                if ($this->dataHasRelationshipField($fieldName)) {
                    continue;
                }

                $relationshipDef = $this->getRelationship($fieldName, $fieldDef);
                if (empty($relationshipDef)) {
                    continue;
                }

                $this->createRelationshipField(
                    $relationshipDef,
                    $fieldDef['relationship']
                );
            }
        }
    }

    protected function getRelationship($fieldName, $fieldDef)
    {
        $dictionary = EntityCreatorManager::$dictionary;
        if (!empty($dictionary[$fieldDef['relationship']]['relationships'][$fieldDef['relationship']])) {
            $rel = $dictionary[$fieldDef['relationship']]['relationships'][$fieldDef['relationship']];
            if (!empty($dictionary[$fieldDef['relationship']]['true_relationship_type'])) {
                $rel['true_relationship_type'] = $dictionary[$fieldDef['relationship']]['true_relationship_type'];
            }
            return $rel;
        }

        $bean = BeanFactory::getBean($this->moduleName);
        if ($bean) {
            $bean->load_relationship($fieldName);
            return $bean->$fieldName->def || false;
        }

        return false;
    }

    protected function dataHasRelationshipField($relationshipName)
    {
        foreach ($this->data['relationshipFields'] as $relationshipField) {
            if ($relationshipField['name'] === $relationshipName) {
                return true;
            }
        }
        return false;
    }

    protected function createRelationshipField($relationshipDef, $relationshipName)
    {
        global $entityCreator;
        $dictionary = EntityCreatorManager::$dictionary;
        $relationshipField = [
            'name' => '',
            'attributes' => [],
            'isCollection' => false,
        ];

        $relationshipSide = '';
        $targetSide = '';
        foreach ($relationshipDef as $key => $value) {
            if (
                $value == $this->moduleName
                || ('Employees' == $this->moduleName && 'Users' == $value)
                || ('Users' == $this->moduleName && 'Employees' == $value)
            ) {
                $relationshipSide = explode('_', $key)[0];
                $targetSide = 'lhs' === $relationshipSide ? 'rhs' : 'lhs';
                break;
            }
        }

        $targetFieldName = $this->getRelationshipLinkFieldName($relationshipDef, $targetSide, $relationshipName);
        $relationshipField['name'] = $this->getRelationshipLinkFieldName($relationshipDef, $relationshipSide, $relationshipName);

        if (empty($relationshipField['name']) || $this->dataHasRelationshipField($relationshipField['name'])) {
            return;
        }

        $module = [
            'module' => '',
            'key' => '',
            'table' => '',
            'join_key' => '',
        ];
        $target = [
            'module' => '',
            'key' => '',
            'table' => '',
            'join_key' => '',
        ];
        foreach (['module', 'key', 'table'] as $attr) {
            $target[$attr] = $relationshipDef[$targetSide . '_' . $attr] ?? '';
            $module[$attr] = $relationshipDef[$relationshipSide . '_' . $attr] ?? '';
        }

        if (empty($dictionary[$target['module']]) || empty($dictionary[$module['module']])) {
            return;
        }

        $joinTable = $relationshipDef['join_table'] ?? null;
        if ($joinTable) {
            $target['join_key'] = $relationshipDef['join_key_' . $targetSide] ?? '';
            $module['join_key'] = $relationshipDef['join_key_' . $relationshipSide] ?? '';
        }

        // Detect semantic one-to-one via junction table
        $isSemanticOneToOne = false;
        if (!empty($joinTable)) {
            // Pattern 1: relationship_type='one-to-one' with join_table — invalid for Doctrine, convert to ManyToMany
            if ($relationshipDef['relationship_type'] === 'one-to-one') {
                $relationshipDef['relationship_type'] = 'many-to-many';
                $isSemanticOneToOne = true;
            }
            // Pattern 2: true_relationship_type='one-to-one' propagated from metadata, relationship_type already 'many-to-many'
            elseif (($relationshipDef['true_relationship_type'] ?? '') === 'one-to-one'
                && $relationshipDef['relationship_type'] === 'many-to-many') {
                $isSemanticOneToOne = true;
            }
            // Pattern 3: check dictionary directly for true_relationship_type
            if (!$isSemanticOneToOne) {
                $dictionary = EntityCreatorManager::$dictionary;
                if (($dictionary[$relationshipName]['true_relationship_type'] ?? '') === 'one-to-one') {
                    $isSemanticOneToOne = true;
                }
            }
        }

        $relationshipField['attributes'] = $this->buildRelationshipAttributes(
            $relationshipDef['relationship_type'],
            $module,
            $target,
            $relationshipSide,
            $joinTable,
            $relationshipField['name'],
            $targetFieldName
        );
        $relationshipField['isCollection'] = 'many-to-many' === $relationshipDef['relationship_type'] || ('one-to-many' === $relationshipDef['relationship_type'] && 'lhs' === $relationshipSide);

        if ($isSemanticOneToOne) {
            $relationshipField['isSemanticOneToOne'] = true;
            $relationshipField['targetModule'] = $target['module'];
        }

        $this->data['relationshipFields'][] = $relationshipField;
        if (!in_array($target['module'], $entityCreator['CreatingEntities']) && !empty($dictionary[$target['module']])) {
            $entityCreator['CreatingEntities'][] = $target['module'];
            try{
            (new EntityCreator($target['module'], $dictionary[$target['module']]))->run();
            (new CustomEntityCreator($target['module'], $dictionary[$target['module']]))->run();
            } catch (Exception $e) {
                sugar_die("Exception caught while creating related entity: " . $e->getMessage());
        }
    }
    }

    protected function getRelationshipLinkFieldName($relationshipDef, $side, $relationshipName)
    {
        $dictionary = EntityCreatorManager::$dictionary;
        
        if (empty($side)) {
            return '';
        }

        $moduleName = $relationshipDef[$side . '_module'];
        if (empty($moduleName)) {
            return '';
        }

        if (empty($dictionary[$moduleName])) {
            return '';
        }
        
        $module = $dictionary[$moduleName];

        foreach($module['fields'] as $fieldName => $fieldDef) {
            if (
                'link' === $fieldDef['type'] 
                && ! empty($fieldDef['relationship']) 
                && $fieldDef['relationship'] === $relationshipName
            ) {
                return $fieldName;
            }
        }

        return '';
    }

    protected function buildRelationshipAttributes($relationshipType, $module, $target, $relationshipSide, $joinTable = null, $currentFieldName = null, $targetFieldName = null)
    {
        $attributes = [
            'relationshipType' => '',
            'joinAttributes' => [],
            'relationshipAttributes' => [],
            'inverseJoinAttributes' => [],
        ];

        $directionType = in_array($target['module'], ['SecurityGroups', 'EmailAddresses']) ? 'unidirectional' : 'bidirectional';
        $selfReferencing = $module['module'] === $target['module'];
        switch ($relationshipType) {
            case 'one-to-many':
                $attributes['relationshipAttributes'][] = 'targetEntity=' . $target['module'] . '::class';
                if ('lhs' === $relationshipSide) {
                    $attributes['relationshipType'] = 'OneToMany';
                    if ($selfReferencing) {
                        $key = 'id' !== $module['key'] ? $module['key'] : $target['key'];
                        $attributes['relationshipAttributes'][] = 'mappedBy="' . $key . '"';
                        $this->changeFieldToSelfReference($key, $attributes['relationshipType'], $currentFieldName);
                    } else {
                        $attributes['relationshipAttributes'][] = 'mappedBy="' . ($targetFieldName ?: $target['table']) . '"';
                    }
                } else {
                    $attributes['relationshipType'] = 'ManyToOne';
                    $attributes['relationshipAttributes'][] = 'inversedBy="' . ($targetFieldName ?: $module['table']) . '"';
                    $attributes['joinAttributes'][] = 'name="' . $module['key'] . '"';
                    $attributes['joinAttributes'][] = 'referencedColumnName="' . $target['key'] . '"';
                }
                break;
            case 'one-to-one':
                $attributes['relationshipAttributes'][] = 'targetEntity=' . $target['module'] . '::class';
                $attributes['relationshipType'] = 'OneToOne';
                if ('lhs' === $relationshipSide) {
                    $attributes['relationshipAttributes'][] = 'mappedBy="' . ($currentFieldName ?: $module['table']) . '"';
                } else if (empty($joinTable)) {
                    $attributes['relationshipAttributes'][] = 'inversedBy="' . ($currentFieldName ?: $module['table']) . '"';
                    $attributes['joinAttributes'][] = 'name="' . $module['key'] . '"';
                    $attributes['joinAttributes'][] = 'referencedColumnName="' . $target['key'] . '"';
                    $attributes['inverseJoinAttributes'][] = 'name="' . $target['key'] . '"';
                    $attributes['inverseJoinAttributes'][] = 'referencedColumnName="' . $module['key'] . '"';
                } else {
                    $attributes['relationshipAttributes'][] = 'inversedBy="' . ($currentFieldName ?: $module['table']) . '"';
                    $attributes['inverseJoinAttributes'][] = 'name="' . $target['join_key'] . '"';
                    $attributes['inverseJoinAttributes'][] = 'referencedColumnName="' . $module['key'] . '"';
                    $attributes['joinAttributes'][] = 'name="' . $module['join_key'] . '"';
                    $attributes['joinAttributes'][] = 'referencedColumnName="' . $target['key'] . '"';
                }
                break;
            case 'many-to-many':
                if (empty($joinTable)) {
                    throw new \Exception("Join table is required for many-to-many relationships.");
                }

                $attributes['relationshipType'] = 'ManyToMany';
                if ('unidirectional' === $directionType) {
                    $attributes['relationshipAttributes'][] = 'targetEntity=' . $target['module'] . '::class';
                    $attributes['relationshipAttributes'][] = 'inversedBy="' . ($targetFieldName ?: $target['table']) . '"';
                    $attributes['joinAttributes'][] = 'name="' . $module['join_key'] . '"';
                    $attributes['joinAttributes'][] = 'referencedColumnName="' . $target['key'] . '"';
                    $attributes['inverseJoinAttributes'][] = 'name="' . $target['join_key'] . '"';
                    $attributes['inverseJoinAttributes'][] = 'referencedColumnName="' . $module['key'] . '"';
                } else if ('lhs' === $relationshipSide) {
                    $attributes['relationshipAttributes'][] = 'targetEntity=' . $target['module'] . '::class';
                    $attributes['relationshipAttributes'][] = 'mappedBy="' . ($targetFieldName ?: $module['table']) . '"';
                } else {
                    $attributes['relationshipAttributes'][] = 'targetEntity=' . $target['module'] . '::class';
                    $attributes['relationshipAttributes'][] = 'inversedBy="' . ($targetFieldName ?: $module['table']) . '"';
                    $attributes['inverseJoinAttributes'][] = 'name="' . $target['join_key'] . '"';
                    $attributes['inverseJoinAttributes'][] = 'referencedColumnName="' . $module['key'] . '"';
                    $attributes['joinAttributes'][] = 'name="' . $module['join_key'] . '"';
                    $attributes['joinAttributes'][] = 'referencedColumnName="' . $target['key'] . '"';
                }
                break;
        }

        $relationAttributes = [];
        if ($joinTable && !empty($attributes['joinAttributes'] && !empty($attributes['inverseJoinAttributes']))) {
            $relationAttributes[] = '@ORM\\JoinTable(name="' . $joinTable . '", joinColumns={@ORM\\JoinColumn(' . implode(', ', $attributes['joinAttributes']) . ')}, inverseJoinColumns={@ORM\\JoinColumn(' . implode(', ', $attributes['inverseJoinAttributes']) . ')})';
        } else {
            if (!empty($attributes['joinAttributes'])) {
                $relationAttributes[] = '@ORM\\JoinColumn(' . implode(', ', $attributes['joinAttributes']) . ')';
            }
            if ($joinTable) {
                $relationAttributes[] = '@ORM\\JoinTable(name="' . $joinTable . '")';
            }
        }

        $relationAttributes[] = '@ORM\\' . $attributes['relationshipType'] . '(' . implode(', ', $attributes['relationshipAttributes']) . ')';

        return $relationAttributes;
    }

    protected function changeFieldToSelfReference($fieldName, $relationshipType, $linkName)
    {
        $field = $this->data['fields'][$fieldName] ?? null;
        if (empty($field) || empty($linkName)) {
            return;
        }
        unset($field['columnAttributes']);
        switch ($relationshipType) {
            case 'OneToMany':
                $field['attributes'][] = '@ORM\\ManyToOne(targetEntity=' . $this->moduleName . '::class, inversedBy="' . $linkName . '")';
                $field['attributes'][] = '@ORM\\JoinColumn(name="' . $fieldName . '", referencedColumnName="id")';
                break;
        }
        $this->data['fields'][$fieldName] = $field;
    }

    protected function buildIndexes()
    {
        if (!empty($this->vardefs['indices'])) {
            $this->data['indexes'] = [];
            foreach ($this->vardefs['indices'] as $index) {
                if (isset($index['fields']) && is_array($index['fields'])) {
                    $this->data['indexes'][] = [
                        'name' => $index['name'],
                        'columns' => implode('", "', $index['fields']),
                    ];
                }
            }
        } else {
            $this->data['indexes'] = [];
        }
    }

    protected function buildAdditionalUseStatements()
    {
        foreach ($this->data['relationshipFields'] as $relationshipField) {
            if ($relationshipField['isCollection']) {
                $this->data['additionalUseStatements'][] = 'use Doctrine\\Common\\Collections\\ArrayCollection';
                $this->data['additionalUseStatements'][] = 'use Doctrine\\Common\\Collections\\Collection';
                break;
            }
        }
    }

    protected function buildConstructorFields()
    {
        foreach ($this->data['relationshipFields'] as $relationshipField) {
            if ($relationshipField['isCollection']) {
                $this->data['constructorFields'][] = '$this->' . $relationshipField['name'] . ' = new ArrayCollection();';
            }
        }

        if (!empty($this->data['generate_custom_entity']) && $this->is_custom == false) {
            $this->data['constructorFields'][] = '$this->setCustomEntity(new ' . $this->moduleName . '_cstm());';
        }
    }

    protected function buildAdditionalMethods()
    {
        if (!empty($this->vardefs['fields']['email1'])) {
            $this->data['additionalMethods'][] = <<<'PHP'
    public function getEmail1(): string
            {
                $conn = $this->getEntityManager()->getConnection();

                $sql = '
                    SELECT ea.email_address
                    FROM email_addresses ea
                    INNER JOIN email_addr_bean_rel eabr
                        ON eabr.email_address_id = ea.id
                    WHERE eabr.bean_id = :bean_id
                    AND eabr.bean_module = :bean_module
                    AND eabr.primary_address = :primary_address
                    AND eabr.deleted = :deleted
                    LIMIT 1
                ';

                $module_name = $this->getModuleName();
                if (in_array($module_name, ['Employees'])) {
                    $module_name = 'Users';
                }

                $stmt = $conn->prepare($sql);
                $result = $stmt->executeQuery([
                    'bean_id' => $this->id,
                    'bean_module' => $module_name,
                    'primary_address' => 1,
                    'deleted' => 0,
                ]);

                return $result->fetchOne() ?: '';
    }

    public function getSerialized(bool $json = false): array|string
    {
        $data = parent::getSerialized($json);
        $data['email1'] = $this->getEmail1();
        return $data;
    }
    PHP;
        }

        $this->buildSemanticOneToOneGetters();
    }

    protected function buildSemanticOneToOneGetters()
    {
        $usedGetterNames = [];
        foreach ($this->data['relationshipFields'] as $relationshipField) {
            if (empty($relationshipField['isSemanticOneToOne'])) {
                continue;
            }

            $targetModule = $relationshipField['targetModule'];
            $linkName = $relationshipField['name'];
            $getterName = 'get' . $this->singularize($targetModule);

            // Avoid getter name collisions
            if (in_array($getterName, $usedGetterNames)) {
                $getterName = 'get' . ucfirst($linkName);
            }
            $usedGetterNames[] = $getterName;

            $this->data['additionalMethods'][] =
                '    public function ' . $getterName . '(): ?' . $targetModule . "\n"
                . "    {\n"
                . '        if ($this->' . $linkName . ' instanceof \Doctrine\Common\Collections\Collection) {' . "\n"
                . '            return $this->' . $linkName . '->first() ?: null;' . "\n"
                . "        }\n"
                . "        return null;\n"
                . "    }";
        }
    }

    protected function singularize(string $word): string
    {
        if (str_ends_with($word, 'ies')) {
            return substr($word, 0, -3) . 'y';
        }
        if (str_ends_with($word, 'sses')) {
            return substr($word, 0, -2);
        }
        if (str_ends_with($word, 's') && !str_ends_with($word, 'ss')) {
            return substr($word, 0, -1);
        }
        return $word;
    }

    public static function hasCustomTable(string $table_name): bool
    {
        $db = DBManagerFactory::getInstance();
        $tables = $db->getTablesArray();
        return in_array(strtolower($table_name . static::CUSTOM_SUFFIX), $tables);
    }

}
