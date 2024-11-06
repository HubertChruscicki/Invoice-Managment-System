<?php

require_once 'src/controllers/DefaultController.php';

class Router{
    
    public static $routes;

    public static function get($url, $view){
        self::$routes[$url] = $view;
    }

    public static function run($url){
        $action = explode("/", $url)[0];
        if (!array_key_exists($action, self::$routes)){
            die("Wrong url!");
        }
        $controllerName = self::$routes[$action];
        $controller = new $controllerName();
        $method = $action ?: 'index';
    
        $controller->$method();
    }

}