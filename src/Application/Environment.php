<?php namespace Application;


class UnknownEnvironment extends \Exception {}

class Environment {

  const ENVS = ['development', 'production'];

  private $env;

  public function __construct($env) {
    if (in_array($env, Environment::ENVS)) {
      $this->env = $env;
    } else {
      throw new \Errors\UnknownEnvironment();
    }
  }

  public function is_development() {
    return $this->env=='development';
  }

  public function is_production() {
    return $this->env=='production';
  }

  public function env() {
    return $this->env;
  }

  public function __toString() {
    return $this->env;
  }

}
