<?php
  namespace FPW\Core\Utils;

  class Route {
    private string $_path;

    private \Closure|array|string $_callback;

    /**
     * @var array<string,(\Closure|array|string)[]>
     */
    private array $_middleware;

    /**
     * @var \FPW\DTO\Route[]
     */
    private array $_routes = array();

    /**
     * @param \Closure|array|string $callback
     */
    public function __construct(string $path, callable $callback) {
      $this->_path = $path;
      $this->_callback = $callback;

      $paths = ($path === '') ? array() : explode('/', $path);

      foreach ($paths as $pi => $p) { array_push($this->_routes, new \FPW\DTO\Route($p)); }
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

    public function route(\FPW\App $app, array $paths): void {
      try {
        $this->____route($app, $paths);
      } catch (\Throwable $th) { throw $th; }
    }

    private function ____route(\FPW\App $app, array $paths): void {
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
          function ($i) use ($paths, $routes) {
            $type = $routes[$i]->type;

            if (isset($type)) {
              return settype($paths[$i], $routes[$i]->type);
            } else { return $paths[$i]; }
          },
          array_keys($routes)
        )
      );
      $args = array_combine($keys, $values);

      $this->____invokeCallback($app, $this->_callback, $args);
    }

    /**
     * @param \Closure|array|string $callback
     * @param array<string,mixed> $args
     */
    private function ____invokeCallback(\FPW\App $app, callable $callback, array $args): void {
      if (is_callable($callback)) {
        $args['context'] = $app;

        if (is_array($callback)) {
          $rm = new \ReflectionMethod($callback[0], $callback[1]);

          if ($rm->isStatic()) {
            $rm->invokeArgs(null, $args);
          } else { $rm->invokeArgs($callback[0], $args); }
        } else if (is_string($callback)) {
          if (strpos($callback, '::') !== false) {
            $rm = null;

            if (is_callable('ReflectionMethod::createFromMethodName')) {
              $rm = \ReflectionMethod::createFromMethodName($callback);
            } else { $rm = new \ReflectionMethod($callback); }

            $rm->invokeArgs(null, $args);
          } else {
            $rf = new \ReflectionFunction($callback);

            $rf->invokeArgs($args);
          }
        } else {
          $rf = new \ReflectionFunction($callback);

          $rf->invokeArgs($args);
        }
      } else if (
        is_callable($callback, true) &&
        is_array($callback)
      ) {
        $rm = new \ReflectionMethod($callback[0], $callback[1]);

        $rm->invokeArgs(new $callback[0]($app), $args);
      }
    }
  }
?>