<?php
  namespace FFP\Errors\Http;

  class Unauthorized extends \FFP\Implements\Http\Error {
    protected ?\FFP\Enums\Http\Status $_httpStatus = \FFP\Enums\Http\Status::UNAUTHORIZED;
  }
?>