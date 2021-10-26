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
    case "registerDone":
        echo "<div class='alert alert-success' role='alert'>
        <center>Congratulations! You're registered successfully on Musify! <br> Are you ready? Log in to Musify, to listen some cool beats!</center>
        </div>";
        include(__DIR__."/Controller/Selector.php");
        break;
    case "failedRegister":
        $msg = array();
        foreach ($_GET as $key => $value) { 
            if($key == "email_wrong"){array_push($msg, "Wrong email address!<br>");}
            if($key == "password_not_match"){array_push($msg, "Password is not matching!<br>");}
            if($key == "not_accepted_terms"){array_push($msg, "Terms and conditions are not accepted!<br>");}
        }
        echo "<div class='alert alert-danger' role='alert'>
        <center><b>Failed to sign you in: </b><br>";
        foreach($msg as $m){ 
            echo $m; 
        } 
        echo "</center></div>";
        include(__DIR__."/Controller/Selector.php");
        break;
}

?>