<?php

class User extends \Chronicle\Base {

  public static $table_name = 'users';

  public function __construct(...$args) {
    parent::__construct(...$args);
    $this->add_attribute('phone_number')->set_initial_value('no phone number');
  }

  public static $validations = [
    'admin' => ['presence' => true],
    'phone_number' => ['presence' => true, 'numericality'=>true],
  ];

}
