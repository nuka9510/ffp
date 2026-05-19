<?php
  require_once(__DIR__.'/../vendor/autoload.php');

  $__interfaces = new RecursiveIteratorIterator(new RecursiveDirectoryIterator(__DIR__.'/interfaces'));
  $__implements = new RecursiveIteratorIterator(new RecursiveDirectoryIterator(__DIR__.'/implements'));
  $__errors = new RecursiveIteratorIterator(new RecursiveDirectoryIterator(__DIR__.'/errors'));
  $__enums = new RecursiveIteratorIterator(new RecursiveDirectoryIterator(__DIR__.'/enums'));
  $__dto = new RecursiveIteratorIterator(new RecursiveDirectoryIterator(__DIR__.'/dto'));
  $__database = new RecursiveIteratorIterator(new RecursiveDirectoryIterator(__DIR__.'/database'));
  $__core = new RecursiveIteratorIterator(new RecursiveDirectoryIterator(__DIR__.'/core'));
  $__models = new RecursiveIteratorIterator(new RecursiveDirectoryIterator(__DIR__.'/../models'));
  $__controllers = new RecursiveIteratorIterator(new RecursiveDirectoryIterator(__DIR__.'/../controllers'));
  $__config = new RecursiveIteratorIterator(new RecursiveDirectoryIterator(__DIR__.'/../config'));

  foreach ($__interfaces as $ii => $i) {
    if (
      $i->isFile() &&
      $i->getExtension() === 'php'
    ) { require_once($i->getPathname()); }
  }

  foreach ($__implements as $ii => $i) {
    if (
      $i->isFile() &&
      $i->getExtension() === 'php'
    ) { require_once($i->getPathname()); }
  }

  foreach ($__errors as $ei => $e) {
    if (
      $e->isFile() &&
      $e->getExtension() === 'php'
    ) { require_once($e->getPathname()); }
  }

  foreach ($__enums as $ei => $e) {
    if (
      $e->isFile() &&
      $e->getExtension() === 'php'
    ) { require_once($e->getPathname()); }
  }

  foreach ($__dto as $di => $d) {
    if (
      $d->isFile() &&
      $d->getExtension() === 'php'
    ) { require_once($d->getPathname()); }
  }

  foreach ($__database as $di => $d) {
    if (
      $d->isFile() &&
      $d->getExtension() === 'php'
    ) { require_once($d->getPathname()); }
  }

  foreach ($__core as $ci => $c) {
    if (
      $c->isFile() &&
      $c->getExtension() === 'php'
    ) { require_once($c->getPathname()); }
  }

  foreach ($__models as $mi => $m) {
    if (
      $m->isFile() &&
      $m->getExtension() === 'php'
    ) { require_once($m->getPathname()); }
  }

  foreach ($__controllers as $ci => $c) {
    if (
      $c->isFile() &&
      $c->getExtension() === 'php'
    ) { require_once($c->getPathname()); }
  }

  foreach ($__config as $ci => $c) {
    if (
      $c->isFile() &&
      $c->getExtension() === 'php'
    ) { require_once($c->getPathname()); }
  }

  unset($__interfaces);
  unset($__implements);
  unset($__errors);
  unset($__enums);
  unset($__dto);
  unset($__database);
  unset($__core);
  unset($__models);
  unset($__controllers);
  unset($__config);
?>