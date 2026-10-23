<?php
/**
 * MintHCM
 *
 * Admin view metadata for Elasticsearch configuration.
 * This file defines the panels and fields rendered by the Vue AdminView
 * at /#/Administration/ElasticsearchSettings.
 *
 * Structure mirrors recordviewdefs.php so MintPanel components can be reused.
 */

$viewdefs['Administration']['ElasticsearchSettings'] = [
    'order' => ['connection', 'tools'],
    'panels' => [
        'connection' => [
            'component' => 'MintPanelAdminConfig',
            'data' => [
                'title' => 'LBL_ELASTIC_SEARCH_SETTINGS',
                'sections' => [
                    'connection' => [
                        'title' => 'LBL_ES_CONNECTION_SETTINGS',
                        'fields' => [
                            [
                                ['name' => 'host', 'type' => 'varchar', 'label' => 'LBL_ELASTIC_SEARCH_HOST', 'config_key' => 'search:ElasticSearch'],
                                ['name' => 'elasticsearch_index_prefix', 'type' => 'varchar', 'label' => 'LBL_ES_INDEX_PREFIX'],
                            ],
                            [
                                ['name' => 'user', 'type' => 'varchar', 'label' => 'LBL_ELASTIC_SEARCH_USER', 'config_key' => 'search:ElasticSearch'],
                                ['name' => 'pass', 'type' => 'varchar', 'inputType' => 'password', 'label' => 'LBL_ELASTIC_SEARCH_PASS', 'config_key' => 'search:ElasticSearch'],
                            ],
                        ],
                    ],
                ],
            ],
        ],
        'tools' => [
            'component' => 'MintPanelElasticsearchTools',
        ],
    ],
];
