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
Router::post('addCategory', 'CategoryController');

Router::get('getClients', 'ClientsController');
Router::get('howManyClients', 'ClientsController');
Router::get('deleteClient', 'ClientsController');
Router::post('addClient', 'ClientsController');

Router::get('getProducts', 'ProductsController');
Router::get('howManyProducts', 'ProductsController');
Router::get('deleteProduct', 'ProductsController');
Router::post('addProduct', 'ProductsController'); //tu zmieniane wszystkie metdoy na post


Router::post('login', 'SecurityController');
Router::get('logout', 'SecurityController');
Router::post('register', 'SecurityController'); //todo zmienione na post sprawdzic
//TODO CHANGEPASS SECURITYCONTROLLER



Router::get('forgot_password', 'DefaultController'); //TODO TEZ PRZEROBIC NA SECURITYCONTROLLER


Router::run($path);

