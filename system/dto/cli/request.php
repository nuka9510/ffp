<?php
  namespace FFP\DTO\Cli;

  /**
   * @property-read string $path
   * @property-read string[] $paths
   */
  class Request extends \FFP\Implements\Route\Request {
    private string $_path;

    /**
     * @var string[]
     */
    private array $_paths;

    public function __get(string $name) {
      return match ($name) {
        'path' => $this->_path,
        'paths' => $this->_paths,
        default => null,
      };
    }

    public function __construct(\FFP\App $app) {
      parent::__construct($app);

      try {
        $path = \FFP\Route\Router::convertPath(parse_url(urldecode($_SERVER['argv'][1]), PHP_URL_PATH));
        $paths = ($path === '') ? array() : explode('/', $path);

        $this->_path = $path;
        $this->_paths = $paths;
      } catch (\ValueError $th) {
        throw new \FFP\Errors\Http\MethodNotAllowed(array('message' => "Unsupported HTTP method. - {$_SERVER['REQUEST_METHOD']}"), \FFP\Enums\Http\Error::VIEW);
      } catch (\Throwable $th) { throw $th; }

      \FFP\Logger::info("request - {$_SERVER['argv'][1]}");
    }
  }
?>