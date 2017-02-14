<?php namespace Middleware;

class ExceptionWrapper {

  private static $exception_status_codes = [
    'Error\NoRouteMatches' => 'page_not_found'
  ];

  private $traceback;

  public function __construct($exception) {
    $this->exception = $exception;
  }

  public function getHttpStatus() {
    if (array_key_exists($this->getType(), self::$exception_status_codes)) {
      $status = self::$exception_status_codes[$this->getType()];
    }
    return $status ?? 500;
  }

  public function getMessage() {
    return $this->exception->getMessage();
  }

  public function getType() {
    return get_class($this->exception);
  }

  public function getTrace() {
    if ($this->traceback == null) {
      $this->traceback = array_map(function($trace){
        if (substr($trace['function'], -strlen('{closure}')) === '{closure}') {
          $trace['class'] = 'closure in '.$trace['class'];
        }

        return array_merge([
          'file' => null,
          'line' => null,
          'function' => null,
          'class' => 'PHP Internal',
          'type' => null,
          'args' => []
        ], $trace);
      }, $this->exception->getTrace());
    }
    return $this->traceback;
  }

  public function to_template_path() {
    if (array_key_exists($this->getType(), self::$exception_status_codes)) {
      $template = self::$exception_status_codes[$this->getType()];
    }
    $template = $template ?? 'standard';
    return "templates/$template.php";
  }

  public function __call($method, $args) {
    return $this->exception->$method(...$args);
  }
}
