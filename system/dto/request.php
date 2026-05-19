<?php
  namespace FPW\DTO;

  /**
   * @property-read \FPW\Enums\Route\Method $method
   * @property-read string $scheme
   * @property-read string $host
   * @property-read string $path
   * @property-read string $query
   * @property-read string[] $paths
   * @property-read ?string $referer
   * @property-read string $clientIp
   */
  class Request {
    private \FPW\Enums\Route\Method $_method;

    private string $_scheme;

    private string $_host;

    private string $_path;

    private string $_query;

    /**
     * @var string[]
     */
    private array $_paths;

    private ?string $_referer;

    private string $_clientIp;

    public function __get(string $name) {
      return match ($name) {
        'method' => $this->_method,
        'scheme' => $this->_scheme,
        'host' => $this->_host,
        'path' => $this->_path,
        'query' => $this->_query,
        'paths' => $this->_paths,
        'referer' => $this->_referer,
        'clientIp' => $this->_clientIp,
        default => null,
      };
    }

    public function __construct() {
      try {
        $method = \FPW\Enums\Route\Method::from($_SERVER['REQUEST_METHOD']);
        $scheme = $this->____getScheme();
        $host = $_SERVER['HTTP_HOST'];
        $path = \FPW\Core\Utils\Route::convertPath(parse_url(urldecode($_SERVER['REQUEST_URI']), PHP_URL_PATH));
        $query = $_SERVER['QUERY_STRING'];
        $paths = ($path === '') ? array() : explode('/', $path);
        $referer = $this->____getReferer();
        $clientIp = $this->____getClientIp();

        $this->_method = $method;
        $this->_scheme = $scheme;
        $this->_host = $host;
        $this->_path = $path;
        $this->_query = $query;
        $this->_paths = $paths;
        $this->_referer = $referer;
        $this->_clientIp = $clientIp;
      } catch (\ValueError $th) {
        throw new \FPW\Errors\Http\MethodNotAllowed(array('message' => "Unsupported HTTP method. - {$_SERVER['REQUEST_METHOD']}"), \FPW\Enums\Http\Error::VIEW);
      } catch (\Throwable $th) { throw $th; }

      \FPW\Logger::info("requestHandle - {$_SERVER['REQUEST_METHOD']} {$scheme}://{$host}{$_SERVER['REQUEST_URI']}");
    }

    private function ____getScheme(): string { return $_SERVER['HTTP_X_FORWARDED_PROTO'] ?? $_SERVER['REQUEST_SCHEME']; }

    private function ____getReferer(): ?string {
      $referer = $_SERVER['HTTP_REFERER'];

      if (isset($referer)) {
        $host = parse_url($referer, PHP_URL_HOST);
        $port = parse_url($referer, PHP_URL_PORT);
        $path = parse_url($referer, PHP_URL_PATH);

        if (isset($port)) {
          $port = ":{$port}";
        } else { $port = ''; }

        $referer = "{$host}{$port}{$path}";
      }

      return $referer;
    }

    private function ____getClientIp() { return $_SERVER['HTTP_X_FORWARDED_FOR'] ?? $_SERVER['REMOTE_ADDR']; }
  }
?>