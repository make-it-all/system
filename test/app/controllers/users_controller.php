<?php

class UsersController extends ApplicationController {

  public function index() {
    $this->users = ['Henry', 'Bob', 'Robert'];
    $this->render('new');
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
