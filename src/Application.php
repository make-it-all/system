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
    self::$paths['public'] = 'public/';
    self::$paths['logs/requests'] = 'logs/requests.log';
  }

  public static function set_default_middleware() {
    //Serve static files
    if (true) {
      Rack::add('\Middleware\StaticFile', self::$paths['public']);
    }

    //Rewrites request method allowing browsers to send put, patch and delete requests
    Rack::add('\Middleware\MethodOverride');

    //logs the request
    $logs_file = self::$paths['logs/requests'];
    Rack::add('\Middleware\Logger', new Application\Logger($logs_file));

    //handles errors for user such as 500 and 404
    Rack::add('\Middleware\ExceptionPresenter');

    Rack::add('Application');
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
