<?php

require_once "config.php";


class Database {
    private $username;
    private $database;
    private $password;
    private $server;
    private $host;


    public function __construct () {
        $this -> username = USERNAME;
        $this -> database = DATABASE;
        $this -> password = PASSWORD;
        $this -> server = SERVER;
        $this -> host = HOST;
    }

    public function connect() {

        try {
            $conn = new PDO(
                "pgsql:host=$this->server;port=5432; dbname=$this->database",
                $this->username,
                $this->password,
                //required ssl mode
                ["sslmode"  => "prefer"]
            );


            //allows to display db errors
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            return $conn;
        }
        catch(PDOException $e) {
            die("DB connection failed: " . $e->getMessage());
        }
    }

}
?>

