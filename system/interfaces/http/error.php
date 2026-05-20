<?php
  namespace FFP\Interfaces\Http;

  /**
   * @property-read ?\FFP\Enums\Http\Status $status
   * @property-read \FFP\Enums\Http\Error $type
   */
  interface Error extends \Throwable {
    /**
     * @param  array{
     *   message: string,
     *   code: int,
     *   previous: null|\Throwable
     * } $args
     */
    public function __construct(array $args = array(), \FFP\Enums\Http\Error $type = \FFP\Enums\Http\Error::VIEW);
  }
?>