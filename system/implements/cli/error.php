<?php
  namespace FFP\Implements\Cli;

  /**
   * @property-read ?\FFP\Enums\Http\Status $status
   */
  abstract class Error extends \Exception implements \FFP\Interfaces\Cli\Error {
    protected ?\FFP\Enums\Http\Status $_status = null;

    public function __get(string $name) {
      return match ($name) {
        'status' => $this->_status,
        default => null,
      };
    }
  }
?>