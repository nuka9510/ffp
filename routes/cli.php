<?php
  use FFP\Route\Cli;
  use \FFP\Enums\Interceptor\Handle;

  Cli::append('/cli-test', function (\FFP\App $context, \FFP\DTO\Cli\Request $request, \FFP\DTO\Cli\Response $response) {
    \FFP\Logger::debug(print_r($context, true));
    \FFP\Logger::debug(print_r($request, true));
    \FFP\Logger::debug(print_r($response, true));
  })->interceptor(Handle::PRE, function (\FFP\App $context, \FFP\DTO\Cli\Request $request, \FFP\DTO\Cli\Response $response) {
    \FFP\Logger::debug(print_r($context, true));
    \FFP\Logger::debug(print_r($request, true));
    \FFP\Logger::debug(print_r($response, true));
  })->interceptor(Handle::POST, function (\FFP\App $context, \FFP\DTO\Cli\Request $request, \FFP\DTO\Cli\Response $response) {
    \FFP\Logger::debug(print_r($context, true));
    \FFP\Logger::debug(print_r($request, true));
    \FFP\Logger::debug(print_r($response, true));
  });
?>