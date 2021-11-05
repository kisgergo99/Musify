<?php

class Music{
    private $database;
    private $usertype;

    public function __construct(){
        $this->database = new Database();
        $this->usertype = new Usertype();
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
                <a href='#' class='singleMusicMenu list-group-item list-group-item-action list-group-item-dark' data-value='".$m['music_path']."' music-id='".$m['music_id']."'>".$m['music_artist_name']." - ".$m['music_track_name']."</a>
            </li>";
        }
        echo "</ul></div> ";
    }

    public function searchMusic($musicid, $musicpath){
        return $this->database->searchMusic($musicid, $musicpath);
    }

    public function getLatestAlbums($limit){
        $latestAlbums = $this->database->getLatestAlbums($limit);
        $count = count($latestAlbums);
        if($count % 5 != 0){
            $page = floor($count/5)+1;
        }else{
            $page = $count/5;
        }
        $seged = 0;
        for($a=1; $a<=$page; $a++){
            echo '<div class="card-group text-black">';
            for($i=$seged; $i<=min($a*5, $count)-1; $i++){
                echo '<div class="card album-item" album-id="'.$latestAlbums[$i]["album_id"].'" style="max-width:20%">
                                <img class="card-img-top" style="" src="'.$latestAlbums[$i]["album_artwork_path"].'" alt="Card image cap">
                                <div class="card-body">
                                <h5 class="card-title">'.$latestAlbums[$i]["album_name"].'</h5>
                                <p class="card-text">'.$latestAlbums[$i]["album_artist_name"].'</p>
                                </div>
                                <div class="card-footer">
                                <small class="text-muted">'.$latestAlbums[$i]["album_release_date"].'</small>
                                </div>
                            </div>';
                $seged++;
            }
            echo '</div>';
        }
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
}

?>