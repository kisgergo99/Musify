<?php
require(__DIR__."/../../loadhelper.php");
$usertype = new Usertype();

if($usertype->isLoggedIn()){
    include("mainscreen.php");
}else{
    redirect("/musify/index.php");
    die();
}


?>