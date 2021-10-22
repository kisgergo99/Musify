<?php
ob_start();
session_start();


class Usertype{
    private $type;
    private $username;
    private $valid;
    private $database;

    public function __construct(){
        $this->valid = false;
        $this->type = "GUEST";
        $this->username = "";
    }

    public function createLoginSession(string $username){
        $userdata = array();
        $_SESSION['user']['username'] = $username;
        $this->username = $username;
        $_SESSION['user']['type'] = 'user';
    }

    public function isLoggedIn(){
        if(isset($_SESSION['user'])){
            return true;
        }else{
            return false;
        }
    }

    public function getUsername(){
        if(isset($_SESSION['user']) && isset($_SESSION['user']['username'])){
            return $_SESSION['user']['username'];
        }
    }

    public function dropSession(){
        unset($_SESSION['user']);
    }

}

?>