<?php
  namespace FPW\Core;

  class Route {
    /**
     * @var array<'HEAD'|'OPTIONS'|'GET'|'POST'|'PUT'|'PATCH'|'DELETE',array<string,array{route:\FPW\Core\Utils\Route,depth:int}>>
     */
    private static array $_routes = array(
      'HEAD' => array(),
      'OPTIONS' => array(),
      'GET' => array(),
      'POST' => array(),
      'PUT' => array(),
      'PATCH' => array(),
      'DELETE' => array()
    );

    public static function init(): void {
      foreach (static::$_routes as $rk => &$r) {
        foreach ($r as $_ri => &$_r) { $_r['depth'] = $_r['route']->depth(); }
      }
    }

    /**
     * @param array{
     *   context: \FPW\App,
     *   request: \FPW\DTO\Request,
     *   response: \FPW\DTO\Response
     * } $args
     */
    public static function route(array $args): void {
      $routes = array_filter(
        static::$_routes[$args['request']->method->value],
        function ($r) use ($args) { return $r['depth'] === count($args['request']->paths); }
      );
      $route = null;

      foreach ($routes as $ri => $r) {
        if (!$r['route']->match($args['request']->paths)) { continue; }

        $route = $r['route'];

        break;
      }

      if (!isset($route)) { throw new \FPW\Errors\Http\NotFound(array('message' => "Route not found. path: /{$args['request']->path}"), \FPW\Enums\Http\Error::VIEW); }

      $args['context']->DBDriverRefresh();

      $route->route($args);
    }

    public static function append(\FPW\Enums\Route\Method $method, string $path, callable $callback): \FPW\Core\Utils\Route {
      static::$_routes[$method->value][$path]['route'] = new \FPW\Core\Utils\Route(\FPW\Core\Utils\Route::convertPath($path), $callback);

      return static::$_routes[$method->value][$path]['route'];
    }
  }
?>