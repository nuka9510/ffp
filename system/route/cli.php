<?php
  namespace FFP\Route;

  class Cli {
    /**
     * @var array<string,array{route:\FFP\Route\Router,depth:int}>
     */
    private static array $_routes = array();

    public static function init(): void {
      foreach (static::$_routes as $rk => &$r) { $r['depth'] = $r['route']->depth(); }
    }

    /**
     * @param array{
     *   context: \FFP\App,
     *   request: \FFP\DTO\Cli\Request,
     *   response: \FFP\DTO\Cli\Response
     * } $args
     */
    public static function route(array $args): void {
      $routes = array_filter(
        static::$_routes,
        function ($r) use ($args) { return $r['depth'] === count($args['request']->paths); }
      );
      $route = null;

      foreach ($routes as $ri => $r) {
        if (!$r['route']->match($args['request']->paths)) { continue; }

        $route = $r['route'];

        break;
      }

      if (!isset($route)) { throw new \FFP\Errors\Cli\NotFound("Route not found. path: /{$args['request']->path}"); }

      \FFP\Logger::info("route - /{$route->path}");

      $route->route($args);
    }

    public static function append(string $path, \Closure|array|string $callback): \FFP\Route\Router {
      static::$_routes[$path]['route'] = new \FFP\Route\Router($path, $callback);

      return static::$_routes[$path]['route'];
    }
  }

  Cli::append('/__session_gc', function (\FFP\App $context, \FFP\DTO\Cli\Request $request, \FFP\DTO\Cli\Response $response) { session_gc(); });
?>