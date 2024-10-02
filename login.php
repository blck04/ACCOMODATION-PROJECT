<?php

require_once 'connect.php'; // Include database connection

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $studentNumber = trim($_POST["student_number"]);
    $idNumber = trim($_POST["id_number"]);

    // Validate input
    if (empty($studentNumber) || empty($idNumber)) {
        echo "Please enter both student number and ID number.";
    } else {
        // Prepare and execute the query
        $query = "SELECT id_number FROM accepted WHERE student_number = ?";
        $stmt = $conn->prepare($query);
        
        if ($stmt) {
            $stmt->bind_param("s", $studentNumber);
            $stmt->execute();
            $result = $stmt->get_result();

            // Check if a record was found
            if ($result->num_rows === 1) {
                $row = $result->fetch_assoc();
                $storedIdNumber = $row["id_number"];

                // Compare the entered ID number with the stored ID number
                if ($idNumber === $storedIdNumber) {
                    session_start();
                    $_SESSION["student_id"] = $studentNumber; 
                    header("Location: student-dash-board.html"); // Redirect to dashboard
                    exit();
                } else {
                    echo "<script>alert('Incorrect student number or ID number.')</script>";
                }
            } else {
                echo "<script>alert('Invalid student number or ID number.')</script>";
            }
            $stmt->close(); // Close the statement
        } else {
            echo "Database error: Unable to prepare statement.";
        }
    }
}

// Optional: Close the database connection
$conn->close();
?>