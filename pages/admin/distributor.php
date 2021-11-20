<?php
require(__DIR__."/../../loadhelper.php");
$database = new Database();
$usertype = new Usertype();
$albums = $database->getAlbumsToDistributor($usertype->getDistributorId());

echo "
    <div id='uploadMusic' title='Music upload helper'>
    <h5>First upload your music, then copy your path of the track.</h5>
    <form method='post' action='' enctype='multipart/form-data' id='myform'>
        <div >
            <input type='file' id='file' name='file' class='musicfile' accept='.mp3,.wav'/>
            <input type='button' class='but_upload' value='Upload music' id='but_upload'>
        </div>
        <div class='preview'>
            <input type='text' id='uploaded_path' class='form-control music_path'>
        </div>
    </form>
    </div>";
echo "<script src='functions.js'></script>";

if($usertype->isLoggedIn() && $usertype->getPrivilege($usertype->getUsername()) == 'distributor' && $usertype->publish()){
    if(isset($_REQUEST['editAlbum']) || isset($_REQUEST['addNewAlbum'])){
        if(isset($_REQUEST['editAlbum'])){
            editAlbum($_REQUEST['editAlbum'], $database->getAlbumInfo($_REQUEST['editAlbum'],3));
        }
        if(isset($_REQUEST['addNewAlbum'])){
            addAlbum();
        }
    }else{
        albumList($albums);
    }
    if(isset($_REQUEST['deleteAlbum'])){
        $database->deleteAlbum($_REQUEST['deleteAlbum']);
        redirect('distributor.php');
    }
    if(isset($_POST['updateAlbum'])){
        //var_dump($_POST);
        $database->updateAlbum($_POST);
        redirect("distributor.php");
    }
    if(isset($_POST['createNewAlbum'])){
        $database->createAlbum($_POST, $usertype->getDistributorId());
        redirect("distributor.php");
    }
}else{
    redirect("../../");
}


function albumList($albums){
    echo "<div class='table-responsive-sm p-3'><table class='table text-white' style=''>
        <tr class='table-active text-white'>
            <th scope='col'>Album Artwork</th>
            <th scope='col'>Album ID</th>
            <th scope='col'>Album Artist name</th>
            <th scope='col'>Album Name</td>
            <th scope='col'>Album Release date</td>
            <th scope='col'> Edit </th>
            <th scope='col'> Delete Album </th>
        </tr>

        ";
        if(empty($albums)){
            echo "<tr><td colspan='100%' class='text-center'><i>There's no released album yet.</i></td></tr>";
        }else{
            foreach($albums as $a){
                echo "<tr scope='row'>";
                    echo "<td><img src='".$a['album_artwork_path']."' alt='Album Artwork image' class='w-25'/></td>";
                    echo "<td>".$a['album_id']."</td>";
                    echo "<td>".$a['album_artist_name']."</td>";
                    echo "<td>".$a['album_name']."</td>";
                    echo "<td>".$a['album_release_date']."</td>";
                    echo "<td><a href='distributor.php?editAlbum=".$a['album_id']."'>Edit album</a></td>";
                    echo "<td><a href='distributor.php?deleteAlbum=".$a['album_id']."' style='color: red;' onclick='return confirm(\"Are you sure you want to delete this album? This process is irreversible!\");'>Delete Album</a></td>";
                echo "</tr>";
            }
        }
        echo "</table>";
        echo "<a href='distributor.php?addNewAlbum' style='float: right'><button class='btn btn-primary'><i class='fa fa-plus-circle'></i> Add new Album</button></a>
        </div>";
        
        
}

function editAlbum($id, $albumArray){
    echo "<form action='".$_SERVER['PHP_SELF']."' method=POST>
    <div class='container text-white w-100 p-5'>
        <h1 class='text-center'>Edit Album</h1>
        <div class='row border justify-content-sm-center rounded'> 
            <div class='col'>
                <div class='form-group'>
                    <h2 class='text-center'>Album ID: ".$albumArray[0]['album_id']."</h2>
                    <input type='hidden' name='album_id' id='album_id' value='".$albumArray[0]['album_id']."'>
                    <hr>

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
                    <hr>
                    <h2 class='text-center'>Tracks of album:</h2>
                    <center>
                        <a href='#'><button type='button' class='btn btn-primary btn-block w-25' id='uploadFileButton'>
                        <svg xmlns='http://www.w3.org/2000/svg' width='16' height='16' fill='currentColor' class='bi bi-arrow-left-square' viewBox='0 0 16 16'>
                            <path fill-rule='evenodd' d='M15 2a1 1 0 0 0-1-1H2a1 1 0 0 0-1 1v12a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1V2zM0 2a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v12a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V2zm11.5 5.5a.5.5 0 0 1 0 1H5.707l2.147 2.146a.5.5 0 0 1-.708.708l-3-3a.5.5 0 0 1 0-.708l3-3a.5.5 0 1 1 .708.708L5.707 7.5H11.5z'/>
                        </svg>
                            Upload Music
                        </button></a>
                    </center>";
                    
                    $i = 0;
                    foreach($albumArray as $a){
                        echo "
                        <h4>Music id:".$a['music_id']."</h4>
                        
                        <label for='music_status'>Is the music published?</label>
                        <div class='form-check form-switch'>
                                    <input type='checkbox' class='form-check-input' name='music_status_".$i."' id='music_status' ";
                                    if($a['music_status']==1){
                                        echo "checked";
                                    }
                                    echo ">
                        </div>

                        <input type='hidden' name='music_id_".$i."' value='".$a['music_id']."'>

                        <label for='music_artist_name'>Music composed by: </label>
                        <input type='text' name='music_artist_name_".$i."' id='music_artist_name' value='".$a['music_artist_name']."' class='form-control'>

                        <label for='music_track_name'>Track name: </label>
                        <input type='text' name='music_track_name_".$i."' id='music_track_name' value='".$a['music_track_name']."' class='form-control'>

                        <label for='music_artwork_path'>Music artwork path: </label>
                        <input type='text' name='music_artwork_path_".$i."' id='music_artwork_path' value='".$a['music_artwork_path']."' class='form-control'>

                        <label>Music path: </label>
                        <input type='text' name='music_path_".$i."' id='music_path' class='form-control music_path' value='".$a['music_path']."'>

                        <hr>
                        ";
                        $i++;
                    }

                    echo "<input type='hidden' name='count' value='".$i."'>";
                    echo "<button type='submit' class='btn btn-primary w-100' name='updateAlbum' id='updateAlbum'>
                    <svg xmlns='http://www.w3.org/2000/svg' width='16' height='16' fill='currentColor' class='bi bi-pencil' viewBox='0 0 16 16'>
                        <path d='M12.146.146a.5.5 0 0 1 .708 0l3 3a.5.5 0 0 1 0 .708l-10 10a.5.5 0 0 1-.168.11l-5 2a.5.5 0 0 1-.65-.65l2-5a.5.5 0 0 1 .11-.168l10-10zM11.207 2.5 13.5 4.793 14.793 3.5 12.5 1.207 11.207 2.5zm1.586 3L10.5 3.207 4 9.707V10h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.293l6.5-6.5zm-9.761 5.175-.106.106-1.528 3.821 3.821-1.528.106-.106A.5.5 0 0 1 5 12.5V12h-.5a.5.5 0 0 1-.5-.5V11h-.5a.5.5 0 0 1-.468-.325z'/>
                    </svg>
                        Save Changes
                    </button>";

                    echo "</div>";

                    echo "";

    echo "</div>
        </div>
    </div>
    </form>";
}

function addAlbum(){
    echo "
    <form action='".$_SERVER['PHP_SELF']."' method=POST>
    <div class='container text-white w-100 p-5'>
        <h1 class='text-center'>Create new Album</h1>
        <div class='row border justify-content-sm-center rounded'> 
            <div class='col'>
                <div class='form-group'>
                    <label for='album_name'>Album Name: </label>
                    <input type='text' name='album_name' id='album_name' class='form-control' required>

                    <label for='album_artist_name'>Album Artist(s) name: </label>
                    <input type='text' name='album_artist_name' id='album_artist_name' class='form-control' required>

                    <label for='album_release_date'>Album release date: </label>
                    <input type='date' name='album_release_date' id='album_release_date' class='form-control' required>

                    <label for='album_artwork_path'>Album artwork path: </label>
                    <input type='text' name='album_artwork_path' id='album_artwork_path' class='form-control' required>

                    <form method='post' action='' enctype='multipart/form-data' id='albumartworkpath'>
                        <div >
                            <input type='file' id='file_albumartwork' name='file_albumartwork' accept='.jpeg,.jpg,.png'/>
                            <input type='button' class='button' value='Upload Artwork' id='albumartwork_but_upload'>
                        </div>
                    </form>
                    <hr>
                    <h2 class='text-center'>Tracks of the album:</h2>
                    <center>
                        <a href='#'><button type='button' class='btn btn-primary btn-block w-25' id='uploadFileButton'>
                        <svg xmlns='http://www.w3.org/2000/svg' width='16' height='16' fill='currentColor' class='bi bi-arrow-left-square' viewBox='0 0 16 16'>
                            <path fill-rule='evenodd' d='M15 2a1 1 0 0 0-1-1H2a1 1 0 0 0-1 1v12a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1V2zM0 2a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v12a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V2zm11.5 5.5a.5.5 0 0 1 0 1H5.707l2.147 2.146a.5.5 0 0 1-.708.708l-3-3a.5.5 0 0 1 0-.708l3-3a.5.5 0 1 1 .708.708L5.707 7.5H11.5z'/>
                        </svg>
                            Upload Music
                        </button></a>
                    </center>

                    <label for='numOfMusic'>Number of music: </label>
                    <input type='number' id='numOfMusic' class='form-control' name='count' min='1' value='0' required>

                    <div class='trackContent'></div>
                    ";

                    echo "<button type='submit' class='btn btn-primary w-100' name='createNewAlbum' id='createNewAlbum'>
                    <svg xmlns='http://www.w3.org/2000/svg' width='16' height='16' fill='currentColor' class='bi bi-pencil' viewBox='0 0 16 16'>
                        <path d='M12.146.146a.5.5 0 0 1 .708 0l3 3a.5.5 0 0 1 0 .708l-10 10a.5.5 0 0 1-.168.11l-5 2a.5.5 0 0 1-.65-.65l2-5a.5.5 0 0 1 .11-.168l10-10zM11.207 2.5 13.5 4.793 14.793 3.5 12.5 1.207 11.207 2.5zm1.586 3L10.5 3.207 4 9.707V10h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.293l6.5-6.5zm-9.761 5.175-.106.106-1.528 3.821 3.821-1.528.106-.106A.5.5 0 0 1 5 12.5V12h-.5a.5.5 0 0 1-.5-.5V11h-.5a.5.5 0 0 1-.468-.325z'/>
                    </svg>
                        Create Album
                    </button>";

                    echo "</div>";

                    echo "";

    echo "</div>
        </div>
    </div>
    </form>";
}

?>

