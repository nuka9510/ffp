<?php
  namespace FFP\Errors\Cli;

  /**
   * @property-read \FFP\Enums\Http\Status $status
   */
  class NotFound extends \FFP\Implements\Cli\Error {
    protected ?\FFP\Enums\Http\Status $_status = \FFP\Enums\Http\Status::NOT_FOUND;
  }
?>