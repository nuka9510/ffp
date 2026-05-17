<?php
  namespace FPW\Implements\Database;

  /**
   * @property-read array{sql: string, param: mixed[]}|null $lastQuery
   */
  abstract class Driver implements \FPW\Interfaces\Database\Driver {
    protected \FPW\DTO\Database\Driver $_driver;

    protected \PDO $_pdo;

    protected string $_dbms;

    protected ?\FPW\Enums\Database\Transaction $_transactionMode = null;

    protected ?\PDOStatement $_stmt = null;

    /** @var string[] */
    protected array $_sql = array();

    /** @var array{mixed,int}[] */
    protected array $_param = array();

    protected bool $_isSet = false;

    protected bool $_isWhere = false;

    /** @var null|array{sql: string, param: mixed[]} */
    protected ?array $_lastQuery = null;

    public function __get(string $name) {
      return match ($name) {
        'lastQuery' => $this->_lastQuery,
        default => null,
      };
    }

    #[\Override]
    public function __construct(\FPW\DTO\Database\Driver $driver) { $this->_driver = $driver; }

    /**
     * @throws \PDOException
     */
    #[\Override]
    public function connect(): void {
      try {
        $this->_pdo = new \PDO($this->_driver->dsn, $this->_driver->username, $this->_driver->password, $this->_driver->options);
      } catch (\Throwable $th) { throw $th; }
    }

    #[\Override]
    public function isConnected(): bool {
      try {
        $this->query('SELECT 1');

        $this->____execute();

        return true;
      } catch (\Throwable $th) { return false; }
    }

    /**
     * @throws \FPW\Errors\Database\Driver
     */
    #[\Override]
    public function beginTransaction(\FPW\Enums\Database\Transaction $transactionMode): void {
      try {
        $this->_transactionMode = match ($transactionMode) {
          \FPW\Enums\Database\Transaction::R,
          \FPW\Enums\Database\Transaction::W => $transactionMode,
        };
      } catch (\UnhandledMatchError $th) {
        \FPW\Logger::error($th->getMessage());

        throw new \FPW\Errors\Database\Driver('Invalid transaction type.');
      }

      $this->_pdo->beginTransaction();
    }

    #[\Override]
    public function inTransaction(): bool { return $this->_pdo->inTransaction(); }

    /**
     * @throws \PDOException|\FPW\Errors\Database\Driver
     */
    #[\Override]
    public function commit(): void {
      try {
        if ($this->inTransaction()) {
          if ($this->_pdo->commit()) {
            $this->_transactionMode = null;
          } else { throw new \FPW\Errors\Database\Driver("Failed to commit PDO transaction."); }
        } else { $this->_transactionMode = null; }
      } catch (\Throwable $th) { throw $th; }
    }

    /**
     * @throws \PDOException|\FPW\Errors\Database\Driver
     */
    #[\Override]
    public function rollback(): void {
      try {
        if ($this->inTransaction()) {
          if ($this->_pdo->rollBack()) {
            $this->_transactionMode = null;
          } else { throw new \FPW\Errors\Database\Driver("Failed to rollback PDO transaction."); }
        } else { $this->_transactionMode = null; }
      } catch (\Throwable $th) { throw $th; }
    }

    /**
     * @param  null|array|array{mixed,int}[] $param
     */
    #[\Override]
    public function query(string $sql, ?array $param = null): void {
      $this->___query($sql, $param);

      $this->_isSet = false;
      $this->_isWhere = false;
    }

    /**
     * @param  null|array|array{mixed,int}[] $param
     */
    #[\Override]
    public function where(\FPW\Enums\Database\Operator $operator, string $sql, ?array $param = null): void {
      $sql = (($this->_isWhere) ? $operator->value : 'WHERE')." {$sql}";

      $this->___query($sql, $param);

      $this->_isSet = false;
      $this->_isWhere = true;
    }

    /**
     * @param  array|array{mixed,int}[]|null $param [= null]
     */
    #[\Override]
    public function set(string $sql, ?array $param = null): void {
      $sql = (($this->_isSet) ? ',' : 'SET')." {$sql}";

      $this->___query($sql, $param);

      $this->_isSet = true;
      $this->_isWhere = false;
    }

    /**
     * @throws \PDOException|\FPW\Errors\Database\Driver
     */
    #[\Override]
    public function select(?\FPW\Interfaces\Database\SelectOption $option = null): void {
      try {
        $this->____execute();
      } catch (\Throwable $th) { throw $th; }
    }

    /**
     * @throws \PDOException|\FPW\Errors\Database\Driver
     */
    #[\Override]
    public function insert(): void {
      try {
        $this->____execute();
      } catch (\Throwable $th) { throw $th; }
    }

    /**
     * @throws \PDOException|\FPW\Errors\Database\Driver
     */
    #[\Override]
    public function update(): void {
      try {
        $this->____execute();
      } catch (\Throwable $th) { throw $th; }
    }

    /**
     * @throws \PDOException|\FPW\Errors\Database\Driver
     */
    #[\Override]
    public function delete(): void {
      try {
        $this->____execute();
      } catch (\Throwable $th) { throw $th; }
    }

    /**
     * @throws \PDOException
     */
    #[\Override]
    public function fetch(int $mode = \PDO::FETCH_ASSOC): mixed {
      try {
        return $this->_stmt->fetch($mode);
      } catch (\Throwable $th) { throw $th; }
    }

    /**
     * @throws \PDOException
     */
    #[\Override]
    public function fetchAll(int $mode = \PDO::FETCH_ASSOC): array {
      try {
        return $this->_stmt->fetchAll($mode);
      } catch (\Throwable $th) { throw $th; }
    }

    /**
     * @throws \PDOException|\FPW\Errors\Database\Driver
     */
    #[\Override]
    public function lastInsertId(?string $name = null): string {
      try {
        $id = $this->_pdo->lastInsertId($name);

        if ($id === false) { throw new \FPW\Errors\Database\Driver('Failed to retrieve last insert ID.'); }

        return $id;
      } catch (\Throwable $th) { throw $th; }
    }

    /**
     * @throws \PDOException
     */
    #[\Override]
    public function rowCount(): int {
      try {
        return $this->_stmt->rowCount();
      } catch (\Throwable $th) { throw $th; }
    }

    #[\Override]
    public function errorCode(): ?string { return $this->_stmt->errorCode(); }

    #[\Override]
    public function errorInfo(): array {
      if (isset($this->_stmt)) {
        $errorInfo = $this->_stmt->errorInfo();

        array_push($errorInfo, $this->lastQuery);

        return $errorInfo;
      } else { return array(null, null, null, $this->lastQuery); }
    }

    /**
     * @throws \PDOException|\FPW\Errors\Database\Driver
     */
    protected function ____execute(): void {
      $sql = trim(join(' ', $this->_sql));

      try {
        $this->_stmt = $this->_pdo->prepare($sql);

        if ($this->_stmt === false) { throw new \FPW\Errors\Database\Driver('Failed to create PDOStatement.', 100); }

        foreach ($this->_param as $k => &$v) {
          if (!$this->_stmt->bindParam($k + 1, $v[0], $v[1])) { throw new \FPW\Errors\Database\Driver('Failed to bind parameter to PDOStatement.'); }
        }

        if (!$this->_stmt->execute()) { throw new \FPW\Errors\Database\Driver('Failed to execute PDOStatement'); }
      } catch (\FPW\Errors\Database\Driver $th) {
        $this->_stmt = match ($th->getCode()) {
          100 => null,
          default => $this->_stmt,
        };

        throw $th;
      } catch (\PDOException $th) {
        throw $th;
      } catch (\Throwable $th) {
        throw $th;
      } finally {
        $this->lastQuery = array(
          'sql' => $sql,
          'param' => array_map(function ($v) { return $v[0]; }, $this->_param)
        );
        $this->_sql = array();
        $this->_param = array();
        $this->_isSet = false;
        $this->_isWhere = false;
      }
    }

    /**
     * @param  array|array{mixed,int}[]|null $param [= null]
     */
    private function ___query(string $sql, ?array $param): void {
      if (is_array($param)) {
        foreach ($param as $v) {
          if (is_array($v)) {
            array_push($this->_param, $v);
          } else { array_push($this->_param, array($v, $this->____getParamType($v))); }
        }
      }

      array_push($this->_sql, $sql);
    }

    private function ____getParamType(mixed $param): int {
      return match (gettype($param)) {
        'NULL' => \PDO::PARAM_NULL,
        'boolean' => \PDO::PARAM_BOOL,
        'integer' => \PDO::PARAM_INT,
        'double',
        'string' => \PDO::PARAM_STR,
        default => \PDO::PARAM_STR,
      };
    }
  }
?>