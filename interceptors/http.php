<?php
  use \FFP\Interceptor\Http;
  use \FFP\Enums\Interceptor\Handle;

  Http::append(Handle::PRE, function (\FFP\App $context, \FFP\DTO\Http\Request $request, \FFP\DTO\Http\Response $response) {
    \FFP\Logger::debug('global interceptor pre-handle');
  });

  Http::append(Handle::POST, function (\FFP\App $context, \FFP\DTO\Http\Request $request, \FFP\DTO\Http\Response $response) {
    \FFP\Logger::debug('global interceptor post-handle');
  });
?>