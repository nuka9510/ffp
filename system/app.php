<?php
  namespace FFP;

  $__profile = $_SERVER['APP_PROFILE'] ?? '';

  if ($__profile === '') { $__profile = null; }

  $__database = __DIR__.'/../databases/'.($__profile ?? 'index').'.php';
  
  if (file_exists($__database)) {
    require_once($__database);
  } else { throw new \Exception('Database configuration file not found for profile: '.($__profile ?? '')); }

  unset($__profile);
  unset($__database);

  /**
   * @property-read bool $isCli
   * @property-read bool $isWorker
   * @property-read ?string $profile
   * @property-read string $charset
   * @property-read bool $xss
   * @property-read array<string,mixed> $env
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

    public function __get(string $name) {
      return match ($name) {
        'isCli' => $this->_isCli,
        'isWorker' => $this->_isWorker,
        'profile' => $this->_profile,
        'charset' => $this->_charset,
        'xss' => $this->_xss,
        'env' => $this->_env,
        default => null,
      };
    }

    public function __construct(bool $isCli, bool $isWorker) {
      $this->_isCli = $isCli;
      $this->_isWorker = $isWorker;
    }

    public function boot() {
      if (!$this->_isCli) { Logger::info('project boot - '.($_SERVER['APP_SCHEME'] ?? 'http://').($_SERVER['APP_HOST'] ?? 'localhost').':'.($_SERVER['APP_PORT'] ?? 8081)); }

      try {
        $this->_DBDrivers = \FFP\Database\Driver::getDrivers();
        $this->_profile = $_SERVER['APP_PROFILE'] ?? null;
        $this->_charset = $_SERVER['APP_CHARSET'] ?? 'UTF-8';
        $this->_xss = ($_SERVER['APP_XSS'] ?? 'off') === 'on';
        $this->_env = $GLOBALS['env'] ?? array();

        if ($this->_isCli) {
          \FFP\Route\Cli::init();
        } else { \FFP\Route\Http::init(); }
      } catch (\Throwable $th) { throw $th; }
    }

    public function requestHandle() {
      if ($this->_isCli) {
        $this->____cliHandle();
      } else { $this->____httpHandle(); }
    }

    public function shutdown() {
      Logger::info('project shutdown');
    }

    public function getDBDriver(string $key = 'default'): ?\FFP\Interfaces\Database\Driver { return $this->_DBDrivers[$key]; }

    private function ____cliHandle() {
      $this->____sessionStart();
      $this->____DBDriverRefresh();

      $res = new \FFP\DTO\Cli\Response($this);

      try {
        $req = new \FFP\DTO\Cli\Request($this);

        \FFP\Route\Cli::route(array(
          'context' => $this,
          'request' => $req,
          'response' => $res
        ));
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

      $res = new \FFP\DTO\Http\Response($this);

      try {
        $req = new \FFP\DTO\Http\Request($this);

        \FFP\Route\Http::route(array(
          'context' => $this,
          'request' => $req,
          'response' => $res
        ));
      } catch (\Throwable $th) {
        $error = match ($th::class) {
          \FFP\Errors\Http\Unauthorized::class => $th,
          \FFP\Errors\Http\Forbidden::class => $th,
          \FFP\Errors\Http\NotFound::class => $th,
          \FFP\Errors\Http\MethodNotAllowed::class => $th,
          default => new \FFP\Errors\Http\InternalServerError(
            array(
              'message' => $th->getMessage(),
              'code' => $th->getCode(),
              'previous' => $th->getPrevious()
            ),
            \FFP\Enums\Http\Error::VIEW
          ),
        };

        \FFP\Logger::error($error->getMessage());

        $res->error($error);
      } finally {
        $this->____DBDriverReset();

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
          $options['save_handler'] === 'file' &&
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