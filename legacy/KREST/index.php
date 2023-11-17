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


if ( !defined('sugarEntry') ) {
   define('sugarEntry', 'SLIM');
}

require_once 'include/entryPoint.php';
require_once 'KREST/KRESTManager.php';

if ( !class_exists('\Slim\App') ) {
   require_once 'KREST/autoload.php';
}

// Mint start #60447
$GLOBALS['app_list_strings'] = return_app_list_strings_language($GLOBALS['current_language']);
// Mint end #60447

$app = new \Slim\App();
$KRESTManager = new KRESTManager($app);

$app->add(function ($req, $res, $next) use($KRESTManager) {
   $KRESTManager->requestParams = $req->getParsedBody();
   $response = $next($req, $res);
   return $response
                   ->withHeader('Access-Control-Allow-Origin', '*')
                   ->withHeader('Access-Control-Allow-Methods', '*');
});


$KRestDirHandle = opendir('./KREST/extensions');
while ( false !== ($KRestNextFile = readdir($KRestDirHandle)) ) {
   $statusInclude = 'NOP';
   if ( preg_match('/.php$/', $KRestNextFile) ) {
      $statusInclude = 'included';
      require_once('./KREST/extensions/' . $KRestNextFile);
   }
}

if ( is_dir('./custom/KREST/extensions') ) {
   $KRestDirHandle = opendir('./custom/KREST/extensions');
   if ( $KRestDirHandle ) {
      while ( false !== ($KRestNextFile = readdir($KRestDirHandle)) ) {
         if ( preg_match('/.php$/', $KRestNextFile) ) {
            require_once('./custom/KREST/extensions/' . $KRestNextFile);
         }
      }
   }
}
// check if we have extension in the local path
$KRestDirHandle = opendir('./modules');
if ( $KRestDirHandle ) {
   while ( ($KRestNextDir = readdir($KRestDirHandle)) !== false ) {
      if ( $KRestNextDir != '.' && $KRestNextDir != '..' && is_dir('./modules/' . $KRestNextDir) && file_exists('./modules/' . $KRestNextDir . '/KREST/extensions') ) {
         $KRestSubDirHandle = opendir('./modules/' . $KRestNextDir . '/KREST/extensions');
         if ( $KRestSubDirHandle ) {
            while ( false !== ($KRestNextFile = readdir($KRestSubDirHandle)) ) {
               if ( preg_match('/.php$/', $KRestNextFile) ) {
                  require_once('./modules/' . $KRestNextDir . '/KREST/extensions/' . $KRestNextFile);
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
