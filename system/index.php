<?php
  namespace FPW;

  $boot_flag = true;

  try {
    require_once(__DIR__.'/logger.php');
    require_once(__DIR__.'/require.php');
    require_once(__DIR__.'/app.php');
  } catch (\Throwable $th) {
    Logger::log($th->getMessage(), $th->getCode());

    $boot_flag = false;
  }


  if ($boot_flag) {
    $app = new \FPW\App();

    try {
      $app->boot();
    } catch (\Throwable $th) {
      Logger::log($th->getMessage(), $th->getCode());

      $boot_flag = false;
    }

    if ($boot_flag) {
      if ($_SERVER['FRANKENPHP_WORKER']) {
        while (frankenphp_handle_request([$app, 'requestHandle'])) {
          gc_collect_cycles();
        }
      } else { $app->requestHandle(); }
    }

    $app->shutdown();
  }
?>