<?php
  namespace FPW\Database;

  /**
   * @var array<string, class-string<\FPW\Interfaces\Database\Driver>>
   */
  const SUPPORT = array(
    'mysql' => MySqlDriver::class
  );
?>