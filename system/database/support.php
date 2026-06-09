<?php
  namespace FFP\Database;

  /**
   * @var array<string, class-string<\FFP\Interfaces\Database\Driver>>
   */
  const SUPPORT = array(
    'mysql' => MySqlDriver::class,
    'oci' => OracleDriver::class,
    'sqlsrv' => MSSqlDriver::class,
    'dblib' => MSSqlDriver::class,
    'pgsql' => PostgreDriver::class,
    'sqlite' => SQLiteDriver::class
  );
?>