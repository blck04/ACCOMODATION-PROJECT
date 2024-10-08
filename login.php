<?php

require_once 'connect.php'; // Include database connection
session_start(); // Start session

define('ADMIN_USERNAME', 'root');
define('ADMIN_PASSWORD', 'admin'); // Note: In a real-world scenario, use a secure password hashing algorithm

function AdminLogin() {
    if (empty($_POST["user_name"]) || empty($_POST["admin_password"])) {
        return "Please enter both username and password.";
    } elseif ($_POST["user_name"] !== ADMIN_USERNAME || $_POST["admin_password"] !== ADMIN_PASSWORD) {
        return "Invalid username or password.";
    } else {
        // Set the admin session variable
        $_SESSION['admin_logged_in'] = true;
        header('Location: admin-dashboard.php'); // Redirect to admin dashboard
        exit;
    }
}

function StudentLogin($input, $idnumber) {
    global $conn;

    // Validate student input
    if (empty($input) || empty($idnumber)) {
        return "Please enter both student number and ID number.";
    } else {
        // Prepare and execute the query for student login
        $query = "SELECT id_number FROM dashboard WHERE student_number = ?";
        $stmt = $conn->prepare($query);
        
        if ($stmt) {
            $stmt->bind_param("s", $input); // Use input as student number
            $stmt->execute();
            $result = $stmt->get_result();

            // Check if a record was found
            if ($result->num_rows === 1) {
                $row = $result->fetch_assoc();
                $storedIdNumber = $row["id_number"];

                // Compare the entered ID number with the stored ID number
                if ($idnumber === $storedIdNumber) {
                    $_SESSION["student_id"] = $input; 
                    header("Location: student-dashboard.php"); // Redirect to student dashboard
                    exit();
                } else {
                    return "Incorrect student number or ID number.";
                }
            } else {
                return "Invalid student number or ID number.";
            }
//            $stmt->close(); // Close the statement
        } else {
            return "Database error: Unable to prepare statement.";
        }
    }
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Use null coalescing operator to avoid undefined index warnings
    $input = trim($_POST["user_name"] ?? ""); // For admin and student login
    $idNumber = trim($_POST["id_number"] ?? ""); // For student login

    $error = null; // Initialize an error variable

    // Check if logging in as admin
    if (!empty($_POST["admin_password"])) {
        $error = AdminLogin();
    } elseif (!empty($input) && !empty($idNumber)) { // Ensure both input fields are filled for student
        $error = StudentLogin($input, $idNumber);
    } else {
        $error = "Please enter the required fields.";
    }

    // If an error occurred, display it
    if ($error) {
        echo $error;
        exit;
    }
}

// Optional: Close the database connection
$conn->close();
