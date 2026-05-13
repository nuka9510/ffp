<?php
  class BootStrap {
    public function __construct() {}

    public function boot() {
      frankenphp_log('boot strap frankenPHP project', FRANKENPHP_LOG_LEVEL_INFO);
    }

    public function requestHandle() {
      frankenphp_log('requestHandle frankenPHP project', FRANKENPHP_LOG_LEVEL_INFO);
      phpinfo();
    }

    public function shutdown() {
      frankenphp_log('shutdown strap frankenPHP project', FRANKENPHP_LOG_LEVEL_INFO);
    }
  }
?>