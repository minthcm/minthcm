<?php
require_once 'vendor/autoload.php';

class EntityCreator
{
    protected const TPL_DIR_PATH           = '/tpls';
    protected const ENTITY_TEMPLATE        = 'Entity.tpl';
    protected const ENTITY_FOLDER_PATH     = './app/Entities/';
    protected const REPOSITORY_FOLDER_PATH = "MintHCM\\Api\\Repositories\\";
    protected const ENTITY_NAMESPACE       = 'MintHCM\\Api\\Entities';

    protected const ORM_TYPE_MAP = [
        'id' => 'string',
        'varchar' => 'string',
        'int' => 'integer',
        'float' => 'float',
        'bool' => 'boolean',
        'date' => 'date',
        'datetime' => 'datetime',
        'text' => 'text',
        'enum' => 'string',
        'multienum' => 'string',
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
    ];

    protected const SKIP_TYPES = [
        'link',
        'function',
        'time',
    ];

    protected $vardefs;
    protected $moduleName;
    protected $data;

    public function __construct(string $moduleName, array $vardefs)
    {
        $this->moduleName = $moduleName;
        $this->vardefs = $vardefs;
    }

    public function run()
    {
        $this->buildData();
        $this->buildFields();
        $this->buildRelationshipFields();
        $this->buildIndexes();
        $this->buildAdditionalUseStatements();
        $this->buildConstructorFields();
        $this->createEntity();
    }

    protected function buildData()
    {
        $this->data = [
            'table' => $this->vardefs['table'],
            'className' => $this->moduleName,
            'entityNamespace' => self::ENTITY_NAMESPACE,
            'fields' => [],
            'relationshipFields' => [],
            'additionalUseStatements' => [],
            'constructorFields' => [],
        ];

        $repositorySet = ! empty($this->vardefs['doctrineEntity']['repository']);
        if ($repositorySet) {
            $this->data['repositoryClassPath'] = self::REPOSITORY_FOLDER_PATH . $this->vardefs['doctrineEntity']['repository'];
            $this->data['repositorySet'] = true;
        }
    }

    protected function buildFields()
    {
        foreach ($this->vardefs['fields'] as $fieldName => $fieldDef) {
            if ((! empty($fieldDef['source']) && 'non-db' === $fieldDef['source'])
                || in_array($fieldDef['type'], self::SKIP_TYPES)) {
                continue;
            }

            $field = [
                'name' => $fieldName,
                'columnAttributes' => '',
                'isId' => false,
            ];

            $attributes = [];
            if (! empty($fieldDef['type']) || ! empty($fieldDef['dbType'])) {
                $type = $fieldDef['dbType'] ?? $fieldDef['type'];
                $ORM_type = self::ORM_TYPE_MAP[$type] ?? null;

                if (! $ORM_type) {
                    throw new \Exception("Unsupported field type: $type for field: $fieldName");
                }

                $attributes[] = 'type="' . $ORM_type . '"';

                if (! empty($fieldDef['len'])) {
                    $attributes[] = 'length="' . $fieldDef['len'] . '"';
                } else if ('id' == $type || 'relate' == $type) {
                    $attributes[] = 'length="36"';
                }

                if ('id' == $type && 'id' == $fieldName) {
                    $field['isId'] = true;
                }
            }

            $field['columnAttributes'] = implode(', ', $attributes);
            $this->data['fields'][$field['name']] = $field;
        }
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
            if ('link' === $fieldDef['type'] && ! empty($fieldDef['relationship'])) {
                if($this->dataHasRelationshipField($fieldName)) {
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
        global $dictionary;
        if(!empty($dictionary[$fieldDef['relationship']]['relationships'][$fieldDef['relationship']])) {
            return $dictionary[$fieldDef['relationship']]['relationships'][$fieldDef['relationship']];
        }

        $bean = BeanFactory::getBean($this->moduleName);
        if($bean) {
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
        global $entityCreator, $dictionary, $beanList;
        $relationshipField = [
            'name' => '',
            'attributes' => [],
            'isCollection' => false,
        ];

        foreach ($relationshipDef as $key => $value) {
            if ($value == $this->moduleName
                || ('Employees' == $this->moduleName && 'Users' == $value)
                || ('Users' == $this->moduleName && 'Employees' == $value)) {
                $relationshipSide = explode('_', $key)[0];
                $targetSide = 'lhs' === $relationshipSide ? 'rhs' : 'lhs';
                break;
            }
        }

        foreach ($this->vardefs['fields'] as $fieldName => $fieldDef) {
            if ('link' === $fieldDef['type'] && $fieldDef['relationship'] === $relationshipName) {
                $relationshipField['name'] = $fieldName;
                break;
            }
        }

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

        $targetBean = $beanList[$target['module']] ?? '';
        $moduleBean = $beanList[$module['module']] ?? '';
        if (empty($dictionary[$targetBean]) || empty($dictionary[$moduleBean])) {
            return;
        }

        $joinTable = $relationshipDef['join_table'] ?? null;
        if ($joinTable) {
            $target['join_key'] = $relationshipDef['join_key_' . $targetSide] ?? '';
            $module['join_key'] = $relationshipDef['join_key_' . $relationshipSide] ?? '';
        }
        
        $relationshipField['attributes'] = $this->buildRelationshipAttributes(
            $relationshipDef['relationship_type'],
            $module,
            $target,
            $relationshipSide,
            $joinTable,
            $relationshipField['name']
        );
        $relationshipField['isCollection'] = 'many-to-many' === $relationshipDef['relationship_type'] || ('one-to-many' === $relationshipDef['relationship_type'] && 'lhs' === $relationshipSide);
        $this->data['relationshipFields'][] = $relationshipField;

        $targetBeanName = $beanList[$target['module']];
        if (! in_array($target['module'], $entityCreator['CreatingEntities']) && ! empty($dictionary[$targetBeanName])) {
            $entityCreator['CreatingEntities'][] = $target['module'];
            (new EntityCreator($target['module'], $dictionary[$targetBeanName]))->run();
        }
    }

    protected function buildRelationshipAttributes($relationshipType, $module, $target, $relationshipSide, $joinTable = null, $currentFieldName = '')
    {
        $attributes = [
            'relationshipType' => '',
            'joinAttributes' => [],
            'relationshipAttributes' => [],
            'inverseJoinAttributes' => [],
        ];

        $directionType = 'SecurityGroups' == $target['module'] ? 'unidirectional' : 'bidirectional';
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
                        $attributes['relationshipAttributes'][] = 'mappedBy="' . $module['table'] . '"';
                    }
                } else {
                    $attributes['relationshipType'] = 'ManyToOne';
                    $attributes['relationshipAttributes'][] = 'inversedBy="' . $module['table'] . '"';
                    $attributes['joinAttributes'][] = 'name="' . $module['key'] . '"';
                    $attributes['joinAttributes'][] = 'referencedColumnName="' . $target['key'] . '"';
                }
                break;
            case 'one-to-one':
                $attributes['relationshipAttributes'][] = 'targetEntity=' . $target['module'] . '::class';
                $attributes['relationshipType'] = 'OneToOne';
                if ('lhs' === $relationshipSide) {
                    $attributes['relationshipAttributes'][] = 'mappedBy="' . $module['table'] . '"';
                } else if(empty($joinTable)) {
                    $attributes['relationshipAttributes'][] = 'inversedBy="' . $module['table'] . '"';
                    $attributes['joinAttributes'][] = 'name="' . $module['key'] . '"';
                    $attributes['joinAttributes'][] = 'referencedColumnName="' . $target['key'] . '"';
                    $attributes['inverseJoinAttributes'][] = 'name="' . $target['key'] . '"';
                    $attributes['inverseJoinAttributes'][] = 'referencedColumnName="' . $module['key'] . '"';
                } else {
                    $attributes['relationshipAttributes'][] = 'inversedBy="' . $module['table'] . '"';
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
                    $attributes['relationshipAttributes'][] = 'inversedBy="' . $target['table'] . '"';
                    $attributes['joinAttributes'][] = 'name="' . $module['join_key'] . '"';
                    $attributes['joinAttributes'][] = 'referencedColumnName="' . $target['key'] . '"';
                    $attributes['inverseJoinAttributes'][] = 'name="' . $target['join_key'] . '"';
                    $attributes['inverseJoinAttributes'][] = 'referencedColumnName="' . $module['key'] . '"';
                } else if ('lhs' === $relationshipSide) {
                    $attributes['relationshipAttributes'][] = 'targetEntity=' . $target['module'] . '::class';
                    $attributes['relationshipAttributes'][] = 'mappedBy="' . $module['table'] . '"';
                } else {
                    $attributes['relationshipAttributes'][] = 'targetEntity=' . $target['module'] . '::class';
                    $attributes['relationshipAttributes'][] = 'inversedBy="' . $module['table'] . '"';
                    $attributes['inverseJoinAttributes'][] = 'name="' . $target['join_key'] . '"';
                    $attributes['inverseJoinAttributes'][] = 'referencedColumnName="' . $module['key'] . '"';
                    $attributes['joinAttributes'][] = 'name="' . $module['join_key'] . '"';
                    $attributes['joinAttributes'][] = 'referencedColumnName="' . $target['key'] . '"';
                }
                break;
        }

        $relationAttributes = [];
        if ($joinTable && ! empty($attributes['joinAttributes'] && ! empty($attributes['inverseJoinAttributes']))) {
            $relationAttributes[] = '@ORM\\JoinTable(name="' . $joinTable . '", joinColumns={@ORM\\JoinColumn(' . implode(', ', $attributes['joinAttributes']) . ')}, inverseJoinColumns={@ORM\\JoinColumn(' . implode(', ', $attributes['inverseJoinAttributes']) . ')})';
        } else {
            if (! empty($attributes['joinAttributes'])) {
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
        switch($relationshipType) {
            case 'OneToMany':
                $field['attributes'][] = '@ORM\\ManyToOne(targetEntity=' . $this->moduleName . '::class, inversedBy="' . $linkName . '")';
                $field['attributes'][] = '@ORM\\JoinColumn(name="' . $fieldName . '", referencedColumnName="id")';
                break;
        }
        $this->data['fields'][$fieldName] = $field;
    }

    protected function buildIndexes()
    {
        if (! empty($this->vardefs['indices'])) {
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
    }

    protected function createEntity()
    {
        $smarty = new Smarty();
        $smarty->setTemplateDir(dirname(__FILE__) . self::TPL_DIR_PATH);
        $smarty->assign($this->data);
        $classCode = $smarty->fetch(self::ENTITY_TEMPLATE);

        chdir('../api');
        $filePath = self::ENTITY_FOLDER_PATH . $this->moduleName . '.php';
        if (! is_dir(self::ENTITY_FOLDER_PATH)) {
            mkdir(self::ENTITY_FOLDER_PATH, 0755, true);
        }
        file_put_contents($filePath, $classCode);
        chdir('../legacy');
    }
}
