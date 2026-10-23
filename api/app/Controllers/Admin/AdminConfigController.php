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

use MintHCM\Data\BeanFactory;
use MintHCM\Utils\LegacyConnector;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Psr7\Response;
use Throwable;

/**
 * Generic admin config controller.
 *
 * Reads and writes $sugar_config values driven by metadata files located in
 * legacy/modules/Administration/metadata/admins/{viewName}.php.
 *
 * Each field in the metadata may carry:
 *   - `config_key` (string, colon-separated) — nested path inside $sugar_config.
 *     Example: 'search:ElasticSearch' → $sugar_config['search']['ElasticSearch']['fieldName']
 *   - `inputType` = 'password' — the value is masked on GET, and skipped on PUT when still masked.
 *
 * If a field has no `config_key`, its value lives at $sugar_config['fieldName'] (top-level).
 */
class AdminConfigController
{
    private const MASKED_PASSWORD = '***';

    // -------------------------------------------------------------------------
    // GET /admin/config/{viewName}
    // -------------------------------------------------------------------------

    public function getConfig(Request $request, Response $response, array $args): Response
    {
        $viewName = $args['viewName'] ?? '';
        $fieldDefs = $this->getFieldDefs($viewName);

        global $sugar_config;

        // Some fields (e.g. system_name, proxy_*) are stored in the DB config
        // table and are not present in $sugar_config. Load them via the
        // Administration bean as a fallback.
        $focus = BeanFactory::getBean('Administration');
        $focus->retrieveSettings();
        $adminSettings = $focus->settings ?? [];

        $data = [];
        foreach ($fieldDefs as $def) {
            $name = $def['name'];

            if (($def['type'] ?? '') === 'image') {
                $data[$name] = $this->resolveImageUrl($def);
                continue;
            }

            $configKey = $def['config_key'] ?? null;
            $value = $this->resolveConfigValue($sugar_config, $name, $configKey);

            // Fall back to DB-stored Administration settings when not in $sugar_config
            if ($value === null && $configKey === null && isset($adminSettings[$name])) {
                $value = $adminSettings[$name];
            }

            if (($def['inputType'] ?? '') === 'password') {
                $data[$name] = !empty($value) ? self::MASKED_PASSWORD : '';
            } else {
                $data[$name] = $value ?? '';
            }
        }

        return $this->jsonResponse($response, $data);
    }

    // -------------------------------------------------------------------------
    // PUT /admin/config/{viewName}
    // -------------------------------------------------------------------------

    public function saveConfig(Request $request, Response $response, array $args): Response
    {
        $viewName  = $args['viewName'] ?? '';
        $body      = $request->getParsedBody() ?? [];
        $fields    = $body['fields'] ?? [];
        $fieldDefs = $this->getFieldDefs($viewName);

        // LegacyConnector loads the class in legacy context (with proper try/finally cwd management)
        $lc = new LegacyConnector('Configurator', 'modules/Configurator/Configurator.php');

        $config = $lc->config;

        // Collect fields that must be saved via Administration::saveSetting (DB config table)
        $adminSettingsToSave = [];

        foreach ($fieldDefs as $def) {
            $name = $def['name'];
            if (!array_key_exists($name, $fields)) {
                continue;
            }

            $value = $this->sanitizeValue($fields[$name], $def);

            // Skip masked passwords — keep the existing value in config
            if (($def['inputType'] ?? '') === 'password' && $value === self::MASKED_PASSWORD) {
                continue;
            }

            $settingsKey = $def['settings_key'] ?? null;
            if ($settingsKey !== null) {
                // This field is stored in the DB config table, not in $sugar_config
                $adminSettingsToSave[$settingsKey] = $value;
                continue;
            }

            $configKey = $def['config_key'] ?? null;
            $this->setConfigValue($config, $name, $value, $configKey);
        }
        $lc->config = $config;
        $lc->saveConfig();

        // Save DB-stored fields via Administration::saveSetting
        if (!empty($adminSettingsToSave)) {
            $focus = BeanFactory::getBean('Administration');
            foreach ($adminSettingsToSave as $key => $value) {
                [$category, $settingName] = explode(':', $key, 2);
                $focus->saveSetting($category, $settingName, $value);
            }
        }

        return $this->jsonResponse($response, ['status' => 'success']);
    }

    // -------------------------------------------------------------------------
    // POST /admin/config/{viewName}/image/{fieldName}
    // -------------------------------------------------------------------------

    public function uploadImage(Request $request, Response $response, array $args): Response
    {
        $viewName  = $args['viewName'] ?? '';
        $fieldName = $args['fieldName'] ?? '';

        $fieldDef = $this->findFieldDef($viewName, $fieldName);
        if ($fieldDef === null || ($fieldDef['type'] ?? '') !== 'image' || empty($fieldDef['upload_handler'])) {
            $response = $response->withStatus(400);
            return $this->jsonResponse($response, ['error' => 'Field is not a valid image upload field']);
        }

        $uploadedFiles = $request->getUploadedFiles();
        $uploadedFile  = $uploadedFiles['file'] ?? null;
        if ($uploadedFile === null || $uploadedFile->getSize() === 0) {
            $response = $response->withStatus(400);
            return $this->jsonResponse($response, ['error' => 'No file uploaded']);
        }

        $clientExt = strtolower(pathinfo($uploadedFile->getClientFilename() ?? '', PATHINFO_EXTENSION));
        // verify_uploaded_image() checks the file extension against a limited allowlist that
        // only includes 'png' and 'tmp' by default. Map jpeg/jpg → png so the validation passes;
        // the image is always saved as company_logo.png regardless of the source format.
        $allowedExts = ['jpg' => 'png', 'jpeg' => 'png', 'png' => 'png', 'gif' => 'gif'];
        $tmpExt = $allowedExts[$clientExt] ?? 'png';
        $tmpPath = sys_get_temp_dir() . '/' . uniqid('admin_img_', true) . '.' . $tmpExt;
        try {
            $uploadedFile->moveTo($tmpPath);

            $handler = $fieldDef['upload_handler'];
            $lc      = new LegacyConnector('Configurator', 'modules/Configurator/Configurator.php');
            $result  = $lc->{$handler}($tmpPath);

            if ($result === false) {
                $response = $response->withStatus(422);
                return $this->jsonResponse($response, ['error' => 'Image validation failed — file must be a valid .jpg or .png']);
            }
        } catch (Throwable $e) {
            $response = $response->withStatus(500);
            return $this->jsonResponse($response, ['error' => 'Image upload failed. Please check the file format and try again.']);
        } finally {
            if (file_exists($tmpPath)) {
                unlink($tmpPath);
            }
        }

        return $this->jsonResponse($response, ['status' => 'success']);
    }

    // -------------------------------------------------------------------------
    // Helpers
    // -------------------------------------------------------------------------

    /**
     * Load flat list of all field definitions for the given view from the admins/ metadata directory.
     *
     * @return array<int, array<string, mixed>>
     */
    private function getFieldDefs(string $viewName): array
    {
        $admins_dir = __DIR__ . '/../../../../legacy/modules/Administration/metadata/admins';
        if (!is_dir($admins_dir)) {
            return [];
        }

        foreach (scandir($admins_dir) ?: [] as $file) {
            if (!str_ends_with($file, '.php')) {
                continue;
            }
            $viewdefs = [];
            include $admins_dir . '/' . $file;
            if (!empty($viewdefs['Administration'][$viewName])) {
                return $this->extractFieldDefs($viewdefs['Administration'][$viewName]);
            }
        }

        return [];
    }

    /**
     * Flatten all section → row → field definitions from a view meta array.
     * Skips fields with `static_value` — those are display-only and not stored in $sugar_config.
     *
     * @return array<int, array<string, mixed>>
     */
    private function extractFieldDefs(array $viewMeta): array
    {
        $defs = [];
        foreach ($viewMeta['panels'] ?? [] as $panel) {
            foreach ($panel['data']['sections'] ?? [] as $section) {
                foreach ($section['fields'] ?? [] as $row) {
                    foreach ($row as $fieldDef) {
                        if (empty($fieldDef['name'])) {
                            continue;
                        }
                        if (!empty($fieldDef['static_value'])) {
                            continue;
                        }
                        $defs[] = $fieldDef;
                    }
                }
            }
        }
        return $defs;
    }

    /**
     * Resolve the current server-side URL for an image field.
     * Returns a path relative to the web root (e.g. "legacy/custom/SuiteP/images/company_logo.png")
     * that the Vue frontend can use as <img src>.
     *
     * @param array<string, mixed> $def
     */
    private function resolveImageUrl(array $def): string
    {
        if (($def['upload_handler'] ?? '') !== 'saveCompanyLogo') {
            return '';
        }

        return LegacyConnector::runCallable(function () {
            // saveCompanyLogo() saves to custom/themes/default/images/company_logo.png,
            // so the glob needs two wildcard levels: custom/*/*/images/
            $customMatches = glob('custom/*/*/images/company_logo.png') ?: [];
            if (!empty($customMatches)) {
                return 'legacy/' . $customMatches[0];
            }
            $defaultMatches = glob('themes/*/images/company_logo.png') ?: [];
            return !empty($defaultMatches) ? 'legacy/' . $defaultMatches[0] : '';
        });
    }

    /**
     * Find a single field definition by name from the given view metadata.
     *
     * @return array<string, mixed>|null
     */
    private function findFieldDef(string $viewName, string $fieldName): ?array
    {
        foreach ($this->getFieldDefs($viewName) as $def) {
            if (($def['name'] ?? '') === $fieldName) {
                return $def;
            }
        }
        return null;
    }

    /**
     * Read a single config value, optionally nested via a colon-separated config_key.
     *
     * @param array<string, mixed> $config
     */
    private function resolveConfigValue(array $config, string $fieldName, ?string $configKey): mixed
    {
        if ($configKey !== null) {
            $keys = explode(':', $configKey);
            $current = $config;
            foreach ($keys as $key) {
                if (!isset($current[$key])) {
                    return null;
                }
                $current = $current[$key];
            }
            return $current[$fieldName] ?? null;
        }
        return $config[$fieldName] ?? null;
    }

    /**
     * Strip HTML tags and enforce max length from the field definition.
     *
     * @param array<string, mixed> $def
     */
    private function sanitizeValue(mixed $value, array $def): string
    {
        $str    = strip_tags((string) $value);
        $maxLen = isset($def['len']) ? (int) $def['len'] : 0;
        if ($maxLen > 0 && mb_strlen($str) > $maxLen) {
            $str = mb_substr($str, 0, $maxLen);
        }
        return $str;
    }

    /**
     * Write a single config value, optionally nested via a colon-separated config_key.
     *
     * @param array<string, mixed> $config
     */
    private function setConfigValue(array &$config, string $fieldName, string $value, ?string $configKey): void
    {
        if ($configKey !== null) {
            $keys = explode(':', $configKey);
            $current = &$config;
            foreach ($keys as $key) {
                if (!isset($current[$key]) || !is_array($current[$key])) {
                    $current[$key] = [];
                }
                $current = &$current[$key];
            }
            $current[$fieldName] = $value;
        } else {
            $config[$fieldName] = $value;
        }
    }

    private function jsonResponse(Response $response, array $data): Response
    {
        $response = $response->withHeader('Content-type', 'application/json');
        $response->getBody()->write(json_encode($data));
        return $response;
    }

}
