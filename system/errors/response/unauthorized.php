<?php
  namespace FFP\Errors\Response;

  /**
   * @property-read \FFP\Enums\Response\Status $status
   */
  class Unauthorized extends \FFP\Implements\Response\Error {
    protected ?\FFP\Enums\Response\Status $_status = \FFP\Enums\Response\Status::UNAUTHORIZED;
  }
?>