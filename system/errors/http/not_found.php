<?php
  namespace FFP\Errors\Http;

  class NotFound extends \FFP\Implements\Http\Error {
    protected ?\FFP\Enums\Http\Status $_httpStatus = \FFP\Enums\Http\Status::NOT_FOUND;
  }
?>