<?php
  use FFP\Route\Http;
  use FFP\Enums\Route\Method;
  use \FFP\Enums\Interceptor\Handle;

  Http::append(Method::GET, '/', function (\FFP\App $context, \FFP\DTO\Http\Request $request, \FFP\DTO\Http\Response $response) {
    \FFP\Logger::debug(print_r($context, true));
    \FFP\Logger::debug(print_r($request, true));
    \FFP\Logger::debug(print_r($response, true));

    $response->view('index');
  })->interceptor(Handle::PRE, function (\FFP\App $context, \FFP\DTO\Cli\Request $request, \FFP\DTO\Cli\Response $response) {
    \FFP\Logger::debug(print_r($context, true));
    \FFP\Logger::debug(print_r($request, true));
    \FFP\Logger::debug(print_r($response, true));
  })->interceptor(Handle::POST, function (\FFP\App $context, \FFP\DTO\Cli\Request $request, \FFP\DTO\Cli\Response $response) {
    \FFP\Logger::debug(print_r($context, true));
    \FFP\Logger::debug(print_r($request, true));
    \FFP\Logger::debug(print_r($response, true));
  });

  Http::append(Method::GET, '/test', [\Controllers\Index::class, 'getTest']);

  Http::append(Method::GET, '/test/{page:int}', [\Controllers\Index::class, 'getTest']);
?>