<?php
  namespace FPW\Database;

  class MySqlDriver extends \FPW\Implements\Database\Driver {
    /** @var int[] */
    private array $_version;

    private bool $_sLock;

    #[\Override]
    public function __construct(\FPW\DTO\Database\Driver $config) {
      parent::__construct($config);

      try {
        $this->connect();
      } catch (\Throwable $th) { throw $th; }

      $this->____setVersion();
      $this->____setSLock();
    }

    /**
     * @param null|\FPW\Enums\Database\Mysql\Option $option
     * @throws \PDOException|\FPW\Errors\Database\Driver
     */
    #[\Override]
    public function select(?\FPW\Interfaces\Database\SelectOption $option = null): void {
      try {
        if ($this->inTransaction()) {
          match ($this->_transactionMode) {
            \FPW\Enums\Database\Transaction::R => array_push($this->_sql, ($this->_sLock) ? 'FOR SHARE' : 'LOCK IN SHARE MODE'),
            \FPW\Enums\Database\Transaction::W => array_push($this->_sql, 'FOR UPDATE'),
          };

          if ($this->_sLock) {
            match ($option) {
              \FPW\Enums\Database\Mysql\Option::NOWAIT,
              \FPW\Enums\Database\Mysql\Option::SKIP_LOCKED => array_push($this->_sql, $option->value),
            };
          }
        }

        $this->____execute();
      } catch (\Throwable $th) { throw $th; }
    }

    private function ____setVersion(): void {
      $version = $this->_pdo->getAttribute(\PDO::ATTR_SERVER_VERSION);

      $this->_dbms = preg_match('/MariaDB/', $version) ? 'mariaDB' : 'mysql';
      $this->_version = array_map(
        function ($v) { return (int) $v; },
        explode('.', preg_replace('/-MariaDB.+$/', '', $version))
      );
    }

    private function ____setSLock(): void {
      $this->_sLock = match ($this->_dbms) {
        'mysql' => $this->_version[0] >= 8 &&
                    $this->_version[1] >= 0,
        'mariaDB' => $this->_version[0] >= 10 &&
                      $this->_version[1] >= 6 &&
                      $this->_version[2] >= 0,
      };
    }
  }
?>