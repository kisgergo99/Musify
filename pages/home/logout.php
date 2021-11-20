<?php

require(__DIR__."/../../loadhelper.php");
$usertype = new Usertype();
if(isset($_POST['kijelentkezes'])){
	if($usertype->isLoggedIn()){
		$usertype->dropSession();
		header('Location: '. "./index.php");
		die();
	}
}

?>