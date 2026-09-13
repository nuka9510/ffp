<?php
  use \FFP\Route\Cli;

  Cli\ROUTER->get('/', function () { \FFP\Logger::debug('cli route index'); });
?>