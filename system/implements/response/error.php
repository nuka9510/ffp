<?php
  namespace FFP\Implements\Response;

  /**
   * @property-read ?\FFP\Enums\Response\Status $status
   */
  abstract class Error extends \Exception implements \FFP\Interfaces\Response\Error {
    protected ?\FFP\Enums\Response\Status $_status = null;

    public function __get(string $name) {
      return match ($name) {
        'status' => $this->_status,
        default => null,
      };
    }
  }
?>