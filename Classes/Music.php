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
        echo '<h3 class="text-center">Latest uploaded</h3><hr>
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
}

?>