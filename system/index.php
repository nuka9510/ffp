<?php
  $system_dir = new DirectoryIterator(__DIR__.'/../system');
  $config_dir = new DirectoryIterator(__DIR__.'/../config');

  foreach ($system_dir as $sdi => $sd) {
    if (
      !$sd->isDir() &&
      $sd->getExtension() === 'php' &&
      $sd->getFilename() !== 'index.php'
    ) { require_once($sd->getPathname()); }
  }

  foreach ($config_dir as $cdi => $cd) {
    if (
      !$cd->isDir() &&
      $cd->getExtension() === 'php'
    ) { require_once($cd->getPathname()); }
  }

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