<?php

class UsersController extends ApplicationController {

  public $view_folder = 'users';

  public function index() {
    $this->users = ['Henry', 'Bob', 'Robert'];
  }

  public function show() {
    $this->user = 'Henry';
  }

  public function new() {
    new User();
    $this->x = 2;
  }

  public function edit() {

  }

}
