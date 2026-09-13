<?php
  namespace FFP\Interfaces\Response;

  /**
   * @property-read ?\FFP\Enums\Response\Status $status
   */
  interface Error extends \Throwable {}
?>