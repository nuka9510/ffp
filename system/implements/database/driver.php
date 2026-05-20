<?php
  namespace FFP\Implements\Database;

  /**
   * @property-read array{sql: string, param: mixed[]}|null $lastQuery
   */
  abstract class Driver implements \FFP\Interfaces\Database\Driver {
    protected \FFP\DTO\Database\Driver $_driver;

    protected \PDO $_pdo;

    protected string $_dbms;

    protected ?\FFP\Enums\Database\Transaction $_transactionMode = null;

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

    public function __construct(\FFP\DTO\Database\Driver $driver) { $this->_driver = $driver; }

    /**
     * @throws \PDOException
     */
    public function connect(): void {
      try {
        $this->_pdo = new \PDO($this->_driver->dsn, $this->_driver->username, $this->_driver->password, $this->_driver->options);

        \FFP\Logger::info("Database connected. - {$this->_driver->key}: {$this->_driver->dsn}");
      } catch (\Throwable $th) { throw $th; }
    }

    public function isConnected(): bool {
      try {
        $this->query('SELECT 1');

        $this->____execute();

        return true;
      } catch (\Throwable $th) { return false; }
    }

    public function reset(): void {
      $this->_lastQuery = null;
      $this->_sql = array();
      $this->_param = array();
      $this->_isSet = false;
      $this->_isWhere = false;
      $this->_transactionMode = null;

      $this->rollback();
    }

    /**
     * @throws \FFP\Errors\Database\Driver
     */
    public function beginTransaction(\FFP\Enums\Database\Transaction $transactionMode): void {
      try {
        $this->_transactionMode = match ($transactionMode) {
          \FFP\Enums\Database\Transaction::R,
          \FFP\Enums\Database\Transaction::W => $transactionMode,
        };
      } catch (\UnhandledMatchError $th) {
        \FFP\Logger::error($th->getMessage());

        throw new \FFP\Errors\Database\Driver('Invalid transaction type.');
      }

      $this->_pdo->beginTransaction();
    }

    public function inTransaction(): bool { return $this->_pdo->inTransaction(); }

    /**
     * @throws \PDOException|\FFP\Errors\Database\Driver
     */
    public function commit(): void {
      try {
        if ($this->inTransaction()) {
          if ($this->_pdo->commit()) {
            $this->_transactionMode = null;
          } else { throw new \FFP\Errors\Database\Driver("Failed to commit PDO transaction."); }
        } else { $this->_transactionMode = null; }
      } catch (\Throwable $th) { throw $th; }
    }

    /**
     * @throws \PDOException|\FFP\Errors\Database\Driver
     */
    public function rollback(): void {
      try {
        if ($this->inTransaction()) {
          if ($this->_pdo->rollBack()) {
            $this->_transactionMode = null;
          } else { throw new \FFP\Errors\Database\Driver("Failed to rollback PDO transaction."); }
        } else { $this->_transactionMode = null; }
      } catch (\Throwable $th) { throw $th; }
    }

    /**
     * @param  null|array|array{mixed,int}[] $param
     */
    public function query(string $sql, ?array $param = null): void {
      $this->___query($sql, $param);

      $this->_isSet = false;
      $this->_isWhere = false;
    }

    /**
     * @param  null|array|array{mixed,int}[] $param
     */
    public function where(\FFP\Enums\Database\Operator $operator, string $sql, ?array $param = null): void {
      $sql = (($this->_isWhere) ? $operator->value : 'WHERE')." {$sql}";

      $this->___query($sql, $param);

      $this->_isSet = false;
      $this->_isWhere = true;
    }

    /**
     * @param  array|array{mixed,int}[]|null $param [= null]
     */
    public function set(string $sql, ?array $param = null): void {
      $sql = (($this->_isSet) ? ',' : 'SET')." {$sql}";

      $this->___query($sql, $param);

      $this->_isSet = true;
      $this->_isWhere = false;
    }

    /**
     * @throws \PDOException|\FFP\Errors\Database\Driver
     */
    public function select(?\FFP\Interfaces\Database\SelectOption $option = null): void {
      try {
        $this->____execute();
      } catch (\Throwable $th) { throw $th; }
    }

    /**
     * @throws \PDOException|\FFP\Errors\Database\Driver
     */
    public function insert(): void {
      try {
        $this->____execute();
      } catch (\Throwable $th) { throw $th; }
    }

    /**
     * @throws \PDOException|\FFP\Errors\Database\Driver
     */
    public function update(): void {
      try {
        $this->____execute();
      } catch (\Throwable $th) { throw $th; }
    }

    /**
     * @throws \PDOException|\FFP\Errors\Database\Driver
     */
    public function delete(): void {
      try {
        $this->____execute();
      } catch (\Throwable $th) { throw $th; }
    }

    /**
     * @throws \PDOException
     */
    public function fetch(int $mode = \PDO::FETCH_ASSOC): mixed {
      try {
        return $this->_stmt->fetch($mode);
      } catch (\Throwable $th) { throw $th; }
    }

    /**
     * @throws \PDOException
     */
    public function fetchAll(int $mode = \PDO::FETCH_ASSOC): array {
      try {
        return $this->_stmt->fetchAll($mode);
      } catch (\Throwable $th) { throw $th; }
    }

    /**
     * @throws \PDOException|\FFP\Errors\Database\Driver
     */
    public function lastInsertId(?string $name = null): string {
      try {
        $id = $this->_pdo->lastInsertId($name);

        if ($id === false) { throw new \FFP\Errors\Database\Driver('Failed to retrieve last insert ID.'); }

        return $id;
      } catch (\Throwable $th) { throw $th; }
    }

    /**
     * @throws \PDOException
     */
    public function rowCount(): int {
      try {
        return $this->_stmt->rowCount();
      } catch (\Throwable $th) { throw $th; }
    }

    public function errorCode(): ?string { return $this->_stmt->errorCode(); }

    public function errorInfo(): array {
      if (isset($this->_stmt)) {
        $errorInfo = $this->_stmt->errorInfo();

        array_push($errorInfo, $this->_lastQuery);

        return $errorInfo;
      } else { return array(null, null, null, $this->_lastQuery); }
    }

    /**
     * @throws \PDOException|\FFP\Errors\Database\Driver
     */
    protected function ____execute(): void {
      $sql = trim(join(' ', $this->_sql));

      try {
        $this->_stmt = $this->_pdo->prepare($sql);

        if ($this->_stmt === false) { throw new \FFP\Errors\Database\Driver('Failed to create PDOStatement.', 100); }

        foreach ($this->_param as $k => &$v) {
          if (!$this->_stmt->bindParam($k + 1, $v[0], $v[1])) { throw new \FFP\Errors\Database\Driver('Failed to bind parameter to PDOStatement.'); }
        }

        if (!$this->_stmt->execute()) { throw new \FFP\Errors\Database\Driver('Failed to execute PDOStatement'); }
      } catch (\FFP\Errors\Database\Driver $th) {
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
        $this->_lastQuery = array(
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