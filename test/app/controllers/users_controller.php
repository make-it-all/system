<?php

class UsersController extends ApplicationController {

  public function index() {
    $this->users = User::all()->results();
  }

  public function show() {
    $this->user = 'Henry';
  }

  public function new() {
    $this->user = User::new();
  }

  public function create() {
    $this->user = User::new($this->user_params());
    if ($this->user->save()) {
      $this->redirect_to('/users', ['flash' => 'User Created']);
    } else {
      $this->render('new');
    }
  }

  public function edit() {

  }

  private function user_params() {
    return $this->params->require('user')->permit('name', 'email', 'admin', 'terms');
  }

}
