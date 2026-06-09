<?php
  namespace FFP\Database;

  class SQLiteDriver extends \FFP\Implements\Database\Driver {
    public function __construct(\FFP\DTO\Database\Driver $config) {
      parent::__construct($config);

      try {
        $this->connect();
      } catch (\Throwable $th) { throw $th; }

      $this->_dbms = 'sqlite';
    }

    /**
     * SQLite는 행 수준 잠금을 지원하지 않으므로 옵션을 무시하고 실행합니다.
     * 
     * @param null|\FFP\Interfaces\Database\SelectOption $option
     * @throws \PDOException|\FFP\Errors\Database\Driver
     */
    public function select(?\FFP\Interfaces\Database\SelectOption $option = null): void {
      try {
        $this->____execute();
      } catch (\Throwable $th) { throw $th; }
    }

    /**
     * SQLite의 동시성 제어를 위해 트랜잭션 모드를 오버라이드합니다.
     * 쓰기 트랜잭션(W) 시 'BEGIN IMMEDIATE'를 사용하여 데이터베이스 락 충돌을 방지합니다.
     * 
     * @throws \FFP\Errors\Database\Driver
     */
    public function beginTransaction(\FFP\Enums\Database\Transaction $transactionMode): void {
      try {
        $this->_transactionMode = $transactionMode;

        $mode = match ($transactionMode) {
          \FFP\Enums\Database\Transaction::R => 'DEFERRED',
          \FFP\Enums\Database\Transaction::W => 'IMMEDIATE',
        };

        $this->_pdo->exec("BEGIN {$mode} TRANSACTION");
      } catch (\Throwable $th) {
        \FFP\Logger::error($th->getMessage());

        throw new \FFP\Errors\Database\Driver('Failed to begin SQLite transaction.');
      }
    }
  }
?>