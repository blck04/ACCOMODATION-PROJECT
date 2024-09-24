<?php

    // Database connection

    $conn = mysqli_connect('localhost', 'root', 'your_password', 'accommodation');
    if(!$conn){
        die("Connection Failed : " . mysqli_connect_error());}

?>