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
      $req = new \FPW\DTO\Request();
      $res = new \FPW\DTO\Response();

      \FPW\Core\Route::route($this, $req);
    }

    public function shutdown() {
      Logger::info('project shutdown');
    }

    public function getDBDriver(string $key = 'default'): ?\FPW\Interfaces\Database\Driver { return $this->_DBDrivers[$key]; }
  }
?>