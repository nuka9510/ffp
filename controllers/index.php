<?php
  namespace Controllers;

  class Index extends \FFP\Core\Controller {
    public function __construct(array $args) {
      parent::__construct($args);
    }

    public function getTest(int $page = 1): void {
      \FFP\Logger::debug("page: {$page}");

      $this->response->view('index');
    }
  }
?>