<?php

if(isset($_POST['bejelentkezes']) || isset($_POST['kijelentkezes'])){
    include("login.php");
}else{
    include(__DIR__."/Frontend/index.html");
}

if(isset($_POST['register'])){
    include("register.php");
}



?>