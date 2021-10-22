<?php

if(isset($_GET['status'])){
    $statuscode = $_GET['status'];
}else{
    $statuscode = '';
}

switch($statuscode){
    default:
        include(__DIR__."/Controller/Selector.php");
}

?>