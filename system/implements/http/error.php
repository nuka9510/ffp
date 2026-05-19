<?php
  namespace FFP\Implements\Http;

  abstract class Error extends \Exception implements \FFP\Interfaces\Http\Error {
    protected ?\FFP\Enums\Http\Status $_httpStatus = null;

    protected \FFP\Enums\Http\Error $_type;

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
    public function __construct(array $args = array(), \FFP\Enums\Http\Error $type = \FFP\Enums\Http\Error::VIEW) {
      parent::__construct($args['message'] ?? '', $args['code'] ?? 0, $args['previous'] ?? null);

      $this->_type = $type;
    }
  }
?>