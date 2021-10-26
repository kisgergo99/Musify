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
        $this->database = new Database();
    }

    public function checkCaptcha($userInput){
		if($_SESSION["captcha_for_user"] == $userInput){
			return true;
		}else{
			return false;
		}
	}

    public function createLoginSession(string $username){
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

    public function getLastname(){
        if($this->isLoggedIn()){
            if(!isset($_SESSION['user']['lastname'])){
                $_SESSION['user']['lastname'] = $this->database->getLastname($_SESSION['user']['username']);
                return $_SESSION['user']['lastname'];
            }else{
                return $_SESSION['user']['lastname'];
            }
            
        }
    }

    public function dropSession(){
        unset($_SESSION['user']);
    }

    public function getUserByEmail($email){
		return $this->database->getUserCredentials($email);
	}

}

?>