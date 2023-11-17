<?php

if (!defined('sugarEntry')) {
    define('sugarEntry', true);
}

use Symfony\Component\Yaml\Yaml;

require_once 'lib/Search/ElasticSearch/ElasticSearchVardefsReader.php';

class MappingsGenerator
{
    protected $metadata_file = 'eslistviewdefs.php';
    protected $output_file_path = 'lib/Search/ElasticSearch/defaultParams.yml';
    protected $not_standard_fields = [
        'name' => 'name.name',
        'first_name' => 'name.first',
        'last_name' => 'name.last',
        'date_entered' => 'meta.created.date',
        'created_by' => 'meta.created.user_id',
        'date_modified' => 'meta.modified.date',
        'modified_user_id' => 'meta.modified.user_id',
        'assigned_user_id' => 'meta.assigned.user_id',
        'modified_by_name' => 'meta.modified.user_name',
        'created_by_name' => 'meta.created.user_name',
        'assigned_user_name' => 'meta.assigned.user_name',
        'primary_address_city' => 'address.primary.city',
        'primary_address_state' => 'address.primary.state',
        'primary_address_postalcode' => 'address.primary.postalcode',
        'primary_address_street' => 'address.primary.street',
        'primary_address_country' => 'address.primary.country',
        'phone_mobile' => 'phone.mobile',
    ];

    // From vardefs to elastic
    protected $type_mapping = [
        'date' => 'date',
        'datetime' => 'date',
        'datetimecombo' => 'date',
        'bool' => 'boolean',
        'text' => 'text',
        'int' => 'integer',
    ];

    protected $types = [
        'date' => [
            'type' => 'date',
            'format' => 'yyyy-MM-dd HH:mm:ss||yyyy-MM-dd',
        ],
        'text' => [
            'type' => 'text',
            'fields' => [
                'keyword' => [
                    'type' => 'keyword',
                    'ignore_above' => 256,
                ],
            ],
        ],
        'boolean' => [
            'type' => 'boolean',
        ],
        'long' => [
            'type' => 'long',
        ],
        'integer' => [
            'type' => 'integer',
        ],
    ];

    protected function getModulesWithElastic()
    {
        global $beanList;
        $modulesWithElastic = [];
        foreach ($beanList as $module => $value) {
            if (file_exists("custom/modules/{$module}/metadata/{$this->metadata_file}")) {
                $data = [
                    'module' => $module,
                    'path' => "custom/modules/{$module}/metadata/{$this->metadata_file}",
                ];
                array_push($modulesWithElastic, $data);
            } else if (file_exists("modules/{$module}/metadata/{$this->metadata_file}")) {
                $data = [
                    'module' => $module,
                    'path' => "modules/{$module}/metadata/{$this->metadata_file}",
                ];
                array_push($modulesWithElastic, $data);
            }
        }

        return $modulesWithElastic;
    }

    public function generateMappings()
    {
        $esv_reader = new \ElasticSearchVardefsReader;
        $modulesWithElastic = $this->getModulesWithElastic();
        $mappings = [];
        foreach ($modulesWithElastic as $module) {
            include $module['path'];
            $bean = BeanFactory::newBean($module['module']);
            $data = $ESListViewDefs[$module['module']];
            $fields_to_map = $this->setFieldsToMap($data);
            $defs = $bean->field_defs;
            $key = !empty($data['es_module']) ? $data['es_module'] : $module['module'];

            foreach ($fields_to_map as $field) {
                if ($defs[$field]['source'] != "non-db") {
                    $es_type_name = $this->type_mapping[$defs[$field]['type']] ?? 'text';
                    $es_type = $this->types[$es_type_name];

                    if (!empty($this->not_standard_fields[$field])) {
                        $mappings = $this->handleNotStandardField($this->not_standard_fields[$field], $mappings, $key, $es_type);
                    } else if (!empty($defs[$field])) {
                        // else if because script does not work well for fields: search_name, recr_contact_agree oraz current_user_only
                        $mappings['mappings'][$key]['properties'][$field] = $this->getPropertyMappingConfig($defs[$field]);
                    }
                }
            }

            $tracked_links = [];
            $nested_properties = $esv_reader->getModuleNestedProperties($module['module']);
            foreach ($nested_properties as $property_name => $nested_config) {
                $link_field_name = $esv_reader->getLinkFieldName($property_name, $nested_config);
                if (!$bean->load_relationship($link_field_name)) {
                    continue;
                }

                $related_module_name = $esv_reader->getRelatedModuleName($bean, $link_field_name);
                $related_bean = BeanFactory::newBean($related_module_name);

                $properties = [];
                foreach ($nested_config['fields'] as $field_name) {
                    if (!empty($related_bean->field_defs[$field_name])) {
                        $properties[$field_name] = $this->getPropertyMappingConfig($related_bean->field_defs[$field_name]);
                    }
                }

                $mappings['mappings'][$key]['properties'][$property_name] = [
                    'type' => 'nested',
                    'properties' => $properties,
                ];


                // If more than the primary key is indexed in the document.
                // We will have to take care to index related records even when writing a record from another module 
                // - the relationship does not have to change
                if (!$this->includesAtMostPrimaryKey($nested_config['fields'])) {
                    $tracked_links[] = $link_field_name;
                }
            }

            $this->saveNestedTrackingCache($module['module'], $tracked_links);
        }

        $this->parseMappingsToYaml($mappings);
    }

    protected function includesAtMostPrimaryKey(array $fields): bool
    {
        return $fields === [] || $fields === ['id'];
    }

    protected function saveNestedTrackingCache(string $module_name, array $tracked_links): void
    {
        $nested_cache_file = "cache/modules/{$module_name}/es.nested.php";
        $array_items = empty($tracked_links)
            ? ''
            : '"' . implode('","', $tracked_links). '"';

        $cache_file_content = '<?php $tracked_links = [' . $array_items . '];';
        file_put_contents($nested_cache_file, $cache_file_content);
    }

    protected function getPropertyMappingConfig(array $field_def): array
    {
        if (in_array($field_def['type'], ['date', 'datetime', 'datetimecombo'])) {
            return $this->types['date'];
        } else if ('bool' == $field_def['type']) {
            return $this->types['boolean'];
        } else if ('int' == $field_def['type']) {
            return $this->types['integer'];
        } else {
            return $this->types['text'];
        }
    }

    protected function parseMappingsToYaml($mappings)
    {
        $yaml = Yaml::dump($mappings, 10, 2);
        file_put_contents($this->output_file_path, $yaml);
    }

    protected function handleNotStandardField($es_field, $mappings, $key, $es_type)
    {
        $es_field_parts = explode('.', $es_field);
        $count = count($es_field_parts);
        $sub_mappings = &$mappings['mappings'][$key];
        foreach ($es_field_parts as $es_field_part) {
            if (!isset($sub_mappings['properties'][$es_field_part])) {
                $sub_mappings['properties'][$es_field_part] = [];
            }
            $sub_mappings = &$sub_mappings['properties'][$es_field_part];
            $count--;
            if ($count == 0) {
                $sub_mappings = $es_type;
            }
        }
        return $mappings;
    }

    protected function setFieldsToMap($data)
    {
        $fields_to_map = [];
        $columns = array_map('strtolower', array_keys($data['columns'] ? $data['columns'] : []));
        $search = array_map('strtolower', array_keys($data['search'] ? $data['search'] : []));

        $fields_to_map = array_unique(array_merge($columns, $search));

        return $fields_to_map;
    }
}
