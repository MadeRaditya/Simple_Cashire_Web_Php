<?php
    $host = "localhost";
    $username = "root";
    $password = "";
    $dbName ="kasir-app";

    $conn = mysqli_connect($host, $username, $password, $dbName);

    if(!$conn){
        die("Connection failed: " . mysqli_connect_error());
    }

?>

