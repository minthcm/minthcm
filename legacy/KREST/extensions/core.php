<?php

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

$KRESTManager->excludeFromAuthentication('/');
$KRESTManager->excludeFromAuthentication('/sysinfo');

$KRESTManager->registerExtension('core', '2.0');

$app->get('/', function ($request, $response) use ($KRESTManager) {
   return $response->withJson(array(
              'version' => '2.0',
              'extensions' => $KRESTManager->extensions
   ));
});

$app->get('/sysinfo', function ($request, $response) use ($KRESTManager) {
   return $response->withJson(array(
              'version' => '2.0',
              'extensions' => $KRESTManager->extensions
   ));
});

$app->get('/validatesession', function ($request, $response) {
   $sessionData = $request->getQueryParams();
   $KRESTManager = new KRESTManager($this);
   return $response->withJson($KRESTManager->validate_session($sessionData['session_id']));
});
