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

use MintHCM\Api\Controllers\Admin\AdminConfigController;
use MintHCM\Api\Controllers\Admin\ElasticSearchAdminController;
use MintHCM\Api\Controllers\Admin\LanguagesAdminController;
use MintHCM\Api\Middlewares\Params\ParamTypes\StringType;
use MintHCM\Api\Middlewares\Params\ParamTypes\BoolType;
use MintHCM\Api\Middlewares\Params\ParamTypes\ArrayType;
use MintHCM\Api\Middlewares\Params\ParamTypes\FileType;

$routes = [
    'admin_config_get' => [
        'method' => 'GET',
        'path' => '/admin/config/{viewName}',
        'class' => AdminConfigController::class,
        'function' => 'getConfig',
        'desc' => 'Get admin view config values for the given view (driven by admins/ metadata)',
        'options' => [
            'auth' => true,
            'admin_only' => true,
        ],
        'pathParams' => [
            'viewName' => ['type' => StringType::class, 'required' => true],
        ],
        'queryParams' => [],
        'bodyParams' => [],
    ],
    'admin_config_save' => [
        'method' => 'PUT',
        'path' => '/admin/config/{viewName}',
        'class' => AdminConfigController::class,
        'function' => 'saveConfig',
        'desc' => 'Save admin view config values for the given view (driven by admins/ metadata)',
        'options' => [
            'auth' => true,
            'admin_only' => true,
        ],
        'pathParams' => [
            'viewName' => ['type' => StringType::class, 'required' => true],
        ],
        'queryParams' => [],
        'bodyParams' => [
            'fields' => ['type' => ArrayType::class, 'required' => true],
        ],
    ],
    'admin_config_upload_image' => [
        'method' => 'POST',
        'path' => '/admin/config/{viewName}/image/{fieldName}',
        'class' => AdminConfigController::class,
        'function' => 'uploadImage',
        'desc' => 'Upload an image file for an admin config field with upload_handler (e.g. company_logo)',
        'options' => [
            'auth' => true,
            'admin_only' => true,
        ],
        'pathParams' => [
            'viewName' => ['type' => StringType::class, 'required' => true],
            'fieldName' => ['type' => StringType::class, 'required' => true],
        ],
        'queryParams' => [],
        'bodyParams' => [
            'file' => ['type' => FileType::class, 'required' => true, 'desc' => 'Image file to upload'],
        ],
    ],
    'admin_elasticsearch_test_connection' => [
        'method' => 'POST',
        'path' => '/admin/elasticsearch/test-connection',
        'class' => ElasticSearchAdminController::class,
        'function' => 'testConnection',
        'desc' => 'Test Elasticsearch connection with supplied credentials',
        'options' => [
            'auth' => true,
            'admin_only' => true,
        ],
        'pathParams' => [],
        'queryParams' => [],
        'bodyParams' => [
            'host' => ['type' => StringType::class, 'required' => true],
            'user' => ['type' => StringType::class, 'required' => false],
            'pass' => ['type' => StringType::class, 'required' => false],
        ],
    ],
    'admin_elasticsearch_schedule_index' => [
        'method' => 'POST',
        'path' => '/admin/elasticsearch/schedule-index',
        'class' => ElasticSearchAdminController::class,
        'function' => 'scheduleIndex',
        'desc' => 'Schedule a full Elasticsearch reindex job',
        'options' => [
            'auth' => true,
            'admin_only' => true,
        ],
        'pathParams' => [],
        'queryParams' => [],
        'bodyParams' => [],
    ],
    'admin_elasticsearch_get_modules' => [
        'method' => 'GET',
        'path' => '/admin/elasticsearch/modules',
        'class' => ElasticSearchAdminController::class,
        'function' => 'getModules',
        'desc' => 'Get all indexable modules with record counts',
        'options' => [
            'auth' => true,
            'admin_only' => true,
        ],
        'pathParams' => [],
        'queryParams' => [],
        'bodyParams' => [],
    ],
    'admin_elasticsearch_index_module' => [
        'method' => 'POST',
        'path' => '/admin/elasticsearch/index-module/{module}',
        'class' => ElasticSearchAdminController::class,
        'function' => 'indexModule',
        'desc' => 'Immediately index up to 10 000 records for a specific module',
        'options' => [
            'auth' => true,
            'admin_only' => true,
        ],
        'pathParams' => [
            'module' => ['type' => StringType::class, 'required' => true],
        ],
        'queryParams' => [],
        'bodyParams' => [],
    ],
    'admin_elasticsearch_toggle_module' => [
        'method' => 'PUT',
        'path' => '/admin/elasticsearch/module-toggle/{module}',
        'class' => ElasticSearchAdminController::class,
        'function' => 'toggleModule',
        'desc' => 'Toggle indexation inclusion for a module; optionally update legacy view setting',
        'options' => [
            'auth' => true,
            'admin_only' => true,
        ],
        'pathParams' => [
            'module' => ['type' => StringType::class, 'required' => true],
        ],
        'queryParams' => [],
        'bodyParams' => [
            'enabled' => ['type' => BoolType::class, 'required' => true],
            'enable_legacy_view' => ['type' => BoolType::class, 'required' => false],
        ],
    ],
    'admin_languages_get' => [
        'method' => 'GET',
        'path' => '/admin/languages',
        'class' => LanguagesAdminController::class,
        'function' => 'getLanguages',
        'desc' => 'Get all installed language packs with enabled/locked flags',
        'options' => [
            'auth' => true,
            'admin_only' => true,
        ],
        'pathParams' => [],
        'queryParams' => [],
        'bodyParams' => [],
    ],
    'admin_languages_save' => [
        'method' => 'PUT',
        'path' => '/admin/languages',
        'class' => LanguagesAdminController::class,
        'function' => 'saveLanguages',
        'desc' => 'Save disabled language keys and default system language to config_override.php',
        'options' => [
            'auth' => true,
            'admin_only' => true,
        ],
        'pathParams' => [],
        'queryParams' => [],
        'bodyParams' => [
            'disabled' => ['type' => ArrayType::class, 'required' => true],
            'default_language' => ['type' => StringType::class, 'required' => false],
        ],
    ],
];
