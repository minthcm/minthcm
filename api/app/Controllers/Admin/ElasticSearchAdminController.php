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

namespace MintHCM\Api\Controllers\Admin;

use Elasticsearch\ClientBuilder;
use Elasticsearch\Common\Exceptions\BadRequest400Exception;
use MintHCM\Data\BeanFactory;
use MintHCM\Utils\LegacyConnector;
use Psr\Http\Message\ServerRequestInterface as Request;
use SchedulersJob;
use Slim\Psr7\Response;
use SugarJobQueue;
use SuiteCRM\Search\ElasticSearch\ElasticSearchClientBuilder;
use SuiteCRM\Search\ElasticSearch\ElasticSearchIndexer;
use SuiteCRM\Search\SearchModules;

class ElasticSearchAdminController
{
    private const MAX_INDEX_RECORDS = 10000;

    // -------------------------------------------------------------------------
    // POST /admin/elasticsearch/test-connection
    // -------------------------------------------------------------------------

    public function testConnection(Request $request, Response $response, array $args): Response
    {
        $body = $request->getParsedBody() ?? [];
        $host = (string) ($body['host'] ?? '');
        $user = (string) ($body['user'] ?? '');
        $pass = (string) ($body['pass'] ?? '');

        // If the password is still masked, substitute the real password from config
        if ($pass === '***') {
            global $sugar_config;
            $pass = $sugar_config['search']['ElasticSearch']['pass'] ?? '';
        }

        $return = LegacyConnector::runCallable(function () use ($host, $user, $pass) {
            require_once 'lib/Search/ElasticSearch/ElasticSearchClientBuilder.php';
            require_once 'lib/Search/ElasticSearch/ElasticSearchIndexer.php';

            try {
                $config = [
                    ElasticSearchClientBuilder::sanitizeHost([
                        'host' => $host,
                        'user' => $user,
                        'pass' => $pass,
                    ]),
                ];

                $client  = ClientBuilder::create()->setHosts($config)->build();
                $indexer = new ElasticSearchIndexer($client);
                $info    = $client->info();
                $time    = $indexer->ping();

                return [
                    'status' => 'success',
                    'ping'   => $time,
                    'info'   => $info,
                ];
            } catch (BadRequest400Exception $exception) {
                $error = json_decode($exception->getMessage());
                return [
                    'status' => 'fail',
                    'error'  => $error->error->reason ?? $exception->getMessage(),
                ];
            } catch (\Exception $exception) {
                return [
                    'status'    => 'fail',
                    'error'     => $exception->getMessage(),
                    'errorType' => get_class($exception),
                ];
            } catch (\Throwable $throwable) {
                return [
                    'status'    => 'fail',
                    'error'     => $throwable->getMessage(),
                    'errorType' => get_class($throwable),
                ];
            }
        });

        return $this->jsonResponse($response, $return);
    }

    // -------------------------------------------------------------------------
    // POST /admin/elasticsearch/schedule-index
    // -------------------------------------------------------------------------

    public function scheduleIndex(Request $request, Response $response, array $args): Response
    {
        LegacyConnector::runCallable(function () {
            require_once 'modules/SchedulersJobs/SchedulersJob.php';
            require_once 'include/SugarQueue/SugarJobQueue.php';

            $job                   = new SchedulersJob();
            $job->name             = 'Full index requested by an administrator';
            $job->target           = 'function::runElasticSearchIndexerScheduler';
            $job->data             = json_encode(['partial' => false]);
            $job->assigned_user_id = 1;

            $queue = new SugarJobQueue();
            $queue->submitJob($job);
        });

        return $this->jsonResponse($response, ['status' => 'success']);
    }

    // -------------------------------------------------------------------------
    // GET /admin/elasticsearch/modules
    // -------------------------------------------------------------------------

    public function getModules(Request $request, Response $response, array $args): Response
    {
        $result = LegacyConnector::runCallable(function () {
            global $db;

            $modules_data = SearchModules::getAllModules();
            $all_modules  = array_merge($modules_data['enabled'], $modules_data['disabled']);

            // Phase 1: collect module metadata (no DB queries — bean instantiation only)
            $module_meta = [];
            foreach ($all_modules as $item) {
                $module = $item['module'];
                $seed   = BeanFactory::newBean($module);

                if (empty($seed) || empty($seed->table_name) || !isset($seed->field_defs['date_indexed'])) {
                    continue;
                }

                $es_list_view_file = "modules/{$module}/metadata/eslistviewdefs.php";
                $list_view_file    = "modules/{$module}/metadata/listviewdefs.php";

                $module_meta[$module] = [
                    'label'            => $item['label'],
                    'enabled'          => in_array($item, $modules_data['enabled'], true),
                    'table'            => $seed->table_name,
                    'has_es_list_view' => file_exists($es_list_view_file) || file_exists("custom/{$es_list_view_file}"),
                    'has_list_view'    => file_exists($list_view_file)    || file_exists("custom/{$list_view_file}"),
                    'total'            => 0,
                    'unindexed'        => 0,
                ];
            }

            // Phase 2: single UNION ALL replaces ~2N individual COUNT queries
            if (!empty($module_meta)) {
                $union_parts = [];
                foreach ($module_meta as $module => $meta) {
                    $table     = $meta['table'];
                    $moduleSql = $db->quote($module);
                    $union_parts[] = "SELECT '$moduleSql' AS module, COUNT(*) AS cnt, 0 AS is_unindexed FROM $table WHERE deleted = 0";
                    $union_parts[] = "SELECT '$moduleSql' AS module, COUNT(*) AS cnt, 1 AS is_unindexed FROM $table WHERE deleted = 0 AND (date_indexed IS NULL OR date_indexed < date_modified)";
                }

                $res = $db->query(implode(' UNION ALL ', $union_parts));
                while ($row = $db->fetchByAssoc($res)) {
                    $m = $row['module'];
                    if (!isset($module_meta[$m])) {
                        continue;
                    }
                    if ($row['is_unindexed']) {
                        $module_meta[$m]['unindexed'] = (int) $row['cnt'];
                    } else {
                        $module_meta[$m]['total'] = (int) $row['cnt'];
                    }
                }
            }

            // Phase 3: reconcile 'unindexed' against the real document count in Elasticsearch.
            // The date_indexed heuristic above only reflects what the DB *thinks* was indexed —
            // if documents are removed directly in ES (bypassing the app), date_indexed stays set
            // and the heuristic would keep reporting those records as indexed. Falls back to the
            // heuristic above (left untouched) if ES can't be reached.
            $es_counts = $this->getElasticSearchDocCounts(array_keys($module_meta));
            if ($es_counts !== null) {
                foreach ($module_meta as $module => &$meta) {
                    $meta['unindexed'] = max(0, $meta['total'] - ($es_counts[$module] ?? 0));
                }
                unset($meta);
            }

            // Phase 4: format result
            return array_values(array_map(
                fn($module, $meta) => [
                    'module'           => $module,
                    'label'            => $meta['label'],
                    'enabled'          => $meta['enabled'],
                    'total'            => $meta['total'],
                    'unindexed'        => $meta['unindexed'],
                    'has_es_list_view' => $meta['has_es_list_view'],
                    'has_list_view'    => $meta['has_list_view'],
                ],
                array_keys($module_meta),
                $module_meta
            ));
        });

        return $this->jsonResponse($response, $result);
    }

    // -------------------------------------------------------------------------
    // POST /admin/elasticsearch/index-module/{module}
    // -------------------------------------------------------------------------

    public function indexModule(Request $request, Response $response, array $args): Response
    {
        $module = $args['module'] ?? '';

        try {
            $indexed = LegacyConnector::runCallable(function () use ($module) {
                require_once 'lib/Search/ElasticSearch/ElasticSearchIndexer.php';

                $error = $this->validateModule($module);
                if ($error !== null) {
                    throw new InvalidModuleException($error);
                }

                return $this->indexModuleNow($module);
            });
        } catch (InvalidModuleException $e) {
            return $this->jsonResponse($response->withStatus(400), $e->getErrorData());
        } catch (\Throwable $e) {
            return $this->jsonResponse(
                $response->withStatus(500),
                ['error' => 'Indexing failed', 'message' => $e->getMessage()]
            );
        }

        return $this->jsonResponse($response, ['indexed' => $indexed]);
    }

    /**
     * Reindexes all unindexed/stale records of $module against Elasticsearch.
     * Must be called within legacy context (uses globals and legacy classes).
     */
    private function indexModuleNow(string $module): int
    {
        require_once 'lib/Search/ElasticSearch/ElasticSearchIndexer.php';

        global $sugar_config, $db;
        $es_config    = $sugar_config['search']['ElasticSearch'] ?? [];
        $original_max = $sugar_config['search']['ElasticSearch']['max_batch_size'] ?? null;
        $sugar_config['search']['ElasticSearch']['max_batch_size'] = self::MAX_INDEX_RECORDS;

        $indexed = 0;
        try {
            $host_config = ElasticSearchClientBuilder::sanitizeHost([
                'host' => $es_config['host'] ?? 'localhost',
                'user' => $es_config['user'] ?? '',
                'pass' => $es_config['pass'] ?? '',
            ]);
            $client  = ClientBuilder::create()->setHosts([$host_config])->build();
            $indexer = new ElasticSearchIndexer($client);

            $seed = BeanFactory::newBean($module);
            if (!empty($seed->table_name) && isset($seed->field_defs['date_indexed'])) {
                $table = $seed->table_name;
                $res   = $db->query(
                    "SELECT COUNT(*) AS cnt FROM $table WHERE deleted = 0 AND (date_indexed IS NULL OR date_indexed < date_modified)"
                );
                if ($row = $db->fetchByAssoc($res)) {
                    $indexed = min((int) $row['cnt'], self::MAX_INDEX_RECORDS);
                }
            }

            $indexer->indexModule($module);
        } finally {
            if ($original_max !== null) {
                $sugar_config['search']['ElasticSearch']['max_batch_size'] = $original_max;
            } else {
                unset($sugar_config['search']['ElasticSearch']['max_batch_size']);
            }
        }

        return $indexed;
    }

    /**
     * Returns the real document count per module straight from Elasticsearch, using a single
     * indices stats request (one round-trip regardless of module count). Each module is indexed
     * into its own index (`{prefix}_{module}`), so no per-document discriminator field is needed.
     *
     * Returns null (instead of an empty array) if Elasticsearch could not be reached at all, so
     * callers can distinguish "ES unreachable" from "no documents in any index".
     *
     * Must be called within legacy context (uses globals and legacy classes).
     *
     * @return array<string, int>|null module => real document count
     */
    private function getElasticSearchDocCounts(array $modules): ?array
    {
        if (empty($modules)) {
            return [];
        }

        require_once 'lib/Search/ElasticSearch/ElasticSearchIndexer.php';

        try {
            global $sugar_config;
            $es_config   = $sugar_config['search']['ElasticSearch'] ?? [];
            $host_config = ElasticSearchClientBuilder::sanitizeHost([
                'host' => $es_config['host'] ?? 'localhost',
                'user' => $es_config['user'] ?? '',
                'pass' => $es_config['pass'] ?? '',
            ]);
            $client = ClientBuilder::create()->setHosts([$host_config])->build();

            $prefix = ElasticSearchIndexer::getIndexPrefix();

            // A wildcard pattern is used instead of an explicit index list: an explicit list
            // 404s the *entire* request the moment a single named index doesn't exist yet (e.g.
            // a module that was never indexed), and the PHP client's indices.stats endpoint
            // doesn't whitelist ignore_unavailable to work around that. A wildcard simply
            // returns whatever currently exists — no error for zero or partial matches.
            //
            // A terms aggregation on `_index` is used instead of indices.stats: stats' docs.count
            // is a Lucene segment-level count that includes nested child documents (this indexer
            // maps some relate fields as `nested`), so it overcounts real records — a user with
            // nested role/group data can inflate its "document count" far past 1. An aggregation
            // over search hits only counts root documents, matching what _count/_search return.
            $stats = $client->search([
                'index' => $prefix . '_*',
                'body'  => [
                    'size' => 0,
                    'aggs' => [
                        'by_index' => [
                            'terms' => [
                                'field' => '_index',
                                'size'  => 10000,
                            ],
                        ],
                    ],
                ],
            ]);

            $lower_to_module = array_combine(array_map('strtolower', $modules), $modules);
            $index_prefix    = $prefix . '_';

            $counts = [];
            foreach ($stats['aggregations']['by_index']['buckets'] ?? [] as $bucket) {
                $index = $bucket['key'];
                if (strpos($index, $index_prefix) !== 0) {
                    continue;
                }
                $module_key = substr($index, strlen($index_prefix));
                if (isset($lower_to_module[$module_key])) {
                    $module = $lower_to_module[$module_key];
                    $counts[$module] = (int) $bucket['doc_count'];
                }
            }

            return $counts;
        } catch (\Throwable $e) {
            return null;
        }
    }

    // -------------------------------------------------------------------------
    // PUT /admin/elasticsearch/module-toggle/{module}
    // -------------------------------------------------------------------------

    public function toggleModule(Request $request, Response $response, array $args): Response
    {
        $module             = $args['module'] ?? '';
        $body               = $request->getParsedBody() ?? [];
        $enabled            = (bool) ($body['enabled'] ?? false);
        $enable_legacy_view = isset($body['enable_legacy_view']) ? (bool) $body['enable_legacy_view'] : null;

        try {
            $indexResult = LegacyConnector::runCallable(function () use ($module, $enabled) {
                $error = $this->validateModule($module);
                if ($error !== null) {
                    throw new InvalidModuleException($error);
                }

                $display               = SearchModules::getUnifiedSearchModulesDisplay();
                $display[$module]['visible'] = $enabled;
                $enabled_modules       = array_keys(array_filter($display, fn($d) => $d['visible']));
                $_REQUEST['enabled_modules'] = implode(',', $enabled_modules);
                SearchModules::saveGlobalSearchSettings();
                unset($_REQUEST['enabled_modules']);

                // Re-enabling a module only makes it visible to search settings — records
                // added/changed while it was disabled were never indexed, so index the
                // current state immediately instead of requiring a manual Repair.
                if (!$enabled) {
                    return ['indexed' => null, 'error' => null];
                }
                try {
                    return ['indexed' => $this->indexModuleNow($module), 'error' => null];
                } catch (\Throwable $e) {
                    return ['indexed' => null, 'error' => $e->getMessage()];
                }
            });
        } catch (InvalidModuleException $e) {
            return $this->jsonResponse($response->withStatus(400), $e->getErrorData());
        }

        if ($enable_legacy_view !== null) {
            $this->updateLegacyViewsSetting($module, $enable_legacy_view);
        }

        return $this->jsonResponse($response, [
            'status'     => 'success',
            'module'     => $module,
            'enabled'    => $enabled,
            'indexed'    => $indexResult['indexed'],
            'indexError' => $indexResult['error'],
        ]);
    }

    // -------------------------------------------------------------------------
    // Helpers
    // -------------------------------------------------------------------------

    /**
     * Returns an error array if $module is not in the known indexable list, null otherwise.
     * Must be called within legacy context (SearchModules requires it).
     *
     * @return array{error: string, message: string}|null
     */
    private function validateModule(string $module): ?array
    {
        $modules_data = SearchModules::getAllModules();
        $all_modules  = array_merge($modules_data['enabled'], $modules_data['disabled']);
        $known        = array_column($all_modules, 'module');

        if (!in_array($module, $known, true)) {
            return [
                'error'   => 'Invalid module',
                'message' => "Module '$module' is not a known indexable module",
            ];
        }

        return null;
    }

    private function updateLegacyViewsSetting(string $module, bool $enable_legacy_view): void
    {
        $legacy_views_file = __DIR__ . '/../../../constants/legacy_views.php';
        if (!file_exists($legacy_views_file)) {
            return;
        }

        $legacy_views = include $legacy_views_file;
        if (!is_array($legacy_views)) {
            return;
        }

        if (!isset($legacy_views[$module])) {
            $legacy_views[$module] = [];
        }
        $legacy_views[$module]['list'] = $enable_legacy_view;

        $date    = date('Y-m-d H:i:s');
        $content = "<?php\n\n// Auto-generated by MintHCM on $date. Do not edit manually.\n\nreturn " . var_export($legacy_views, true) . ";\n";
        file_put_contents($legacy_views_file, $content);
    }

    private function jsonResponse(Response $response, array $data): Response
    {
        $response = $response->withHeader('Content-type', 'application/json');
        $response->getBody()->write(json_encode($data));
        return $response;
    }
}

