<?php
  namespace FFP\Errors\Http;

  class Forbidden extends \FFP\Implements\Http\Error {
    protected ?\FFP\Enums\Http\Status $_httpStatus = \FFP\Enums\Http\Status::FORBIDDEN;
  }
?>