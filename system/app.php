<?php
  namespace FPW;

  $profile = $_SERVER['APP_PROFILE'] ?? '';

  if ($profile === '') { $profile = null; }

  $database = __DIR__.'/../databases/'.($profile ?? 'index').'.php';
  
  if (file_exists($database)) {
    require_once($database);
  } else { throw new \Exception('Database configuration file not found for profile: '.($profile ?? ''), FRANKENPHP_LOG_LEVEL_ERROR); }

  class App {
    /**
     * @var array<string,\FPW\Interfaces\Database\Driver>
     */
    private array $_db_driver;

    public function boot() {
      frankenphp_log('boot frankenPHP project - '.($_SERVER['APP_SCHEME'] ?? 'http://').($_SERVER['APP_HOST'] ?? 'localhost').':'.($_SERVER['APP_PORT'] ?? 8081), FRANKENPHP_LOG_LEVEL_INFO);

      try {
        $this->_db_driver = \FPW\Database\Config::getDriver();
      } catch (\Throwable $th) { throw $th; }
    }

    public function requestHandle() { \FPW\Core\Route::route($this); }

    public function shutdown() {
      frankenphp_log('shutdown strap frankenPHP project', FRANKENPHP_LOG_LEVEL_INFO);
    }

    public function getDBDriver(string $key = 'default'): \FPW\Interfaces\Database\Driver { return $this->_db_driver[$key]; }
  }
?>