<?php


class Application {

  public static $paths = [];
  public static $router;

  public static function run() {
    self::set_default_paths();
    self::set_default_middleware();
    self::get_router()->parse_file(self::$paths['routes']);

    Rack::run();
  }

  public function call($env) {
    var_dump(self::get_router()->routes[1]->match('/users/123/asd'));

    $routes = self::get_router()->match_path($env->path, $env->method);
    foreach ($routes as $route) {
      var_dump($route);
    }
    return [404, [], []];
  }

  public static function set_default_paths() {
    self::$paths['controllers'] = 'app/controllers';
    self::$paths['views'] = 'app/views';
    self::$paths['models'] = 'app/models';
    self::$paths['routes'] = 'config/routes.php';
  }

  public static function set_default_middleware() {
    //Rewrites request method allowing browsers to send put, patch and delete requests
    Rack::add('\Middleware\MethodOverride');

    Rack::add('Application');
  }

  public function get_router() {
    if (self::$router === null) {
      self::$router = new Router;
    }
    return self::$router;
  }

}
