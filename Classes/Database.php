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
            die();
        }else{
            mysqli_query($this->mysqli,"SET character_set_results=utf8");
            mysqli_query($this->mysqli,"set names 'utf8'");
        }
    }

    public function getUserCredentials($email){
        $stmt = $this->mysqli->prepare("SELECT user_id, username, user_email, user_password, user_type FROM users WHERE user_email=?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        while($row = mysqli_fetch_array($result)){
            if($result->num_rows > 0){
                $userArray = array(
                    "user_id" => $this->filter($row['user_id']),
                    "username" => $this->filter($row['username']),
                    "user_email" => $this->filter($row['user_email']),
                    "user_password" => $this->filter($row['user_password']),
                    "user_type" => $row['user_type'],
                );
            }
        }
        if(isset($userArray)){
            return $userArray;
        }else{
            return $userArray = array();
        };
        $stmt->close();
    }

    public function createUserInDB($user_email, $firstname, $lastname, $hashedpass, $terms, $sub){
        $stmt = $this->mysqli->prepare("INSERT INTO users (username, user_password, user_email, user_firstname, user_lastname, user_subscription_status, user_subscription_expiredate, user_type) 
        VALUES (?,?,?,?,?,?,?,?)");
        if($sub == "on"){
            //Now + 30 days
            $subExpire = date("Y-m-d", time() + 2592000);
            $sub = 1;
        }else{
            $subExpire = NULL;
            $sub = 0;
        }
        $username = $this->generateUsername($firstname, $lastname);
        $usertype = 0; 

        $stmt->bind_param("sssssisi", $username, $hashedpass, $user_email, $firstname, $lastname, $sub, $subExpire, $usertype);
        if($stmt->execute()){
            return true;
        }else{
            return false;
        }
        $stmt->close();
    }

    public function closeConn(){
        $this->mysqli->close();
    }

    private function generateUsername($firstname, $lastname){
    $userNamesList = array();
    $firstChar = str_split($firstname, 1)[0];
    $firstTwoChar = str_split($firstname, 2)[0];
    /**
     * an array of numbers that may be used as suffix for the user names index 0 would be the year
     * and index 1, 2 and 3 would be month, day and hour respectively.
     */
    $numSufix = explode('-', date('Y-m-d-H')); 

    // create an array of nice possible user names from the first name and last name
    array_push($userNamesList, 
        $firstname,                 //james
        $lastname,                 // oduro
        $firstname.$lastname,       //jamesoduro
        $firstname.'.'.$lastname,   //james.oduro
        $firstname.'-'.$lastname,   //james-oduro
        $firstChar.$lastname,       //joduro
        $firstTwoChar.$lastname,    //jaoduro,
        $firstname.$numSufix[0],    //james2019
        $firstname.$numSufix[1],    //james12 i.e the month of reg
        $firstname.$numSufix[2],    //james28 i.e the day of reg
        $firstname.$numSufix[3]     //james13 i.e the hour of day of reg
    );


    $isAvailable = false; //initialize available with false
    $index = 0;
    $maxIndex = count($userNamesList) - 1;

    // loop through all the userNameList and find the one that is available
    do {
        $availableUserName = $userNamesList[$index];
        $isAvailable = $this->isAvailable($availableUserName);
        $limit =  $index >= $maxIndex;
        $index += 1;
        if($limit){
            break;
        }
    } while (!$isAvailable );

    // if all of them is not available concatenate the first name with the user unique id from the database
    // Since no two rows can have the same id. this will sure give a unique username
    if(!$isAvailable){
        return $firstname.rand();
    }
    return $availableUserName;
}

    private function isAvailable($userName){
        $result = $this->mysqli->query("SELECT user_id FROM users WHERE username='$userName'") or die($this->mysqli->error());
    
        // We know username exists if the rows returned are more than 0
        if ( $result->num_rows > 0 ) {
             //echo 'User with this username already exists!';
             return false;
        }else{
            return true;
        }
    }

    private function filter($value){
        $value = htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
        $value = $this->mysqli->real_escape_string($value);
        return $value;
    }
}