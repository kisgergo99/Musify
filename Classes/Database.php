<?php
include("configuration.php");


class Database{
    private $mysqli = NULL;
    const HOST = HOST;
    const USER = USER;
    const PASS = PASS;
    const DBNAME = DBNAME;

    public function __construct(){
        $this->mysqli = new mysqli(self::HOST, self::USER, self::PASS, self::DBNAME);

        if ($this->mysqli->connect_errno) {
            echo "Hiba a kapcsolódásban: " . $this->mysqli->connect_error;
            exit();
        }else{
            mysqli_query($this->mysqli,"SET character_set_results=utf8");
            mysqli_query($this->mysqli,"set names 'utf8'");

        }
    }
}