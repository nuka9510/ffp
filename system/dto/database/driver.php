<?php
  namespace FPW\DTO\Database;

  /**
   * @property-read string $dsn
   * @property-read null|string $username
   * @property-read null|string $password
   * @property-read null|array<int,mixed> $options
   */
  class Driver {
    private string $_dsn;

    private ?string $_username;

    private ?string $_password;

    /** @var null|array<int,mixed> */
    private ?array $_options;

    public function __get(string $name) {
      return match ($name) {
        'dsn' => $this->_dsn,
        'username' => $this->_username,
        'password' => $this->_password,
        'options' => $this->_options,
        default => null,
      };
    }

    /**
     * @param  array<'dsn'|'username'|'password'|'options',null|string|array<int,mixed>> $config
     */
    public function __construct(array $config) {
      $this->_dsn = $config['dsn'];
      $this->_username = $config['username'] ?? null;
      $this->_password = $config['password'] ?? null;
      $this->_options = $config['options'] ?? null;
    }

    public function getDriver(): \FPW\Interfaces\Database\Driver {
      $driver = explode(':', $this->_dsn)[0];

      if (array_key_exists($driver, \FPW\Database\SUPPORT)) {
        try {
          return new (\FPW\Database\SUPPORT[$driver])($this);
        } catch (\Throwable $th) { throw $th; }
      } else { throw new \Exception("Unsupported database driver: {$driver}", FRANKENPHP_LOG_LEVEL_ERROR); }
    }
  }
?>