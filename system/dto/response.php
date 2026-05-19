<?php
  namespace FPW\DTO;

  class Response {
    private ?\FPW\Interfaces\Http\Error $_error = null;

    /**
     * @var array<string,string[]>
     */
    private array $_headers = array();

    public function __construct() {}

    public function setError(\FPW\Interfaces\Http\Error $error) { $this->_error = $error; }

    public function setHeader() {}

    public function appendHeader() {}

    public function deleteHeader() {}

    public function error() {
      http_response_code($this->_error->httpStatus->value);

      if ($this->_error::class === \FPW\Errors\Http\MethodNotAllowed::class) { header('Allow: '.implode(', ', array_map(function ($m) { return $m->value; }, \FPW\Enums\Route\Method::cases()))); }

      match ($this->_error->type) {
        \FPW\Enums\Http\Error::VIEW => $this->____errorView(),
        \FPW\Enums\Http\Error::TEXT => $this->____errorText(),
      };
    }

    private function ____errorView() {
      $path = "{$_SERVER['DOCUMENT_ROOT']}/views/error/{$this->_error->getCode()}.php";

      if (!file_exists($path)) { return $this->____errorText(); }

      ob_start();

      $res = array('message' => $this->_error->getMessage());

      extract($res);

      include_once($path);

      $view = ob_get_contents();

      ob_end_clean();

      header('Content-Type: text/html; charset=UTF-8', false);
      header('Cache-Control: no-cache, no-store, must-revalidate;', false);

      echo $view;
    }

    private function ____errorText() {
      header('Content-Type: text/plain; charset=UTF-8', false);
      header('Cache-Control: no-cache, no-store, must-revalidate;', false);

      echo $this->_error->getMessage();
    }
  }
?>