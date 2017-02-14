<?php namespace Middleware;

class Head {

  public function __construct($app) {
    $this->app = $app;
  }

  public function call($env) {
    if ($env->method == 'HEAD') {
      $env->method = 'GET';
      list($status, $headers, $body) = $this->app->call($env);
      return [$status, $headers, []];
    } else {
      $env->method = 'GET';
      return $this->app->call($env);
    }
  }
  
}
