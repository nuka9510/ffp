<?php
  namespace FFP\Core;

  class Model {
    private \FFP\Interfaces\Database\Driver $_dirver;

    public function __construct(\FFP\Interfaces\Database\Driver $driver) {
      $this->_dirver = $driver;

      if (!$driver->isConnected()) {
        try {
          $driver->connect();
        } catch (\PDOException $th) {
          \FFP\Logger::error($th->getMessage());

          throw $th;
        } catch (\Throwable $th) { throw $th; }
      }
    }

    public function isConnected(): bool { return $this->_dirver->isConnected(); }

    /**
     * @throws \FFP\Errors\Database\Driver
     */
    public function beginTransaction(\FFP\Enums\Database\Transaction $transactionMode): void { $this->_dirver->beginTransaction($transactionMode); }

    public function inTransaction(): bool { return $this->_dirver->inTransaction(); }

    /**
     * @throws \PDOException|\FFP\Errors\Database\Driver
     */
    public function commit(): void { $this->_dirver->commit(); }

    /**
     * @throws \PDOException|\FFP\Errors\Database\Driver
     */
    public function rollback(): void { $this->_dirver->rollback(); }

    /**
     * @param  null|array|array{mixed,int}[] $param
     */
    public function query(string $sql, ?array $param = null): void { $this->_dirver->query($sql, $param); }

    /**
     * @param  null|array|array{mixed,int}[] $param
     */
    public function where(\FFP\Enums\Database\Operator $operator, string $sql, ?array $param = null): void { $this->_dirver->where($operator, $sql, $param); }

    /**
     * @param  null|array|array{mixed,int}[] $param
     */
    public function set(string $sql, ?array $param = null): void { $this->_dirver->set($sql, $param); }

    /**
     * @throws \PDOException|\FFP\Errors\Database\Driver
     */
    public function select(?\FFP\Interfaces\Database\SelectOption $option = null): void { $this->_dirver->select($option); }

    /**
     * @throws \PDOException|\FFP\Errors\Database\Driver
     */
    public function insert(): void { $this->_dirver->insert(); }

    /**
     * @throws \PDOException|\FFP\Errors\Database\Driver
     */
    public function update(): void { $this->_dirver->update(); }

    /**
     * @throws \PDOException|\FFP\Errors\Database\Driver
     */
    public function delete(): void { $this->_dirver->delete(); }

    /**
     * @throws \PDOException
     */
    public function fetch(int $mode = \PDO::FETCH_ASSOC): mixed { return $this->_dirver->fetch($mode); }

    /**
     * @throws \PDOException
     */
    public function fetchAll(int $mode = \PDO::FETCH_ASSOC): array { return $this->_dirver->fetchAll($mode); }

    /**
     * @throws \PDOException|\FFP\Errors\Database\Driver
     */
    public function lastInsertId(?string $name = null): string { return $this->_dirver->lastInsertId($name); }

    /**
     * @throws \PDOException
     */
    public function rowCount(): int { return $this->_dirver->rowCount(); }

    public function errorCode(): ?string { return $this->_dirver->errorCode(); }

    /**
     * @return array{
     *   null|string,
     *   null|int,
     *   null|string,
     *   array{
     *     sql: string,
     *     param: mixed[]
     *   }
     * }
     * `[0]`: `SQLSTATE`
     * `[1]`: `Driver specific error code`
     * `[2]`: `Driver specific error message`
     * `[3]`: `execute prepared query info`
     */
    public function errorInfo(): array { return $this->_dirver->errorInfo(); }
  }
?>