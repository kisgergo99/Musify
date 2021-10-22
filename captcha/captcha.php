<?php
    session_start();
    unset($_SESSION['captcha_for_user']);
    header("Content-type: image/jpeg");
    $im=imagecreatetruecolor(150,40);
    $feher=imagecolorallocate($im,255,255,255);
    $fekete=imagecolorallocate($im,0,0,0);
    $szurke=imagecolorallocate($im,125,125,125);

    $chars="abcdefhjkmnpqrstuxy345789";
    $str="";
    for ($i=0;$i<6;$i++){
        $rand=rand(0,strlen($chars)-1);
        $str.=$chars[$rand];
    }

    $_SESSION["captcha_for_user"]=$str;
    $font = __DIR__."\Lato-SemiboldItalic.ttf";
    //echo $font;

    imagefill($im,0,0,$feher);
    imagettftext($im,20,0,12,32,$szurke,$font,$str);
    imagettftext($im,20,0,10,30,$fekete,$font,$str);
    
    imagejpeg($im);
    imagedestroy($im);
?>