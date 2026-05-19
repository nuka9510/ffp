<?php
  namespace Controllers;

  class Index extends \FFP\Core\Controller {
    public function __construct(array $args) {
      parent::__construct($args);
    }

    public function getTest(int $id): void {
      \FFP\Logger::debug("id: {$id}");

      $this->response->view('index');
    }
  }
?>