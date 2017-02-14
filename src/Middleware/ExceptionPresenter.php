<?php namespace Middleware;

class ExceptionPresenter {

  const FAILSAFE = [500, ['Content-Type' => 'text/plain'], [
      '500 Internal Server Error'
    . 'If you are the system admin then please refer'
    . 'to the logs to see what went wrong.'
    ]];

  function __construct($app) {
    $this->app = $app;
  }

  function call($env) {
    try {
      return $this->app->call($env);
    } catch(\Exception $e) {
      if (true) {
        $wrapper = new ExceptionWrapper($e);
        try {
          return $this->render_exception($env, $e);
        } catch(\Exception $e) {
          return self::FAILSAFE;
        }
      } else {
        throw $e;
      }
    }


  }


}
