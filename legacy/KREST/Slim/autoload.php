<?php

spl_autoload_register(function($class_name) {
   static $class_map = array(
      '\\Slim\\App' => '/App.php',
      '\\Slim\\CallableResolver' => '/CallableResolver.php',
      '\\Slim\\CallableResolverAwareTrait' => '/CallableResolverAwareTrait.php',
      '\\Slim\\Collection' => '/Collection.php',
      '\\Slim\\Container' => '/Container.php',
      '\\Slim\\DefaultServicesProvider' => '/DefaultServicesProvider.php',
      '\\Slim\\DeferredCallable' => '/DeferredCallable.php',
      '\\Slim\\MiddlewareAwareTrait' => '/MiddlewareAwareTrait.php',
      '\\Slim\\Routable' => '/Routable.php',
      '\\Slim\\Route' => '/Route.php',
      '\\Slim\\RouteGroup' => '/RouteGroup.php',
      '\\Slim\\Router' => '/Router.php',
      '\\Slim\\Exception\\ContainerException' => '/Exception/ContainerException.php',
      '\\Slim\\Exception\\ContainerValueNotFoundException' => '/Exception/ContainerValueNotFoundException.php',
      '\\Slim\\Exception\\InvalidMethodException' => '/Exception/InvalidMethodException.php',
      '\\Slim\\Exception\\MethodNotAllowedException' => '/Exception/MethodNotAllowedException.php',
      '\\Slim\\Exception\\NotFoundException' => '/Exception/NotFoundException.php',
      '\\Slim\\Exception\\SlimException' => '/Exception/SlimException.php',
      '\\Slim\\Handlers\\Strategies\\RequestResponse' => '/Handlers/Strategies/RequestResponse.php',
      '\\Slim\\Handlers\\Strategies\\RequestResponseArgs' => '/Handlers/Strategies/RequestResponseArgs.php',
      '\\Slim\\Handlers\\AbstractError' => '/Handlers/AbstractError.php',
      '\\Slim\\Handlers\\AbstractHandler' => '/Handlers/AbstractHandler.php',
      '\\Slim\\Handlers\\Error' => '/Handlers/Error.php',
      '\\Slim\\Handlers\\NotAllowed' => '/Handlers/NotAllowed.php',
      '\\Slim\\Handlers\\NotFound' => '/Handlers/NotFound.php',
      '\\Slim\\Handlers\\PhpError' => '/Handlers/PhpError.php',
      '\\Slim\\Http\\Body' => '/Http/Body.php',
      '\\Slim\\Http\\Cookies' => '/Http/Cookies.php',
      '\\Slim\\Http\\Environment' => '/Http/Environment.php',
      '\\Slim\\Http\\Headers' => '/Http/Headers.php',
      '\\Slim\\Http\\Message' => '/Http/Message.php',
      '\\Slim\\Http\\Request' => '/Http/Request.php',
      '\\Slim\\Http\\RequestBody' => '/Http/RequestBody.php',
      '\\Slim\\Http\\Response' => '/Http/Response.php',
      '\\Slim\\Http\\Stream' => '/Http/Stream.php',
      '\\Slim\\Http\\UploadedFile' => '/Http/UploadedFile.php',
      '\\Slim\\Http\\Uri' => '/Http/Uri.php',
      '\\Slim\\Interfaces\\Http\\CookiesInterface' => '/Interfaces/Http/CookiesInterface.php',
      '\\Slim\\Interfaces\\Http\\EnvironmentInterface' => '/Interfaces/Http/EnvironmentInterface.php',
      '\\Slim\\Interfaces\\Http\\HeadersInterface' => '/Interfaces/Http/HeadersInterface.php',
      '\\Slim\\Interfaces\\CallableResolverInterface' => '/Interfaces/CallableResolverInterface.php',
      '\\Slim\\Interfaces\\CollectionInterface' => '/Interfaces/CollectionInterface.php',
      '\\Slim\\Interfaces\\InvocationStrategyInterface' => '/Interfaces/InvocationStrategyInterface.php',
      '\\Slim\\Interfaces\\RouteGroupInterface' => '/Interfaces/RouteGroupInterface.php',
      '\\Slim\\Interfaces\\RouteInterface' => '/Interfaces/RouteInterface.php',
      '\\Slim\\Interfaces\\RouterInterface' => '/Interfaces/RouterInterface.php',
   );

   if ( !empty($class_map[$class_name]) ) {
      $path = __DIR__ . $class_map[$class_name];
      require_once($path);
   }
}, false);
