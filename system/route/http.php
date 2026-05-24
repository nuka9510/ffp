<?php
  namespace FFP\Route;

  class Http {
    /**
     * @var array<'HEAD'|'OPTIONS'|'GET'|'POST'|'PUT'|'PATCH'|'DELETE',array<string,array{route:\FFP\Route\Router,depth:int}>>
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
     *   context: \FFP\App,
     *   request: \FFP\DTO\Http\Request,
     *   response: \FFP\DTO\Http\Response
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

      if (!isset($route)) { throw new \FFP\Errors\Http\NotFound(array('message' => "Route not found. path: /{$args['request']->path}"), \FFP\Enums\Http\Error::VIEW); }

      \FFP\Logger::info("route - /{$route->path}");

      $route->route($args);
    }

    public static function append(\FFP\Enums\Route\Method $method, string $path, \Closure|array|string $callback): \FFP\Route\Router {
      static::$_routes[$method->value][$path]['route'] = new \FFP\Route\Router($path, $callback);

      return static::$_routes[$method->value][$path]['route'];
    }
  }
?>