<?php
  use \FFP\Interceptor\Cli;
  use \FFP\Enums\Interceptor\Handle;

  Cli::append(Handle::PRE, function (\FFP\App $context, \FFP\DTO\Cli\Request $request, \FFP\DTO\Cli\Response $response) {
    \FFP\Logger::debug('global interceptor pre-handle');
  });

  Cli::append(Handle::POST, function (\FFP\App $context, \FFP\DTO\Cli\Request $request, \FFP\DTO\Cli\Response $response) {
    \FFP\Logger::debug('global interceptor post-handle');
  });
?>