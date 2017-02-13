<?php


class Application {

  public static $paths = [];
  public static $router;

  public static function run() {
    self::set_default_paths();
    self::set_default_middleware();
    self::setup_chronicle();
    self::get_router()->parse_file(self::$paths['routes']);

    Rack::run();
  }

  public function call($env) {
    $routes = self::get_router()->match_path($env->path, $env->method);
    foreach ($routes as $route) {
      return $this->dispatch($route);
    }
    return [404, [], []];
  }

  public static function set_default_paths() {
    self::$paths['controllers'] = 'app/controllers';
    self::$paths['views'] = 'app/views';
    self::$paths['models'] = 'app/models';
    self::$paths['routes'] = 'config/routes.php';
    self::$paths['config/database'] = 'config/database.php';
  }

  public static function set_default_middleware() {
    //Rewrites request method allowing browsers to send put, patch and delete requests
    Rack::add('\Middleware\MethodOverride');

    Rack::add('Application');
  }

  public static function setup_chronicle() {
    $config = (function(){
      require Application::$paths['config/database'];
      return get_defined_vars();
    })();
    Chronicle\Base::setup_connection($config);
  }

  public static function get_router() {
    if (self::$router === null) {
      self::$router = new Router;
    }
    return self::$router;
  }

  public static function dispatch($request_glob) {
    $controller_path = self::$paths['controllers'];
    $controller_file = $request_glob['controller'];
    $controller_name = ucfirst($controller_file).'Controller';
    require_once "$controller_path/$controller_file.php";
    $controller = new $controller_name;
    return $controller->process($request_glob);
  }

}
