<?php namespace Application;

class View {

  private $controller;

  public function __construct($controller) {
    $this->controller = $controller;
  }

  public function render($action, $locals=[]) {
    $this->action = $action;
    $this->locals = $locals;
    if (isset($this->controller->layout)) {
      return $this->render_layout($this->controller->layout);
    } else {
      return $this->render_template();
    }
  }

  public function render_layout($layout) {
    ob_start();
    extract($this->locals);
    require $this->to_layout_path($layout);
    return ob_get_clean();
  }

  public function render_template() {
    require 'app/views/'.$this->controller->to_view_path().'/'.$this->action.'.php';
  }

  public function to_layout_path($layout) {
    return "app/views/layouts/$layout.php";
  }
}
