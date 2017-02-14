<?php namespace Application;

class View {

  private $layout;

  public function setLayout($layout=false) {
    $this->layout = $layout;
  }

  public function render() {
    if (isset($this->layout)) {
      
    }
  }

}
