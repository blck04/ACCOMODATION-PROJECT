<?php
require_once 'connect.php'; // Include your database connection
session_start();

// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Check if the student is logged in
if (!isset($_SESSION["student_id"])) {
    header("Location: login.php"); // Redirect to login if not authenticated
    exit();
}

// Debugging: Print the session ID
echo "Session ID: " . htmlspecialchars($_SESSION["student_id"]) . "<br>";

// Fetch student data
$studentId = $_SESSION["student_id"];

$query = "SELECT full_name, account_status, due_date, checkin_date, checkout_date, accommodation_status FROM dashboard WHERE student_number = ?";
$stmt = $conn->prepare($query);

if (!$stmt) {
    die("Query preparation failed: " . $conn->error);
}

$stmt->bind_param("s", $studentId);
$stmt->execute();
$result = $stmt->get_result();

// Debugging: Print the number of rows found
echo "Rows found: " . $result->num_rows . "<br>";

// Check if student data is found
if ($result->num_rows === 1) {
    $studentData = $result->fetch_assoc();
    
    // Output the student data
    $fullName = htmlspecialchars($studentData['full_name']);
    $rentDue = $studentData['account_status'] ? 'Yes' : 'No';
    $dueDate = htmlspecialchars($studentData['due_date']);
    $checkInDate = htmlspecialchars($studentData['checkin_date']);
    $checkOutDate = htmlspecialchars($studentData['checkout_date']);
    $location = htmlspecialchars($studentData['accommodation_status']);
    
} else {
    echo "Student data not found.";
    exit();
}

$conn->close(); // Close the database connection
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Student Dashboard</title>
    <link
      rel="stylesheet"
      href="https://fonts.googleapis.com/css?family=Oswald"
    />
    <link
      rel="stylesheet"
      href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css"
    />
    <link rel="stylesheet" href="styles/student-dash-board.css" />
  </head>
  <body>
    <nav>
      <ul>
        <li><a href="#">Home</a></li>
        <li><a href="#">Payments</a></li>
        <li><a href="#">Logout</a></li>
      </ul>
    </nav>

    <div class="dashboard">
      <div class="greetings">
        <h1>Welcome, <?php echo $fullName; ?>!</h1>
        <h2>Account Status:</h2>
        <p>Rent Due: <?php echo $rentDue; ?></p>
        <p>Due Date: <?php echo $dueDate; ?></p>
        <h2>Accommodation Status:</h2>
        <p>Location: <?php echo $location; ?></p>
        <p>Check-in Date: <?php echo $checkInDate; ?></p>
        <p>Check-out Date: <?php echo $checkOutDate; ?></p>
      <div class="notice-board">
        <h3>Notice Board</h3>
        <p>Reminder: Rent payment is due on : </p>
        <p>Important:N/A!</p>
      </div>
    </div>
    <div class="footer">@Web Cadets. Inc</div>
  </body>
</html>