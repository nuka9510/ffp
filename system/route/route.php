<?php
  namespace FFP\Route;

  /**
   * @property-read string $path
   * @property-read string $method
   */
  class Route extends \League\Route\Route {
    /**
     * @var \FFP\Route\Handle
     */
    private \FFP\Route\Handle $_routeHandle;

    /**
     * @var \FFP\Route\Handle[]
     */
    private array $_preHandle = array();

    /**
     * @var \FFP\Route\Handle[]
     */
    private array $_postHandle = array();

    public function __get(string $name) {
      return match ($name) {
        'path' => $this->getPath(),
        'method' => $this->getMethod(),
        default => null,
      };
    }

    public function __construct(string $method, string $path, \Closure|array|string $callback) {
      parent::__construct($method, $path, $callback);

      $this->_routeHandle = new \FFP\Route\Handle($callback);
    }

    public function depth(): int {
      $trimmed = trim($this->getPath(), '/');

      return ($trimmed === '') ? 0 : count(explode('/', $trimmed));
    }

    public function interceptor(\FFP\Enums\Interceptor\Handle $handle, \Closure|array|string $callback): self {
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
     * @param array<string,string> $vars
     */
    public function route(array $args, array $vars = array()): void {
      $_args = $this->____invokeArgs($args, $vars);

      try {
        if ($args['context']->isCli) {
          if (!\FFP\Interceptor\Cli::preHandle($_args)) { return; }
        } else {
          if (!\FFP\Interceptor\Http::preHandle($_args)) { return; }
        }

        foreach ($this->_preHandle as $phi => $ph) {
          if (!($ph->invokeHandle($_args) ?? true)) { return; }
        }

        $this->_routeHandle->invokeHandle($_args);
      } finally {
        try {
          if ($args['context']->isCli) {
            \FFP\Interceptor\Cli::postHandle($_args);
          } else {
            \FFP\Interceptor\Http::postHandle($_args);
          }
        } catch (\Throwable $th) {
          \FFP\Logger::error($th->getMessage());
        }

        foreach ($this->_postHandle as $phi => $ph) {
          try {
            $ph->invokeHandle($_args);
          } catch (\Throwable $th) {
            \FFP\Logger::error($th->getMessage());
          }
        }
      }
    }

    /**
     * @param array{
     *   context: \FFP\App,
     *   request: \FFP\Interfaces\Route\Request,
     *   response: \FFP\Interfaces\Route\Response
     * } $args
     * @param array<string,string> $vars
     * @return array<string,mixed>
     */
    private function ____invokeArgs(array $args, array $vars): array {
      $convertedVars = array();
      $path = $this->getPath();

      foreach ($vars as $key => $val) {
        if (preg_match('/{' . preg_quote($key, '/') . ':(int|integer|number)}/', $path)) {
          $convertedVars[$key] = intval($val);
        } else if (preg_match('/{' . preg_quote($key, '/') . ':(float|double)}/', $path)) {
          $convertedVars[$key] = floatval($val);
        } else if (preg_match('/{' . preg_quote($key, '/') . ':(bool|boolean)}/', $path)) {
          $convertedVars[$key] = boolval($val);
        } else if (is_numeric($val) && (string)(int)$val === (string)$val) {
          $convertedVars[$key] = intval($val);
        } else {
          $convertedVars[$key] = $val;
        }
      }

      return array_merge($convertedVars, $args);
    }
  }
?>
