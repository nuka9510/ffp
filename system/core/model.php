<?php
  namespace FPW\Core;

  class Model {
    private \FPW\Interfaces\Database\Driver $_dirver;

    public function __construct(\FPW\Interfaces\Database\Driver $driver) {
      $this->_dirver = $driver;

      if (!$driver->isConnected()) {
        try {
          $driver->connect();
        } catch (\PDOException $th) {
          \FPW\Logger::error($th->getMessage());

          throw $th;
        } catch (\Throwable $th) { throw $th; }
      }
    }
  }
?>