<?php namespace Controller;

class Base {

  const BASE_NAME = 'BaseController';
  private static $view_folder = '';
  private static $allowed_vars = ['params'];

  private $pre_action_vars;
  private $action_vars;

  protected $params;

  protected $status;
  protected $response_headers = [];
  protected $response_body = [];

  protected $performed = false;



  public function process($params) {
    $action = $params['action'];
    $template = $params['action'];

    $this->params = $params;

    $this->status = 200;
    $this->response_headers = [];
    $this->response_body = null;

    $this->pre_action_vars = get_object_vars($this);
    $this->send_before_action($action);
    if (!$this->performed) {
      $this->send_action($action);
    }
    if (!$this->performed) {
      $this->response_body = $this->render_view($template, $this->action_vars());
    }
    return [$this->status, $this->response_headers, $this->response_body];
  }

  public function send_before_action($action) {
    if (method_exists($this, 'before_action')) {
      $this->before_action($action);
    }
  }

  public function send_action($action) {
    if (method_exists($this, $action)) {
      $this->$action();
    }
  }

  public function action_vars() {
    if (is_null($this->action_vars)) {
      $this->action_vars = [];
      if (isset($this->pre_action_vars)) {
        $locals = array_diff_key(get_object_vars($this), $this->pre_action_vars);
      }
      $allowed = array_intersect_key(get_object_vars($this), array_flip(self::$allowed_vars));
      $this->action_vars = array_merge($locals ?? [], $allowed);
    }
    return $this->action_vars;
  }

  public function render_view($template, $locals) {
    $view = new \Application\View($this);
    return $view->__render_action($template, $locals);
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

  public function render($template) {
    if ($this->performed) {
      throw new \Application\Error\ActionPerformed('This action has already either rendered or redirected and can not render again.');
    }
    $this->response_body = $this->render_view($template, $this->action_vars());
    $this->performed = true;
  }

  public function redirect_to($to, $flash=[]) {
    if ($this->performed) {
      throw new \Application\Error\ActionPerformed('This action has already either rendered or redirected and can not redirect again.');
    }
    $this->response_headers['Location'] = $to;
    $this->performed = true;
  }

}
