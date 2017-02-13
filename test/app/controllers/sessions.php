<?php

class SessionsController extends Controller\Base {

  public $view_folder = 'sessions';

  public function new() {
    $this->user = 'Bob';
    $this->title = 'LOGIN TO THE SISTYE;';
    $this->render('my_page');
  }

}
