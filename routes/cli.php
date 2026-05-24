<?php
  use FFP\Route\Cli as Cli;

  Cli::append('/cli-test', function (\FFP\App $context, \FFP\DTO\Cli\Request $request, \FFP\DTO\Cli\Response $response) {
    print_r($context);
    print_r($request);
    print_r($response);
  });
?>