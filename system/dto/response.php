<?php
  namespace FFP\DTO;

  /**
   * @property-read array<string,bool>[] $headers
   */
  class Response {
    private \FFP\App $_app;

    /**
     * @var array<string,bool>[]
     */
    private array $_headers = array();

    public function __get(string $name) {
      return match ($name) {
        'headers' => $this->_headers,
        default => null,
      };
    }

    public function __construct(\FFP\App $app) { $this->_app = $app; }

    public function setHeader(string $header, bool $replace = true): void { array_push($this->_headers, array($header, $replace)); }

    public function redirect(string $path, \FFP\Enums\Http\Status $status = \FFP\Enums\Http\Status::SEE_OTHER): void {
      http_response_code($status->value);

      header("Location: {$path}");
    }

    public function goBack(?string $msg, \FFP\Enums\Http\Status $status = \FFP\Enums\Http\Status::FORBIDDEN): void {
      http_response_code($status->value);

      $this->____headerApp();
      $this->____headerRes();

      header("Content-Type: text/html; charset={$this->_app->charset}");

      $html = '<script>';

      if ($msg !== null) { $html .= 'alert(\''.addslashes($msg).'\');'; }

      $html .= "
          history.back();
        </script>
      ";

      file_put_contents('php://output', $html);
    }

    public function view(string $path, array $res = array(), bool $return = false): ?string {
      $path = "{$_SERVER['DOCUMENT_ROOT']}/views/{$path}.php";

      ob_start();

      extract($res, EXTR_SKIP);

      try {
        include_once($path);
      } catch (\Throwable $th) { \FFP\Logger::error($th->getMessage()); }

      $view = ob_get_contents();

      ob_end_clean();

      if (!$return) {
        http_response_code(\FFP\Enums\Http\Status::OK->value);

        $this->____headerApp();
        $this->____headerRes();

        header("Content-Type: text/html; charset={$this->_app->charset}");

        file_put_contents('php://output', $view);

        return null;
      } else { return $view; }
    }

    public function text(string $msg): void {
      http_response_code(\FFP\Enums\Http\Status::OK->value);

      $this->____headerApp();
      $this->____headerRes();

      header("Content-Type: text/plain; charset={$this->_app->charset}");

      file_put_contents('php://output', $msg);
    }

    public function json(array $res = array()): void {
      http_response_code(\FFP\Enums\Http\Status::OK->value);

      $this->____headerApp();
      $this->____headerRes();

      header("Content-Type: application/json; charset={$this->_app->charset}");

      file_put_contents('php://output', json_encode($res, JSON_UNESCAPED_UNICODE));
    }

    public function file(string $path, bool $attach = false, ?string $fileName = null): void {
      if (!isset($path)) {
        $this->error(new \FFP\Errors\Http\NotFound(array('message' => 'File not found.'), \FFP\Enums\Http\Error::TEXT));

        return;
      }

      $this->____headerApp();
      $this->____headerRes();

      header("Content-Length: ".filesize($path).";");

      if ($attach) {
        $ext = array_slice(explode('.', $path), -1, 1)[0];

        header("Content-Disposition: attachment; filename*={$this->_app->charset}''".rawurlencode(isset($fileName) ? $fileName : time()).".{$ext}");
        header("Content-Type: application/octet-stream;");
      } else { header("Content-Type: ".mime_content_type($path).";"); }

      readfile($path);
    }

    public function error(\FFP\Interfaces\Http\Error $error): void {
      http_response_code($error->httpStatus->value);

      if ($error::class === \FFP\Errors\Http\MethodNotAllowed::class) { header('Allow: '.implode(', ', array_map(function ($m) { return $m->value; }, \FFP\Enums\Route\Method::cases()))); }

      match ($error->type) {
        \FFP\Enums\Http\Error::VIEW => $this->____errorView($error),
        \FFP\Enums\Http\Error::TEXT => $this->____errorText($error),
      };
    }

    private function ____headerApp() {
      foreach ($this->_app->env['headers'] as $hi => $h) { header($h[0], $h[1] ?? true); }
    }

    private function ____headerRes() {
      foreach ($this->_headers as $hi => $h) { header($h[0], $h[1]); }
    }

    private function ____errorView(\FFP\Interfaces\Http\Error $error) {
      $path = "{$_SERVER['DOCUMENT_ROOT']}/views/error/{$error->getCode()}.php";

      if (!file_exists($path)) { return $this->____errorText($error); }

      ob_start();

      $res = array('message' => $error->getMessage());

      extract($res, EXTR_SKIP);

      try {
        include_once($path);
      } catch (\Throwable $th) { \FFP\Logger::error($th->getMessage()); }

      $view = ob_get_contents();

      ob_end_clean();

      $this->____headerApp();

      header("Content-Type: text/html; charset={$this->_app->charset}");

      file_put_contents('php://output', $view);
    }

    private function ____errorText(\FFP\Interfaces\Http\Error $error) {
      $this->____headerApp();

      header("Content-Type: text/plain; charset={$this->_app->charset}");

      file_put_contents('php://output', $error->getMessage());
    }
  }
?>