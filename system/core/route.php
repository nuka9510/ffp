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

    public static function route(\FPW\App $app, \FPW\DTO\Request $req): void {
      try {
        $routes = array_filter(
          static::$_routes[$req->method->value],
          function ($r) use ($req) { return $r['depth'] === count($req->paths); }
        );
        $route = null;

        foreach ($routes as $ri => $r) {
          if (!$r['route']->match($req->paths)) { continue; }

          $route = $r['route'];

          break;
        }

        if (isset($route)) {

          $route->route($app, $req);

        } else { throw new \FPW\Errors\Route("Route not found. path: /{$req->path}"); }
      } catch (\FPW\Errors\Route $th) {
        http_response_code(404);

        throw $th;
      } catch (\Throwable $th) { \FPW\Logger::error($th->getMessage()); }
    }

    public static function append(\FPW\Enums\Route\Method $method, string $path, callable $callback): \FPW\Core\Utils\Route {
      static::$_routes[$method->value][$path]['route'] = new \FPW\Core\Utils\Route(\FPW\Core\Utils\Route::convertPath($path), $callback);

      return static::$_routes[$method->value][$path]['route'];
    }
  }
?>