<?php
  namespace FFP\Route;

  require_once(__DIR__ . '/route.php');

  class Router extends \League\Route\Router {
    public function __construct(?\FastRoute\RouteCollector $routeCollector = null) {
      parent::__construct($routeCollector);

      $this->addPatternMatcher('int', '[0-9]+');
      $this->addPatternMatcher('integer', '[0-9]+');
      $this->addPatternMatcher('string', '[^/]+');
      $this->addPatternMatcher('float', '[0-9]+(?:\.[0-9]+)?');
      $this->addPatternMatcher('double', '[0-9]+(?:\.[0-9]+)?');
    }

    public function map(string|array $method, string $path, callable|array|string|\Psr\Http\Server\RequestHandlerInterface $handler): \FFP\Route\Route {
      $path = static::normalizePath($path);
      $route = new \FFP\Route\Route($method, $path, $handler);

      $this->routes[] = $route;
      $this->routesPrepared = false;

      return $route;
    }

    public function compile(): void {
      if ($this->routesPrepared) { return; }

      while (!empty($this->groups)) {
        $group = array_shift($this->groups);
        $group();
      }

      $this->buildNameIndex();

      $routes = array_merge(array_values($this->routes), array_values($this->namedRoutes));

      foreach ($routes as $route) {
        $this->routeCollector->addRoute($route->getMethod(), $this->parseRoutePath($route->getPath()), $route);
      }

      $this->routesPrepared = true;
      $this->routesData = $this->routeCollector->getData();
    }

    public function matchRoute(string $method, string $path): array {
      if (!$this->routesPrepared) {
        $this->compile();
      }

      $dispatcher = new \FastRoute\Dispatcher\GroupCountBased($this->routesData);
      $uri = ($path === '' || $path === '/') ? '/' : '/' . trim($path, '/');

      return $dispatcher->dispatch($method, $uri);
    }

    public static function convertPath(string $path): string {
      return preg_replace('/^\/|\/$/', '', $path);
    }

    public static function normalizePath(string $path): string {
      // Support old FFP regex syntax: /{<regex>name} -> /{name:regex}
      $path = preg_replace('/{<([^>]+)>(\w+)}/', '{$2:$1}', $path);

      return ($path === '' || $path === '/') ? '/' : '/' . trim($path, '/');
    }
  }
?>