<?php
  use FFP\Route\Http as Http;
  use FFP\Enums\Route\Method;

  Http::append(Method::GET, '/', function (\FFP\App $context, \FFP\DTO\Http\Request $request, \FFP\DTO\Http\Response $response) {
    \FFP\Logger::debug(print_r($context, true));
    \FFP\Logger::debug(print_r($request, true));
    \FFP\Logger::debug(print_r($response, true));

    $response->view('index');
  });

  Http::append(Method::GET, '/test', [\Controllers\Index::class, 'getTest']);

  Http::append(Method::GET, '/test/{page:int}', [\Controllers\Index::class, 'getTest']);
?>