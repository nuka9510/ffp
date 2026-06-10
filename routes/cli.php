<?php
  use FFP\Route\Cli;
  use \FFP\Enums\Interceptor\Handle;

  Cli::append('/', function (\FFP\App $context, \FFP\DTO\Cli\Request $request, \FFP\DTO\Cli\Response $response) { \FFP\Logger::debug('cli route index'); })
      ->interceptor(Handle::PRE, function (\FFP\App $context, \FFP\DTO\Cli\Request $request, \FFP\DTO\Cli\Response $response) { \FFP\Logger::debug('local interceptor pre-handle'); })
      ->interceptor(Handle::POST, function (\FFP\App $context, \FFP\DTO\Cli\Request $request, \FFP\DTO\Cli\Response $response) { \FFP\Logger::debug('local interceptor post-handle'); });
?>