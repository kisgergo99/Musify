<?php
spl_autoload_register(function ($name) {
    require_once(__DIR__."/../../Classes/$name.php");
});


$music = new Music();

if(!isset($_REQUEST['albumPage'])){
    listRecentAlbums($music);
}else{
    getAlbumPage($_REQUEST['albumPage']);
}

function listRecentAlbums($music){
    $music->getLatestAlbums(10);
}

function getAlbumPage($albumId){
    echo "Selected album ID: ".$albumId;
}

?>