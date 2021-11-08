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



    /* -- USER -- */

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

    public function getLastname($username){
        $stmt = $this->mysqli->prepare("SELECT user_lastname FROM users WHERE username=?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();
        while($row = mysqli_fetch_array($result)){
            if($result->num_rows > 0){
                return $this->filter($row['user_lastname']);
            }else{
                return "UNKNOWN";
            }
        }
    }

    public function isEmailRegistered($email){
        $stmt = $this->mysqli->prepare("SELECT user_email FROM users WHERE user_email=?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        while($row = mysqli_fetch_array($result)){
            if($result->num_rows > 0){
                return true;
            }else{
                return false;
            }
        }
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






    /* -- MUSIC -- */

    public function getMusicList(){
        $stmt = $this->mysqli->prepare("SELECT music_id, music_artist_name, music_track_name, music_path, music_artwork_path FROM music WHERE music_status=1");
        $stmt->execute();
        $result = $stmt->get_result();
        while($row = mysqli_fetch_array($result)){
            if($result->num_rows > 0){
                $returnArray[$row['music_id']] = array(
                    "music_id" => $this->filter($row['music_id']),
                    "music_artist_name" => $this->filter($row['music_artist_name']),
                    "music_track_name" => $this->filter($row['music_track_name']),
                    "music_path" => $this->filter($row['music_path']),
                    "music_artwork_path" => $this->filter($row['music_artwork_path']),
                );
            }
        }

        if(isset($returnArray)){
            return $returnArray;
        }else{
            return $returnArray = array();
        };
    }

    public function searchMusic($musicid, $musicpath){
        $stmt = $this->mysqli->prepare("SELECT music_id, music_artist_name, music_track_name, music_artwork_path FROM music WHERE music_id=? AND music_path=?");
        $stmt->bind_param("is", $musicid, $musicpath);
        $stmt->execute();
        $result = $stmt->get_result();
        while($row = mysqli_fetch_array($result)){
            if($result->num_rows > 0 && $row['music_id'] == $musicid){
               return $returnArray = array(
                "music_artist_name" => $this->filter($row['music_artist_name']),
                "music_track_name" => $this->filter($row['music_track_name']),
                "music_artwork_path" => $this->filter($row['music_artwork_path']),
               );
            }else{
                return $returnArray = array();
            }
        }
    }

    public function getLatestAlbums($limit){
        $stmt = $this->mysqli->prepare("SELECT * FROM albums ORDER BY album_release_date DESC LIMIT $limit");
        $stmt->execute();
        $result = $stmt->get_result();
        $i=0;
        while($row = mysqli_fetch_array($result)){
            if($result->num_rows > 0){
                $returnArray[$i] = array(
                    "album_id" => $this->filter($row['album_id']),
                    "album_artist_name" => $this->filter($row['album_artist_name']),
                    "album_name" => $this->filter($row['album_name']),
                    "album_artwork_path" => $this->filter($row['album_artwork_path']),
                    "album_release_date" => $this->filter($row['album_release_date']),
                    "album_distributed_by" => $this->filter($row['album_distributed_by']),
                );
            }
            $i++;
        }

        if(isset($returnArray)){
            return $returnArray;
        }else{
            return $returnArray = array();
        };
    }

    public function getAlbumInfo($albumId){
        $stmt = $this->mysqli->prepare("SELECT * FROM albums, music WHERE music.album_id=? AND albums.album_id=? AND music_status=1");
        $id = $albumId;
        $stmt->bind_param("ii", $id, $id);
        $stmt->execute();
        $result = $stmt->get_result();
        $i = 0;
        while($row = mysqli_fetch_array($result)){
            if($result->num_rows > 0){
                $returnArray[$i] = array(
                    "album_id" => $this->filter($row['album_id']),
                    "album_artist_name" => $this->filter($row['album_artist_name']),
                    "album_name" => $this->filter($row['album_name']),
                    "album_artwork_path" => $this->filter($row['album_artwork_path']),
                    "album_release_date" => $this->filter($row['album_release_date']),
                    "album_distributed_by" => $this->filter($row['album_distributed_by']),
                    "music_id" => $this->filter($row['music_id']),
                    "music_artist_name" => $this->filter($row['music_artist_name']),
                    "music_track_name" => $this->filter($row['music_track_name']),
                    "music_path" => $this->filter($row['music_path']),
                    "music_artwork_path" => $this->filter($row['music_artwork_path']),
                );
                $i++;
            }
        }

        if(isset($returnArray)){
            return $returnArray;
        }else{
            return $returnArray = array();
        };
    }

    public function searchByKey($key){
        $key = "%".$this->filter($key)."%";
        $stmt = $this->mysqli->prepare("SELECT * FROM albums WHERE albums.album_name LIKE ? OR albums.album_artist_name LIKE ?");
        $stmt->bind_param("ss",$key,$key);
        $stmtMusic = $this->mysqli->prepare("SELECT * FROM music WHERE music_track_name LIKE ? or music_artist_name LIKE ?");
        $stmtMusic->bind_param("ss",$key,$key);
        $stmt->execute();
        
        $result = $stmt->get_result();
        $i = 0;
        while($row = mysqli_fetch_array($result)){
            if($result->num_rows > 0){
               $returnAlbums[$i] = array(
                "album_id" => $this->filter($row['album_id']),
                "album_artist_name" => $this->filter($row['album_artist_name']),
                "album_name" => $this->filter($row['album_name']),
                "album_artwork_path" => $this->filter($row['album_artwork_path']),
                "album_release_date" => $this->filter($row['album_release_date']),
                "album_distributed_by" => $this->filter($row['album_distributed_by']),

               );
               $i++;
            }else{
                $returnAlbums = "No Music";
            }
        }
        $stmt->close();
        $stmtMusic->execute();
        $resultMusic = $stmtMusic->get_result();
        $a = 0;
        while($rowM = mysqli_fetch_array($resultMusic)){
            if($resultMusic->num_rows > 0){
               $returnMusic[$a] = array(
                "music_id" => $this->filter($rowM['music_id']),
                "music_artist_name" => $this->filter($rowM['music_artist_name']),
                "music_track_name" => $this->filter($rowM['music_track_name']),
                "music_path" => $this->filter($rowM['music_path']),
                "music_artwork_path" => $this->filter($rowM['music_artwork_path']),

               );
               $a++;
            }else{
                $returnMusic = "No music";
            }
        }
        
        return $returnArray = array(
            "music" => $returnMusic,
            "album" => $returnAlbums,
        );
    }




    /* -- OTHERS -- */

    public function closeConn(){
        $this->mysqli->close();
    }

    private function generateUsername($firstname, $lastname){
        $userNamesList = array();
        $firstChar = str_split($firstname, 1)[0];
        $firstTwoChar = str_split($firstname, 2)[0];
        $numSufix = explode('-', date('Y-m-d-H')); 

        array_push($userNamesList, 
            $firstname, 
            $lastname,        
            $firstname.$lastname,      
            $firstname.'.'.$lastname,  
            $firstname.'-'.$lastname,  
            $firstChar.$lastname,      
            $firstTwoChar.$lastname, 
            $firstname.$numSufix[0], 
            $firstname.$numSufix[1],   
            $firstname.$numSufix[2],   
            $firstname.$numSufix[3]     
        );


        $isAvailable = false; 
        $index = 0;
        $maxIndex = count($userNamesList) - 1;

        do {
            $availableUserName = $userNamesList[$index];
            $isAvailable = $this->isAvailable($availableUserName);
            $limit =  $index >= $maxIndex;
            $index += 1;
            if($limit){
                break;
            }
        } while (!$isAvailable );

        if(!$isAvailable){
            return $firstname.rand();
        }
        return $availableUserName;
    }

    private function isAvailable($userName){
        $result = $this->mysqli->query("SELECT user_id FROM users WHERE username='$userName'") or die($this->mysqli->error());
    
        if ( $result->num_rows > 0 ) {
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