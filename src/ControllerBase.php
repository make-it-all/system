<?php namespace Controller;

class Base {

  protected $headers = [];
  protected $response_body = [];

  protected $performed = false;

  public function render($file) {
    if ($this->performed) {
      throw new ActionPerformed('This action has already either rendered or redirected and can not render again.');
    } else {
      //TODO: RENDER ACTION
      $filename = Application::$view_root . '/' . $this->view_folder . '/' . $file . '.php';
      require $filename;
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
