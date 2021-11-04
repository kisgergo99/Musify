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
        for($a=0; $a<=$page; $a++){
            echo '<div class="card-group text-black">';
            for($i=0; $i<$count; $i++){
                foreach($latestAlbums as $a){
                    echo '<div class="card border-danger album-item" album-id="'.$a["album_id"].'" style="max-width:20%">
                                <img class="card-img-top" style="" src="'.$a["album_artwork_path"].'" alt="Card image cap">
                                <div class="card-body">
                                <h5 class="card-title">'.$a["album_name"].'</h5>
                                <p class="card-text">'.$a["album_artist_name"].'</p>
                                </div>
                                <div class="card-footer">
                                <small class="text-muted">'.$a["album_release_date"].'</small>
                                </div>
                            </div>
                            ';
                }
            }
            echo '</div>';
        }
    }
}

?>