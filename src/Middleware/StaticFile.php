<?php namespace Middleware;

//Serves static assets from a given public folder without calling
//further down in the stack
class StaticFile {

 //gets the route to the public file directory
  public function __construct($app, $root) {
    $this->app = $app;
    $this->root = $root;
  }
  //checks for a file in the public directory
  public function call($env) {
    $path = $this->root . $env->path;
    //checks that the path isn't a dir and is a file
    if (!is_dir($path) && file_exists($path)) {

      $content_type = mime_content_type($path);
      $content_length = filesize($path);
      $content = file_get_contents($path);
      //returns a status of 200 and the contents of the file path
      return [200, ["Content-Type" => $content_type, "Content-Length" => $content_length], [$content]];
    }
    //calls the next stack of middleware if a file is not found
    return $this->app->call($env);
  }

}
