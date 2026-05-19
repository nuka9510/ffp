<?php
  namespace FPW\Errors\Http;

  class Forbidden extends \FPW\Implements\Http\Error {
    protected ?\FPW\Enums\Http\Status $_httpStatus = \FPW\Enums\Http\Status::FORBIDDEN;
  }
?>