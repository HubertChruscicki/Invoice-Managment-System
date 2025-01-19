<?php

require_once 'AppController.php';

class DefaultController extends AppController {


    public function index()
    {
        if(!isset($_SESSION['id']))
        {
            $this->render('login');
            return;
        }
        $this->render('main');

    }

    public function user()
    {
        if(!isset($_SESSION['id']))
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
    }

}