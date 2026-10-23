<?php

/**
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
 */

namespace MintHCM\Api\Controllers\Admin;

use MintHCM\Utils\LanguageUtils;
use MintHCM\Utils\LegacyConnector;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Psr7\Response;

/**
 * Admin controller for language pack management.
 *
 * GET  /admin/languages — returns all installed languages with enabled/default/locked flags
 * PUT  /admin/languages — saves disabled language keys and the default system language to config_override.php
 */
class LanguagesAdminController
{
    // -------------------------------------------------------------------------
    // GET /admin/languages
    // -------------------------------------------------------------------------

    public function getLanguages(Request $request, Response $response, array $args): Response
    {
        global $sugar_config;

        $allLanguages = $sugar_config['languages'] ?? [];
        $defaultLang  = $sugar_config['default_language'] ?? 'en_us';
        $disabledArr  = LanguageUtils::parseDisabledLanguages($sugar_config['disabled_languages'] ?? '');

        $result = [];
        foreach ($allLanguages as $key => $label) {
            $result[] = [
                'key'     => $key,
                'label'   => $label,
                'enabled' => !in_array($key, $disabledArr, true),
                'default' => $key === $defaultLang,
                'locked'  => $key === $defaultLang,
            ];
        }

        return $this->jsonResponse($response, $result);
    }

    // -------------------------------------------------------------------------
    // PUT /admin/languages
    // -------------------------------------------------------------------------

    public function saveLanguages(Request $request, Response $response, array $args): Response
    {
        global $sugar_config;

        $body           = $request->getParsedBody() ?? [];
        $allLanguages   = $sugar_config['languages'] ?? [];
        $disabled       = array_values(array_intersect($body['disabled'] ?? [], array_keys($allLanguages)));
        $currentDefault = $sugar_config['default_language'] ?? 'en_us';
        $defaultLang    = $body['default_language'] ?? $currentDefault;

        if (!isset($allLanguages[$defaultLang])) {
            return $this->jsonResponse(
                $response->withStatus(422),
                ['error' => 'Unknown default system language']
            );
        }

        if (in_array($defaultLang, $disabled, true)) {
            return $this->jsonResponse(
                $response->withStatus(422),
                ['error' => 'Cannot disable the default system language']
            );
        }

        $lc = new LegacyConnector('Configurator', 'modules/Configurator/Configurator.php');
        $config = $lc->config;
        $config['disabled_languages'] = implode(',', $disabled);
        $config['default_language'] = $defaultLang;
        $lc->config = $config;
        $lc->saveConfig();

        if ($defaultLang !== $currentDefault) {
            // Cached label sets are keyed by language only, but their fallback chain depends on default_language
            sugar_cache_reset_full();
        }

        return $this->jsonResponse($response, ['status' => 'success']);
    }

    // -------------------------------------------------------------------------
    // Helpers
    // -------------------------------------------------------------------------

    private function jsonResponse(Response $response, array $data): Response
    {
        $response = $response->withHeader('Content-type', 'application/json');
        $response->getBody()->write(json_encode($data));
        return $response;
    }
}
