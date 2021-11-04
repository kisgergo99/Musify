<?php

class Music{
    private $database;

    public function __construct(){
        $this->database = new Database();
    }

    public function getMusicList(){
        return $this->database->getMusicList();
    }

    public function searchMusic($musicid, $musicpath){
        return $this->database->searchMusic($musicid, $musicpath);
    }

    public function getLatestAlbums($limit){
        return $this->database->getLatestAlbums($limit);
    }
}

?>