<?php

class Application {

  public static $paths = [];
  private static $router;
  private static $env;
  public static $request;
  public static $config;
  public static $i18n;

  public static function run() {
    self::set_default_paths();
    self::set_autoloader();

    //loads configuration
    self::load_config();

    //add default middleware
    self::set_default_middleware();

    self::setup_chronicle();
    self::get_router()->parse_file(self::$paths['routes']);

    //run application
    Rack::run();
  }

  public function call($request) {
    self::$request = $request;
    $routes = self::get_router()->match_path($request->path, $request->method);
    foreach ($routes as $route) {
      $params = new Application\Params($route);
      return $this->dispatch($params);
    }
    throw new Application\Error\NoRouteMatches();
  }

  public static function set_default_paths() {
    self::$paths['controllers'] = 'app/controllers/';
    self::$paths['views'] = 'app/views/';
    self::$paths['models'] = 'app/models/';
    self::$paths['routes'] = 'config/routes.php';
    self::$paths['public'] = 'public/';
    self::$paths['logs/requests'] = 'logs/requests.log';
    self::$paths['config'] = 'config/environment/';
    self::$paths['config/env_file'] = 'config/environment.txt';
    self::$paths['config/i18n'] = 'config/i18n/';
  }

  public static function set_autoloader() {
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
    if (static::$config['serve_static_assets']) {
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
    if (static::env()->is_development()) {
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
    Chronicle\Base::setup_connection(self::$config['database']);
  }

  public static function load_config() {
    self::$config = (function(){
      require Application::$paths['config'].Application::env().'.php';
      return get_defined_vars();
    })();
  }

  public static function get_router() {
    if (self::$router === null) {
      self::$router = new \Application\Router;
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

  public static function env() {
    if (file_exists(Application::$paths['config/env_file'])) {
      $env_name = file_get_contents(Application::$paths['config/env_file']);
      $env_name = trim($env_name);
    }
    if (self::$env == null) { self::$env = new Application\Environment($env_name ?? $_ENV['system_env'] ?? 'development'); }
    return self::$env;
  }

  public static function set_environment($env) {
    self::$env = new Application\Environment($env);
  }

  public static function asset_path($type, $name) {
    $dir = "public/assets/$type/";
    if (is_dir($dir)) {
      foreach (scandir($dir) as $file) {
        if ($file == '.' || $file == '..') { continue; }
        $parts = explode('.', $file);
        $file_ext = array_pop($parts);
        $file_name = implode('_', $parts);
        if ($file_name == $name) {
          return "/assets/$type/$file";
        }
      }
    }
    throw new \Application\Error\AssetNotFound("$name, $type");
  }

  private static function language() {
    return self::$config['language'];
  }

  private static function load_i18n() {
    $language = self::language();
    require self::$paths['config/i18n']."$language.php";
    self::$i18n = get_defined_vars();
  }

  public static function I18n($key, $args) {
    if (self::$i18n === null) {
      self::load_i18n();
    }
    $steps = explode('.', $key);
    $current = self::$i18n;
    foreach($steps as $step) {
      if (array_key_exists($step, $current)) {
        $current = $current[$step];
      } else {
        return $i18n['no_translation_available'] ?? 'no translation';
      }
    }
    if (is_string($current) && !empty($args)) {
      foreach($args as $key => $value) {
        $current = preg_replace("/\{\{$key\}\}/", $value, $current);
      }
    }
    return $current;
  }

}
