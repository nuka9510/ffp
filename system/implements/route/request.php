<?php
  namespace FFP\Implements\Route;

  abstract class Request implements \FFP\Interfaces\Route\Request {
    private \FFP\App $_app;

    public function __construct(\FFP\App $app) { $this->_app = $app; }
  }
?>