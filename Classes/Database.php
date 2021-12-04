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
            echo "<br>Kérlek módosítsd a Classes/configuration.php fájlt!";
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
                    "user_subscription_status" => $this->filter($row['user_subscription_status']),
                );
            }
        }
        if(isset($userArray)){
            return $userArray;
            $stmt->close();
        }else{
            return $userArray = array();
            $stmt->close();
        };
    }

    public function getUsers(){
        $stmt = $this->mysqli->prepare("SELECT user_id, username, user_email, user_firstname, user_lastname, user_subscription_status, user_subscription_expiredate, user_type, user_distributor_id FROM users");
        $stmt->execute();
        $result = $stmt->get_result();
        $i = 0;
        while($row = mysqli_fetch_array($result)){
            if($result->num_rows > 0){
                $userArray[$i] = array(
                    "user_id" => $this->filter($row['user_id']),
                    "username" => $this->filter($row['username']),
                    "user_email" => $this->filter($row['user_email']),
                    "user_firstname" => $this->filter($row['user_firstname']),
                    "user_lastname" => $this->filter($row['user_lastname']),
                    "user_subscription_status" => $this->filter($row['user_subscription_status']),
                    "user_subscription_expiredate" => $this->filter($row['user_subscription_expiredate']),
                    "user_type" => $row['user_type'],
                    "user_distributor_id" => $this->filter($row['user_distributor_id']),
                );
            }
            $i++;
        }
        if(!empty($userArray)){
            return $userArray;
            $stmt->close();
        }else{
            return $userArray = array();
            $stmt->close();
        };
    }

    public function getUserById($id){
        $stmt = $this->mysqli->prepare("SELECT user_id, username, user_email, user_firstname, user_lastname, user_subscription_status, user_subscription_expiredate, user_type, user_distributor_id FROM users WHERE user_id=?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        while($row = mysqli_fetch_array($result)){
            if($result->num_rows > 0){
                $userArray = array(
                    "user_id" => $this->filter($row['user_id']),
                    "username" => $this->filter($row['username']),
                    "user_email" => $this->filter($row['user_email']),
                    "user_firstname" => $this->filter($row['user_firstname']),
                    "user_lastname" => $this->filter($row['user_lastname']),
                    "user_subscription_status" => $this->filter($row['user_subscription_status']),
                    "user_subscription_expiredate" => $this->filter($row['user_subscription_expiredate']),
                    "user_type" => $row['user_type'],
                    "user_distributor_id" => $this->filter($row['user_distributor_id']),
                );
            }
        }
        if(!empty($userArray)){
            return $userArray;
            $stmt->close();
        }else{
            return $userArray = array();
            $stmt->close();
        };
    }

    public function getLastname($username){
        $stmt = $this->mysqli->prepare("SELECT user_lastname FROM users WHERE username=?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();
        while($row = mysqli_fetch_array($result)){
            if($result->num_rows > 0){
                return $this->filter($row['user_lastname']);
                $stmt->close();
            }else{
                return "UNKNOWN";
                $stmt->close();
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
                $stmt->close();
            }else{
                return false;
                $stmt->close();
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
            $stmt->close();
        }else{
            return false;
            $stmt->close();
        }
    }

    private function changeSubscription($username, $to){
        $stmt = $this->mysqli->prepare("UPDATE users SET user_subscription_status=?, user_subscription_expiredate=? WHERE username=?");
        if($to==0){
            $null = NULL;
            $stmt->bind_param("iss", $to, $null, $username);
        }else{
            $stmt->bind_param("iss", $to, date("Y-m-d", time() + 2592000), $username);
        }
        $stmt->execute();
        $stmt->close();
    }

    public function isSubscribed($username){
        $stmt = $this->mysqli->prepare("SELECT user_subscription_status, user_subscription_expiredate FROM users WHERE username=?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();

        while($row = mysqli_fetch_array($result)){
            if($result->num_rows > 0){
                if($row['user_subscription_expiredate'] < date("Y-m-d")){
                    $this->changeSubscription($username, 0);
                    return false;
                }else{
                    if($row['user_subscription_status'] == 1){
                        return true;
                        $stmt->close();
                    }else{
                        return false;
                        $stmt->close();
                    }
                }
            }
        }
    }

    public function getUserPrivilege($username){
        $stmt = $this->mysqli->prepare("SELECT user_type FROM users WHERE username=?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();

        while($row = mysqli_fetch_array($result)){
            if($result->num_rows > 0){
                return $row['user_type'];
            }
        }
        $stmt->close();
    }

    public function getDistributorId($username){
        $stmt = $this->mysqli->prepare("SELECT user_distributor_id FROM users WHERE username=?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();

        while($row = mysqli_fetch_array($result)){
            if($result->num_rows > 0){
                return $row['user_distributor_id'];
            }
        }
        $stmt->close();
    }

    public function canItPublish($distributorId){
        $stmt = $this->mysqli->prepare("SELECT distributor_publish_status FROM distributors WHERE distributor_id=?");
        $stmt->bind_param("i", $distributorId);
        $stmt->execute();
        $result = $stmt->get_result();

        while($row = mysqli_fetch_array($result)){
            if($result->num_rows > 0){
                if($row['distributor_publish_status'] == 1){
                    return true;
                }else{
                    return false;
                }
            }
        }
        $stmt->close();
    }

    public function editUser($editArray){
        $stmt = $this->mysqli->prepare("UPDATE users SET username=?, user_subscription_status=?, user_subscription_expiredate=?, user_type=?, user_distributor_id=? WHERE user_id=?");
        if($editArray['user_subscription_status'] == 'on'){
            $usersub = 1;
        }else{
            $usersub = 0;
        }

        if($editArray['user_type'] == 'listener') {$usertype = 0;}
        if($editArray['user_type'] == 'admin') {$usertype = 1;}
        if($editArray['user_type'] == 'distributor') {$usertype = 2;}

        if($usertype == 2){
            $distid=$editArray['distributor_select'];
        }else{
            $distid=NULL;
        }
        $stmt->bind_param("sisiii", $editArray['username'], $usersub, $editArray['user_subscription_expiredate'], $usertype, $distid, $editArray['user_id']);
        $status = $stmt->execute() or die($stmt->error);
        $stmt->close();

    }

    public function deleteUser($id){
        $stmt = $this->mysqli->prepare("DELETE FROM users WHERE user_id=?");
        $stmt->bind_param("i", $id);
        $status = $stmt->execute() or die($stmt->error);
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
            $stmt->close();
        }else{
            return $returnArray = array();
            $stmt->close();
        };
    }

    public function searchMusic($musicid){
        $stmt = $this->mysqli->prepare("SELECT music_id, music_artist_name, music_track_name, music_artwork_path FROM music WHERE music_id=?");
        $stmt->bind_param("i", $musicid);
        $stmt->execute();
        $result = $stmt->get_result();
        while($row = mysqli_fetch_array($result)){
            if($result->num_rows > 0 && $row['music_id'] == $musicid){
               return $returnArray = array(
                "music_artist_name" => $this->filter($row['music_artist_name']),
                "music_track_name" => $this->filter($row['music_track_name']),
                "music_artwork_path" => $this->filter($row['music_artwork_path']),
               );
               $stmt->close();
            }else{
                return $returnArray = array();
                $stmt->close();
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
            $stmt->close();
        }else{
            return $returnArray = array();
            $stmt->close();
        };
    }

    public function getAlbumInfo($albumId, $status){
        $id = $albumId;
        if($status == 3){
            $stmt = $this->mysqli->prepare("SELECT * FROM albums, music WHERE music.album_id=? AND albums.album_id=?");
            $stmt->bind_param("ii", $id, $id);
        }else{
            $stmt = $this->mysqli->prepare("SELECT * FROM albums, music WHERE music.album_id=? AND albums.album_id=? AND music_status=?");
            $stmt->bind_param("iii", $id, $id, $status);
        }
        
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
                    "music_status" => $this->filter($row['music_status']),
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
            $stmt->close();
        }else{
            return $returnArray = array();
            $stmt->close();
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

    public function getMusicLocationById($id){
        $stmt = $this->mysqli->prepare("SELECT music_id, music_path FROM music WHERE music_status=1 AND music_id=?");
        $stmt->bind_param("i",$id);
        $stmt->execute();
        $result = $stmt->get_result();
        while($row = mysqli_fetch_array($result)){
            if($result->num_rows > 0){
                return $row['music_path'];
            }
        }

        $stmt->close();
    }

    public function getAlbumsToDistributor($dId){
        $stmt = $this->mysqli->prepare("SELECT * FROM albums WHERE album_distributed_id=?");
        $stmt->bind_param("i", $dId);
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
            $stmt->close();
        }else{
            return $returnArray = array();
            $stmt->close();
        };
    }

    public function deleteAlbum($id){
        $stmt = $this->mysqli->prepare("DELETE FROM albums WHERE album_id=?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $stmt->close();
        $stmtMusic = $this->mysqli->prepare("DELETE FROM music WHERE album_id=?");
        $stmtMusic->bind_param("i", $id);
        $stmtMusic->execute();
        $stmtMusic->close();
        

    }

    public function updateAlbum($updateArray){
        $stmt = $this->mysqli->prepare("UPDATE albums SET album_name=?, album_artist_name=?, album_release_date=?, album_artwork_path=? WHERE album_id=?");
        $stmt->bind_param("sssss", $updateArray['album_name'], $updateArray['album_artist_name'], $updateArray['album_release_date'], $updateArray['album_artwork_path'], $updateArray['album_id']);
        $status = $stmt->execute() or die($stmt->error);
        $stmt->close();

        $stmtMusic = $this->mysqli->prepare("UPDATE music SET music_artist_name=?, music_track_name=?, music_path=?, music_status=? WHERE album_id=? AND music_id=?");
        for($i = 0; $i<$updateArray['count']; $i++){
            if($updateArray['music_status_'.$i] == "on"){
                $updateArray['music_status_'.$i] = 1;
            }else{
                $updateArray['music_status_'.$i] = 0;
            }
            $stmtMusic->bind_param("ssssss", $updateArray['music_artist_name_'.$i], $updateArray['music_track_name_'.$i], $updateArray['music_path_'.$i], $updateArray['music_status_'.$i], $updateArray['album_id'], $updateArray['music_id_'.$i]);
            $stmtMusic->execute();
        }
        $stmtMusic->close();
        
    }

    public function createAlbum($createArray, $distributorId){
        $stmt = $this->mysqli->prepare("INSERT INTO albums (album_artist_name, album_name, album_artwork_path, album_release_date, album_distributed_by, album_distributed_id) VALUES (?,?,?,?,(SELECT distributor_name FROM distributors WHERE distributor_id=?),?)");
        $stmt->bind_param("ssssii", $createArray['album_artist_name'], $createArray['album_name'], $createArray['album_artwork_path'], $createArray['album_release_date'], $distributorId, $distributorId);
        $status = $stmt->execute() or die($stmt->error);
        

        $now = date("Y-m-d");
        $latestAlbumId= $stmt->insert_id;
        $stmtMusic = $this->mysqli->prepare("INSERT INTO music (music_artist_name, music_track_name, music_path, music_artwork_path, music_status, music_updated, album_id, music_distributed_by, music_distributed_id) VALUES (?,?,?,?,?,?,?,(SELECT distributor_name FROM distributors WHERE distributor_id=?),?)");
        for($i = 1; $i<$createArray['count']+1; $i++){
            if($createArray['music_status_'.$i] == "on"){
                $createArray['music_status_'.$i] = 1;
            }else{
                $createArray['music_status_'.$i] = 0;
            }
            $stmtMusic->bind_param("sssssssss", $createArray['music_artist_name_'.$i], $createArray['music_track_name_'.$i], $createArray['music_path_'.$i], $createArray['music_artwork_path_'.$i], $createArray['music_status_'.$i], $now, $latestAlbumId, $distributorId, $distributorId);
            $stmtMusic->execute() or die($stmt->error);
        }
        $stmt->close();
        $stmtMusic->close();
        
    }





    /* -- OTHERS -- */

    public function closeConn(){
        $this->mysqli->close();
    }

    public function getDistributorList(){
        $stmt = $this->mysqli->prepare("SELECT * FROM distributors");
        $stmt->execute();
        $result = $stmt->get_result();
        $i = 0;
        while($row = mysqli_fetch_array($result)){
            if($result->num_rows > 0){
                $returnArray[$i] = array(
                    "distributor_id" => $this->filter($row['distributor_id']),
                    "distributor_name" => $this->filter($row['distributor_name']),
                    "distributor_publish_status" => $this->filter($row['distributor_publish_status']),
                );
            }
            $i++;
        }

        if(isset($returnArray)){
            return $returnArray;
            $stmt->close();
        }else{
            return $returnArray = array();
            $stmt->close();
        };
    }

    public function getDistById($id){
        $stmt = $this->mysqli->prepare("SELECT * FROM distributors WHERE distributor_id=?");
        $stmt->bind_param('i',$id);
        $stmt->execute();
        $result = $stmt->get_result();
        while($row = mysqli_fetch_array($result)){
            if($result->num_rows > 0){
                $returnArray = array(
                    "distributor_id" => $this->filter($row['distributor_id']),
                    "distributor_name" => $this->filter($row['distributor_name']),
                    "distributor_publish_status" => $this->filter($row['distributor_publish_status']),
                );
            }
        }

        if(isset($returnArray)){
            return $returnArray;
            $stmt->close();
        }else{
            return $returnArray = array();
            $stmt->close();
        };
    }

    public function editDist($editArray){
        $stmt = $this->mysqli->prepare("UPDATE distributors SET distributor_name=?, distributor_publish_status=? WHERE distributor_id=?");
        if($editArray['dist_publish_status'] == 'on'){
            $diststat = 1;
        }else{
            $diststat = 0;
        }
        $stmt->bind_param("sii", $editArray['dist_name'], $diststat, $editArray['dist_id']);
        $status = $stmt->execute() or die($stmt->error);
        $stmt->close();
    }

    public function addDist($array){
        $stmt = $this->mysqli->prepare("INSERT INTO distributors (distributor_name, distributor_publish_status) VALUES (?,?)");
        if($array['dist_publish_status'] == 'on'){
            $diststat = 1;
        }else{
            $diststat = 0;
        }
        $stmt->bind_param("si", $array['dist_name'], $diststat);
        $status = $stmt->execute() or die($stmt->error);
        $stmt->close();
    }

    public function deleteDist($id){
        $stmt = $this->mysqli->prepare("DELETE FROM distributors WHERE distributor_id=?");
        $stmt->bind_param("i", $id);
        $status = $stmt->execute() or die($stmt->error);
        $stmt->close();
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