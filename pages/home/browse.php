<?php
$music = new Music();
getAllMusicInLink($music);


function getAllMusicInLink($music){
    $musiclist = $music->getMusicList();
    echo '<ul id="list">';
    foreach($musiclist as $m){
        echo "<li><a href='#' class='singleMusicMenu' data-value='".$m['music_path']."' music-id='".$m['music_id']."'>".$m['music_artist_name']." - ".$m['music_track_name']."</a></li>";
    }
    echo "</ul>";
}

?>



