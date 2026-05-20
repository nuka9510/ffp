<?php
  namespace FFP\Errors\Http;

  /**
   * @property-read \FFP\Enums\Http\Status $status
   */
  class MethodNotAllowed extends \FFP\Implements\Http\Error {
    protected ?\FFP\Enums\Http\Status $_status = \FFP\Enums\Http\Status::METHOD_NOT_ALLOWED;
  }
?>