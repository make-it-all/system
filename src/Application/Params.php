<?php namespace Application;

class Params implements \ArrayAccess, \Iterator {

  private $vars = [];

  public function __construct($request_glob, $from_array=false) {
    if ($from_array) {
      $this->vars = $request_glob;
    } else {
      $this->vars = \Application::$request->request_vars;
      $this->vars = array_merge($this->vars, $request_glob['params']);
      $this->vars['controller'] = $request_glob['controller'];
      $this->vars['action'] = $request_glob['action'];
    }
  }

  public function require($key) {
    if (array_key_exists($key, $this->vars)) {
      return new self($this->vars[$key], true);
    } else {
      throw new \Error\MissingParam($key);
    }
  }

  public function permit(...$attrs) {
    $attrs = array_flip($attrs);
    $allowed = array_intersect_key($this->vars, $attrs);
    return new self($allowed, true);
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
    return current($this->vars);
  }
  public function next() {
    return next($this->vars);
  }
  public function key() {
    return key($this->vars);
  }
  public function valid() {
    return $this->offsetExists($this->key());
  }
  public function rewind() {
    return reset($this->vars);
  }


}
