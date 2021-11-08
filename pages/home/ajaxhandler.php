<?php
spl_autoload_register(function ($name) {
    require_once(__DIR__."/../../Classes/$name.php");
});
$music = new Music();

if(isset($_GET['menu'])){
    switch($_REQUEST["menu"]){
        default:
            include("browse.php");
            break;
        case "menu_browse":
            include("browse.php");
            break;
        case "menu_albums":
            include("albums.php");
            break;
        case "menu_search":
            include("search.php");
            break;
        }
}

if(isset($_REQUEST["s"])){
    if(strlen($_REQUEST["s"]) > 2){
        $music->searchByKey($_REQUEST['s']);
    }
}

if(isset($_GET['musicInfo']) && isset($_GET['musicid']) && isset($_GET['musicpath'])){
    $data = $music->searchMusic($_REQUEST['musicid'], $_REQUEST['musicpath']);
    echo json_encode($data, JSON_UNESCAPED_UNICODE );
    exit;
}


?>