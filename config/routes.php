<?php
  use FFP\Core\Route as Route;
  use FFP\Enums\Route\Method;

  Route::append(Method::GET, '/', function (\FFP\App $context, \FFP\DTO\Request $request, \FFP\DTO\Response $response) {
    \FFP\Logger::debug(print_r($context, true));
    \FFP\Logger::debug(print_r($request, true));
    \FFP\Logger::debug(print_r($response, true));

    $response->view('index');
  });

  Route::append(Method::GET, '/test/{page:int}', [\Controllers\Index::class, 'getTest']);
?>