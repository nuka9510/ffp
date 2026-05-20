<?php
  use FFP\Core\Route as Route;
  use FFP\Enums\Route\Method;
  use FFP\Logger;

  Route::append(Method::GET, '/', function (\FFP\App $context, \FFP\DTO\Request $request, \FFP\DTO\Response $response) {
    Logger::debug(print_r($context, true));
    Logger::debug(print_r($request, true));
    Logger::debug(print_r($response, true));

    $response->view('index');
  });

  Route::append(Method::GET, '/test/{page:int}', [\Controllers\Index::class, 'getTest']);
?>