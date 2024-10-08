<?php
    // Database connection
    $conn = mysqli_connect('localhost', 'root', '', 'accommodation');
    if(!$conn){
        die("Connection Failed : " . mysqli_connect_error());}