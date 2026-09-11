<?php
  namespace FFP\Route;

  require_once(__DIR__ . '/router.php');
  require_once(__DIR__ . '/route.php');

  class Cli {
    private static ?\FFP\Route\Router $_router = null;

    public static function getRouter(): \FFP\Route\Router {
      if (static::$_router === null) {
        static::$_router = new \FFP\Route\Router();
      }

      return static::$_router;
    }

    public static function init(): void {
      static::getRouter()->compile();
    }

    /**
     * @param array{
     *   context: \FFP\App,
     *   request: \FFP\DTO\Cli\Request,
     *   response: \FFP\DTO\Cli\Response
     * } $args
     */
    public static function route(array $args): void {
      $path = $args['request']->path;

      $match = static::getRouter()->matchRoute('CLI', $path);

      if ($match[0] !== \FastRoute\Dispatcher::FOUND) {
        throw new \FFP\Errors\Cli\NotFound("Route not found. path: /{$args['request']->path}");
      }

      /** @var \FFP\Route\Route $route */
      $route = $match[1];
      $vars = $match[2];

      $logPath = '/' . ltrim($route->getPath(), '/');
      \FFP\Logger::info("route - {$logPath}");

      $route->route($args, $vars);
    }

    public static function append(string $path, \Closure|array|string $callback): \FFP\Route\Route {
      return static::getRouter()->map('CLI', $path, $callback);
    }

    public static function map(string $path, \Closure|array|string $callback): \FFP\Route\Route {
      return static::getRouter()->map('CLI', $path, $callback);
    }

    public static function group(string $prefix, callable $group): \League\Route\RouteGroup {
      return static::getRouter()->group($prefix, $group);
    }
  }

  Cli::append('/__session_gc', function (\FFP\App $context, \FFP\DTO\Cli\Request $request, \FFP\DTO\Cli\Response $response) { session_gc(); });
?>