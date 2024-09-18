<?php
    $fullName = $_POST['fullName'] = null;
    $studentNumber = $_POST['studentNumber']= null;
    $gender = $_POST['gender']= null;
    $email = $_POST['email']= null;
    $idNumber = $_POST['idNumber']= null;
    $phoneNumber = $_POST['phoneNumber']= null;
    $address = $_POST['address']= null;
    //$studentID = $_POST['studentID']; // File
    //$nationalID = $_POST['nationalID']; // File
    $guardian_name = $_POST['guardian_name']= null;
    $phone = $_POST['phone']= null;
    $id = $_POST['id']= null; // This is the guardian's ID number

    // Database connection

    $conn = mysqli_connect('localhost', 'root', 'your_password', 'accommodation');
    if($conn->connect_error){
        die("Connection Failed : " . $conn->connect_error);
    }else{
        $stmt = mysqli_prepare($conn, "insert into signup(fullName, studentNumber, gender, email, idNumber, phoneNumber, address, guardian_name, phone) values(?, ?, ?, ?, ?, ?, ?, ?, ?)");
        mysqli_stmt_bind_param($stmt, "sssssissi", $fullName, $studentNumber, $gender, $email, $idNumber, $phoneNumber,$address, $guardian_name, $phone);
        mysqli_stmt_execute($stmt);
        echo "It fucking worked";
        mysqli_close($conn);
    }

?>