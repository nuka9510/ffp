<?php
  namespace FFP;

  class Logger {
    private static bool $_isCli;

    public static function log(string $message, int $level = FRANKENPHP_LOG_LEVEL_INFO, array $context = array()): void {
      if (static::____isCli()) {
        fwrite(STDOUT, "{$message}\r\n");
      } else { frankenphp_log($message, $level, $context); }
    }

    public static function debug(string $message, array $context = array()): void {
      if (static::____isCli()) {
        fwrite(STDOUT, "{$message}\r\n");
      } else { frankenphp_log($message, FRANKENPHP_LOG_LEVEL_DEBUG, $context); }
    }

    public static function info(string $message, array $context = array()): void {
      if (static::____isCli()) {
        fwrite(STDOUT, "{$message}\r\n");
      } else { frankenphp_log($message, FRANKENPHP_LOG_LEVEL_INFO, $context); }
    }

    public static function warn(string $message, array $context = array()): void {
      if (static::____isCli()) {
        fwrite(STDOUT, "{$message}\r\n");
      } else { frankenphp_log($message, FRANKENPHP_LOG_LEVEL_WARN, $context); }
    }

    public static function error(string $message, array $context = array()): void {
      if (static::____isCli()) {
        fwrite(STDERR, "{$message}\r\n");
      } else { frankenphp_log($message, FRANKENPHP_LOG_LEVEL_ERROR, $context); }
    }

    private static function ____isCli(): bool {
      if (!isset(static::$_isCli)) { static::$_isCli = (PHP_SAPI === 'cli'); }

      return static::$_isCli;
    }
  }
?>