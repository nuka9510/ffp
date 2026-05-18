<?php
  namespace FPW\DTO;

  /**
   * @property-read ?\FPW\Enums\Http\Status $httpStatus
   */
  class Response {
    private ?\FPW\Enums\Http\Status $_httpStatus;

    /**
     * @var array<string,string[]>
     */
    private array $_headers = array();

    public function __get(string $name) {
      return match ($name) {
        'httpStatus' => $this->_httpStatus,
        default => null,
      };
    }

    public function __construct() {}

    public function setHttpStatus(\FPW\Enums\Http\Status $status): void { $this->_httpStatus = $status; }

    public function setHeader() {}

    public function appendHeader() {}

    public function deleteHeader() {}
  }
?>