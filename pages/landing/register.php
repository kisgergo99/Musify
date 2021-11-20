<?php

if(isset($_POST["register"])){
    $errors = array();

    if(isset($_POST['user_email']) && isset($_POST['firstname']) &&
    isset($_POST['lastname']) && isset($_POST['passw']) && isset($_POST['passw_again']) &&
    isset($_POST['termsandcond']) && isset($_POST['captcha_code'])){
        $database = new Database();
        $user_email = htmlspecialchars($_POST['user_email'], ENT_QUOTES, 'UTF-8');
        if(!filter_var($user_email, FILTER_VALIDATE_EMAIL)){
            array_push($errors, "email_wrong");
        }
        if($database->isEmailRegistered($user_email)){
            array_push($errors, "email_exists");
        }
        if(!$usertype->checkCaptcha(htmlspecialchars($_POST['captcha_code'], ENT_QUOTES, 'UTF-8'))){
            array_push($errors, "captcha_fail");
        }
        $captcha = htmlspecialchars($_POST['captcha_code'], ENT_QUOTES, 'UTF-8');
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
            unset($msg);
			die();
        }
    }else{
        header('Location: '. "./index.php?status=failedRegister");
		die();
    }
}else{
    header('Location: '. "../../");
	die();
}

?>