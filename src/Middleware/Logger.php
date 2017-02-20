<?php namespace Middleware;

//Logs information about requests
class Logger {

  private $app;
  private $logger;

  public function __construct($app, $logger) {
    $this->app = $app;
    $this->logger = $logger;
  }
  //records the time, date, request method, request path, server address for
  //the request
  public function call($env) {
    $method = $env->method;
    $path = $env->path;
    $server = $env->server_name;
    $start_time = microtime(true);
    $at = date('r', $start_time);
    $this->logger->log("Begin $method to $path on $server at $at");

    list($status, $_, $_) = $response = $this->app->call($env);

    //returns the status and duration of the request
    $duration = microtime(true) - $start_time;
    $duration = $duration*1000;
    $duration = round($duration, 3, PHP_ROUND_HALF_UP);
    $this->logger->log("Finish $status in {$duration}ms");
    $this->logger->log();
    $this->logger->flush();
    return $response;
  }

}
