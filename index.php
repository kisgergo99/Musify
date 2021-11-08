<?php

if(isset($_GET['status'])){
    $statuscode = $_GET['status'];
}else{
    $statuscode = '';
}

if(!file_exists("./Classes/configuration.php")){
    $statuscode = "missingconf";
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
            if($key == "email_exists"){array_push($msg, "This email has been already registered. Is it you? Try to log in!<br>");}
            if($key == "password_not_match"){array_push($msg, "Password is not matching!<br>");}
            if($key == "not_accepted_terms"){array_push($msg, "Terms and conditions are not accepted!<br>");}
            if($key == "captcha_fail"){array_push($msg, "Wrong security code given! (Captcha)<br>");}
        }
        echo "<div class='alert alert-danger' role='alert'>
        <center><b>Failed to sign you in: </b><br>";
        foreach($msg as $m){ 
            echo $m; 
        } 
        echo "</center></div>";
        include(__DIR__."/Controller/Selector.php");
        break;
    case "missingconf":
        echo "You need to enter your MySQL connection first!";
        echo "<h3>If you hadn't already imported the included SQL file into your database ('SQL/musify.sql'), please do it!</h3>";
        echo "<form method='POST' enctype='application/x-www-form-urlencoded'>
                Host: <input type='text' name='host' id='host'>
                Username: <input type='text' name='username' id='username'>
                Password: <input type='password' name='password' id='password'>
                Database name: <input type='text' name='dbname' id='dbname'>
                <input type='submit' name='submit' id='submit' value='Save configuration'>
                </form>
        ";
}

if(isset($_POST['host']) && isset($_POST['username']) && isset($_POST['password']) && isset($_POST['dbname'])){
    $conffile = fopen("./Classes/configuration.php", "w");
    $conftxt = '<?php

    const HOST = "'.$_POST["host"].'";
    const USER = "'.$_POST["username"].'";
    const PASS = "'.$_POST["password"].'";
    const DBNAME = "'.$_POST["dbname"].'";

?>';
    fwrite($conffile, $conftxt);
    fclose($conffile);
    header("Location: ./");
}

?>