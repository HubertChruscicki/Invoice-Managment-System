<?php

require_once 'AppController.php';

class DefaultController extends AppController {

    public function index()
    {
        $this->render('main'); //TODO
    }

    public function login()
    {
        $this->render('login');
    }

    public function register()
    {
        $this->render('register');
    }
    public function forgot_password()
    {
        $this->render('forgot-password');
    }
    public function afterlogin()
    {
        $this->render('afterlogin');
    }


}