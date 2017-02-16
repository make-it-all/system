<?php namespace Controller;

class Base {

  const BASE_NAME = 'BaseController';
  private static $view_folder = '';

  protected $headers = [];
  protected $response_body = [];

  protected $performed = false;


  public function process($params) {
    $action = $params['action'];
    $this->params = $params;

    $this->status = 200;
    $this->response_headers = [];
    $this->response_body = null;

    $locals = $this->send_action($action);
    if (!$this->performed) {
      $this->response_body = $this->render_view($params, $locals);
    }
    return [$this->status, $this->response_headers, $this->response_body];
  }

  public function send_action($action) {
    if (method_exists($this, $action)) {
      $pre_vars = get_object_vars($this);
      $this->$action();
      $locals = array_diff_key(get_object_vars($this), $pre_vars);
      return $locals;
    }
  }

  public function render_view($params, $locals) {
    $view = new \Application\View($this);
    return $view->__render_action($params['action'], $locals);
  }

  public static function controller_name() {
    if (get_called_class() == get_class()) {
      return self::BASE_NAME;
    } else {
      return strtolower(substr(get_called_class(), 0, -10));
    }
  }

  public static function view_folder() {
    return isset(static::$view_folder) ? static::$view_folder : static::controller_name();
  }

  public static function partial_paths() {
    $paths = [static::view_folder()];
    foreach(class_parents(get_called_class()) as $parent) {
      $paths[] = $parent::view_folder();
    }
    return $paths;
  }

}














// public function default_render($action, $locals) {
//   try {
//     $this->render($action, $locals);
//   } catch(\Error\FileNotFound $e) {
//     throw new ActionNotFound($this, $action);
//   }
// }
//
// public function render($file, $locals=[]) {
//   if ($this->performed) {
//     throw new ActionPerformed('This action has already either rendered or redirected and can not render again.');
//   } else {
//     $filename = \Application::$paths['views'] . '/' . $this->view_folder . '/' . $file . '.php';
//     if (!file_exists($filename)) {
//       throw new \Error\FileNotFound();
//     }
//     $this->_response_body = (function($__view_file_path) use ($locals){
//       extract($locals);
//       ob_start();
//       require $__view_file_path;
//       return ob_get_clean();
//     })($filename);
//     $this->performed = true;
//   }
// }
//
// public function redirect($to) {
//   if ($this->performed) {
//     throw new ActionPerformed('This action has already either rendered or redirected and can not redirect again.');
//   } else {
//     $this->headers['Location'] = $to;
//     $this->performed = true;
//   }
// }
//
// public function to_view_path() {
//   return strtolower(substr(get_called_class(), 0, -strlen('Controller')));
// }
