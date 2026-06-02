<?php
  namespace FFP\Implements\Route;

  /**
   * @property-read \FFP\App $context
   */
  abstract class Request implements \FFP\Interfaces\Route\Request {
    private \FFP\App $_app;

    public function __get(string $name) {
      return match ($name) {
        'context' => $this->_app,
        default => null,
      };
    }

    public function __construct(\FFP\App $app) { $this->_app = $app; }
  }
?>