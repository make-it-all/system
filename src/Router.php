<?php

class Router {

  public $routes = [];

  public function root($to) {
    return $this->match('/', $to, ['GET']);
  }
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
    $via = (array)$via;
    $path = $this->normalize_path($path);
    $this->routes[] = new Route($path, $to, $via);
  }
  public function resources($resource_name, $opts=[]) {
    $only = $opts['only'] ?? null;
    $except = $opts['except'] ?? null;
    $base_routes = [
      'index'=>["/$resource_name", "$resource_name#index", 'GET'],
      'show'=>["/$resource_name/:id", "$resource_name#show", 'GET'],
      'new'=>["/$resource_name/new", "$resource_name#new", 'GET'],
      'create'=>["/$resource_name", "$resource_name#create", 'POST'],
      'edit'=>["/$resource_name/:id/edit", "$resource_name#edit", 'GET'],
      'update'=>["/$resource_name/:id", "$resource_name#update", 'PUT'],
      'destroy'=>["/$resource_name/:id", "$resource_name#destroy", 'DELETE']
    ];
    if ($except !== null) {
      $base_routes = array_diff_key($routes, array_flip($except));
    }
    if ($only !== null) {
      $base_routes = array_intersect_key($routes, array_flip($only));
    }
    foreach($base_routes as $route) {
      call_user_func_array([$this, 'match'], $route);
    }
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
      if ($match !== false) {
        yield $match;
      }
    }
  }
}
