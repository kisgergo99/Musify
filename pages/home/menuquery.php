<?php

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

?>