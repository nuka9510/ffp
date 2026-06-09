<?php
  namespace FFP\Interceptor;

  class Cli {
    /**
     * @var \FFP\Route\Handle[]
     */
    private static array $_preHandle = array();

    /**
     * @var \FFP\Route\Handle[]
     */
    private static array $_postHandle = array();

    public static function append(\FFP\Enums\Interceptor\Handle $handle, \Closure|array|string $callback): void {
      match ($handle) {
        \FFP\Enums\Interceptor\Handle::PRE => array_push(static::$_preHandle, new \FFP\Route\Handle($callback)),
        \FFP\Enums\Interceptor\Handle::POST => array_push(static::$_postHandle, new \FFP\Route\Handle($callback)),
      };
    }

    /**
     * @param array<string,mixed> $args
     */
    public static function preHandle(array $args): bool {
      foreach (static::$_preHandle as $phi => $ph) {
        if (!($ph->invokeHandle($args) ?? true)) { return false; }
      }

      return true;
    }

    /**
     * @param array<string,mixed> $args
     */
    public static function postHandle(array $args) {
      foreach (static::$_postHandle as $phi => $ph) { $ph->invokeHandle($args); }
    }
  }
?>