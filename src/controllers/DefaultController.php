<?php

require_once 'AppController.php';

class DefaultController extends AppController {

//    public function index()
//    {
//        $this->render('login'); //TODO
//    }
//
//    public function login()
//    {
//        $this->render('login');
//    }
//
//    public function register()
//    {
//        $this->render('register');
//    }
//    public function forgot_password()
//    {
//        $this->render('forgot-password');
//    }

    public function index()
    {
        if(!isset($_SESSION['id'])) //TODO ZOBACZY CZY NIE EMAIL
        {
            $this->render('login');
            return;
        }
        $this->render('main'); //TODO pewnie dashboard

    }

    public function user()
    {
        if(!isset($_SESSION['id'])) //TODO ZOBACZY CZY NIE EMAIL
        {
            $this->render('login');
            return;
        }
        $this->render('user');
    }
    public function main() {
        if (!isset($_SESSION['id'])) {
            $this->render('login');
            return;
        }
//        $this->render('main');
    }

}