<?php
  namespace FFP\Database;

  class MSSqlDriver extends \FFP\Implements\Database\Driver {
    /** @var string[] */
    private array $_hint = array();

    public function __construct(\FFP\DTO\Database\Driver $config) {
      parent::__construct($config);

      try {
        $this->connect();
      } catch (\Throwable $th) { throw $th; }

      $this->_dbms = 'mssql';
    }

    /**
     * @param null|\FFP\Enums\Database\Mssql\Option $option
     * @throws \PDOException|\FFP\Errors\Database\Driver
     */
    public function select(?\FFP\Interfaces\Database\SelectOption $option = null): void {
      try {
        if ($this->inTransaction() && isset($this->_transactionMode)) {
          array_push($this->_hint, 'ROWLOCK');

          match ($this->_transactionMode) {
            \FFP\Enums\Database\Transaction::R => null,
            \FFP\Enums\Database\Transaction::W => array_push($this->_hint, 'UPDLOCK'),
          };

          if (isset($option)) {
            match ($option) {
              \FFP\Enums\Database\Mssql\Option::NOWAIT,
              \FFP\Enums\Database\Mssql\Option::READPAST => array_push($this->_hint, $option->value),
            };
          }
        }

        $this->____execute();
      } catch (\Throwable $th) { throw $th; }
    }

    protected function ____sql(): string {
      $sql = parent::____sql();

      if (!empty($this->_hint)) {
        // FROM 절 뒤의 테이블 명을 찾아 힌트를 삽입합니다.
        // 정규식: FROM [공백] [테이블명] [Optional: AS alias]
        $sql = preg_replace('/(FROM\s+[\w\.]+(?:\s+(?:AS\s+)?\w+)?)/i', '$1 WITH ('.join(', ', $this->_hint).')', $sql, 1);

        $this->_hint = array();
      }

      return $sql;
    }
  }
?>