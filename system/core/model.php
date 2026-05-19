<?php
  namespace FFP\Core;

  class Model {
    private \FFP\Interfaces\Database\Driver $_dirver;

    public function __construct(\FFP\Interfaces\Database\Driver $driver) {
      $this->_dirver = $driver;

      if (!$driver->isConnected()) {
        try {
          $driver->connect();
        } catch (\PDOException $th) {
          \FFP\Logger::error($th->getMessage());

          throw $th;
        } catch (\Throwable $th) { throw $th; }
      }
    }
  }
?>