<?php namespace Middleware;

class MethodOverride {

  const METHODS = ['GET', 'POST', 'PUT', 'PATCH', 'DELETE', 'HEAD'];
  const OVERRIDE_PARAM_KEY = "__method";


  function __construct($app) {
    $this->app = $app;
  }

  function call($env) {
    if (isset($env->request[self::OVERRIDE_PARAM_KEY])) {
      $override = $env->request[self::OVERRIDE_PARAM_KEY];
      $override = strtoupper($override);
      if (in_array($override, self::METHODS)) {
        $env->set('method_override.original', $env->method);
        $env->method = $override;
      }
    }
    return $this->app->call($env);
  }

}
