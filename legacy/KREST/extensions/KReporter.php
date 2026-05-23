<?php

// require_once('modules/KReports/KReport.php');
require_once('modules/KReports/KReport.php');
require_once('modules/KReports/KReportVisualizationManager.php');
require_once('modules/KReports/KReportPresentationManager.php');
require_once('modules/KReports/KReportRESTHandler.php');

$KReportRestHandler = new KReporterRESTHandler();

$app->group('/KReporter', function () use ($KRESTManager, $KReportRestHandler) {

   $this->group('/core', function () use ($KRESTManager, $KReportRestHandler) {
      $this->get('/whereinitialize', function ($request, $response) use ($KRESTManager) {
         $restHandler = new KReporterRESTHandler();
         return $response->withJson($restHandler->whereInitialize());
      });
      $this->get('/whereoperators', function ($request, $response) use ($KRESTManager) {
         $restHandler = new KReporterRESTHandler();
         $getParams = $request->getQueryParams();
         return $response->withJson($restHandler->getWhereOperators($getParams['path'], $getParams['grouping'], $getParams['designer']));
      });
      $this->get('/enumoptions', function ($request, $response) use ($KRESTManager) {
         $restHandler = new KReporterRESTHandler();
         $getParams = $request->getQueryParams();
         return $response->withJson($restHandler->getEnumOptions($getParams['path'], $getParams['grouping'], json_decode(html_entity_decode($getParams['operators']), true)));
      });
      $this->get('/nodes', function ($request, $response) use ($KRESTManager) {
         $restHandler = new KReporterRESTHandler();
         $getParams = $request->getQueryParams();
         return $response->withJson($restHandler->getNodes($getParams['nodeid']));
      });
      $this->get('/fields', function ($request, $response) use ($KRESTManager) {
         $restHandler = new KReporterRESTHandler();
         $getParams = $request->getQueryParams();
         return $response->withJson($restHandler->getFields($getParams['nodeid']));
      });
      $this->get('/buckets', function ($request, $response) use ($KRESTManager) {
         $restHandler = new KReporterRESTHandler();
         $getParams = $request->getQueryParams();
         return $response->withJson($restHandler->getBuckets());
      });
      $this->get('/modulefields', function ($request, $response) use ($KRESTManager) {
         $restHandler = new KReporterRESTHandler();
         $getParams = $request->getQueryParams();
         return $response->withJson($restHandler->getModuleFields($getParams['module']));
      });
      $this->get('/wherefunctions', function ($request, $response) use ($KRESTManager) {
         $restHandler = new KReporterRESTHandler();
         return $response->withJson($restHandler->getWhereFunctions());
      });
      $this->get('/autocompletevalues', function ($request, $response) use ($KRESTManager) {
         $restHandler = new KReporterRESTHandler();
         $getParams = $request->getQueryParams();
         return $response->withJson($restHandler->geAutoCompletevalues($getParams['path'], $getParams['query'], $getParams['start'], $getParams['limit']));
      });
      $this->get('/layouts', function ($request, $response) use ($KRESTManager) {
         $restHandler = new KReporterRESTHandler();
         return $response->withJson($restHandler->getLayouts());
      });

      $this->get('/vizcolors', function ($request, $response) use ($KRESTManager) {
         $restHandler = new KReporterRESTHandler();
         return $response->withJson($restHandler->getVizColors());
      });
      $this->post('/savelayout', function ($request, $response) use ($KReportRestHandler) {
         $postBody = json_decode($request->getBody(), true);
         return $response->withJson($KReportRestHandler->saveStandardLayout($postBody['record'], $postBody['layout']));
      });
      $this->get('/config', function ($request, $response) use ($KRESTManager) {
         $restHandler = new KReporterRESTHandler();
         return $response->withJson($restHandler->getConfig());
      });
      $this->get('/labels', function ($request, $response) use ($KRESTManager) {
         $restHandler = new KReporterRESTHandler();
         return $response->withJson($restHandler->getLabels());
      });
   });

   $this->group('/user', function () use ($KRESTManager, $KReportRestHandler) {
      $this->get('/datetimeformat', function ($request, $response) use ($KRESTManager) {
         $restHandler = new KReporterRESTHandler();
         return $response->withJson($restHandler->get_user_datetime_format());
      });
      $this->get('/userprefs', function ($request, $response) use ($KRESTManager) {
         $restHandler = new KReporterRESTHandler();
         return $response->withJson($restHandler->get_user_prefs());
      });
      $this->get('/getlist', function ($request, $response) use ($KRESTManager) {
         $restHandler = new KReporterRESTHandler();
         $params = $request->getQueryParams();
         return $response->withJson($restHandler->get_users_list($params));
      });
   });



   $this->group('/plugins', function () use ($KReportRestHandler) {
      $this->get('', function ($request, $response) use ($KReportRestHandler) {
         $pluginManager = new KReportPluginManager();

         $params = $request->getParams();

         $pluginData = $pluginManager->getPlugins($params['report']);

         $addDataArray = array();
         if ( $params['addData'] ) {
            $addData = json_decode($params['addData'], true);
            foreach ( $addData as $addDataEntry ) {
               switch ( $addDataEntry ) {
                  case 'currencies':
                     $addDataArray[$addDataEntry] = $KReportRestHandler->getCurrencies();
                     break;
                  case 'sysinfo':
                     $addDataArray[$addDataEntry] = $KReportRestHandler->getSysinfo();
                     break;
               }
            }
         }

         $pluginData['addData'] = $addDataArray;

         return $response->withJson($pluginData);
      });

      $this->group('/action/{plugin}/{action}', function () {
         $this->post('', function ($request, $response, $args) {
            $pluginManager = new KReportPluginManager();
            $params = $request->getParams();

            if ( !$params )
               $params = array();

            //Only return if not null! In case of empty we get a null line in exports (csv, xlsx) and excel can't open file properly
            //return $response->withJson($pluginManager->processPluginAction($plugin, 'action_' . $action, array_merge($getParams,$postBody)));
            $resultsPluginAction = $pluginManager->processPluginAction($args['plugin'], 'action_' . $args['action'], $params);
            if ( !empty($resultsPluginAction) )
               return $response->withJson($resultsPluginAction);
         });
         $this->get('', function ($request, $response, $args) {
            $pluginManager = new KReportPluginManager();
            $getParams = $request->getQueryParams();
            return $response->withJson($pluginManager->processPluginAction($args['plugin'], 'action_' . $args['action'], $getParams));
         });
      });
   });

   $this->get('/{reportId}', function ($request, $response, $args) use ($KRESTManager) {
      $thisReport = BeanFactory::getBean('KReports', $args['reportId']);
      $vizData = json_decode(html_entity_decode($thisReport->visualization_params, ENT_QUOTES, 'UTF-8'), true);
      $pluginManager = new KReportPluginManager();
      $vizObject = $pluginManager->getVisualizationObject('googlecharts');
      return $response->withJson($vizObject->getItem('', $thisReport, $vizData[1]['googlecharts']));
   });
   $this->group('/{reportId}', function () use ($KRESTManager, $KReportRestHandler) {

      $this->group('/snapshot', function () use ($KRESTManager) {
         $this->get('', function ($request, $response, $args) use ($KRESTManager) {
            $thisReport = new KReport();
            $thisReport->retrieve($args['reportId']);
            $requestParams = $request->getQueryParams();
            return $response->withJson($thisReport->getSnapshots($requestParams['withoutActual']));
         });
         $this->group('/{snapshotId}', function () use ($KRESTManager) {
            $this->delete('', function ($request, $response, $args) use ($KRESTManager) {
               $thisReport = new KReport();
               $thisReport->retrieve($args['reportId']);
               $thisReport->deleteSnapshot($args['snapshotId']);
            });
         });
      });

      $this->group('/savedfilter', function () use ($KRESTManager, $KReportRestHandler) {
         $this->get('', function ($request, $response, $args) use ($KRESTManager, $KReportRestHandler) {
            file_put_contents("sugarcrm.log", "savedfilter\n", FILE_APPEND);
            $requestParams = $request->getQueryParams();
            $requestParams['reportid'] = $args['reportId'];
            return $response->withJson($KReportRestHandler->getSavedFilters($requestParams));
         });
         $this->group('/assigneduserid/{assigneduserid}', function () use ($KRESTManager, $KReportRestHandler) {
            $this->get('', function ($request, $response, $args) use ($KRESTManager, $KReportRestHandler) {
               $requestParams = $request->getQueryParams();
               $requestParams = array( 'reportid' => $args['reportId'], 'assigneduserid' => $args['assigneduserid'], 'context' => $requestParams['context'] );
               return $response->withJson($KReportRestHandler->getSavedFilters($requestParams));
            });
         });
         $this->group('/{savedfiltertId}', function () use ($KRESTManager, $KReportRestHandler) {
            $this->delete('', function ($request, $response, $args) use ($KRESTManager, $KReportRestHandler) {
               $KReportRestHandler->deleteSavedFilter($args['savedfiltertId']);
            });
         });
      });

      $this->get('/layout', function ($request, $response, $args) use ($KRESTManager) {
         $layout = array();
         $thisReport = BeanFactory::getBean('KReports', $args['reportId']);
         $vizData = json_decode(html_entity_decode($thisReport->visualization_params, ENT_QUOTES, 'UTF-8'), true);
         return $response->withJson($vizData);
         $vizManager = new KReportVisualizationManager();

         for ( $i = 0; $i < count($vizManager->layouts[$vizData['layout']]['items']); $i++ ) {
            $layout[] = array(
               "top" => $vizManager->layouts[$vizData['layout']]['items'][$i]['top'],
               "left" => $vizManager->layouts[$vizData['layout']]['items'][$i]['left'],
               "height" => $vizManager->layouts[$vizData['layout']]['items'][$i]['height'],
               "width" => $vizManager->layouts[$vizData['layout']]['items'][$i]['width']
            );
         }
         // return $response->withJson($layout);
      });
//        $this->get('/visualization', function ($reportId) use ($KReportRestHandler) {
//            return $response->withJson($KReportRestHandler->getVisualization($reportId, $this->request->get()));
//        });
//        $this->get('/presentation', function ($reportId) use ($KReportRestHandler) {
//            return $response->withJson($KReportRestHandler->getPresentation($reportId, $this->request->get()));
//        });

      $this->group('/visualization', function () use ($KRESTManager, $KReportRestHandler) {
         $this->get('', function ($request, $response, $args) use ($KReportRestHandler) {
            return $response->withJson($KReportRestHandler->getVisualization($args['reportId'], $request->getQueryParams()));
         });
         $this->group('/dynamicoptions/{dynamicoptions}', function () use ($KRESTManager, $KReportRestHandler) {
            $this->get('', function ($request, $response, $args) use ($KRESTManager, $KReportRestHandler) {

               $requestParams = $request->getQueryParams();
               $requestParams['dynamicoptions'] = $args['dynamicoptions'];
               return $response->withJson($KReportRestHandler->getVisualization($args['reportId'], $requestParams));
            });
         });
      });

      $this->group('/presentation', function () use ($KRESTManager, $KReportRestHandler) {
         $this->get('', function ($request, $response, $args) use ($KReportRestHandler) {
            return $response->withJson($KReportRestHandler->getPresentation($args['reportId'], $request->getParams()));
         });
         $this->group('/dynamicoptions/{dynamicoptions}', function () use ($KRESTManager, $KReportRestHandler) {
            $this->get('', function ($request, $response, $args) use ($KRESTManager, $KReportRestHandler) {
               $requestParams = $request->getQueryParams();
               $requestParams['dynamicoptions'] = $args['dynamicoptions'];
               return $response->withJson($KReportRestHandler->getPresentation($args['reportId'], $requestParams));
            });
         });
      });
//        $this->group('/dynamicoptions', function ($reportId, $dynamicoptions) use ($KRESTManager, $KReportRestHandler) {
//            $this->group('/:dynamicoptions', function ($reportId, $dynamicoptions) use ($KRESTManager, $KReportRestHandler) {
//                $this->get('', function ($reportId, $dynamicoptions) use ($KRESTManager, $KReportRestHandler) {
//                    $requestParams = $this->request->get();
//                    $requestParams['dynamicoptions'] = $dynamicoptions;
//                    return $response->withJson($KReportRestHandler->getPresentation($reportId, $requestParams));
//                });
//            });
//        });
   });


   $this->group('/bucketmanager', function () use ($KRESTManager, $KReportRestHandler) {
      $this->get('/enumfields', function ($request, $response) use ($KRESTManager) {
         $restHandler = new KReporterRESTHandler();
         $getParams = $request->getQueryParams();
         return $response->withJson($restHandler->getEnumfields($getParams['modulename']));
      });
      $this->get('/enumfieldvalues', function ($request, $response) use ($KRESTManager) {
         $restHandler = new KReporterRESTHandler();
         $getParams = $request->getQueryParams();
         return $response->withJson($restHandler->getEnumfieldvalues($getParams));
      });
      $this->get('/groupings', function ($request, $response) use ($KRESTManager) {
         $restHandler = new KReporterRESTHandler();
         $getParams = $request->getQueryParams();
         return $response->withJson($restHandler->getGroupings());
      });

      $this->post('/savenewgrouping', function ($request, $response) use ($KReportRestHandler) {
         $postBody = json_decode($request->getBody(), true);
         return $response->withJson($KReportRestHandler->saveNewGrouping($postBody));
      });
      $this->post('/updateGrouping', function ($request, $response) use ($KReportRestHandler) {
         $postBody = json_decode($request->getBody(), true);
         return $response->withJson($KReportRestHandler->updateGrouping($postBody));
      });
      $this->post('/deleteGrouping', function ($request, $response) use ($KReportRestHandler) {
         $postBody = json_decode($request->getBody(), true);
         return $response->withJson($KReportRestHandler->deleteGrouping($postBody));
      });
   });

   $this->group('/dlistmanager', function () use ($KRESTManager, $KReportRestHandler) {
      $this->get('/dlists', function ($request, $response) use ($KRESTManager) {
         $restHandler = new KReporterRESTHandler();
         $getParams = $request->getQueryParams();
         return $response->withJson($restHandler->getDLists());
      });
      $this->get('/users', function ($request, $response) use ($KRESTManager) {
         $restHandler = new KReporterRESTHandler();
         $getParams = $request->getQueryParams();
         return $response->withJson($restHandler->getUsers($getParams));
      });
      $this->get('/contacts', function ($request, $response) use ($KRESTManager) {
         $restHandler = new KReporterRESTHandler();
         $getParams = $request->getQueryParams();
         return $response->withJson($restHandler->getContacts($getParams));
      });
      $this->get('/kreports', function ($request, $response) use ($KRESTManager) {
         $restHandler = new KReporterRESTHandler();
         $getParams = $request->getQueryParams();
         return $response->withJson($restHandler->getKReports($getParams));
      });

      $this->post('/savenewdlist', function ($request, $response) use ($KReportRestHandler) {
         $postBody = json_decode($request->getBody(), true);
         return $response->withJson($KReportRestHandler->saveNewDList($postBody));
      });
      $this->post('/updatedlist', function ($request, $response) use ($KReportRestHandler) {
         $postBody = json_decode($request->getBody(), true);
         return $response->withJson($KReportRestHandler->updateDList($postBody));
      });
      $this->post('/deletedlist', function ($request, $response) use ($KReportRestHandler) {
         $postBody = json_decode($request->getBody(), true);
         return $response->withJson($KReportRestHandler->deleteDList($postBody));
      });
   });
});
