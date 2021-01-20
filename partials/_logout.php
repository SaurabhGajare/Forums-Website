<?php

    session_start();
    echo 'Logging you out... Please wait...';
    
    session_destroy();
    
        if($_SERVER['REQUEST_METHOD']=='POST'){
            $url = $_POST['url']; 
            header("location: ".$url."");
            exit;
        }
        else{
            header("location: /forum");
            exit;
        }
?>