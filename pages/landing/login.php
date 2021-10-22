<?php

$usertype = new Usertype();
if($usertype->checkCaptcha(htmlspecialchars($_POST['captcha_code'], ENT_QUOTES, 'UTF-8'))){
	if(isset($_POST['bejelentkezes']) && $_POST['username'] != NULL && $_POST['passw'] != NULL){
		$passw = htmlspecialchars($_POST['passw'], ENT_QUOTES, 'UTF-8');
		$user = htmlspecialchars($_POST['username'], ENT_QUOTES, 'UTF-8');
		$captcha = htmlspecialchars($_POST['captcha_code'], ENT_QUOTES, 'UTF-8');
	
	
		if($user != NULL && $passw != NULL &&
		($user == $usertype->getUserByEmail($user)['email'] && 
		(password_verify($passw, $usertype->getUserByEmail($user)['password']))) && $captcha == $_SESSION['captcha_for_user']){
			$usertype->createLoginSession($user, $captcha);
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

if(isset($_POST['kijelentkezes'])){
	if($usertype->isLoggedIn()){
		$usertype->dropSession();
		header('Location: '. "../index.php");
		die();
	}
}

