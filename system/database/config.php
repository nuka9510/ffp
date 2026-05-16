<?php
  namespace FPW\Database;

  class Config {
    /**
     * @var array<string,\FPW\DTO\Database\Config>
     */
    private static array $config = array();

    /**
     * @param array<'dsn'|'username'|'password'|'options',string|array<int,mixed>> $config
     */
    public static function set(string $key, array $config) { static::$config[$key] = new \FPW\DTO\Database\Config($config); }

    /**
     * @return array<string,\FPW\Interfaces\Database\Driver>
     */
    public static function getDriver(): array {
      try {
        $connection = array();

        foreach (static::$config as $ck => $c) { $connection[$ck] = $c->getDriver(); }

        return $connection;
      } catch (\Throwable $th) { throw $th; }
    }
  }
?>