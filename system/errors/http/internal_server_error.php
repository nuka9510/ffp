<?php
  namespace FFP\Errors\Http;

  /**
   * @property-read \FFP\Enums\Http\Status $status
   */
  class InternalServerError extends \FFP\Implements\Http\Error {
    protected ?\FFP\Enums\Http\Status $_status = \FFP\Enums\Http\Status::INTERNAL_SERVER_ERROR;
  }
?>