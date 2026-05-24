<?php
  namespace FFP;

  require_once(__DIR__.'/../vendor/autoload.php');
  require_once(__DIR__.'/logger.php');

  $_SERVER['DOCUMENT_ROOT'] = realpath(__DIR__.'/../');

  $__is_cli = (PHP_SAPI === 'cli');
  $__is_worker = boolval($_SERVER['FRANKENPHP_WORKER'] ?? null);

  if ($__is_cli) {
    $__argv = array();

    foreach ($_SERVER['argv'] as $vi => $v) {
      if ($vi <= 1) { continue; }

      preg_match('/^--(?P<key>[^=]+)=(?P<value>.+)$/', $v, $matches);

      $__argv[$matches['key']] = $matches['value'];
    }

    $__dotenv = \Dotenv\Dotenv::createImmutable($_SERVER['DOCUMENT_ROOT'], $__argv['env'] ?? null);
    $__dotenv->load();

    unset($__argv);
    unset($__dotenv);
  }

  $__flag = true;

  try {
    require_once(__DIR__.'/require.php');
    require_once(__DIR__.'/app.php');

    if ($__is_cli) {
      require_once(__DIR__.'/../routes/cli.php');
    } else { require_once(__DIR__.'/../routes/http.php'); }
  } catch (\Throwable $th) {
    \FFP\Logger::error($th->getMessage());

    $__flag = false;
  }

  if ($__flag) {
    $app = new \FFP\App($__is_cli, $__is_worker);

    unset($__is_cli);
    unset($__is_worker);

    try {
      $app->boot();
    } catch (\Throwable $th) {
      \FFP\Logger::error($th->getMessage());

      $__flag = false;
    }

    if ($__flag) {
      unset($__flag);

      if ($app->isWorker) {
        while (frankenphp_handle_request([$app, 'requestHandle'])) { gc_collect_cycles(); }
      } else { $app->requestHandle(); }
    }

    $app->shutdown();
  }
?>