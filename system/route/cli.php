<?php
  namespace FFP\Route\Cli;

  const ROUTER = new \League\Route\Router(),
  CONTAINER = new \League\Container\Container(),
  STRATEGY = new \League\Route\Strategy\ApplicationStrategy();

  CONTAINER
    ->add(\FFP\Core\Controller::class)
    ->addArgument($app);

  STRATEGY->setContainer(CONTAINER);

  ROUTER->setStrategy(STRATEGY);

  // class Cli {
  //   private static ?\FFP\Route\Router $_router = null;

  //   public static function getRouter(): \FFP\Route\Router {
  //     if (static::$_router === null) {
  //       static::$_router = new \FFP\Route\Router();
  //     }

  //     return static::$_router;
  //   }

  //   public static function init(): void {
  //     static::getRouter()->compile();
  //   }

  //   /**
  //    * @param array{
  //    *   context: \FFP\App,
  //    *   request: \FFP\DTO\Cli\Request,
  //    *   response: \FFP\DTO\Cli\Response
  //    * } $args
  //    */
  //   public static function route(array $args): void {
  //     $path = $args['request']->path;

  //     $match = static::getRouter()->matchRoute('CLI', $path);

  //     if ($match[0] !== \FastRoute\Dispatcher::FOUND) {
  //       throw new \FFP\Errors\Cli\NotFound("Route not found. path: /{$args['request']->path}");
  //     }

  //     /** @var \FFP\Route\Route $route */
  //     $route = $match[1];
  //     $vars = $match[2];

  //     $logPath = '/' . ltrim($route->getPath(), '/');
  //     \FFP\Logger::info("route - {$logPath}");

  //     $route->route($args, $vars);
  //   }

  //   public static function append(string $path, \Closure|array|string $callback): \FFP\Route\Route {
  //     return static::getRouter()->map('CLI', $path, $callback);
  //   }

  //   public static function map(string $path, \Closure|array|string $callback): \FFP\Route\Route {
  //     return static::getRouter()->map('CLI', $path, $callback);
  //   }

  //   public static function group(string $prefix, callable $group): \League\Route\RouteGroup {
  //     return static::getRouter()->group($prefix, $group);
  //   }
  // }

  ROUTER->get('/__session_gc', function (\Psr\Http\Message\ServerRequestInterface $request) { session_gc(); });
?>