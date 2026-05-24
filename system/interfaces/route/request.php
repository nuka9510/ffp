<?php
  namespace FFP\Interfaces\Route;

  /**
   * @property-read ?\FFP\Enums\Route\Method $method
   * @property-read ?string $scheme
   * @property-read ?string $host
   * @property-read string $path
   * @property-read ?string $query
   * @property-read string[] $paths
   * @property-read ?string $referer
   * @property-read ?string $clientIp
   */
  interface Request {
    public function __construct(\FFP\App $app);
  }
?>