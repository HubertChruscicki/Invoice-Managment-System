<?php

require_once 'src/controllers/DefaultController.php';
require_once 'src/controllers/SecurityController.php';
require_once 'src/controllers/UserInfoController.php';
require_once 'src/controllers/CategoryController.php';

class Router{
    
    public static $routes;

    public static function get($url, $view){
        self::$routes[$url] = $view;
    }

    public static function post($url, $view){
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