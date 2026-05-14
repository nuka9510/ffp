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

    /**
     * @param  App $app
     */
    public static function route($app) {}
  }
?>