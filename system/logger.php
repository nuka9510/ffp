<?php
  namespace FFP;

  class Logger {
    private static bool $_isCli;

    public static function log(string $message, int $level = FRANKENPHP_LOG_LEVEL_INFO, array $context = array()): void {
      if (static::____isCli()) {
        fwrite(STDOUT, "{$message}\r\n");
        Logger::____cliLog($message, $level, $context);
      } else { frankenphp_log($message, $level, $context); }
    }

    public static function debug(string $message, array $context = array()): void { Logger::log($message, FRANKENPHP_LOG_LEVEL_DEBUG, $context); }

    public static function info(string $message, array $context = array()): void { Logger::log($message, FRANKENPHP_LOG_LEVEL_INFO, $context); }

    public static function warn(string $message, array $context = array()): void { Logger::log($message, FRANKENPHP_LOG_LEVEL_WARN, $context); }

    public static function error(string $message, array $context = array()): void { Logger::log($message, FRANKENPHP_LOG_LEVEL_ERROR, $context); }

    private static function ____isCli(): bool {
      if (!isset(static::$_isCli)) { static::$_isCli = (PHP_SAPI === 'cli'); }

      return static::$_isCli;
    }

    private static function ____cliLog(string $message, int $level, array $context) {
      [$stream, $color, $label] = match ($level) {
        FRANKENPHP_LOG_LEVEL_DEBUG => [STDOUT, "\e[36m", 'DEBUG'],
        FRANKENPHP_LOG_LEVEL_INFO => [STDOUT, "\e[32m", 'INFO'],
        FRANKENPHP_LOG_LEVEL_WARN => [STDERR, "\e[33m", 'WARN'],
        FRANKENPHP_LOG_LEVEL_ERROR => [STDERR, "\e[31m", 'ERROR'],
        default => [STDOUT, "\e[0m", 'LOG']
      };
      $time = date('Y-m-d H:i:s');
      $reset = "\e[0m";
      $ctx = !empty($context) ? ' '.json_encode($context, JSON_UNESCAPED_UNICODE) : '';

      fwrite($stream, "[{$time}] {$color}[{$label}]{$reset} {$message}{$ctx}\r\n");
    }
  }
?>