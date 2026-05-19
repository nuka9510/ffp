<?php
  namespace FFP\Database;

  class Driver {
    /**
     * @var array<string,\FFP\DTO\Database\Driver>
     */
    private static array $drivers = array();

    /**
     * @param array<'dsn'|'username'|'password'|'options',string|array<int,mixed>> $config
     */
    public static function set(string $key, array $config) { static::$drivers[$key] = new \FFP\DTO\Database\Driver($config); }

    /**
     * @return array<string,\FFP\Interfaces\Database\Driver>
     */
    public static function getDrivers(): array {
      try {
        $connection = array();

        foreach (static::$drivers as $ck => $c) { $connection[$ck] = $c->getDriver(); }

        return $connection;
      } catch (\Throwable $th) { throw $th; }
    }
  }
?>