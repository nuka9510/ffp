<?php
  namespace FPW\Core;

  $models_dir = new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator(__DIR__.'/../../models'));
  $controllers_dir = new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator(__DIR__.'/../../controllers'));
  $config_dir = new \DirectoryIterator(__DIR__.'/../../config');

  foreach ($models_dir as $mdi => $md) {
    if (
      $md->isFile() &&
      $md->getExtension() === 'php'
    ) { require_once($md->getPathname()); }
  }

  foreach ($controllers_dir as $cdi => $cd) {
    if (
      $cd->isFile() &&
      $cd->getExtension() === 'php'
    ) { require_once($cd->getPathname()); }
  }

  foreach ($config_dir as $cdi => $cd) {
    if (
      !$cd->isDir() &&
      $cd->getExtension() === 'php'
    ) { require_once($cd->getPathname()); }
  }

  class App {
    public function __construct() {}

    public function boot() {
      frankenphp_log('boot frankenPHP project - '.($_SERVER['APP_SCHEME'] ?? 'http://').($_SERVER['APP_HOST'] ?? 'localhost').':'.($_SERVER['APP_PORT'] ?? 8081), FRANKENPHP_LOG_LEVEL_INFO);
    }

    public function requestHandle() {
      // Route::route($this);
      phpinfo();
    }

    public function shutdown() {
      frankenphp_log('shutdown strap frankenPHP project', FRANKENPHP_LOG_LEVEL_INFO);
    }
  }
?>