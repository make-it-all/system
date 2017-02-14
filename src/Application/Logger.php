<?php namespace Application;

class Logger {

  private $log = [];
  private $output;

  public function __construct($output) {
    $this->output = $output;
  }

  public function log($msg='') {
    $this->log[] = $msg;
  }

  public function flush() {
    $data = implode(PHP_EOL, $this->log);
    file_put_contents($this->output, $data, FILE_APPEND);
    $this->empty();
  }

  public function empty() {
    $this->log = [];
  }

}
