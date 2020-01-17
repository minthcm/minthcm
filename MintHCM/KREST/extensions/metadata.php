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

require_once('KREST/handlers/module.php');

$KRESTModuleHandler = new KRESTModuleHandler($app);

$KRESTManager->registerExtension('metadata', '1.0');

$app->group('/metadata', function () use ($KRESTModuleHandler) {
   $this->get('/modules', function($request, $response) use ($KRESTModuleHandler) {
      return $response->withJson($KRESTModuleHandler->get_modules());
   });
   $this->post('/beandefs', function ($request, $response) {
      $KRESTManager = new KRESTManager($this);
      $postBody = $body = $request->getBody();
      $beanArray = json_decode($postBody, true);
      return $response->withJson($KRESTManager->get_beandefs_multiple($beanArray));
   });
   $this->group('/{beanName}', function () {
      $this->get('/vardefs', function ($request, $response, $args) {
         $KRESTManager = new KRESTManager($this);
         return $response->withJson($KRESTManager->get_bean_vardefs($args['beanName']));
      });
      $this->get('/beandefs', function ($request, $response, $args) {
         $KRESTManager = new KRESTManager($this);
         return $response->withJson($KRESTManager->get_beandefs($args['beanName']));
      });
      $this->get('/language', function ($request, $response, $args) {
         $KRESTManager = new KRESTManager($this);
         return $response->withJson($KRESTManager->get_bean_language($args['beanName']));
      });
   });
});
