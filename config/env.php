<?php
  $env = array(
    'headers' => array(
      array('Cache-Control: no-cache, no-store, must-revalidate;')
    ),
    'session' => array(
      'name' => 'PHPSESSID',
      'save_handler' => 'file',
      'save_path' => "{$_SERVER['DOCUMENT_ROOT']}/sessions",
      'use_strict_mode' => true,
      'use_cookies' => true,
      'use_only_cookies' => true,
      'cookie_lifetime' => 0,
      'cookie_secure' => false,
      'cookie_httponly' => true,
      'cookie_samesite' => 'Lax',
      'gc_maxlifetime' => 1440,
      'gc_probability' => 1,
      'gc_divisor' => 100
    )
  );
?>