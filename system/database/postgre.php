<?php
  namespace FFP\Database;

  class PostgreDriver extends \FFP\Implements\Database\Driver {
    public function __construct(\FFP\DTO\Database\Driver $config) {
      parent::__construct($config);

      try {
        $this->connect();
      } catch (\Throwable $th) { throw $th; }

      $this->_dbms = 'postgre';
    }

    /**
     * @param null|\FFP\Enums\Database\Postgre\Option $option
     * @throws \PDOException|\FFP\Errors\Database\Driver
     */
    public function select(?\FFP\Interfaces\Database\SelectOption $option = null): void {
      try {
        if ($this->inTransaction()) {
          match ($this->_transactionMode) {
            \FFP\Enums\Database\Transaction::R => array_push($this->_sql, 'FOR SHARE'),
            \FFP\Enums\Database\Transaction::W => array_push($this->_sql, 'FOR UPDATE'),
          };

          if (isset($option)) {
            match ($option) {
              \FFP\Enums\Database\Postgre\Option::NOWAIT,
              \FFP\Enums\Database\Postgre\Option::SKIP_LOCKED => array_push($this->_sql, $option->value),
            };
          }
        }

        $this->____execute();
      } catch (\Throwable $th) { throw $th; }
    }
  }
?>