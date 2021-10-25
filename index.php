<?php

if(isset($_GET['status'])){
    $statuscode = $_GET['status'];
}else{
    $statuscode = '';
}

switch($statuscode){
    default:
        include(__DIR__."/Controller/Selector.php");
        break;
    case "loginfailed":
        echo "<div class='alert alert-danger' role='alert'>
                <center>Wrong email or password! Please try again..</center>
                </div>";
        include(__DIR__."/Controller/Selector.php");
        break;
}

?>