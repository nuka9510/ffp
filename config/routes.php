<?php
  use FPW\Core\Route as Route;
  use FPW\Enums\Route\Method;
  use FPW\Logger;

  Route::append(Method::GET, '/', function ($context) {
    Logger::debug(print_r($context, true));

    phpinfo();
  });

  Route::append(Method::GET, '/test', function ($context) {
    Logger::debug(print_r($context, true));

    phpinfo();
  });
?>