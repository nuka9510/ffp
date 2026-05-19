<?php
  namespace FPW\Errors\Http;

  class Unauthorized extends \FPW\Implements\Http\Error {
    protected ?\FPW\Enums\Http\Status $_httpStatus = \FPW\Enums\Http\Status::UNAUTHORIZED;
  }
?>