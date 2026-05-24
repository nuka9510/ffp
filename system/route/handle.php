<?php
  namespace FFP\Route;

  class Handle {
    private \FFP\Enums\Route\Handle $_handle;

    private \ReflectionMethod|\ReflectionFunction $_reflection;

    private \Closure|array|string $_calldable;

    private bool $_isController;

    public function __construct(\Closure|array|string $calldable) {
      $this->_calldable = $calldable;
      $this->____init($calldable);
    }

    /**
     * @param array<string,mixed> $args
     */
    public function invokeHandle(array $args): mixed {
      $methodObj = null;
      $invokeArgs = array();

      if ($this->_handle === \FFP\Enums\Route\Handle::INSTANCE_METHOD) {
        if (is_string($this->_calldable[0])) {
          $classArgs = array();
          $class = new \ReflectionClass($this->_calldable[0]);

          if (!$this->_isController) {
            $params = array_map(
              function ($p) { return $p->name; },
              $class->getConstructor()->getParameters()
            );

            foreach ($params as $p) {
              if (array_key_exists($p, $args)) { $classArgs[$p] = $args[$p]; }
            }
          } else {
            $classArgs = array(
              'args' => array(
                'context' => $args['context'],
                'request' => $args['request'],
                'response' => $args['response']
              )
            );
          }

          $methodObj = $class->newInstanceArgs($classArgs);
        } else { $methodObj = $this->_calldable[0]; }
      }

      $params = array_map(
        function ($p) { return $p->name; },
        $this->_reflection->getParameters()
      );

      foreach ($params as $p) {
        if (array_key_exists($p, $args)) { $invokeArgs[$p] = $args[$p]; }
      }

      return match ($this->_handle) {
        \FFP\Enums\Route\Handle::FUNCTION => $this->_reflection->invokeArgs($invokeArgs),
        \FFP\Enums\Route\Handle::INSTANCE_METHOD => $this->_reflection->invokeArgs($methodObj, $invokeArgs),
        \FFP\Enums\Route\Handle::STATIC_METHOD => $this->_reflection->invokeArgs($methodObj, $invokeArgs),
        default => false,
      };
    }

    private function ____init(\Closure|array|string $calldable) {
      $handle = null;
      $reflection = null;
      $isController = false;

      if (is_callable($calldable)) {
        if (is_array($calldable)) {
          $handle = \FFP\Enums\Route\Handle::METHOD;
        } else if (is_string($calldable)) {
          if (strpos($calldable, '::') !== false) {
            $handle = \FFP\Enums\Route\Handle::STATIC_METHOD;
          } else { $handle = \FFP\Enums\Route\Handle::FUNCTION; }
        } else { $handle = \FFP\Enums\Route\Handle::FUNCTION; }

        $reflection = match ($handle) {
          \FFP\Enums\Route\Handle::METHOD => new \ReflectionMethod($calldable[0], $calldable[1]),
          \FFP\Enums\Route\Handle::STATIC_METHOD => is_callable('ReflectionMethod::createFromMethodName')
                                                      ? \ReflectionMethod::createFromMethodName($calldable)
                                                      : new \ReflectionMethod($calldable),
          \FFP\Enums\Route\Handle::FUNCTION => new \ReflectionFunction($calldable),
        };

        $handle = match ($handle) {
          \FFP\Enums\Route\Handle::METHOD => $reflection->isStatic()
                                                ? \FFP\Enums\Route\Handle::STATIC_METHOD
                                                : \FFP\Enums\Route\Handle::INSTANCE_METHOD,
          default => $handle,
        };
      } else if (is_callable($calldable, true)) {
        $handle = \FFP\Enums\Route\Handle::INSTANCE_METHOD;

        if (is_string($calldable)) { $calldable = explode('::', $calldable); }

        $reflection = new \ReflectionMethod($calldable[0], $calldable[1]);
        $class = new \ReflectionClass($calldable[0]);
        $isController = $class->getName() === \FFP\Core\Controller::class ||
                        is_subclass_of($class->getName(), \FFP\Core\Controller::class);
      }

      $this->_handle = $handle;
      $this->_reflection = $reflection;
      $this->_isController = $isController;
    }
  }
?>