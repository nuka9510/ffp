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
      Logger::info('project boot - '.($_SERVER['APP_SCHEME'] ?? 'http://').($_SERVER['APP_HOST'] ?? 'localhost').':'.($_SERVER['APP_PORT'] ?? 8081));

      try {
        $this->_db_driver = \FPW\Database\Config::getDriver();

        \FPW\Core\Route::init();
      } catch (\Throwable $th) { throw $th; }
    }

    public function requestHandle() {
      \FPW\Logger::info("requestHandle - {$_SERVER['REQUEST_METHOD']} {$_SERVER['REQUEST_SCHEME']}://{$_SERVER['HTTP_HOST']}{$_SERVER['REQUEST_URI']}");

      \FPW\Core\Route::route($this);
    }

    public function shutdown() {
      Logger::info('project shutdown');
    }

    public function getDBDriver(string $key = 'default'): \FPW\Interfaces\Database\Driver { return $this->_db_driver[$key]; }
  }
?>