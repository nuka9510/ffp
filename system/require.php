<?php
  require_once(__DIR__.'/../vendor/autoload.php');

  $__errors = new RecursiveIteratorIterator(new RecursiveDirectoryIterator(__DIR__.'/errors'));

  foreach ($__errors as $ei => $e) {
    if (
      $e->isFile() &&
      $e->getExtension() === 'php'
    ) { require_once($e->getPathname()); }
  }

  $__interfaces = new RecursiveIteratorIterator(new RecursiveDirectoryIterator(__DIR__.'/interfaces'));

  foreach ($__interfaces as $ii => $i) {
    if (
      $i->isFile() &&
      $i->getExtension() === 'php'
    ) { require_once($i->getPathname()); }
  }

  $__implements = new RecursiveIteratorIterator(new RecursiveDirectoryIterator(__DIR__.'/implements'));

  foreach ($__implements as $ii => $i) {
    if (
      $i->isFile() &&
      $i->getExtension() === 'php'
    ) { require_once($i->getPathname()); }
  }

  $__enums = new RecursiveIteratorIterator(new RecursiveDirectoryIterator(__DIR__.'/enums'));

  foreach ($__enums as $ei => $e) {
    if (
      $e->isFile() &&
      $e->getExtension() === 'php'
    ) { require_once($e->getPathname()); }
  }

  $__dto = new RecursiveIteratorIterator(new RecursiveDirectoryIterator(__DIR__.'/dto'));

  foreach ($__dto as $di => $d) {
    if (
      $d->isFile() &&
      $d->getExtension() === 'php'
    ) { require_once($d->getPathname()); }
  }

  $__database = new RecursiveIteratorIterator(new RecursiveDirectoryIterator(__DIR__.'/database'));

  foreach ($__database as $di => $d) {
    if (
      $d->isFile() &&
      $d->getExtension() === 'php'
    ) { require_once($d->getPathname()); }
  }

  $__core = new RecursiveIteratorIterator(new RecursiveDirectoryIterator(__DIR__.'/core'));

  foreach ($__core as $ci => $c) {
    if (
      $c->isFile() &&
      $c->getExtension() === 'php'
    ) { require_once($c->getPathname()); }
  }

  $__models = new RecursiveIteratorIterator(new RecursiveDirectoryIterator(__DIR__.'/../models'));

  foreach ($__models as $mi => $m) {
    if (
      $m->isFile() &&
      $m->getExtension() === 'php'
    ) { require_once($m->getPathname()); }
  }

  $__controllers = new RecursiveIteratorIterator(new RecursiveDirectoryIterator(__DIR__.'/../controllers'));

  foreach ($__controllers as $ci => $c) {
    if (
      $c->isFile() &&
      $c->getExtension() === 'php'
    ) { require_once($c->getPathname()); }
  }

  $__config = new RecursiveIteratorIterator(new RecursiveDirectoryIterator(__DIR__.'/../config'));

  foreach ($__config as $ci => $c) {
    if (
      $c->isFile() &&
      $c->getExtension() === 'php'
    ) { require_once($c->getPathname()); }
  }
?>