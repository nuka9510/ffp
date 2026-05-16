<?php
  namespace FPW\Core;

  class Route {
    private static $routes = array(
      'GET' => array(),
      'POST' => array(),
      'PUT' => array(),
      'PUSH' => array(),
      'DELETE' => array()
    );

    public static function route(\FPW\App $app) { phpinfo(); }
  }
?>