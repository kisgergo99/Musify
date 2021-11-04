<?php

$music = new Music();

listRecentAlbums($music);

function listRecentAlbums($music){
    $latestAlbums = $music->getLatestAlbums(10);
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
                echo '<div class="card border-danger" style="max-width:20%">
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

?>