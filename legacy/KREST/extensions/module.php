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

$KRESTManager->registerExtension('module', '2.0');

$app->group('/module', function () use ($KRESTManager, $KRESTModuleHandler) {
   $this->get('/language', function ($request, $response) use ($KRESTModuleHandler) {
      $getParams = $request->getQueryParams();

      // see if we have a language passed in .. if not use the defaulöt 
      $language = $getParams['lang'];
      if ( empty($language) )
         $language = $GLOBALS['sugar_config']['default_language'];

      $modules = json_decode($getParams['modules']);
      $dynamicDomains = $KRESTModuleHandler->get_dynamic_domains($modules, $language);
      $appListStrings = return_app_list_strings_language($language);
      $appStrings = array_merge($appListStrings, $dynamicDomains);

      $responseArray = array( 'languages' => array( 'available' => $GLOBALS['sugar_config']['languages'], 'default' => $GLOBALS['sugar_config']['default_language'] ), 'mod' => $KRESTModuleHandler->get_mod_language(json_decode($getParams['modules']), $language), 'applang' => return_application_language($language), 'applist' => $appStrings );

      $responseArray['md5'] = md5(json_encode($responseArray));

      // if an md5 was sent in and matches the curent one .. no change .. do not send the language to save bandwidth
      if ( $_REQUEST['md5'] == $responseArray['md5'] ) {
         $responseArray = array( 'md5' => $_REQUEST['md5'] );
      }

      return $response->withJson($responseArray);
   });
   $this->get('/{beanName}', function ($request, $response, $args) use ($KRESTModuleHandler) {
      $searchParams = $request->getQueryParams();

      $postParams = json_decode($request->getBody(), true);
      if ( is_array($postParams) )
         $searchParams = array_merge($searchParams, $postParams);

      return $response->withJson($KRESTModuleHandler->get_bean_list($args['beanName'], $searchParams));
   });
   $this->post('/{beanName}', function ($request, $response, $args) use ($KRESTModuleHandler) {
      $requestParams = $request->getParams();
      $retArray = array();

      $items = json_decode($request->getBody(), true);

      foreach ( $items as $item ) {
         $beanId = $KRESTModuleHandler->add_bean($args['beanName'], $item['id'], array_merge($item, $requestParams));
         $item['id'] = $beanId;
         $retArray[] = $item;
      }

      return $response->withJson($retArray);
   });
   $this->group('/{beanName}', function () use ($KRESTModuleHandler) {
      $this->get('/{beanId}', function ($request, $response, $args) use ($KRESTModuleHandler) {
         $requestParams = $request->getQueryParams();
         return $response->withJson($KRESTModuleHandler->get_bean_detail($args['beanName'], $args['beanId'], $requestParams));
      });
      $this->post('/{beanId}', function ($request, $response, $args) use ($KRESTModuleHandler) {
         $thisBean = $KRESTModuleHandler->add_bean($args['beanName'], $args['beanId'], $request->getParsedBody());
         return $response->withJson($thisBean);
      });
      $this->delete('/{beanId}', function ($request, $response, $args) use ($KRESTModuleHandler) {
         return $KRESTModuleHandler->delete_bean($args['beanName'], $args['beanId']);
      });
      $this->group('/{beanId}', function () use ($KRESTModuleHandler) {
         $this->group('/noteattachment', function () use ($KRESTModuleHandler) {
            $this->get('', function ($request, $response, $args) use ($KRESTModuleHandler) {
               return $response->withJson($KRESTModuleHandler->get_bean_attachment($args['beanName'], $args['beanId']));
            });
         });
         /* $this->group('/attachment', function ($request, $response, $args) {
           $this->post('', function ($request, $response, $args) use ($app) {
           $postBody = $body = $request->getBody();
           $postParams = $request->post();
           require_once('include/SpiceAttachments/SpiceAttachments.php');
           echo SpiceAttachments::saveAttachmentHashFiles($beanName, $beanId, array_merge(json_decode($postBody, true), $postParams));
           });
           $this->get('', function ($request, $response, $args) {
           require_once('include/SpiceAttachments/SpiceAttachments.php');
           echo SpiceAttachments::getAttachmentsForBeanHashFiles($beanName, $beanId);
           });
           $this->delete('/:attachmentId', function ($request, $response, $args) use ($app) {
           require_once('include/SpiceAttachments/SpiceAttachments.php');
           echo SpiceAttachments::deleteAttachment($attachmentId);
           });
           $this->post('/ui', function ($request, $response, $args) {
           /* for fielupload over $_FILE. used by theme
           $postBody = $body = $this->request->getBody();
           $postParams = $this->request->get();
           require_once('include/SpiceAttachments/SpiceAttachments.php');
           echo SpiceAttachments::saveAttachment($beanName, $beanId, array_merge(json_decode($postBody, true), $postParams));
           });
           $this->get('/ui', function ($request, $response, $args) {
           /* for get file url for theme, not file in base64
           require_once('include/SpiceAttachments/SpiceAttachments.php');
           echo SpiceAttachments::getAttachmentsForBean($beanName, $beanId);
           });
           }); */
         $this->group('/favorite', function () use ($KRESTModuleHandler) {
            $this->get('', function($request, $response, $args) use ($KRESTModuleHandler) {
               $actionData = $KRESTModuleHandler->get_favorite($args['beanName'], $args['beanId']);
               return $response->withJson($actionData);
            });
            $this->post('', function($request, $response, $args) use ($KRESTModuleHandler) {
               $actionData = $KRESTModuleHandler->set_favorite($args['beanName'], $args['beanId']);
            });
            $this->delete('', function($request, $response, $args) use ($KRESTModuleHandler) {
               $actionData = $KRESTModuleHandler->delete_favorite($args['beanName'], $args['beanId']);
            });
         });
         /* $app->group('/note', function ($request, $response, $args) {
           $app->get('', function ($beanName, $beanId) {
           require_once('modules/SpiceThemeController/SpiceThemeController.php');
           $SpiceThemeController = new SpiceThemeController();
           echo $SpiceThemeController->getQuickNotes($beanName, $beanId);
           });
           $app->post('', function ($request, $response, $args) {
           require_once('modules/SpiceThemeController/SpiceThemeController.php');
           $postBody = $body = $app->request->getBody();
           $postParams = $app->request->get();
           $data = array_merge(json_decode($postBody, true), $postParams);
           $SpiceThemeController = new SpiceThemeController();
           echo $SpiceThemeController->saveQuickNote($beanName, $beanId, $data);
           });
           $app->post('/:noteId', function ($request, $response, $args) {
           require_once('modules/SpiceThemeController/SpiceThemeController.php');
           $postBody = $body = $app->request->getBody();
           $postParams = $app->request->get();
           $data = array_merge(json_decode($postBody, true), $postParams);
           $SpiceThemeController = new SpiceThemeController();
           echo $SpiceThemeController->editQuickNote($beanName, $beanId, $noteId, $data);
           });
           $app->delete('/:noteId', function ($request, $response, $args) {
           require_once('modules/SpiceThemeController/SpiceThemeController.php');
           $SpiceThemeController = new SpiceThemeController();
           echo $SpiceThemeController->deleteQuickNote($noteId);
           });
           });
           $app->group('/reminder', function ($request, $response, $args) {
           $app->get('', function ($beanName, $beanId) use ($app) {
           require_once('modules/SpiceThemeController/SpiceThemeController.php');
           $SpiceThemeController = new SpiceThemeController();
           echo $SpiceThemeController->getReminder();
           });
           $app->post('', function ($request, $response, $args) {
           $postBody = $body = $app->request->getBody();
           $postParams = $app->request->get();
           $data = array_merge(json_decode($postBody, true), $postParams);
           require_once('modules/SpiceThemeController/SpiceThemeController.php');
           $SpiceThemeController = new SpiceThemeController();
           echo $SpiceThemeController->setReminder($beanName, $beanId, $data);
           });
           $app->delete('', function ($request, $response, $args) {
           require_once('modules/SpiceThemeController/SpiceThemeController.php');
           $SpiceThemeController = new SpiceThemeController();
           echo $SpiceThemeController->removeReminder($beanName, $beanId);
           });
           }); */

         $this->group('/related/{linkname}', function () use ($KRESTModuleHandler) {
            $this->get('', function($request, $response, $args) use ($KRESTModuleHandler) {
               return $response->withJson($KRESTModuleHandler->get_related($args['beanName'], $args['beanId'], $args['linkname']));
            });
            $this->post('', function($request, $response, $args) use ($KRESTModuleHandler) {
               return $response->withJson($KRESTModuleHandler->add_related($args['beanName'], $args['beanId'], $args['linkname']));
            });
            $this->delete('', function($request, $response, $args) use ($KRESTModuleHandler) {
               return $response->withJson($KRESTModuleHandler->delete_related($args['beanName'], $args['beanId'], $args['linkname']));
            });
         });
         $this->post('/{beanAction}', function($request, $response, $args) use ($KRESTModuleHandler) {
            $actionData = $KRESTModuleHandler->execute_bean_action($args['beanName'], $args['beanId'], $args['beanAction'], $request->getParsedBody());
            if ( $actionData === false )
               $response->withStatus(501);
            else {
               return $response->withJson($actionData);
            }
         });
      });
   });
});
