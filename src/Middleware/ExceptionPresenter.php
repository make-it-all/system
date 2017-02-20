<?php namespace Middleware;

class ExceptionPresenter {

  const FAILSAFE = [500, ['Content-Type' => 'text/plain'], [
      "500 Internal Server Error\n"
    . 'If you are the system admin then please refer '
    . 'to the logs to see what went wrong.'
    ]];

  function __construct($app) {
    $this->app = $app;
  }

  function call($env) {
    try {
      return $this->app->call($env);
    } catch(\Exception $e) {
      if (\Application::$config['show_exceptions']) {
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

  private function render_exception($env, $exception) {
    $wrapper = new ExceptionWrapper($exception);

    $this->log($wrapper);

    $status = $wrapper->getHttpStatus();
    $body = $this->render_body($env, $wrapper);
    return [$status, [], $body];
  }

  private function log() {
    #TODO: logging
  }

  private function render_body($env, $wrapper) {
    $status = $wrapper->getHttpStatus();
    $file = "templates/$status.php";

    return $this->render_file($file);
  }


  private function render_file($__path, $locals=[]) {
    extract($locals);
    ob_start();
    require $__path;
    return ob_get_clean();
  }

}
