<?php

/**
 * Mint #60447 - app_list_strings is needed for SecurityGroup::getSecurityModules method (called while inheriting groups)
 */
/*
 * This File is part of KREST is a Restful service extension for SugarCRM
 *
 * Copyright (C) 2015 AAC SERVICES K.S., DOSTOJEVSKÉHO RAD 5, 811 09 BRATISLAVA, SLOVAKIA
 *
 * you can contat us at info@spicecrm.io
 *
 * This program is free software; you can redistribute it and/or modify it under the terms of the GNU General Public License as published by the Free Software Foundation; either version 2 of the License, or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along with this program; if not, write to the Free Software Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA 02111-1307 USA
 */

// set error reporting to E_ERROR
ini_set('error_reporting', 'E_ERROR');
ini_set("display_errors", "off");
chdir(dirname(__FILE__) . '/../');

if (!defined('sugarEntry')) {
    define('sugarEntry', 'SLIM');
}

require_once 'include/entryPoint.php';
require_once 'KREST/KRESTManager.php';
// Mint start #193555
require_once 'KREST/KRESTSqlGuard.php';
// Mint end #193555

if (!class_exists('\Slim\App')) {
    require_once 'KREST/autoload.php';
}

// Mint start #60447
$GLOBALS['app_list_strings'] = return_app_list_strings_language($GLOBALS['current_language']);
// Mint end #60447

$app = new \Slim\App();
$KRESTManager = new KRESTManager($app);

// Mint start #193555
/**
 * Defence in depth: records SQL-injection probing against KREST endpoints.
 *
 * Detection and logging only - deliberately NOT blocking. KREST drives the
 * KReports designer and viewer, whose saved filters and sortings legitimately
 * carry punctuation-heavy values; rejecting on a character pattern here would
 * break them. The actual protection is KRESTSqlGuard at each sink. This exists
 * so that probing against a handler that has not yet adopted the guard is
 * visible in the log rather than silent.
 */
$app->add(function ($req, $res, $next) {
    $probePattern = '/(\bunion\b.{0,40}\bselect\b|\bsleep\s*\(|\bbenchmark\s*\(|\/\*|--\s|\bor\b\s+[\'"]?1[\'"]?\s*=\s*[\'"]?1)/i';

    $suspicious = array();
    foreach ((array)$req->getQueryParams() as $name => $value) {
        if (is_scalar($value) && preg_match($probePattern, (string)$value)) {
            $suspicious[] = $name;
        }
    }

    if (!empty($suspicious)) {
        $userId = isset($GLOBALS['current_user']->id) ? $GLOBALS['current_user']->id : 'anonymous';
        $GLOBALS['log']->security('Possible SQL injection probe against KREST in parameter(s) '
            . implode(', ', $suspicious)
            . ' [' . $req->getMethod() . ' ' . $req->getUri()->getPath()
            . '] [user ' . $userId . ']');
    }

    return $next($req, $res);
});
// Mint end #193555

// Mint start #193555
/**
 * Turns a rejected request parameter into HTTP 400 instead of letting it
 * surface as a generic 500.
 *
 * Added BEFORE the CORS middleware on purpose: Slim runs the most recently
 * added middleware first, so the CORS layer ends up wrapping this one and still
 * decorates the 400 response on the way out.
 *
 * Only KRESTInvalidInputException is handled here; every other throwable keeps
 * going to Slim's default error handler untouched.
 */
$app->add(function ($req, $res, $next) {
    try {
        return $next($req, $res);
    } catch (KRESTInvalidInputException $e) {
        $GLOBALS['log']->security('KREST rejected a request parameter: ' . $e->getMessage()
            . ' [' . $req->getMethod() . ' ' . (string)$req->getUri() . ']');

        return $res->withStatus(400)->withJson(array(
            'error' => array(
                'code' => 'invalid_parameter',
                'message' => $e->getMessage(),
            ),
        ));
    }
});
// Mint end #193555

$app->add(function ($req, $res, $next) use ($KRESTManager) {
    $KRESTManager->requestParams = $req->getParsedBody();
    $response = $next($req, $res);
    return $response
        ->withHeader('Access-Control-Allow-Origin', '*')
        ->withHeader('Access-Control-Allow-Methods', '*');
});

$KRestDirHandle = opendir('./KREST/extensions');
while (false !== ($KRestNextFile = readdir($KRestDirHandle))) {
    $statusInclude = 'NOP';
    if (preg_match('/.php$/', $KRestNextFile)) {
        $statusInclude = 'included';
        require_once './KREST/extensions/' . $KRestNextFile;
    }
}

if (is_dir('./custom/KREST/extensions')) {
    $KRestDirHandle = opendir('./custom/KREST/extensions');
    if ($KRestDirHandle) {
        while (false !== ($KRestNextFile = readdir($KRestDirHandle))) {
            if (preg_match('/.php$/', $KRestNextFile)) {
                require_once './custom/KREST/extensions/' . $KRestNextFile;
            }
        }
    }
}
// check if we have extension in the local path
$KRestDirHandle = opendir('./modules');
if ($KRestDirHandle) {
    while (($KRestNextDir = readdir($KRestDirHandle)) !== false) {
        if ('.' != $KRestNextDir && '..' != $KRestNextDir && is_dir('./modules/' . $KRestNextDir) && file_exists('./modules/' . $KRestNextDir . '/KREST/extensions')) {
            $KRestSubDirHandle = opendir('./modules/' . $KRestNextDir . '/KREST/extensions');
            if ($KRestSubDirHandle) {
                while (false !== ($KRestNextFile = readdir($KRestSubDirHandle))) {
                    if (preg_match('/.php$/', $KRestNextFile)) {
                        require_once './modules/' . $KRestNextDir . '/KREST/extensions/' . $KRestNextFile;
                    }
                }
            }
        }
    }
}

// authenticate
$KRESTManager->authenticate();

// run the request
$app->run();

// cleanup
$KRESTManager->cleanup();
