<?php
  namespace FFP\Interfaces\Route;

  /**
   * @property-read ?array<string,bool>[] $headers
   */
  interface Response {
    public function __construct(\FFP\App $app);

    public function setHeader(string $header, bool $replace = true): void;

    public function redirect(string $path, \FFP\Enums\Http\Status $status = \FFP\Enums\Http\Status::SEE_OTHER): void;

    public function goBack(?string $msg, \FFP\Enums\Http\Status $status = \FFP\Enums\Http\Status::FORBIDDEN): void;

    public function view(string $path, array $res = array(), bool $return = false): ?string;

    public function text(string $msg): void;

    public function json(array $res = array()): void;

    public function file(string $path, bool $attach = false, ?string $fileName = null): void;

    public function error(\FFP\Interfaces\Http\Error $error): void;
  }
?>