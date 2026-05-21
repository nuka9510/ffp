<?php
  $_SERVER['DOCUMENT_ROOT'] = realpath(__DIR__.'/../');

  require_once(__DIR__.'/../config/env.php');

  if (session_status() === PHP_SESSION_NONE) {
    $options = $env['session'];

    $options['gc_probability'] = 0;

    session_start($options);

    session_gc();
  }
?>