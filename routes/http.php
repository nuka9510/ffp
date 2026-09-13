<?php
  use \FFP\Route\Http;

  Http\ROUTER->get('/', [\Controllers\Index::class, 'getIndex']);

  Http\ROUTER->get('/{id:int}', [\Controllers\Index::class, 'getIndex']);
?>