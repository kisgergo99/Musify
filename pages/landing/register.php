<?php

$database = new Database();

if(isset($_POST["register"])){
    $errors = array();

    if(isset($_POST['user_email']) && isset($_POST['firstname']) &&
    isset($_POST['lastname']) && isset($_POST['passw']) && isset($_POST['passw_again']) &&
    isset($_POST['termsandcond'])){
        $user_email = htmlspecialchars($_POST['user_email'], ENT_QUOTES, 'UTF-8');
        if(!filter_var($user_email, FILTER_VALIDATE_EMAIL)){
            array_push($errors, "email_wrong");
        }
		$firstname = htmlspecialchars($_POST['firstname'], ENT_QUOTES, 'UTF-8');
        $lastname = htmlspecialchars($_POST['lastname'], ENT_QUOTES, 'UTF-8');
        $password = $_POST['passw'];
        $password_again = $_POST['passw_again'];
        if($password !== $password_again){
            array_push($errors, "password_not_match");
        }
        if($_POST['termsandcond'] != "on"){
            array_push($errors, "not_accepted_terms");
        }

        if(empty($errors)){
            $hashedpass = password_hash($password, PASSWORD_DEFAULT);

            echo $_POST['subscriptionDemo'];
            //Itt lesz majd egy E-mail verification kóddal együtt, és csak akkor tud belépni, ha megerősítette az emailt
            if($database->createUserInDB($user_email, $firstname, $lastname, $hashedpass, $_POST['termsandcond'], $_POST['subscriptionDemo'])){
                header('Location: '. "./index.php?status=registerDone");
			    die();
            }else{
                echo "szar van a levesben:D";
            }
        }else{
            foreach($errors as $e){
                $msg .= $e."=1&";
            }
            header('Location: '. "./index.php?status=failedRegister&".$msg);
			die();
        }
    }
}

?>