<?php

$usertype = new Usertype();
if(isset($_POST['bejelentkezes']) && $usertype->checkCaptcha(htmlspecialchars($_POST['captcha_code'], ENT_QUOTES, 'UTF-8'))){
	if(isset($_POST['bejelentkezes']) && $_POST['user_email'] != NULL && $_POST['passw'] != NULL){
		$passw = $_POST['passw'];
		$user = htmlspecialchars($_POST['user_email'], ENT_QUOTES, 'UTF-8');
		$captcha = htmlspecialchars($_POST['captcha_code'], ENT_QUOTES, 'UTF-8');
	
	
		if($user != NULL && $passw != NULL &&
		($user == $usertype->getUserByEmail($user)['user_email'] && 
		(password_verify($passw, $usertype->getUserByEmail($user)['user_password']))) && $captcha == $_SESSION['captcha_for_user']){
			$usertype->createLoginSession($usertype->getUserByEmail($user)['username'], $captcha);
			header('Location: '. "./index.php");
			die();
		}else{
			header('Location: '. "./index.php?status=loginfailed");
			die();
		}
	}
}else{
	header('Location: '. "./index.php?status=loginfailed");
	die();
}

