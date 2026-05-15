<?php
  namespace FPW\Core;

  class Database {
    private $config;

    private $support = array(
      'mysql'
    );

    /**
     * @param  \Database $db
     */
    public function init($db) {
      $this->config = $db->config;
    }
    
    private function ____conn(&$db) {
      $driver = explode(':', $db['dsn'])[0];

      if (in_array($driver, $this->support)) {
        # code...
      } else {
        # code...
      }
      
      $db['pdo'] = new \PDO($db['dsn'], $db['username'], $db['password'], $db['options']);
    }
  }
?>