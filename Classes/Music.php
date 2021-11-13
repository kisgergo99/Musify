<?php

class Music{
    private $database;
    private $usertype;

    public function __construct(){
        $this->database = new Database();
        $this->usertype = new Usertype();
    }

       /* OPENSSL Ecrypt-Decrypt felhasználói jelszavakra */
    private function encrypt_decrypt($action, $string) {
        $encrypt_method = "AES-256-CBC";
        $secret_key = 'szemusify-2021-projektmunka2';
        $secret_iv = '14785236987456321';

        // hash
        $key = hash('sha256', $secret_key);
        
        // iv - encrypt method AES-256-CBC expects 16 bytes - else you will get a warning
        $iv = substr(hash('sha256', $secret_iv), 0, 16);

        if ( $action == 'encrypt' ) {
            $output = openssl_encrypt($string, $encrypt_method, $key, 0, $iv);
            $output = base64_encode($output);
        } else if( $action == 'decrypt' ) {
            $string = base64_decode($string);
            $output = openssl_decrypt($string, $encrypt_method, $key, 0, $iv);
            
        }

        return $output;
    }

    public function encrypt($value){
        return $this->encrypt_decrypt('encrypt', $value);
    }

    public function decrypt($value){
        return $this->encrypt_decrypt('decrypt', $value);
    }

    public function getMusicList(){
        $musiclist = $this->database->getMusicList();
        echo '<h3 class="text-center">Latest uploaded</h3><hr>';
        $this->echoMusic($musiclist);
    }

    private function echoMusic($musiclist){
        echo '
        <div class="list-group">
        <ul id="list" style="list-style:none; width:75%; margin: auto;">';
        foreach($musiclist as $m){
            echo "<li>
                <a href='#' class='singleMusicMenu list-group-item list-group-item-action list-group-item-dark' data-value='getmusic.php?music-id=".$this->encrypt($m['music_id'])."' music-id='".$this->encrypt($m['music_id'])."'>".$m['music_artist_name']." - ".$m['music_track_name']."</a>
            </li>";
        }
        echo "</ul></div> ";
    }

    private function echoAlbums($array){
        $count = count($array);
        if($count % 5 != 0){
            $page = floor($count/5)+1;
        }else{
            $page = $count/5;
        }
        $seged = 0;
        for($a=1; $a<=$page; $a++){
            echo '<div class="card-group text-black">';
            for($i=$seged; $i<=min($a*5, $count)-1; $i++){
                echo '<div class="card album-item" album-id="'.$array[$i]["album_id"].'" style="max-width:20%">
                                <img class="card-img-top" style="" src="'.$array[$i]["album_artwork_path"].'" alt="Card image cap">
                                <div class="card-body">
                                <h5 class="card-title">'.$array[$i]["album_name"].'</h5>
                                <p class="card-text">'.$array[$i]["album_artist_name"].'</p>
                                </div>
                                <div class="card-footer">
                                <small class="text-muted">'.$array[$i]["album_release_date"].'</small>
                                </div>
                            </div>';
                $seged++;
            }
            echo '</div>';
        }
    }

    public function searchMusic($musicid){
        if($this->usertype->isSubscribed()){
            return $this->database->searchMusic($musicid);
        }else{
            return "Please subscribe to listen music!";
        }
    }

    public function searchByKey($key){
        $resultArray = $this->database->searchByKey($key);
        if(!empty($resultArray["music"])){
            $music = $resultArray["music"];
            $this->echoMusic($music);
        }else{
            echo "No music found!";
        }
        echo "<hr>";
        if(!empty($resultArray["album"])){
            $albums = $resultArray["album"];
            $this->echoAlbums($albums);
        }else{
            echo "No albums found!";
        }
        
    }

    public function getLatestAlbums($limit){
        $latestAlbums = $this->database->getLatestAlbums($limit);
        $this->echoAlbums($latestAlbums);
    }

    public function getTracksFromAlbum($albumId){
        $albumArray = $this->database->getAlbumInfo($albumId);
        if(!empty($albumArray)){
            echo '
            <table class="album-displaycontainer">
                <tr>
                    <th><img class="w-75" src="'.$albumArray[0]['album_artwork_path'].'" /></th>
                    <th>
                        <h1>'.$albumArray[0]['album_name'].'</h1>
                        <hr>
                        <h3>'.$albumArray[0]['album_artist_name'].'</h3>
                        <p>'.$albumArray[0]['album_release_date'].'</p>
                    </th>
                </tr> 
            </table>
            <hr>
            ';
            $this->echoMusic($albumArray);
            echo '<hr><small class="center">'.$albumArray[0]['album_distributed_by'].'</small>';
        }
    }

    public function getMusicLocationById($id){
        return $this->database->getMusicLocationById($id);
    }
}

?>