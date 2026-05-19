<?php
  namespace FPW\Errors\Http;

  class MethodNotAllowed extends \FPW\Implements\Http\Error {
    protected ?\FPW\Enums\Http\Status $_httpStatus = \FPW\Enums\Http\Status::METHOD_NOT_ALLOWED;
  }
?>