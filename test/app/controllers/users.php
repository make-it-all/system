<?php

class UsersController extends Controller\Base {

  public $view_folder = 'users';

  public function index() {
    $this->users = ['Henry', 'Bob', 'Robert'];
  }

  public function show() {
    $this->user = 'Henry';
  }

}
