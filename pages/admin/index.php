<?php
require(__DIR__."/../../loadhelper.php");
$usertype = new Usertype();

if($usertype->isLoggedIn()){
    if($usertype->getPrivilege($usertype->getUsername()) == 'admin'){
        redirect("admin.php");
    }
    
    if($usertype->getPrivilege($usertype->getUsername()) == 'distributor'){
        redirect("distributor.php");
    }
}else{
    redirect("../");
}


?>