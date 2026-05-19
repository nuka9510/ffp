<?php
  namespace FPW\Implements\Http;

  abstract class Error extends \Exception implements \FPW\Interfaces\Http\Error {
    protected ?\FPW\Enums\Http\Status $_httpStatus = null;

    protected \FPW\Enums\Http\Error $_type;

    public function __get(string $name) {
      return match ($name) {
        'httpStatus' => $this->_httpStatus,
        'type' => $this->_type,
        default => null,
      };
    }

    /**
     * @param  array{
     *   message: string,
     *   code: int,
     *   previous: null|\Throwable
     * } $args
     */
    #[\Override]
    public function __construct(array $args = array(), \FPW\Enums\Http\Error $type = \FPW\Enums\Http\Error::VIEW) {
      parent::__construct($args['message'] ?? '', $args['code'] ?? 0, $args['previous'] ?? null);

      $this->_type = $type;
    }
  }
?>