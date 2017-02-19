<?php namespace Middleware;

class ExceptionWrapper {

  private static $exception_status_codes = [
    'Error\ActionNotFound' => '404',
    'Error\ActionPerformed' => '500',
    'Error\AssetNotFound' => '404',
    'Error\ErrorException' => '500',
    'Error\FileNotFound' => '500',
    'Error\MissingParam' => '500',
    'Error\MissingPartial' => '500',
    'Error\NoRouteMatches' => '404',
    'Error\UnknownEnvironment' => '500',
    'Error\ColumnTypeUnknown' => '500',
    'Error\InvalidAttribute' => '500',
    'Error\MethodNotFound' => '500',
    'Error\RecordNotFound' => '404'

  ];

  private $traceback;

  public function __construct($exception) {
    $this->exception = $exception;
  }

  public function getHttpStatus() {
    return self::$exception_status_codes[$this->getType()] ?? 500;
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

  public function __call($method, $args) {
    return $this->exception->$method(...$args);
  }
}
