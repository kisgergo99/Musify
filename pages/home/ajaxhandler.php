<?php
spl_autoload_register(function ($name) {
    require_once(__DIR__."/../../Classes/$name.php");
});
$music = new Music();


//EZ A MENÜKNEK
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

//EZ A KERESÉS FUNKCIÓNAK MEGY
if(isset($_REQUEST["s"])){
    if(strlen($_REQUEST["s"]) > 0){
        $music->searchByKey($_REQUEST['s']);
    }
}


//EZ A WEBPLAYERNEK MEGY, A ZENEI INFÓKRÓL
if(isset($_GET['musicInfo']) && isset($_GET['musicid'])){
    $data = $music->searchMusic($music->decrypt($_REQUEST['musicid']));
    if(is_array($data)){
        echo json_encode($data, JSON_UNESCAPED_UNICODE );
    }else{
        echo "Please subscribe to listen music!";
    }
    exit;
}


?>