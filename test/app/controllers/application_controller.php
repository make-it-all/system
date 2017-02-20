<?php

class ApplicationController extends Controller\Base {
  public $layout = 'application';

  public function before_action($action) {
    if ($this->current_user() == null) {
      $this->redirect_to('/');
    }
  }

  public function current_user() {
    if $_SESSION['uid']
  }

}
