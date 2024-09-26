<?php


include 'connect.php'; 

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST["username"];
    $password = $_POST["password"];

    
    if (empty($username) || empty($password)) {
        
        echo "Please enter both username and password.";
    } else {
        // Perform authentication using a secure hashing algorithm (e.g., bcrypt)
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        // Retrieve user information from the database
        $query = "SELECT * FROM users WHERE username = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $storedPassword = $row["password"];

            // Verify password using password_verify()
            if (password_verify($password, $storedPassword)) {
                // Authentication successful, redirect to dashboard or appropriate page
                session_start(); // Start a session to store user data
                $_SESSION["username"] = $username; // Store username in the session
                header("Location:student-dash-board.html"); // Replace "dashboard.php" with your actual dashboard page
                exit();
            } else {
                // Incorrect password, display an error message
                echo "Invalid username or password.";
            }
        } else {
            // User not found, display an error message
            echo "Invalid username or password.";
        }
    }
}