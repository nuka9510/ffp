<?php
  namespace FFP\Errors\Http;

  class InternalServerError extends \FFP\Implements\Http\Error {
    protected ?\FFP\Enums\Http\Status $_httpStatus = \FFP\Enums\Http\Status::INTERNAL_SERVER_ERROR;
  }
?>