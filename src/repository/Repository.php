<?php

require_once __DIR__.'/../../Database.php';
class Repository
{
    protected $database;

    //singleton TODO
    public function __construct(){
        $this->database = new Database();
    }
}