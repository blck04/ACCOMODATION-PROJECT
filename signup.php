<?php
require("connect.php");

$fullName = $_POST['fullName'] ;
$studentNumber = $_POST['studentNumber'];
$gender = $_POST['gender'];
$email = $_POST['email'];
$idNumber = $_POST['idNumber'];
$phoneNumber = $_POST['phoneNumber'];
$address = $_POST['address'];
//$studentID = $_POST['studentID']; // File
//$nationalID = $_POST['nationalID']; // File
$guardian_name = $_POST['guardian_name'];
$phone = $_POST['phone'];
$guardian_id = $_POST['guardian_id']; // This is the guardian's ID number

$query = "INSERT INTO signup(fullName,studentNumber,gender,email,idNumber,phoneNumber,address,guardian_name,phone,guardian_id	
) VALUES ('$fullName', '$studentNumber', '$gender', '$email', '$idNumber', '$phoneNumber', '$address','$guardian_name', '$phone','$guardian_id')";

$result = mysqli_query($conn, $query) or die(mysqli_error($conn));
if(mysqli_affected_rows($conn)){
    echo "<script>alert('You have successfully signed up.')</script>";
    header('refresh:1; ./login.html');
}else{
    echo "It didn't work";
}

mysqli_close($conn);
