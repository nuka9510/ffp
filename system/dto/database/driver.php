<?php
  namespace FFP\DTO\Database;

  /**
   * @property-read string $key
   * @property-read string $dsn
   * @property-read null|string $username
   * @property-read null|string $password
   * @property-read null|array<int,mixed> $options
   */
  class Driver {
    private string $_key;

    private string $_dsn;

    private ?string $_username;

    private ?string $_password;

    /** @var null|array<int,mixed> */
    private ?array $_options;

    public function __get(string $name) {
      return match ($name) {
        'key' => $this->_key,
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
    public function __construct(string $key, array $config) {
      $this->_key = $key;
      $this->_dsn = $config['dsn'];
      $this->_username = $config['username'] ?? null;
      $this->_password = $config['password'] ?? null;
      $this->_options = $config['options'] ?? null;
    }

    public function getDriver(): \FFP\Interfaces\Database\Driver {
      $driver = explode(':', $this->_dsn)[0];

      if (array_key_exists($driver, \FFP\Database\SUPPORT)) {
        try {
          return new (\FFP\Database\SUPPORT[$driver])($this);
        } catch (\Throwable $th) { throw $th; }
      } else { throw new \Exception("Unsupported database driver: {$driver}"); }
    }
  }
?>