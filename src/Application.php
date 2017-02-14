<?php

class Application {

  public static $paths = [];
  public static $router;
  public static $env;

  public static function run() {
    self::set_default_paths();
    self::set_autoloader();
    self::set_default_middleware();
    self::setup_chronicle();
    self::get_router()->parse_file(self::$paths['routes']);

    Rack::run();
  }

  public function call($env) {
    self::$env = $env;
    $routes = self::get_router()->match_path($env->path, $env->method);
    foreach ($routes as $route) {
      $params = new Application\Params($route);
      return $this->dispatch($params);
    }
    throw new \Error\NoRouteMatches();
  }

  public static function set_default_paths() {
    self::$paths['controllers'] = 'app/controllers/';
    self::$paths['views'] = 'app/views/';
    self::$paths['models'] = 'app/models/';
    self::$paths['routes'] = 'config/routes.php';
    self::$paths['public'] = 'public/';
    self::$paths['logs/requests'] = 'logs/requests.log';
    self::$paths['config/database'] = 'config/database.php';
  }

  public function set_autoloader() {
    spl_autoload_register(function($class){
      if (substr($class, -10) == 'Controller') {
        require_once self::$paths['controllers'] . self::toUnderscore($class) . '.php';
      } else {
        $model_path = self::$paths['models'] . strtolower($class) . '.php';
        if (file_exists($model_path)) {
          require_once $model_path;
        }
      }
    });
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

    //shows a debug screen when an exception is thrown
    if (true) {
      Rack::add('\Middleware\ExceptionDebugger');
    }

    //manages flash sessions to expire on page refresh
    Rack::add('\Middleware\Flash');

    //strips body on head request
    Rack::add('\Middleware\Head');

    //processes routes and outputs body
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

  public static function dispatch($params) {
    $controller_class = ucfirst($params['controller']).'Controller';
    $controller = new $controller_class;
    return $controller->process($params);
  }

  public static function toUnderscore($str) {
    $str = preg_replace('([A-Z]+)', '_$0', $str);
    $str = strtolower($str);
    $str = trim($str, '_');
    return $str;
  }

}
