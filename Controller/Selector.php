<?php
require(__DIR__."/../loadhelper.php");

$usertype = new Usertype();
$usertype->createLoginSession("gradden");

if($usertype->isLoggedIn()){
    //include(__DIR__."/../pages/home/index.php");
    redirect("/musify/pages/home/");
}else{
    include(__DIR__."/../pages/landing/index.php");
}


?>