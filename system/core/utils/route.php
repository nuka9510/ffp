<?php
  namespace FPW\Core\Utils;

  class Route {
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

    public function route(\FPW\App $app, \FPW\DTO\Request $req): void {
      try {

        $this->____route($app, $req);

      } catch (\Throwable $th) { throw $th; }
    }

    private function ____route(\FPW\App $app, \FPW\DTO\Request $req): void {
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
          function ($i) use ($req, $routes) {
            $type = $routes[$i]->type;

            if (isset($type)) {
              return $routes[$i]->type->setType($req->paths[$i]);
            } else { return $req->paths[$i]; }
          },
          array_keys($routes)
        )
      );
      $args = array_combine($keys, $values);

      $this->____invokeCallback($app, $req, $this->_callback, $args);
    }

    /**
     * @param \Closure|array|string $callback
     * @param array<string,mixed> $args
     */
    private function ____invokeCallback(\FPW\App $app, \FPW\DTO\Request $req, callable $callback, array $args): void {
      if (is_callable($callback)) {
        $callbackEnum = null;
        $reflection = null;

        if (is_array($callback)) {
          $callbackEnum = \FPW\Enums\Route\Callback::METHOD;
        } else if (is_string($callback)) {
          if (strpos($callback, '::') !== false) {
            $callbackEnum = \FPW\Enums\Route\Callback::STATIC_METHOD;
          } else { $callbackEnum = \FPW\Enums\Route\Callback::FUNCTION; }
        } else { $callbackEnum = \FPW\Enums\Route\Callback::FUNCTION; }

        $reflection = match ($callbackEnum) {
          \FPW\Enums\Route\Callback::METHOD => new \ReflectionMethod($callback[0], $callback[1]),
          \FPW\Enums\Route\Callback::STATIC_METHOD => is_callable('ReflectionMethod::createFromMethodName')
                                                        ? \ReflectionMethod::createFromMethodName($callback)
                                                        : new \ReflectionMethod($callback),
          \FPW\Enums\Route\Callback::FUNCTION => new \ReflectionFunction($callback),
        };

        $callbackEnum = match ($callbackEnum) {
          \FPW\Enums\Route\Callback::METHOD => $reflection->isStatic()
                                                  ? \FPW\Enums\Route\Callback::STATIC_METHOD
                                                  : \FPW\Enums\Route\Callback::INSTANCE_METHOD,
          default => $callbackEnum,
        };

        $params = array_map(
          function ($p) { return $p->name; },
          $reflection->getParameters()
        );

        if (in_array('context', $params)) { $args['context'] = $app; }
        if (in_array('request', $params)) { $args['request'] = $req; }

        match ($callbackEnum) {
          \FPW\Enums\Route\Callback::INSTANCE_METHOD => $reflection->invokeArgs($callback[0], $args),
          \FPW\Enums\Route\Callback::STATIC_METHOD => $reflection->invokeArgs(null, $args),
          \FPW\Enums\Route\Callback::FUNCTION => $reflection->invokeArgs($args),
          default => null,
        };
      } else if (
        is_callable($callback, true) &&
        is_array($callback)
      ) {
        $reflection = new \ReflectionMethod($callback[0], $callback[1]);

        $reflection->invokeArgs(new $callback[0](array('context' => $app, 'request' => $req)), $args);
      }
    }

    public static function convertPath(string $path): string { return preg_replace('/^\/|\/$/', '', $path); }
  }
?>