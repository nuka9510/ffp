<?php
  namespace FPW\Errors\Http;

  class InternalServerError extends \FPW\Implements\Http\Error {
    protected ?\FPW\Enums\Http\Status $_httpStatus = \FPW\Enums\Http\Status::INTERNAL_SERVER_ERROR;
  }
?>