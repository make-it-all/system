<?php

class ProblemsController extends Controller\Base {

  public $view_folder = 'problems';

  public function new() {
    $this->render('index');
  }

  public function index(){
    throw new Exception();

  }

}
