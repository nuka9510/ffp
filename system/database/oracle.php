<?php
  namespace FFP\Database;

  class OracleDriver extends \FFP\Implements\Database\Driver {
    public function __construct(\FFP\DTO\Database\Driver $config) {
      parent::__construct($config);

      try {
        $this->connect();
      } catch (\Throwable $th) { throw $th; }

      $this->_dbms = 'oracle';
    }

    public function isConnected(): bool {
      try {
        $this->query('SELECT 1 FROM DUAL');

        $this->____execute();

        return true;
      } catch (\Throwable $th) { return false; }
    }

    /**
     * @param null|\FFP\Enums\Database\Oracle\Option $option
     * @throws \PDOException|\FFP\Errors\Database\Driver
     */
    public function select(?\FFP\Interfaces\Database\SelectOption $option = null): void {
      try {
        if ($this->inTransaction()) {
          match ($this->_transactionMode) {
            \FFP\Enums\Database\Transaction::R => null, // Oracle은 단순 읽기 시 FOR SHARE에 직접 대응하는 구문이 없습니다.
            \FFP\Enums\Database\Transaction::W => array_push($this->_sql, 'FOR UPDATE'),
          };

          if (
            $this->_transactionMode === \FFP\Enums\Database\Transaction::W &&
            isset($option)
          ) {
            match ($option) {
              \FFP\Enums\Database\Oracle\Option::NOWAIT,
              \FFP\Enums\Database\Oracle\Option::SKIP_LOCKED => array_push($this->_sql, $option->value),
            };
          }
        }

        $this->____execute();
      } catch (\Throwable $th) { throw $th; }
    }
  }
?>