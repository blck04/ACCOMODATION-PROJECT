<?php
require_once 'connect.php'; // Include your database connection
session_start();

// Check if the admin is logged in
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: login.html"); // Redirect to login if not authenticated
    exit();
}

// Handle form submissions
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Update rent due status
    if (isset($_POST['update_rent'])) {
        $studentNumber = $_POST["student_number"] ?? ''; // Ensure variable is set
        $accountStatus = $_POST["account_status"] ?? ''; // Ensure variable is set
    
        // Debugging output
        echo "Student Number: " . htmlspecialchars($studentNumber) . "<br>";
        echo "Account Status: " . htmlspecialchars($accountStatus) . "<br>";
    
        $rentDue = ($accountStatus === 'yes') ? 1 : 'No';
    
        $query = "UPDATE dashboard SET account_status = ? WHERE student_number = ?";
        $stmt = $conn->prepare($query);
        if (!$stmt) {
            die("Query preparation failed: " . $conn->error);
        }
    
        $stmt->bind_param("is", $rentDue, $studentNumber);
        
        if ($stmt->execute()) {
            if ($stmt->affected_rows > 0) {
                echo "Rent status updated successfully.";
            } else {
                echo "No changes made or student number not found.";
            }
        } else {
            echo "Error executing query: " . $stmt->error;
        }
    
        $stmt->close();
    }

    // Update check-in and check-out dates
    if (isset($_POST['update_dates'])) {
        $studentNumber = $_POST["student_number"];
        
        if (isset($_POST["checkin_date"]) && isset($_POST["checkout_date"])) {
            $checkInDate = $_POST["checkin_date"];
            $checkOutDate = $_POST["checkout_date"];
            
            // Validate dates
            if (strtotime($checkInDate) === false || strtotime($checkOutDate) === false) {
                die("Invalid date format.");
            }
    
            // Prepare the SQL statement
            $query = "UPDATE dashboard SET checkin_date = ?, checkout_date = ? WHERE student_number = ?";
            $stmt = $conn->prepare($query);
            
            if (!$stmt) {
                die("Query preparation failed: " . $conn->error);
            }
    
            // Bind parameters correctly as strings
            $stmt->bind_param("sss", $checkInDate, $checkOutDate, $studentNumber);
            
            // Execute the statement and check for errors
            if ($stmt->execute()) {
                if ($stmt->affected_rows > 0) {
                    echo "Dates updated successfully.";
                } else {
                    echo "No changes made; student number may not exist.";
                }
            } else {
                die("Error executing query: " . $stmt->error);
            }
    
            $stmt->close();
        } else {
            echo "Check-in and check-out dates are required.";
        }
    }
}

// Fetch student data
$query = "SELECT full_name, student_number, accommodation_status, account_status, checkin_date, checkout_date FROM dashboard";
$result = $conn->query($query);



$conn->close(); // Close the database connection
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="styles/admin-dashboard.css"><!--External CSS file-->
</head>
<body>
    <div class="container">
        <h1>Admin Dashboard</h1>

        <h2>Update Rent Due Status</h2>
        <form method="POST" action="">
            <label for="student_number">Student Number:</label>
            <input type="text" id="student_number" name="student_number" required>

            <label for="rent_due">Is Rent Due?</label>
            <select id="rent_due" name="rent_due" required>
                <option value="yes">Yes</option>
                <option value="no">No</option>
            </select>

            <button type="submit" name="update_rent">Update Rent Status</button>
        </form>

        <h2>Update Check-in and Check-out Dates</h2>
        <form method="POST" action="">
            <label for="student_number_dates">Student Number:</label>
            <input type="text" id="student_number_dates" name="student_number" required>

            <label for="check_in_date">Check-in Date:</label>
            <input type="date" id="checkin_date" name="checkin_date" required>

            <label for="check_out_date">Check-out Date:</label>
            <input type="date" id="checkout_date" name="checkout_date" required>

            <button type="submit" name="update_dates">Update Dates</button>
        </form>

        <h2>Student Information</h2>
        <table>
    <thead>
        <tr>
            <th>Full Name</th>
            <th>Student Number</th>
            <th>Block & Room</th>
            <th>Rent Due</th>
            <th>Check-in Date</th>
            <th>Check-out Date</th>
        </tr>
        </thead>
    <tbody>
        <?php while ($row = $result->fetch_assoc()): ?>
            <tr>
                <td><?php echo htmlspecialchars($row['full_name']); ?></td>
                <td><?php echo htmlspecialchars($row['student_number']); ?></td>
                <td><?php echo htmlspecialchars($row['accommodation_status']); ?></td>
                <td><?php echo isset($row['account_status']) && $row['account_status'] ? 'Yes' : 'No'; ?></td>
                <td><?php echo htmlspecialchars($row['checkin_date']); ?></td>
                <td><?php echo htmlspecialchars($row['checkout_date']); ?></td>
            </tr>
        <?php endwhile; ?>
    </tbody>
</table>
    </div>
</body>
</html>