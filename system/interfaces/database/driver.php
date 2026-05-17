<?php
  namespace FPW\Interfaces\Database;

  /**
   * @property-read array{sql: string, param: mixed[]}|null $lastQuery
   */
  interface Driver {
    public function __construct(\FPW\DTO\Database\Driver $driver);

    /**
     * @throws \PDOException
     */
    public function connect(): void;

    public function isConnected(): bool;

    /**
     * @throws \FPW\Errors\Database\Driver
     */
    public function beginTransaction(\FPW\Enums\Database\Transaction $transactionMode): void;

    public function inTransaction(): bool;

    /**
     * @throws \PDOException|\FPW\Errors\Database\Driver
     */
    public function commit(): void;

    /**
     * @throws \PDOException|\FPW\Errors\Database\Driver
     */
    public function rollback(): void;

    /**
     * @param  null|array|array{mixed,int}[] $param
     */
    public function query(string $sql, ?array $param = null): void;

    /**
     * @param  null|array|array{mixed,int}[] $param
     */
    public function where(\FPW\Enums\Database\Operator $operator, string $sql, ?array $param = null): void;

    /**
     * @param  null|array|array{mixed,int}[] $param
     */
    public function set(string $sql, ?array $param = null): void;

    /**
     * @throws \PDOException|\FPW\Errors\Database\Driver
     */
    public function select(?\FPW\Interfaces\Database\SelectOption $option = null): void;

    /**
     * @throws \PDOException|\FPW\Errors\Database\Driver
     */
    public function insert(): void;

    /**
     * @throws \PDOException|\FPW\Errors\Database\Driver
     */
    public function update(): void;

    /**
     * @throws \PDOException|\FPW\Errors\Database\Driver
     */
    public function delete(): void;

    /**
     * @throws \PDOException
     */
    public function fetch(int $mode = \PDO::FETCH_ASSOC): mixed;

    /**
     * @throws \PDOException
     */
    public function fetchAll(int $mode = \PDO::FETCH_ASSOC): array;

    /**
     * @throws \PDOException|\FPW\Errors\Database\Driver
     */
    public function lastInsertId(?string $name = null): string;

    /**
     * @throws \PDOException
     */
    public function rowCount(): int;

    public function errorCode(): ?string;

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
    public function errorInfo(): array;
  }
?>