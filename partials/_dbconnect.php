<?php

$servername = 'localhost';
$useraname = 'root';
$password = '';
$database = 'forums';

$conn = mysqli_connect($servername,$useraname,$password,$database);

if(!$conn){
    echo "Error while connecting to DB: ".$mysqli_connect_error();
}

?>