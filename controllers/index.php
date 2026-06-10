<?php
  namespace Controllers;

  class Index extends \FFP\Core\Controller {
    public function __construct(array $args) { parent::__construct($args); }

    public function getIndex(int $id = 0): void { $this->response->view('index', array('id' => $id)); }
  }
?>