<?php namespace Application;

class View {

  private $controller;
  private $action;

  public function __construct($controller) {
    $this->controller = $controller;
  }

  public function __render_action($action, $locals=[]) {
    $this->action = $action;
    $this->locals = $locals;
    if (isset($this->controller->layout)) {
      return $this->__render_layout($this->controller->layout);
    } else {
      return $this->yield();
    }
  }

  private function __render_layout($layout) {
    ob_start();
    extract($this->locals);
    require $this->__to_layout_path($layout);
    return ob_get_clean();
  }

  private function __to_layout_path($layout) {
    return "app/views/layouts/$layout.php";
  }

  private function __to_partial_paths($partial_name) {
    $pos = strrpos($partial_name, '/');
    if ($pos==false) {
      $partial_name = "_$partial_name";
    } else {
      $partial_name = substr_replace($partial_name, '_', $pos+1, 0);
    }
    $partial_name .= '.php';
    return array_map(function($path) use ($partial_name){
      return "app/views/$path/$partial_name";
    }, $this->controller->partial_paths());
  }

  public function yield() {
    if (isset($this->action)) {
      extract($this->locals);
      require 'app/views/'.$this->controller->view_folder().'/'.$this->action.'.php';
    }
  }

  public function render($partial_name, $locals=[]) {
    $locals = array_merge($this->controller->action_vars(), $locals);
    $partial_paths = $this->__to_partial_paths($partial_name);
    foreach($partial_paths as $path) {
      if (file_exists($path)) {
        extract($locals);
        require $path;
        return;
      }
    }
    throw new Error\MissingPartial('Partial Not Found');
  }

  public function include_stylesheet($stylesheet) {
    $stylesheet_path = \Application::asset_path('stylesheets', $stylesheet);
    echo "<link rel='stylesheet' type='text/css' href='$stylesheet_path'>";
  }

  public function include_javascript($script) {
    $script_path = \Application::asset_path('javascripts', $script);
    echo "<script src='$script_path'></script>";
  }

  public function link_to($text, $href, $attrs=[]) {

    if (array_key_exists('method', $attrs)) {
      $method = $attrs['method'];
      unset($attrs['method']);
    }

    $attr_arr = [];
    foreach ($attrs as $key => $value) {
      $attr_arr[] = "$key='$value'";
    }
    $attr_html = implode(' ', $attr_arr);
    if (isset($method)) {
      return "<a $attr_html href='$href' data-method='$method'>$text</a>";
    } else {
      return "<a $attr_html href='$href'>$text</a>";
    }
  }

  public function image_tag($image, $alt=null) {
    $image_path = \Application::asset_path('images', $image);
    $alt = $alt ?? $image;
    return "<img src='$image_path' alt='$alt' />";
  }

  public function icon($icon) {
    return "<i class='fa fa-$icon' aria-hidden='true'></i>";
  }

  public function i($key, $args=[]) {
    return \Application::I18n($key, $args);
  }


  public function form_for($record, $url, $attrs=[]) {
    $attr_arr = [];
    foreach ($attrs as $key => $value) {
      $attr_arr[] = "$key='$value'";
    }
    $attr_html = implode(' ', $attr_arr);
    echo "<form action='$url' method='POST' $attr_html>";
    if ($record->is_persisted()) {
      $this->hidden_field($record, '__method', 'PUT');
    }
  }

  public function abstract_field($record, $type, $name, $value=null) {
    $record_name = is_string($record) ? $record : strtolower(get_class($record));
    $label_text = ucfirst($name);
    $field_name = $record_name . "[$name]";
    if (is_null($value)) { $value = $record->$name; }
    echo "<div class='field'>";
      echo "<label for='{$name}_field'>$label_text</label>";
      echo "<input type='$type' id='{$name}_field' name='$field_name' value='$value' />";
    echo "</div>";
  }

  public function text_field($record, $name, $value=null) {
    $this->abstract_field($record, 'text', $name, $value);
  }

  public function password_field($record, $name, $value=null) {
    $this->abstract_field($record, 'password', $name, $value);
  }

  public function email_field($record, $name, $value=null) {
    $this->abstract_field($record, 'email', $name, $value);
  }

  public function hidden_field($record, $name, $value=null) {
    $record_name = strtolower(get_class($record));
    $field_name = $record_name . "[$name]";
    if (is_null($value)) { $value = $record->$name; }
    echo "<input type='hidden' name='$field_name' value='$value' />";
  }

  public function checkbox_field($record, $name, $value='1') {
    $record_name = strtolower(get_class($record));
    $field_name = $record_name . "[$name]";
    $field_label = ucfirst($name);
    $checked = $record->$name == true;

    echo "<div class='field'>";
      echo "<label for='{$name}_field'>$field_label</label>";
      echo "<input type='hidden' name='$field_name' value='0' />";
      if ($checked) {
        echo "<input type='checkbox' id='{$name}_field' name='$field_name' value='$value' checked='checked'/>";
      } else {
        echo "<input type='checkbox' id='{$name}_field' name='$field_name' value='$value'/>";
      }
    echo "</div>";
  }

  public function submit_button($text) {
    echo "<input type='submit' name='commit' value='$text' />";
  }

}
