<?php
spl_autoload_register(function ($name) {
    require_once(__DIR__."/../../Classes/$name.php");
});


$music = new Music();

if(!isset($_REQUEST['albumPage'])){
    listRecentAlbums($music);
}else{
    getAlbumPage($_REQUEST['albumPage'], $music);
}

function listRecentAlbums($music){
    $music->getLatestAlbums(10);
}

function getAlbumPage($albumId, $music){
    $music->getTracksFromAlbum($albumId);
}

?>