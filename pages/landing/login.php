<?php

$usertype = new Usertype();
if(isset($_POST['bejelentkezes'])){
	if(isset($_POST['bejelentkezes']) && $_POST['user_email'] != NULL && $_POST['passw'] != NULL){
		$passw = $_POST['passw'];
		$user = htmlspecialchars($_POST['user_email'], ENT_QUOTES, 'UTF-8');
		
	
	
		if($user != NULL && $passw != NULL &&
		($user == $usertype->getUserByEmail($user)['user_email'] && 
		(password_verify($passw, $usertype->getUserByEmail($user)['user_password'])))){
			
			
			//Itt legyen egy függvény, ami ellenőrzi az adatbázisban, hogy megerősítette-e az email verificationt!
			
			
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

