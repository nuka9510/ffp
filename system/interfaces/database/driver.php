<?php
  namespace FPW\Interfaces\Database;

  /**
   * @property-read array{sql: string, param: mixed[]}|null $lastQuery
   */
  interface Driver {
    public function __construct(\FPW\DTO\Database\Config $config);

    public function connect(): \PDO;

    public function isConnected(): bool;

    public function beginTransaction(\FPW\Enums\Database\Transaction $transactionMode): void;

    public function inTransaction(): bool;

    public function commit(): void;

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

    public function select(?\FPW\Interfaces\Database\SelectOption $option = null): void;

    public function insert(): void;

    public function update(): void;

    public function delete(): void;

    public function fetch(int $mode = \PDO::FETCH_ASSOC): mixed;

    public function fetchAll(int $mode = \PDO::FETCH_ASSOC): array;

    public function lastInsertId(?string $name = null): string;

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