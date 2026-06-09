<?php
  use \FFP\Interceptor\Cli;
  use \FFP\Enums\Interceptor\Handle;

  Cli::append(Handle::PRE, function (\FFP\App $context, \FFP\DTO\Cli\Request $request, \FFP\DTO\Cli\Response $response) {
    \FFP\Logger::debug(print_r($context, true));
    \FFP\Logger::debug(print_r($request, true));
    \FFP\Logger::debug(print_r($response, true));
  });

  Cli::append(Handle::POST, function (\FFP\App $context, \FFP\DTO\Cli\Request $request, \FFP\DTO\Cli\Response $response) {
    \FFP\Logger::debug(print_r($context, true));
    \FFP\Logger::debug(print_r($request, true));
    \FFP\Logger::debug(print_r($response, true));
  });
?>