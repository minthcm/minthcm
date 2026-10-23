<?php
/**
 * MintHCM
 *
 * Admin view metadata for System Settings (Configurator) configuration.
 * This file defines the panels and fields rendered by the Vue AdminView
 * at /#/Administration/ConfiguratorSettings.
 *
 * Structure mirrors recordviewdefs.php so MintPanel components can be reused.
 *
 * Supported field keys:
 *   - `config_key` (colon-separated) — nested path in $sugar_config.
 *     Example: 'logger:file' → $sugar_config['logger']['file']['fieldName']
 *   - `inputType` => 'password' — value masked on GET, skipped on PUT when still masked.
 *   - `options` (string) — app_list_strings key for enum fields.
 *   - `additional_info` (string) — language label key shown as tooltip icon (mdi-information-outline).
 *   - `upload_handler` (string) — Configurator method name called on file upload (image fields).
 *   - `static_value` (string) — fixed value, never read from or written to $sugar_config.
 */

$viewdefs['Administration']['ConfiguratorSettings'] = [
    'order' => ['config'],
    'panels' => [
        'config' => [
            'component' => 'MintPanelAdminConfig',
            'data' => [
                'title' => 'LBL_CONFIGURE_SETTINGS_TITLE',
                'label_module' => 'Configurator',
                'sections' => [
                    'system' => [
                        'title' => 'DEFAULT_SYSTEM_SETTINGS',
                        'fields' => [
                            [
                                ['name' => 'system_name', 'type' => 'varchar', 'label' => 'SYSTEM_NAME', 'additional_info' => 'SYSTEM_NAME_HELP', 'settings_key' => 'system:name'],
                                [],
                            ],
                            [
                                ['name' => 'company_logo', 'type' => 'image', 'label' => 'NEW_LOGO', 'additional_info' => 'NEW_LOGO_HELP_NO_SPACE', 'upload_handler' => 'saveCompanyLogo'],
                                [],
                            ],
                            [
                                ['name' => 'dashlet_auto_refresh_min', 'type' => 'enum', 'label' => 'LBL_MIN_AUTO_REFRESH_INTERVAL', 'options' => 'dashlet_auto_refresh_options_admin', 'additional_info' => 'LBL_MIN_AUTO_REFRESH_INTERVAL_HELP'],
                                [],
                            ],
                            [
                                ['name' => 'list_max_entries_per_page', 'type' => 'int', 'label' => 'LIST_ENTRIES_PER_LISTVIEW'],
                                ['name' => 'list_max_entries_per_subpanel', 'type' => 'int', 'label' => 'LIST_ENTRIES_PER_SUBPANEL'],
                            ],
                            [
                                ['name' => 'max_dashlets_homepage', 'type' => 'int', 'label' => 'MAX_DASHLETS'],
                                [],
                            ],
                            [
                                ['name' => 'lock_homepage', 'type' => 'bool', 'label' => 'LOCK_HOMEPAGE'],
                                ['name' => 'lock_subpanels', 'type' => 'bool', 'label' => 'LOCK_SUBPANELS'],
                            ],
                            [
                                ['name' => 'hide_subpanels', 'type' => 'bool', 'label' => 'LBL_HIDE_SUBPANELS', 'additional_info' => 'LBL_HIDE_SUBPANELS'],
                                ['name' => 'subpanel_count_method', 'type' => 'enum', 'label' => 'LBL_SUBPANEL_COUNT_METHOD', 'options' => 'subpanel_count_methods'],
                            ],
                            [
                                ['name' => 'enable_action_menu', 'type' => 'bool', 'label' => 'LBL_ENABLE_ACTION_MENU', 'additional_info' => 'LBL_ENABLE_ACTION_MENU_DESC'],
                                ['name' => 'default_module_favicon', 'type' => 'bool', 'label' => 'LBL_MODULE_FAVICON', 'additional_info' => 'LBL_MODULE_FAVICON_HELP'],
                            ],
                            [
                                ['name' => 'configure_ajax_ui', 'type' => 'url', 'label' => 'LBL_CONFIG_AJAX', 'additional_info' => 'LBL_CONFIG_AJAX_DESC', 'static_value' => './legacy/index.php?module=Administration&action=ConfigureAjaxUI'],
                                [],
                            ],
                        ],
                    ],
                    'proxy' => [
                        'title' => 'LBL_PROXY_TITLE',
                        'fields' => [
                            [
                                ['name' => 'proxy_on', 'type' => 'bool', 'label' => 'LBL_PROXY_ON', 'additional_info' => 'LBL_PROXY_ON_DESC', 'settings_key' => 'proxy:on'],
                                [],
                            ],
                            [
                                ['name' => 'proxy_host', 'type' => 'varchar', 'label' => 'LBL_PROXY_HOST', 'settings_key' => 'proxy:host', 'visible_when' => [['field' => 'proxy_on', 'value' => true]]],
                                ['name' => 'proxy_port', 'type' => 'varchar', 'label' => 'LBL_PROXY_PORT', 'settings_key' => 'proxy:port', 'visible_when' => [['field' => 'proxy_on', 'value' => true]]],
                            ],
                            [
                                ['name' => 'proxy_auth', 'type' => 'bool', 'label' => 'LBL_PROXY_AUTH', 'settings_key' => 'proxy:auth', 'visible_when' => [['field' => 'proxy_on', 'value' => true]]],
                                [],
                            ],
                            [
                                ['name' => 'proxy_username', 'type' => 'varchar', 'label' => 'LBL_PROXY_USERNAME', 'settings_key' => 'proxy:username', 'visible_when' => [['field' => 'proxy_on', 'value' => true], ['field' => 'proxy_auth', 'value' => true]]],
                                ['name' => 'proxy_password', 'type' => 'varchar', 'inputType' => 'password', 'label' => 'LBL_PROXY_PASSWORD', 'settings_key' => 'proxy:password', 'visible_when' => [['field' => 'proxy_on', 'value' => true], ['field' => 'proxy_auth', 'value' => true]]],
                            ],
                        ],
                    ],
                    'performance' => [
                        'title' => 'ADVANCED',
                        'fields' => [
                            [
                                ['name' => 'upload_maxsize', 'type' => 'int', 'label' => 'UPLOAD_MAX_SIZE'],
                                [],
                            ],
                            [
                                ['name' => 'calculate_response_time', 'type' => 'bool', 'label' => 'DISPLAY_RESPONSE_TIME'],
                                ['name' => 'verify_client_ip', 'type' => 'bool', 'label' => 'VERIFY_CLIENT_IP'],
                            ],
                            [
                                ['name' => 'log_memory_usage', 'type' => 'bool', 'label' => 'LOG_MEMORY_USAGE'],
                                ['name' => 'dump_slow_queries', 'type' => 'bool', 'label' => 'LOG_SLOW_QUERIES'],
                            ],
                            [
                                ['name' => 'slow_query_time_msec', 'type' => 'int', 'label' => 'SLOW_QUERY_TIME_MSEC'],
                                ['name' => 'stack_trace_errors', 'type' => 'bool', 'label' => 'STACK_TRACE_ERRORS'],
                            ],
                            [
                                ['name' => 'developerMode', 'type' => 'bool', 'label' => 'DEVELOPER_MODE'],
                                [],
                            ],
                            [
                                ['name' => 'vcal_time', 'type' => 'int', 'label' => 'LBL_VCAL_PERIOD', 'additional_info' => 'vCAL_HELP'],
                                ['name' => 'import_max_records_total_limit', 'type' => 'int', 'label' => 'LBL_IMPORT_MAX_RECORDS', 'additional_info' => 'LBL_IMPORT_MAX_RECORDS_HELP'],
                            ],
                        ],
                    ],
                    'logger' => [
                        'title' => 'LBL_LOGGER',
                        'fields' => [
                            [
                                ['name' => 'name', 'type' => 'varchar', 'label' => 'LBL_LOGGER_FILENAME', 'config_key' => 'logger:file'],
                                ['name' => 'ext', 'type' => 'varchar', 'label' => 'LBL_LOGGER_FILE_EXTENSION', 'config_key' => 'logger:file'],
                            ],
                            [
                                ['name' => 'suffix', 'type' => 'enum', 'label' => 'LBL_LOGGER_FILENAME_SUFFIX', 'options' => 'logger_file_suffix_dom', 'config_key' => 'logger:file'],
                                [],
                            ],
                            [
                                ['name' => 'maxSize', 'type' => 'varchar', 'label' => 'LBL_LOGGER_MAX_LOG_SIZE', 'config_key' => 'logger:file'],
                                ['name' => 'dateFormat', 'type' => 'varchar', 'label' => 'LBL_LOGGER_DEFAULT_DATE_FORMAT', 'config_key' => 'logger:file'],
                            ],
                            [
                                ['name' => 'maxLogs', 'type' => 'int', 'label' => 'LBL_LOGGER_MAX_LOGS', 'config_key' => 'logger:file'],
                                ['name' => 'level', 'type' => 'enum', 'label' => 'LBL_LOGGER_LOG_LEVEL', 'options' => 'logger_level_dom', 'config_key' => 'logger'],
                            ],
                            [
                                ['name' => 'stackTrace', 'type' => 'bool', 'label' => 'LBL_STACK_TRACE'],
                                [],
                            ],
                            [
                                ['name' => 'log_view', 'type' => 'url', 'label' => 'LBL_LOGVIEW', 'static_value' => './legacy/index.php?module=Configurator&action=LogView'],
                                [],
                            ],
                        ],
                    ],
                ],
            ],
        ],
    ],
];
