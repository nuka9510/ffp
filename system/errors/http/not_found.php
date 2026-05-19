<?php
  namespace FPW\Errors\Http;

  class NotFound extends \FPW\Implements\Http\Error {
    protected ?\FPW\Enums\Http\Status $_httpStatus = \FPW\Enums\Http\Status::NOT_FOUND;
  }
?>