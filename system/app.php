<?php
  namespace FPW;

  $profile = $_SERVER['APP_PROFILE'] ?? '';

  if ($profile === '') { $profile = null; }

  $database = __DIR__.'/../databases/'.($profile ?? 'index').'.php';
  
  if (file_exists($database)) {
    require_once($database);
  } else { throw new \Exception('Database configuration file not found for profile: '.($profile ?? ''), FRANKENPHP_LOG_LEVEL_ERROR); }

  /**
   * @property-read bool $xss
   * @property-read ?string $profile
   */
  class App {
    /**
     * @var array<string,\FPW\Interfaces\Database\Driver>
     */
    private array $_DBDrivers;

    private bool $_xss;

    private ?string $_profile;

    public function __get(string $name) {
      return match ($name) {
        'xss' => $this->_xss,
        'profile' => $this->_profile,
        default => null,
      };
    }

    public function boot() {
      Logger::info('project boot - '.($_SERVER['APP_SCHEME'] ?? 'http://').($_SERVER['APP_HOST'] ?? 'localhost').':'.($_SERVER['APP_PORT'] ?? 8081));

      try {
        $this->_DBDrivers = \FPW\Database\Driver::getDrivers();
        $this->_xss = ($_SERVER['APP_XSS'] ?? 'off') === 'on';
        $this->_profile = $_SERVER['APP_PROFILE'] ?? null;

        \FPW\Core\Route::init();
      } catch (\Throwable $th) { throw $th; }
    }

    public function requestHandle() {
      $res = new \FPW\DTO\Response();

      try {
        $req = new \FPW\DTO\Request();

        \FPW\Core\Route::route(array(
          'context' => $this,
          'request' => $req,
          'response' => $res
        ));
      } catch (\Throwable $th) {
        $error = match ($th::class) {
          \FPW\Errors\Http\Unauthorized::class => $th,
          \FPW\Errors\Http\Forbidden::class => $th,
          \FPW\Errors\Http\NotFound::class => $th,
          \FPW\Errors\Http\MethodNotAllowed::class => $th,
          default => new \FPW\Errors\Http\InternalServerError(
            array(
              'message' => $th->getMessage(),
              'code' => $th->getCode(),
              'previous' => $th->getPrevious()
            ),
            \FPW\Enums\Http\Error::VIEW
          ),
        };

        \FPW\Logger::error($error->getMessage());

        $res->setError($error);

        $res->error();
      }
    }

    public function shutdown() {
      Logger::info('project shutdown');
    }

    public function getDBDriver(string $key = 'default'): ?\FPW\Interfaces\Database\Driver { return $this->_DBDrivers[$key]; }

    public function DBDriverRefresh(): void {
      foreach ($this->_DBDrivers as $dk => $d) {
        if (!$d->isConnected()) { $d->connect(); }
      }
    }
  }
?>