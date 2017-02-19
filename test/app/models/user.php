<?php

class User extends \Chronicle\Base {

  public static $table_name = 'users';

  public function __construct(...$args) {
    parent::__construct(...$args);
    $this->add_attribute('terms');
  }

  public static $validations = [
    'name' => ['presence' => true],
    'email' => ['presence' => true],
    'terms' => ['acceptance' => true],
    'admin' => ['presence' => true]
  ];

}
