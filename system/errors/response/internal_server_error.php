<?php
  namespace FFP\Errors\Response;

  /**
   * @property-read \FFP\Enums\Response\Status $status
   */
  class InternalServerError extends \FFP\Implements\Response\Error {
    protected ?\FFP\Enums\Response\Status $_status = \FFP\Enums\Response\Status::INTERNAL_SERVER_ERROR;
  }
?>