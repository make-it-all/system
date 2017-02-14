<?php namespace Middleware;

class Flash {

  public function __construct($app) {
    $this->app = $app;
  }

  public function call($env) {

    //TODO handle flash sessions
    return $this->app->call($env);
  }

}
