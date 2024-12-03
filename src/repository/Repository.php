<?php

require_once __DIR__.'/../../Database.php';
class Repository
{
    protected static $instance = null;
    protected $database;
    protected function __construct()
    {
        $this->database = new Database();
    }
    public static function getInstance(): Repository
    {
        if(!isset(self::$instance))
        {
            self::$instance = new Repository();
        }
        return self::$instance;
    }


    private function __clone() {}

    public function __wakeup()
    {
        throw new Exception("Cannot unserialize a singleton.");
    }
}