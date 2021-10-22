<?php
spl_autoload_register(function ($name) {
    require_once(__DIR__."/Classes/$name.php");
});
include(__DIR__."/pages/HTML/basic.html");

function redirect($url, $statusCode = 303)
{
   header('Location: ' . $url, true, $statusCode);
   die();
}

?>