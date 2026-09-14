<?php
  namespace FFP;

  use \FFP\Route;

  /**
   * @property-read bool $isCli
   * @property-read bool $isWorker
   * @property-read ?string $profile
   * @property-read string $charset
   * @property-read bool $xss
   * @property-read array<string,mixed> $env
   * @property-read bool $isBoot
   */
  class App {
    private bool $_isCli;

    private bool $_isWorker;

    /**
     * @var array<string,\FFP\Interfaces\Database\Driver>
     */
    private array $_DBDrivers;

    private ?string $_profile;

    private string $_charset;

    private bool $_xss;

    /**
     * @var array<string,mixed>
     */
    private array $_env;

    private ?\Psr\Http\Message\ServerRequestInterface $_request;

    private \Twig\Environment $_twig;

    private \ReflectionFunction $_handleNotFound;

    private \ReflectionFunction $_handleMethodNotAllowed;

    private \ReflectionFunction $_handleConditionNotMet;

    private bool $_isBoot = false;

    public function __get(string $name) {
      return match ($name) {
        'isCli' => $this->_isCli,
        'isWorker' => $this->_isWorker,
        'profile' => $this->_profile,
        'charset' => $this->_charset,
        'xss' => $this->_xss,
        'env' => $this->_env,
        'request' => $this->_request,
        'twig' => $this->_twig,
        'isBoot' => $this->_isBoot,
        default => null,
      };
    }

    public function __construct(bool $isCli, bool $isWorker) {
      $this->_isCli = $isCli;
      $this->_isWorker = $isWorker;
    }

    public function boot() {
      if (!$this->_isCli) { Logger::info('project boot - '.($_SERVER['APP_SCHEME'] ?? 'http://').($_SERVER['APP_HOST'] ?? 'localhost').':'.($_SERVER['APP_PORT'] ?? 8081)); }

      $this->_DBDrivers = \FFP\Database\Driver::getDrivers();
      $this->_profile = $_SERVER['APP_PROFILE'] ?? null;
      $this->_charset = $_SERVER['APP_CHARSET'] ?? 'UTF-8';
      $this->_xss = ($_SERVER['APP_XSS'] ?? 'off') === 'on';
      $this->_env = $GLOBALS['env'] ?? array();
      $this->_request = null;
      $this->_twig = new \Twig\Environment(new \Twig\Loader\FilesystemLoader(__DIR__.'/../views'));

      if (!isset($this->_handleNotFound)) {
        $this->setHandleNotFound(function (App $app, \League\Route\MatchResult $match) {
          \FFP\Logger::error('Route not found. path: /'.ltrim($app->_request->getUri()->getPath(), '/'));

          throw new \FFP\Errors\Response\NotFound();
        });
      }

      if (!isset($this->_handleMethodNotAllowed)) {
        $this->setHandleMethodNotAllowed(function (App $app, \League\Route\MatchResult $match) {
          \FFP\Logger::error('Method not Allowed. method: '.$app->_request->getMethod().' path: /'.ltrim($app->_request->getUri()->getPath(), '/'));

          throw new \FFP\Errors\Response\MethodNotAllowed();
        });
      }

      if (!isset($this->_handleConditionNotMet)) {
        $this->setHandleConditionNotMet(function (App $app, \League\Route\MatchResult $match) {
          \FFP\Logger::error('Condition not Met. uri: '.$app->_request->getUri()->__toString());

          throw new \FFP\Errors\Response\NotFound();
        });
      }

      $this->_isBoot = true;
    }

    public function requestHandle() {
      if ($this->_isCli) {
        $this->____cliHandle();
      } else { $this->____httpHandle(); }
    }

    public function shutdown() { Logger::info('project shutdown'); }

    public function getDBDriver(string $key = 'default'): ?\FFP\Interfaces\Database\Driver { return $this->_DBDrivers[$key]; }

    public function setHandleNotFound(callable $handle) { $this->_handleNotFound = new \ReflectionFunction($handle); }

    public function setHandleMethodNotAllowed(callable $handle) { $this->_handleMethodNotAllowed = new \ReflectionFunction($handle); }

    public function setHandleConditionNotMet(callable $handle) { $this->_handleConditionNotMet = new \ReflectionFunction($handle); }

    private function ____cliHandle() {
      $this->____sessionStart();
      $this->____DBDriverRefresh();

      try {

      } catch (\Throwable $th) {
        \FFP\Logger::error($th->getMessage());
      } finally {
        $this->____DBDriverReset();

        session_write_close();
      }
    }

    private function ____httpHandle() {
      $this->____sessionStart();
      $this->____DBDriverRefresh();

      try {
        $this->_request = \Laminas\Diactoros\ServerRequestFactory::fromGlobals($_SERVER, $_GET, $_POST, $_COOKIE, $_FILES);

        $match = Route\Http\ROUTER->match($this->_request);

        $response = match ($match->getStatus()) {
          \League\Route\MatchStatus::Found => Route\Http\ROUTER->dispatch($this->_request),
          \League\Route\MatchStatus::NotFound => $this->_handleNotFound->invoke($this, $match),
          \League\Route\MatchStatus::MethodNotAllowed => $this->_handleMethodNotAllowed->invoke($this, $match),
          \League\Route\MatchStatus::ConditionNotMet => $this->_handleConditionNotMet->invoke($this, $match),
        };
      } catch (\Throwable $th) {

      } finally {
        $this->____DBDriverReset();

        $this->_request = null;

        session_write_close();
      }
    }

    private function ____sessionStart() {
      if (session_status() === PHP_SESSION_NONE) {
        $options = $this->_env['session'];

        if (
          $this->_isCli ||
          $this->_isWorker
        ) { $options['gc_probability'] = 0; }

        if (
          $options['save_handler'] === 'files' &&
          !is_dir($options['save_path'])
        ) { mkdir($options['save_path']); }

        session_start($options);
      }
    }

    private function ____DBDriverRefresh(): void {
      foreach ($this->_DBDrivers as $dk => $d) {
        if (!$d->isConnected()) { $d->connect(); }
      }
    }

    private function ____DBDriverReset(): void {
      foreach ($this->_DBDrivers as $dk => $d) { $d->reset(); }
    }
  }
?>