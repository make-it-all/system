<?php

class Router {

  public $routes = [];

  public function get($path, $to) {
    return $this->match($path, $to, ['GET']);
  }
  public function post($path, $to) {
    return $this->match($path, $to, ['POST']);
  }
  public function put($path, $to) {
    return $this->match($path, $to, ['PUT']);
  }
  public function patch($path, $to) {
    return $this->match($path, $to, ['PATCH']);
  }
  public function delete($path, $to) {
    return $this->match($path, $to, ['DELETE']);
  }
  public function match($path, $to, $via) {
    $path = $this->normalize_path($path);
    $this->routes[] = new Route($path, $to, $via);
  }


  public function normalize_path($path) {
    $path = trim($path, '/');
    $path = "/$path";
    $path = preg_replace_callback('/%([a-f0-9]{2})/i', function($matches){return strtoupper("$matches[0]");}, $path);
    return $path;
  }
  public function parse_file($file) {
    (function() use ($file){
      $r = $this;
      require $file;
    })();
  }
  public function match_path($path, $method='GET') {
    foreach($this->routes as $route) {
      $match = $route->match($path, $method);
      if ($match != null) {
        yield $match;
      }
    }
  }
}
