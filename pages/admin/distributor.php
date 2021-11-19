<?php
require(__DIR__."/../../loadhelper.php");
$database = new Database();
$usertype = new Usertype();
$albums = $database->getAlbumsToDistributor($usertype->getDistributorId());
echo "<script src='functions.js'></script>";

if(isset($_REQUEST['editAlbum'])){
    editAlbum($_REQUEST['editAlbum'], $database->getAlbumInfo($_REQUEST['editAlbum'],3));
}else{
    albumList($albums);
}

function albumList($albums){
    echo "<div class='table-responsive-sm p-3'><table class='table text-white' style=''>
        <tr class='table-active text-white'>
            <th scope='col'>Album Artwork</th>
            <th scope='col'>Album ID</th>
            <th scope='col'>Album Artist name</th>
            <th scope='col'>Album Name</td>
            <th scope='col'>Album Release date</td>
            <th scope='col'> Opció </th>
        </tr>

        ";
        foreach($albums as $a){
            echo "<tr scope='row'>";
                echo "<td><img src='".$a['album_artwork_path']."' alt='Album Artwork image' class='w-25'/></td>";
                echo "<td>".$a['album_id']."</td>";
                echo "<td>".$a['album_artist_name']."</td>";
                echo "<td>".$a['album_name']."</td>";
                echo "<td>".$a['album_release_date']."</td>";
                echo "<td><a href='distributor.php?editAlbum=".$a['album_id']."'>Edit album</a></td>";
            echo "</tr>";
        }
        echo "</table></div>";
        
}

function editAlbum($id, $albumArray){
    echo "<form action='".$_SERVER['PHP_SELF']."?saveAlbum' method=POST>
    <div class='container text-white w-100'>
        <div class='row border justify-content-sm-center rounded'> 
            <div class='col'>
                <div class='form-group'>
                    <h2 class='text-center'>Album ID: ".$albumArray[0]['album_id']."</h2>

                    <label for='album_name'>Album Name: </label>
                    <input type='text' name='album_name' id='album_name' value='".$albumArray[0]['album_name']."' class='form-control'>

                    <label for='album_artist_name'>Album Artist(s) name: </label>
                    <input type='text' name='album_artist_name' id='album_artist_name' value='".$albumArray[0]['album_artist_name']."' class='form-control'>

                    <label for='album_release_date'>Album release date: </label>
                    <input type='date' name='album_release_date' id='album_release_date' value='".$albumArray[0]['album_release_date']."' class='form-control'>

                    <label for='album_artwork_path'>Album artwork path: </label>
                    <input type='text' name='album_artwork_path' id='album_artwork_path' value='".$albumArray[0]['album_artwork_path']."' class='form-control'>

                    <form method='post' action='' enctype='multipart/form-data' id='albumartworkpath'>
                        <div class='preview'>
                            <img src='".$albumArray[0]['album_artwork_path']."' id='album_artwork_path' width='100' height='100'>
                        </div>
                        <div >
                            <input type='file' id='file_albumartwork' name='file_albumartwork' accept='.jpeg,.jpg,.png'/>
                            <input type='button' class='button' value='Upload Artwork' id='albumartwork_but_upload'>
                        </div>
                    </form>

                    <h2 class='text-center'>Tracks of album:</h2>";
                    foreach($albumArray as $a){
                        echo "
                        <h4>Music id:".$a['music_id']."</h4>
                        
                        <label for='music_status'>Is the music published?</label>
                        <div class='form-check form-switch'>
                                    <input type='checkbox' class='form-check-input' name='music_status' id='music_status' value='".$a['music_status']."' ";
                                    if($a['music_status']==1){
                                        echo "checked";
                                    }
                                    echo ">
                        </div>

                        <label for='music_artist_name'>Music composed by: </label>
                        <input type='text' name='music_artist_name' id='music_artist_name' value='".$a['music_artist_name']."' class='form-control'>

                        <label for='music_track_name'>Track name: </label>
                        <input type='text' name='music_track_name' id='music_track_name' value='".$a['music_track_name']."' class='form-control'>

                        <label for='music_artwork_path'>Music artwork path: </label>
                        <input type='text' name='music_artwork_path' id='music_artwork_path' value='".$a['music_artwork_path']."' class='form-control'>

                        <label>Music path: </label>
                        <input type='text' name='music_path' id='music_path' class='form-control music_path' value='".$a['music_path']."'>

                        <hr>
                        ";

                    }

                    echo "</div>";

                    echo "<form method='post' action='' enctype='multipart/form-data' id='myform'>
                    <div >
                        <input type='file' id='file' name='file' class='musicfile' accept='.mp3,.wav'/>
                        <input type='button' class='but_upload' value='Upload music' id='but_upload'>
                    </div>
                    <div class='preview'>
                        <input type='text' name='music_path' id='music_path' class='form-control music_path' value='".$a['music_path']."'>
                    </div>
                </form>";

    echo "</div>
        </div>
    </div>
    </form>";
}

?>

