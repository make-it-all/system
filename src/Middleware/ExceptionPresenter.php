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
    $file = $this->template_for_status($wrapper->getHttpStatus());
    $file = "templates/$file.php";

    $locals = [
      'error_type' => $wrapper->getType(),
      'error_message' => $wrapper->getMessage(),
      'traceback' => $wrapper->getTrace(),
      'env' => $env,
      'path' => $env->path,
    ];

    return $this->render_file($file, $locals);
  }


  private function render_file($__path, $locals=[]) {
    extract($locals);
    ob_start();
    require $__path;
    return ob_get_clean();
  }

  public function template_for_status($status) {
    return ['404' => '404', '500'=>'500'][$status] ?? '500';
  }


}
