<?php
  namespace FPW\Core;

  /**
   * @property-read \FPW\App $context
   * @property-read \FPW\DTO\Request $request
   */
  class Controller {
    private \FPW\App $_context;

    private \FPW\DTO\Request $_request;

    private array $_params;

    /**
     * @var array<string,array{
     *   name: string|string[],
     *   type: string|string[],
     *   tmp_name: string|string[],
     *   error: int|int[],
     *   size: int|int[]
     * }>
     */
    private array $_files;

    private bool $isErr = false;

    private string $errMsg = '';

    public function __get(string $name) {
      return match ($name) {
        'context' => $this->_context,
        'request' => $this->_request,
        default => null,
      };
    }

    /**
     * @param  array{
     *   context: \FPW\App,
     *   request: \FPW\DTO\Request
     * } $args
     */
    public function __construct(array $args) {
      $this->_context = $args['context'];
      $this->_request = $args['request'];
      $this->_params = $args['request']->method->getParams();
      $this->_files = $_FILES;
    }

    protected function xssEscape(string $arg): string { return htmlspecialchars($arg, ENT_QUOTES | ENT_HTML5); }

    protected function xssUnescape(string $arg): string { return htmlspecialchars_decode($arg, ENT_QUOTES | ENT_HTML5); }

    protected function getParam(string $key, ?\FPW\Enums\Val\Type $type = null, mixed $default = null, ?bool $xss = null): mixed {
      $param = $this->_params[$key];

      if (is_array($param)) {
        $this->____getParamArr($param, $type, $default, $xss);
      } else { $this->____convertParam($param, $type, $default, $xss); }

      return $param;
    }

    /**
     * @return null|array<string,array{
     *   name: string|string[],
     *   type: string|string[],
     *   tmp_name: string|string[],
     *   error: int|int[],
     *   size: int|int[]
     * }>
     */
    protected function getFile(string $key): null|array { return $this->_files[$key]; }

    /**
     * @template T of \FPW\Core\Model
     *
     * @param  class-string<T> $model
     * @return T
     */
    protected function getModel(string $model, string $driver = 'default'): \FPW\Core\Model {
      $_driver = $this->_context->getDBDriver($driver);

      if (isset($_driver)) {
        return new $model($_driver);
      } else { throw new \FPW\Errors\Controller('Driver not found.'); }
    }

    private function ____getParamArr(array &$param, ?\FPW\Enums\Val\Type $type, mixed $default, ?bool $xss): void {
      foreach ($param as &$v) {
        if (is_array($v)) {
          $this->____getParamArr($v, $type, $default, $xss);
        } else { $this->____convertParam($v, $type, $default, $xss); }
      }
    }

    private function ____convertParam(?string &$param, ?\FPW\Enums\Val\Type $type, mixed $default, ?bool $xss): void {
      if (
        isset($default) &&
        !isset($param)
      ) { $param = $default; }

      if (
        isset($param) &&
        isset($type)
      ) { $param = $type->setType($param); }

      if (is_string($param)) {
        if (!is_bool($xss)) { $xss = $this->_context->xss; }

        if ($xss) { $param = $this->xssEscape($param); }
      }
    }
  }
?>