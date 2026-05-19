<?php
  namespace FFP\Errors\Http;

  class MethodNotAllowed extends \FFP\Implements\Http\Error {
    protected ?\FFP\Enums\Http\Status $_httpStatus = \FFP\Enums\Http\Status::METHOD_NOT_ALLOWED;
  }
?>