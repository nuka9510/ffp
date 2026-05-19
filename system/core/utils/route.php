<?php
  namespace FPW\Core\Utils;

  class Route {
    /**
     * @var \FPW\DTO\Route[]
     */
    private array $_routes = array();

    private \FPW\DTO\Route\Handle $_routeHandle;

    /**
     * @var array<string,\FPW\DTO\Route\Handle[]>
     */
    private array $_middleware;

    /**
     * @param \Closure|array|string $callback
     */
    public function __construct(string $path, callable $callback) {
      $paths = ($path === '') ? array() : explode('/', $path);

      foreach ($paths as $pi => $p) { array_push($this->_routes, new \FPW\DTO\Route($p)); }

      $this->_routeHandle = new \FPW\DTO\Route\Handle($callback);
    }

    public function depth(): int { return count($this->_routes); }

    /**
     * @param string[] $paths
     */
    public function match(array $paths): bool {
      return array_all(
        $paths,
        function ($p) {
          return array_any(
            $this->_routes,
            function ($r) use ($p) { return $r->match($p); }
          );
        }
      );
    }

    /**
     * @param array{
     *   context: \FPW\App,
     *   request: \FPW\DTO\Request,
     *   response: \FPW\DTO\Response
     * } $args
     */
    public function route(array $args): void {
      try {

        $this->_routeHandle->invokeHandle($this->____invokArgs($args));

      } catch (\Throwable $th) { throw $th; }
    }

    /**
     * @param array{
     *   context: \FPW\App,
     *   request: \FPW\DTO\Request,
     *   response: \FPW\DTO\Response
     * } $args
     * @return array<string,mixed>
     */
    private function ____invokArgs(array $args): array {
      $routes = array_filter(
        $this->_routes,
        function ($r) { return $r->isArg(); }
      );
      $keys = array_values(
        array_map(
          function ($r) { return $r->name; },
          $routes
        )
      );
      $values = array_values(
        array_map(
          function ($i) use ($args, $routes) {
            $type = $routes[$i]->type;

            if (isset($type)) {
              return $routes[$i]->type->setType($args['request']->paths[$i]);
            } else { return $args['request']->paths[$i]; }
          },
          array_keys($routes)
        )
      );

      return array_merge(array_combine($keys, $values), $args);
    }

    public static function convertPath(string $path): string { return preg_replace('/^\/|\/$/', '', $path); }
  }
?>