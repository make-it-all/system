<?php namespace Application;

class View {

  private $controller;
  private $action;

  public function __construct($controller) {
    $this->controller = $controller;
  }

  public function __render_action($action, $locals=[]) {
    $this->action = $action;
    $this->locals = $locals;
    if (isset($this->controller->layout)) {
      return $this->__render_layout($this->controller->layout);
    } else {
      return $this->yield();
    }
  }

  private function __render_layout($layout) {
    ob_start();
    extract($this->locals);
    require $this->__to_layout_path($layout);
    return ob_get_clean();
  }

  private function __to_layout_path($layout) {
    return "app/views/layouts/$layout.php";
  }

  private function __to_partial_paths($partial_name) {
    $pos = strrpos($partial_name, '/');
    if ($pos==false) {
      $partial_name = "_$partial_name";
    } else {
      $partial_name = substr_replace($partial_name, '_', $pos+1, 0);
    }
    $partial_name .= '.php';
    return array_map(function($path) use ($partial_name){
      return "app/views/$path/$partial_name";
    }, $this->controller->partial_paths());
  }

  public function yield() {
    if (isset($this->action)) {
      extract($this->locals);
      require 'app/views/'.$this->controller->view_folder().'/'.$this->action.'.php';
    }
  }

  public function render($partial_name, $locals=[]) {
    $partial_paths = $this->__to_partial_paths($partial_name);
    foreach($partial_paths as $path) {
      if (file_exists($path)) {
        extract($locals);
        require $path;
        return;
      }
    }
    throw new \Exception('Partial Not Found');
  }

  public function include_stylesheet($stylesheet) {
    $stylesheet_path = \Application::asset_path('stylesheets', $stylesheet);
    echo "<link rel='stylesheet' type='text/css' href='$stylesheet_path'>";
  }

  public function include_javascript($script) {
    $script_path = \Application::asset_path('javascripts', $script);
    echo "<script src='$script_path'></script>";
  }

  public function link_to($text, $href='#') {
    echo "<a href='$href'>$text</a>";
  }

  public function image_tag($image, $alt=null) {
    $image_path = \Application::asset_path('images', $image);
    $alt = $alt ?? $image;
    echo "<img src='$image_path' alt='$alt' />";
  }

  public function icon($icon) {
    echo "<i class='fa fa-$icon' aria-hidden='true'></i>";
  }

}
