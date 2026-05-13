<?php
  class BootStrap {
    public function __construct() {}

    public function boot() {
      frankenphp_log("boot frankenPHP project - {$_SERVER['FRANKENPHP_SCHEME']}{$_SERVER['FRANKENPHP_HOST']}:{$_SERVER['FRANKENPHP_PORT']}", FRANKENPHP_LOG_LEVEL_INFO);
    }

    public function requestHandle() {
      frankenphp_log('requestHandle frankenPHP project', FRANKENPHP_LOG_LEVEL_DEBUG);
      phpinfo();
    }

    public function shutdown() {
      frankenphp_log('shutdown strap frankenPHP project', FRANKENPHP_LOG_LEVEL_INFO);
    }
  }
?>