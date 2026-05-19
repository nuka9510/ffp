<?php
  namespace FFP\Core\Utils;

  /**
   * @property-read string $path
   */
  class Route {
    private string $_path;

    /**
     * @var \FFP\DTO\Route[]
     */
    private array $_routes = array();

    private \FFP\DTO\Route\Handle $_routeHandle;

    /**
     * @var array<string,\FFP\DTO\Route\Handle[]>
     */
    private array $_middleware;

    public function __get(string $name) {
      return match ($name) {
        'path' => $this->_path,
        default => null,
      };
    }

    public function __construct(string $path, \Closure|array|string $callback) {
      $path = \FFP\Core\Utils\Route::convertPath($path);
      $paths = ($path === '') ? array() : explode('/', $path);

      $this->_path = $path;

      foreach ($paths as $pi => $p) { array_push($this->_routes, new \FFP\DTO\Route($p)); }

      $this->_routeHandle = new \FFP\DTO\Route\Handle($callback);
    }

    public function depth(): int { return count($this->_routes); }

    /**
     * @param string[] $paths
     */
    public function match(array $paths): bool {
      $match = false;

      foreach ($paths as $pi => $p) {
        $match = $this->_routes[$pi]->match($p);

        if (!$match) { break; }
      }

      return $match;
    }

    /**
     * @param array{
     *   context: \FFP\App,
     *   request: \FFP\DTO\Request,
     *   response: \FFP\DTO\Response
     * } $args
     */
    public function route(array $args): void {
      try {

        $this->_routeHandle->invokeHandle($this->____invokArgs($args));

      } catch (\Throwable $th) { throw $th; }
    }

    /**
     * @param array{
     *   context: \FFP\App,
     *   request: \FFP\DTO\Request,
     *   response: \FFP\DTO\Response
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