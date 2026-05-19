<?php
  namespace FPW\Interfaces\Http;

  /**
   * @property-read ?\FPW\Enums\Http\Status $httpStatus
   * @property-read \FPW\Enums\Http\Error $type
   */
  interface Error extends \Throwable {
    /**
     * @param  array{
     *   message: string,
     *   code: int,
     *   previous: null|\Throwable
     * } $args
     */
    public function __construct(array $args = array(), \FPW\Enums\Http\Error $type = \FPW\Enums\Http\Error::VIEW);
  }
?>