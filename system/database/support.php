<?php
  namespace FFP\Database;

  /**
   * @var array<string, class-string<\FFP\Interfaces\Database\Driver>>
   */
  const SUPPORT = array(
    'mysql' => MySqlDriver::class
  );
?>