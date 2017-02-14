<?php namespace Application;

class Params implements \ArrayAccess, \Iterator {

  private $vars = [];

  public function __construct($request_glob) {
    $this->vars = \Application::$env->request_vars;
    $this->vars = array_merge($request_glob['params']);
    $this->vars['controller'] = $request_glob['controller'];
    $this->vars['action'] = $request_glob['action'];
  }


  //----- ArrayAccess - Methods
  public function offsetExists($key) {
    return isset($this->vars[$key]);
  }
  public function offsetUnset($key) {
    unset($this->vars[$key]);
  }
  public function offsetSet($key, $value) {
    if (is_null($key)) {
      $this->vars[] = $value;
    } else {
      $this->vars[$key] = $value;
    }
  }
  public function offsetGet($key) {
    return isset($this->vars[$key]) ? $this->vars[$key] : null;
  }

  //----- Iterator - Methods
  public function current(){
    $this->load();
    return current($this->vars);
  }
  public function next() {
    $this->load();
    return next($this->vars);
  }
  public function key() {
    $this->load();
    return key($this->vars);
  }
  public function valid() {
    $this->load();
    return $this->offsetExists($this->key());
  }
  public function rewind() {
    $this->load();
    return reset($this->vars);
  }


}
