<?php
  namespace FFP;

  require_once(__DIR__.'/../vendor/autoload.php');
  require_once(__DIR__.'/logger.php');

  if (PHP_SAPI === 'cli') {
    # code...
  } else {
    $flag = true;

    try {
      require_once(__DIR__.'/require.php');
      require_once(__DIR__.'/app.php');
    } catch (\Throwable $th) {
      \FFP\Logger::error($th->getMessage());

      $flag = false;
    }

    if ($flag) {
      $app = new \FFP\App();

      try {
        $app->boot();
      } catch (\Throwable $th) {
        \FFP\Logger::error($th->getMessage());

        $flag = false;
      }

      if ($flag) {
        if ($_SERVER['FRANKENPHP_WORKER']) {
          while (frankenphp_handle_request([$app, 'requestHandle'])) { gc_collect_cycles(); }
        } else { $app->requestHandle(); }
      }

      $app->shutdown();
    }
  }
?>