<?php

class UserController extends Controller\Base {

  public $view_folder = 'users';

  public function index() {
    $this->_response_body = 'Hello';
    $users = ['Henry', 'Bob', 'Robert'];
    $this->render('index');
  }

  public function process($action) {
    if (!method_exists($this, $action)) {
      throw new ActionNotFound($this, $action);
    }
    $this->_status = 200;
    $this->_response_headers = [];
    $this->_response_body = null;
    $this->send_action($action);
    return [$this->_status, $this->_response_headers, $this->_response_body];
  }

  public function send_action($action) {
    $this->$action();
    if (!$this->performed) {
      render($action);
      $this->default_render($action);
    }
  }

}
