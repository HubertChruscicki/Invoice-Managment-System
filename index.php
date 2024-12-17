<?php

require 'Routing.php';

$path = trim($_SERVER['REQUEST_URI'], '/');
$path = parse_url($path, PHP_URL_PATH);

Router::get('', 'DefaultController');
Router::get('main', 'DefaultController');
Router::get('user', 'DefaultController');

Router::get('findUserInfo', 'UserInfoController');


Router::get('getCategories', 'CategoryController');
Router::get('howManyCategories', 'CategoryController');
Router::get('deleteCategory', 'CategoryController');
Router::get('addCategory', 'CategoryController');


Router::post('login', 'SecurityController');
Router::get('logout', 'SecurityController');
Router::post('register', 'SecurityController'); //todo zmienione na post sprawdzic
//TODO CHANGEPASS SECURITYCONTROLLER



Router::get('forgot_password', 'DefaultController'); //TODO TEZ PRZEROBIC NA SECURITYCONTROLLER


Router::run($path);

