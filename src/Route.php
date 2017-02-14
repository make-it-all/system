<?php

class Route {
  public function __construct($path, $to, $via) {
    $this->raw_path = $path;
    $this->path = $this->parse_raw_path($path);
    $this->parse_to($to);
    $this->via = $via;
  }
  public function match($path, $via='GET') {
    if (!in_array($via, $this->via)) {
      return false;
    }
    $params = $this->parse_path($path);
    if ($params === null) {
      return false;
    }

    return ['controller'=>$this->controller, 'action'=>$this->action, 'params'=>$params];
  }

  public function parse_to($to) {
    $parts = explode('#', $to);
    $this->controller = $parts[0];
    $this->action = $parts[1];
  }

  public function parse_path($path) {
    if (!preg_match($this->path, $path, $params)){
      return null;
    }
    foreach ($params as $key => $value) {
      if (is_int($key)) {
        unset($params[$key]);
      }
    }
    return $params;
  }
  public function parse_raw_path($raw) {
    $pattern = '/:(\w+)(\{(.*?[^\\\\])\})?/';
    preg_match_all($pattern, $raw, $matches, PREG_OFFSET_CAPTURE);
    $last_offset = 0;
    $n = sizeof($matches[0]);
    $i=0;
    $path = '';
    while ($i<$n) {
      $start = $matches[0][$i][1];
      $match_len = strlen($matches[0][$i][0]);

      $path .= substr($raw, $last_offset, $start-$last_offset);
      $last_offset = $start + $match_len;

      $name = $matches[1][$i][0];
      $matcher = $matches[3][$i][0] ?? '[^\\/]+';
      $regex = "(?P<$name>$matcher)";

      $path .= $regex;
      $i++;
    }
    $path .= substr($raw, $last_offset);
    $path = "#^$path$#";
    return $path;
  }

}
