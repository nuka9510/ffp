<?php
  namespace FFP\Errors\Cli;

  /**
   * @property-read \FFP\Enums\Http\Status $status
   */
  class InternalServerError extends \FFP\Implements\Cli\Error {
    protected ?\FFP\Enums\Http\Status $_status = \FFP\Enums\Http\Status::INTERNAL_SERVER_ERROR;
  }
?>