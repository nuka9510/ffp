<?php
  class Database extends \FPW\Core\Database {
    public $config = array(
      'default' => array(
        'dsn' => 'mysql:host=localhost;dbname=test;charset=utf8mb4',
        'username' => 'user',
        'password' => 'password',
        'options' => [
          \PDO::ATTR_EMULATE_PREPARES => false
        ]
      )
    );
  }
?>