<?php
  namespace FFP\Route;

  /**
   * @property-read string $path
   */
  class Router {
    private string $_path;

    /**
     * @var \FFP\DTO\Route[]
     */
    private array $_routes = array();

    private \FFP\Route\Handle $_routeHandle;

    /**
     * @var \FFP\Route\Handle[]
     */
    private array $_preHandle;

    /**
     * @var \FFP\Route\Handle[]
     */
    private array $_postHandle;

    public function __get(string $name) {
      return match ($name) {
        'path' => $this->_path,
        default => null,
      };
    }

    public function __construct(string $path, \Closure|array|string $callback) {
      $path = \FFP\Route\Router::convertPath($path);
      $paths = ($path === '') ? array() : explode('/', $path);

      $this->_path = $path;

      foreach ($paths as $pi => $p) { array_push($this->_routes, new \FFP\DTO\Route($p)); }

      $this->_routeHandle = new \FFP\Route\Handle($callback);
    }

    public function depth(): int { return count($this->_routes); }

    /**
     * @param string[] $paths
     */
    public function match(array $paths): bool {
      $match = true;

      foreach ($paths as $pi => $p) {
        $match = $this->_routes[$pi]->match($p);

        if (!$match) { break; }
      }

      return $match;
    }

    /**
     * @param array{
     *   context: \FFP\App,
     *   request: \FFP\Interfaces\Route\Request,
     *   response: \FFP\Interfaces\Route\Response
     * } $args
     */
    public function route(array $args): void {
      $_args = $this->____invokArgs($args);
      try {
        if ($args['context']->isCli) {
          if (!\FFP\Interceptor\Cli::preHandle($_args)) { return; }
        } else { if (!\FFP\Interceptor\Http::preHandle($_args)) { return; } }

        foreach ($this->_preHandle as $phi => $ph) {
          if (!($ph->invokeHandle($_args) ?? true)) { return; }
        }

        $this->_routeHandle->invokeHandle($_args);

        if ($args['context']->isCli) {
          \FFP\Interceptor\Cli::postHandle($_args);
        } else { \FFP\Interceptor\Http::postHandle($_args); }

        foreach ($this->_postHandle as $phi => $ph) { $ph->invokeHandle($_args); }
      } catch (\Throwable $th) { throw $th; }
    }

    public function interceptor(\FFP\Enums\Interceptor\Handle $handle, \Closure|array|string $callback): Router {
      match ($handle) {
        \FFP\Enums\Interceptor\Handle::PRE => array_push($this->_preHandle, new \FFP\Route\Handle($callback)),
        \FFP\Enums\Interceptor\Handle::POST => array_push($this->_postHandle, new \FFP\Route\Handle($callback)),
      };

      return $this;
    }

    /**
     * @param array{
     *   context: \FFP\App,
     *   request: \FFP\Interfaces\Route\Request,
     *   response: \FFP\Interfaces\Route\Response
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