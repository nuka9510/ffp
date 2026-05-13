<?php
  require_once(__DIR__.'/../config/config.php');
  require_once(__DIR__."/../{$_SERVER['FRANKENPHP_INDEX']}");

  $app = (new ReflectionClass($_SERVER['FRANKENPHP_APPLICATION']))->newInstance();

  $app->boot();

  if ($_SERVER['FRANKENPHP_WORKER']) {
    while (frankenphp_handle_request([$app, 'requestHandle'])) {
      gc_collect_cycles();
    }
  } else { $app->requestHandle(); }

  $app->shutdown();
?>