<?php
  use FFP\Route\Http;
  use FFP\Enums\Route\Method;
  use \FFP\Enums\Interceptor\Handle;

  Http::append(Method::GET, '/', [\Controllers\Index::class, 'getIndex'])
      ->interceptor(Handle::PRE, function (\FFP\App $context, \FFP\DTO\Http\Request $request, \FFP\DTO\Http\Response $response) { \FFP\Logger::debug('local interceptor pre-handle'); })
      ->interceptor(Handle::POST, function (\FFP\App $context, \FFP\DTO\Http\Request $request, \FFP\DTO\Http\Response $response) { \FFP\Logger::debug('local interceptor post-handle'); });

  Http::append(Method::GET, '/{id:int}', [\Controllers\Index::class, 'getIndex'])
      ->interceptor(Handle::PRE, function (\FFP\App $context, \FFP\DTO\Http\Request $request, \FFP\DTO\Http\Response $response) { \FFP\Logger::debug('local interceptor pre-handle'); })
      ->interceptor(Handle::POST, function (\FFP\App $context, \FFP\DTO\Http\Request $request, \FFP\DTO\Http\Response $response) { \FFP\Logger::debug('local interceptor post-handle'); });
?>