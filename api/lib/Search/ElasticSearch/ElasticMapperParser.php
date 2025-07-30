<?php

/**
 *
 * SugarCRM Community Edition is a customer relationship management program developed by
 * SugarCRM, Inc. Copyright (C) 2004-2013 SugarCRM Inc.
 *
 * SuiteCRM is an extension to SugarCRM Community Edition developed by SalesAgility Ltd.
 * Copyright (C) 2011 - 2018 SalesAgility Ltd.
 *
 * MintHCM is a Human Capital Management software based on SuiteCRM developed by MintHCM,
 * Copyright (C) 2018-2024 MintHCM
 *
 * This program is free software; you can redistribute it and/or modify it under
 * the terms of the GNU Affero General Public License version 3 as published by the
 * Free Software Foundation with the addition of the following permission added
 * to Section 15 as permitted in Section 7(a): FOR ANY PART OF THE COVERED WORK
 * IN WHICH THE COPYRIGHT IS OWNED BY SUGARCRM, SUGARCRM DISCLAIMS THE WARRANTY
 * OF NON INFRINGEMENT OF THIRD PARTY RIGHTS.
 *
 * This program is distributed in the hope that it will be useful, but WITHOUT
 * ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS
 * FOR A PARTICULAR PURPOSE. See the GNU Affero General Public License for more
 * details.
 *
 * You should have received a copy of the GNU Affero General Public License along with
 * this program; if not, see http://www.gnu.org/licenses or write to the Free
 * Software Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA
 * 02110-1301 USA.
 *
 * You can contact SugarCRM, Inc. headquarters at 10050 North Wolfe Road,
 * SW2-130, Cupertino, CA 95014, USA. or at email address contact@sugarcrm.com.
 *
 * The interactive user interfaces in modified source and object code versions
 * of this program must display Appropriate Legal Notices, as required under
 * Section 5 of the GNU Affero General Public License version 3.
 *
 * In accordance with Section 7(b) of the GNU Affero General Public License version 3,
 * these Appropriate Legal Notices must retain the display of the "Powered by SugarCRM"
 * logo and "Supercharged by SuiteCRM" logo and "Reinvented by MintHCM" logo.
 * If the display of the logos is not reasonably feasible for technical reasons, the
 * Appropriate Legal Notices must display the words "Powered by SugarCRM" and
 * "Supercharged by SuiteCRM" and "Reinvented by MintHCM".
 */

namespace MintHCM\Lib\Search\ElasticSearch;

use MintHCM\Utils\ConstantsLoader;

class ElasticMapperParser
{
    private static $instance = null;
    private $map_config;

    const FILE_PATH = 'lib/Search/ElasticSearch/defaultParams';

    private function __construct()
    {
        $this->map_config = [];
    }

    public static function getInstance(): self
    {
        if (null === self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function getDefaultMapParams($module): ?array
    {
        if (empty($this->map_config)) {
            if (file_exists(self::FILE_PATH . '.json')) {
                $this->map_config = json_decode(file_get_contents(self::FILE_PATH . '.json'), true);
                if (json_last_error() !== JSON_ERROR_NONE) {
                    throw new \RuntimeException('Failed to decode JSON: ' . json_last_error_msg());
                }
            } else {
                $file = realpath(__DIR__ . '/../../../../legacy/' . self::FILE_PATH . '.yml');
                $parse = new \Symfony\Component\Yaml\Parser();
                $this->map_config = $parse->parseFile($file);
            }
        }

        return $this->map_config['mappings'][$module];
    }

    public function getFieldAttributePath(string $module, string $field): ?string
    {
        $mappings = $this->getDefaultMapParams($module);

        $list_config = ConstantsLoader::getConstants('list_constants');
        if (in_array($field, $list_config['fields_without_prefix'])) {
            return $field;
        }
        $field = str_replace('.keyword', '', $field);
        if (isset($list_config['sort_mappings'][$field])) {
            $const_field = $list_config['sort_mappings'][$field];
        }
        $path_steps = $const_field ? explode('.', $const_field) : explode('.', $field);
        $path_steps = array_map(function ($n) use ($module) {
            return $module . '__' . $n;
        }, $path_steps);
        $path = $this->findFieldPath($mappings, $path_steps);

        return implode('.', array_filter($path));
    }

    private function parseFieldType(?string $type): string
    {
        switch ($type) {
            case 'text':
            case 'keyword':
                return 'keyword';
            default:
                return '';
        }
        return '';
    }

    private function findFieldPath($array, array | string $looking_for)
    {
        if (is_string($looking_for)) {
            $looking_for = [$looking_for];
        }
        $return = [];
        $current_part = $looking_for[0];
        foreach ($array as $key => $value) {
            if ('properties' === $key) {
                $return[] = $current_part;
                $return = array_merge($return, $this->findFieldPath($value, $looking_for));
            } else if ($key === $current_part && isset($value['type'])) {
                $return[] = $this->parseFieldType($value['type']);
            } else if ($key === $current_part && isset($value['properties'])) {
                array_shift($looking_for);
                $return = array_merge($return, $this->findFieldPath($value, $looking_for));
            }
        }
        return $return;
    }
}
