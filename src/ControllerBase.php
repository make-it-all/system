<?php namespace Controller;

class Base {

  protected $headers = [];
  protected $response_body = [];

  protected $performed = false;


  public function process($request_glob) {
    $action = $request_glob['action'];
    // TODO: PARAMS
    $params = $request_glob['params'];
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
    $pre_vars = get_object_vars($this);
    $this->$action();
    $locals = array_diff_key(get_object_vars($this), $pre_vars);
    if (!$this->performed) {
      $this->render($action, $locals);
    }
  }

  public function render($file, $locals=[]) {
    if ($this->performed) {
      throw new ActionPerformed('This action has already either rendered or redirected and can not render again.');
    } else {
      $filename = \Application::$paths['views'] . '/' . $this->view_folder . '/' . $file . '.php';
      $this->_response_body = (function($__view_file_path) use ($locals){
        extract($locals);
        ob_start();
        require $__view_file_path;
        return ob_get_clean();
      })($filename);
      $this->performed = true;
    }
  }

  public function redirect($to) {
    if ($this->performed) {
      throw new ActionPerformed('This action has already either rendered or redirected and can not redirect again.');
    } else {
      $this->headers['Location'] = $to;
      $this->performed = true;
    }
  }
}
