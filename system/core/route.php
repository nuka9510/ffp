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

    public static function route(\FPW\App $app): void {
      try {
        $method = \FPW\Enums\Route\Method::from($_SERVER['REQUEST_METHOD']);
        $path = static::____convertPath(parse_url(urldecode($_SERVER['REQUEST_URI']), PHP_URL_PATH));
        $paths = ($path === '') ? array() : explode('/', $path);
        $routes = array_filter(
          static::$_routes[$method->value],
          function ($r) use ($paths) { return $r['depth'] === count($paths); }
        );
        $route = null;

        foreach ($routes as $ri => $r) {
          if (!$r['route']->match($paths)) { continue; }

          $route = $r['route'];

          break;
        }

        if (isset($route)) {
          $route->route($app, $paths);
        } else { throw new \FPW\Errors\Route("Route not found. path: /{$path}"); }
      } catch (\FPW\Errors\Route $th) {
        http_response_code(404);

        throw $th;
      } catch (\Throwable $th) { \FPW\Logger::error($th->getMessage()); }
    }

    public static function append(\FPW\Enums\Route\Method $method, string $path, callable $callback): \FPW\Core\Utils\Route {
      static::$_routes[$method->value][$path]['route'] = new \FPW\Core\Utils\Route(static::____convertPath($path), $callback);

      return static::$_routes[$method->value][$path]['route'];
    }

    private static function ____convertPath(string $path): string { return preg_replace('/^\/|\/$/', '', $path); }
  }
?>