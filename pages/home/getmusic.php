<?php

require(__DIR__."/../../loadhelper.php");
$usertype = new Usertype();
$music = new Music();
if(isset($_GET['music-id'])){
    if($usertype->isLoggedIn() && $usertype->isSubscribed()){
        $id = $music->decrypt($_GET['music-id']);


        $musicpath = $music->getMusicLocationById($id);
        $real_path = "{$_SERVER['DOCUMENT_ROOT']}$musicpath";
        $filename = pathinfo($real_path)["basename"];


        $mime_type = "audio/mpeg, audio/wav";
        if(file_exists($real_path)) {
            header('Content-type: '.$mime_type.'');
            header('Content-length: ' . filesize($real_path));
            header('Content-Disposition: filename="drift.mp3"');
            header('X-Pad: avoid browser bug');
            header('Accept-Ranges: bytes');
            readfile($real_path);
        } else {
            header("HTTP/1.0 404 Not Found");
        }
    }else{
        echo "You don't have permission to access this content.";
    }
}else{
    
    echo "You don't have permission to access this content.";
}

?>