<?php

require 'Routing.php';

$path = trim($_SERVER['REQUEST_URI'], '/');
$path = parse_url($path, PHP_URL_PATH);

Router::get('', 'DefaultController');
Router::post('login', 'SecurityController');
Router::get('logout', 'SecurityController');
Router::get('afterlogin', 'DefaultController');
Router::get('register', 'SecurityController');
Router::get('forgot_password', 'DefaultController');


Router::run($path);

