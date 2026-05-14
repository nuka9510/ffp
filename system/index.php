<?php
  $core_dir = new DirectoryIterator(__DIR__.'/core');

  foreach ($core_dir as $cdi => $cd) {
    if (
      !$cd->isDir() &&
      $cd->getExtension() === 'php'
    ) { require_once($cd->getPathname()); }
  }

  $app = new FPW\Core\App();

  $app->boot();

  if ($_SERVER['FRANKENPHP_WORKER']) {
    while (frankenphp_handle_request([$app, 'requestHandle'])) {
      gc_collect_cycles();
    }
  } else { $app->requestHandle(); }

  $app->shutdown();
?>