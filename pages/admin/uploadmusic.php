<?php

if(isset($_FILES['file']['name'])){

    /* Getting file name */
    $filename = $_FILES['file']['name'];
    $filename = preg_replace('/\s+/', '_', $filename);
    
 
    /* Location */
    $location = $_SERVER['DOCUMENT_ROOT']."/musify/audio/".$filename;
    $img_location = $_SERVER['DOCUMENT_ROOT']."/musify/images/".$filename;
    $FileType = pathinfo($location,PATHINFO_EXTENSION);
    $FileType = strtolower($FileType);
 
    /* Valid extensions */
    $valid_extensions = array("mp3","wav");
    $image_extensions = array("jpg", "jpeg", "png");
 
    $path = 0;
    /* Check file extension */
    if(in_array(strtolower($FileType), $valid_extensions)) {
       /* Upload file */
       if(move_uploaded_file($_FILES['file']['tmp_name'],$location)){
          $response = "/musify/audio/".$filename;
       }
    }
    if(in_array(strtolower($FileType), $image_extensions)) {
        /* Upload file */
        if(move_uploaded_file($_FILES['file']['tmp_name'],$img_location)){
           $response = "/musify/images/".$filename;
        }
     }

    echo $response;
    exit;
 }else{
    echo 0;
     header("Location: ./");
 }
 echo 0;
 


?>