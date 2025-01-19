<?php

require 'Routing.php';

$path = trim($_SERVER['REQUEST_URI'], '/');
$path = parse_url($path, PHP_URL_PATH);

Router::get('', 'DefaultController');
Router::get('index', 'DefaultController');
Router::get('main', 'DefaultController');
Router::get('user', 'DefaultController');

Router::get('findUserInfo', 'UserInfoController');
Router::get('getUserPasswordLength', 'UserInfoController');
Router::get('getUsers', 'UserInfoController');
Router::get('howManyUsers', 'UserInfoController');
Router::post('deleteUser', 'UserInfoController');


Router::get('getCategories', 'CategoryController');
Router::get('howManyCategories', 'CategoryController');
Router::post('deleteCategory', 'CategoryController');
Router::post('addCategory', 'CategoryController');

Router::get('getClients', 'ClientsController');
Router::get('howManyClients', 'ClientsController');
Router::post('deleteClient', 'ClientsController');
Router::post('addClient', 'ClientsController');

Router::get('getProducts', 'ProductsController');
Router::get('howManyProducts', 'ProductsController');
Router::post('deleteProduct', 'ProductsController');
Router::post('addProduct', 'ProductsController');


Router::get('getInvoices', 'InvoicesController');
Router::get('howManyInvoices', 'InvoicesController');
Router::post('deleteInvoice', 'InvoicesController');
Router::post('addInvoice', 'InvoicesController');
Router::get('getInvoiceDetails', 'InvoicesController');
Router::get('generateInvoicePDF', 'InvoicesController');


Router::post('login', 'SecurityController');
Router::get('logout', 'SecurityController');
Router::post('register', 'SecurityController'); //todo zmienione na post sprawdzic
Router::post('registerUser', 'SecurityController'); //todo zmienione na post sprawdzic
//TODO CHANGEPASS SECURITYCONTROLLER



Router::get('forgot_password', 'DefaultController'); //TODO TEZ PRZEROBIC NA SECURITYCONTROLLER


Router::run($path);

