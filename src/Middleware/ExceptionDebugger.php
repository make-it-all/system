<?php namespace Middleware;

class ExceptionDebugger {

  static $templates = [
    'Error\NoRouteMatches' => 'page_not_found'
  ];

  public function __construct($app) {
    $this->app = $app;
  }

  public function call($env) {
    set_error_handler(function ($errorNumber, $errorText, $errorFile, $errorLine ) {
      throw new \ErrorException($errorText, 0, $errorNumber, $errorFile, $errorLine);
    });

    try {
      return $this->app->call($env);
    } catch(\Exception $e) {
      if (\Application::$config['show_exceptions']) {
        return $this->render_exception($env, $e);
      } else {
        throw $e;
      }
    }

  }

  private function render_exception($env, $exception) {
    $wrapper = new ExceptionWrapper($exception);

    if (\Application::$config['debug_exceptions']) {
      $status = $wrapper->getHttpStatus();
      $body = $this->render_body($env, $wrapper);
      return [$status, [], $body];
    } else {
      throw $exception;
    }
  }

  private function render_body($env, $wrapper) {

    $locals = [
      'error_type' => $wrapper->getType(),
      'error_message' => $wrapper->getMessage(),
      'traceback' => $wrapper->getTrace(),
      'env' => $env,
      'path' => $env->path,
    ];

    $file = $this->to_template_path($wrapper);
    return $this->render_file($file, $locals);
  }


  private function render_file($__path, $locals=[]) {
    extract($locals);
    ob_start();
    require $__path;
    return ob_get_clean();
  }

  public function to_template_path($wrapper) {
    $template = self::$templates[$wrapper->getType()] ?? 'standard';
    return "templates/$template.php";
  }

}
