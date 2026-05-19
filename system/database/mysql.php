<?php
  namespace FFP\Database;

  class MySqlDriver extends \FFP\Implements\Database\Driver {
    /** @var int[] */
    private array $_version;

    private bool $_sLock;

    public function __construct(\FFP\DTO\Database\Driver $config) {
      parent::__construct($config);

      try {
        $this->connect();
      } catch (\Throwable $th) { throw $th; }

      $this->____setVersion();
      $this->____setSLock();
    }

    /**
     * @param null|\FFP\Enums\Database\Mysql\Option $option
     * @throws \PDOException|\FFP\Errors\Database\Driver
     */
    public function select(?\FFP\Interfaces\Database\SelectOption $option = null): void {
      try {
        if ($this->inTransaction()) {
          match ($this->_transactionMode) {
            \FFP\Enums\Database\Transaction::R => array_push($this->_sql, ($this->_sLock) ? 'FOR SHARE' : 'LOCK IN SHARE MODE'),
            \FFP\Enums\Database\Transaction::W => array_push($this->_sql, 'FOR UPDATE'),
          };

          if ($this->_sLock) {
            match ($option) {
              \FFP\Enums\Database\Mysql\Option::NOWAIT,
              \FFP\Enums\Database\Mysql\Option::SKIP_LOCKED => array_push($this->_sql, $option->value),
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