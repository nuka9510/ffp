<?php
  use \FFP\Interceptor\Http;
  use \FFP\Enums\Interceptor\Handle;

  Http::append(Handle::PRE, function (\FFP\App $context, \FFP\DTO\Cli\Request $request, \FFP\DTO\Cli\Response $response) {
    \FFP\Logger::debug(print_r($context, true));
    \FFP\Logger::debug(print_r($request, true));
    \FFP\Logger::debug(print_r($response, true));
  });

  Http::append(Handle::POST, function (\FFP\App $context, \FFP\DTO\Cli\Request $request, \FFP\DTO\Cli\Response $response) {
    \FFP\Logger::debug(print_r($context, true));
    \FFP\Logger::debug(print_r($request, true));
    \FFP\Logger::debug(print_r($response, true));
  });
?>