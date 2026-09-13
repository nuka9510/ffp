<?php
  namespace FFP\Errors\Response;

  /**
   * @property-read \FFP\Enums\Response\Status $status
   */
  class MethodNotAllowed extends \FFP\Implements\Response\Error {
    protected ?\FFP\Enums\Response\Status $_status = \FFP\Enums\Response\Status::METHOD_NOT_ALLOWED;
  }
?>