<?php
  namespace FFP\Route\Http;

  const ROUTER = new \League\Route\Router(),
  CONTAINER = new \League\Container\Container(),
  WEB_STRATEGY = new \League\Route\Strategy\ApplicationStrategy(),
  API_STRATEGY = new \League\Route\Strategy\JsonStrategy(new \Laminas\Diactoros\ResponseFactory());

  CONTAINER
    ->add(\FFP\Core\Controller::class)
    ->addArgument($app);

  WEB_STRATEGY->setContainer(CONTAINER);

  ROUTER->setStrategy(WEB_STRATEGY);

  // class Http {
  //   private static \FFP\Route\Router $_router;

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
  //    *   request: \FFP\DTO\Http\Request,
  //    *   response: \FFP\DTO\Http\Response
  //    * } $args
  //    */
  //   public static function route(array $args): void {
  //     $method = $args['request']->method->value;
  //     $path = $args['request']->path;

  //     $match = static::getRouter()->matchRoute($method, $path);

  //     if ($match[0] === \FastRoute\Dispatcher::NOT_FOUND) {
  //       throw new \FFP\Errors\Http\NotFound(array('message' => "Route not found. path: /{$args['request']->path}"), \FFP\Enums\Http\Error::VIEW);
  //     }

  //     if ($match[0] === \FastRoute\Dispatcher::METHOD_NOT_ALLOWED) {
  //       throw new \FFP\Errors\Http\MethodNotAllowed(array('message' => "Method not allowed. path: /{$args['request']->path}"), \FFP\Enums\Http\Error::VIEW);
  //     }

  //     /** @var \FFP\Route\Route $route */
  //     $route = $match[1];
  //     $vars = $match[2];

  //     $logPath = '/' . ltrim($route->getPath(), '/');
  //     \FFP\Logger::info("route - {$logPath}");

  //     $route->route($args, $vars);
  //   }

  //   public static function append(\FFP\Enums\Route\Method|string $method, string $path, \Closure|array|string $callback): \FFP\Route\Route {
  //     $methodStr = ($method instanceof \FFP\Enums\Route\Method) ? $method->value : strtoupper((string)$method);

  //     return static::getRouter()->map($methodStr, $path, $callback);
  //   }

  //   public static function get(string $path, \Closure|array|string $callback): \FFP\Route\Route {
  //     return static::getRouter()->get($path, $callback);
  //   }

  //   public static function post(string $path, \Closure|array|string $callback): \FFP\Route\Route {
  //     return static::getRouter()->post($path, $callback);
  //   }

  //   public static function put(string $path, \Closure|array|string $callback): \FFP\Route\Route {
  //     return static::getRouter()->put($path, $callback);
  //   }

  //   public static function patch(string $path, \Closure|array|string $callback): \FFP\Route\Route {
  //     return static::getRouter()->patch($path, $callback);
  //   }

  //   public static function delete(string $path, \Closure|array|string $callback): \FFP\Route\Route {
  //     return static::getRouter()->delete($path, $callback);
  //   }

  //   public static function head(string $path, \Closure|array|string $callback): \FFP\Route\Route {
  //     return static::getRouter()->head($path, $callback);
  //   }

  //   public static function options(string $path, \Closure|array|string $callback): \FFP\Route\Route {
  //     return static::getRouter()->options($path, $callback);
  //   }

  //   public static function map(string $method, string $path, \Closure|array|string $callback): \FFP\Route\Route {
  //     return static::getRouter()->map($method, $path, $callback);
  //   }

  //   public static function group(string $prefix, callable $group): \League\Route\RouteGroup {
  //     return static::getRouter()->group($prefix, $group);
  //   }
  // }
?>