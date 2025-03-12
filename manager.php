<?php
require 'db.php';
session_start();

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    die("error=User not logged in.");
}

// Initialize variables to hold input values
$event_id = '';
$email = '';
$phone_number = '';

// Check if request method is POST
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Validate and sanitize input data
    $event_id = filter_var($_POST['event_id'], FILTER_SANITIZE_NUMBER_INT);
    $email = filter_var($_POST['email'], FILTER_VALIDATE_EMAIL);
    $phone_number = filter_var($_POST['phone_number'], FILTER_SANITIZE_STRING);
    $user_id = $_SESSION['user_id'];

    // Check for valid input
    if (!$event_id || !$email || !$phone_number) {
        echo "error=Invalid input. Please check your details.";
        exit;
    }

    // Prepare SQL query to prevent SQL injection
    $query = "INSERT INTO registrations (event_id, user_id, email, phone_number) 
              VALUES (?, ?, ?, ?)";
    $stmt = mysqli_prepare($conn, $query);

    // Bind parameters
    mysqli_stmt_bind_param($stmt, "iiss", $event_id, $user_id, $email, $phone_number);

    // Execute query
    if (mysqli_stmt_execute($stmt)) {
        echo "success=Registration successful!";
    } else {
        echo "error=Failed to register: " . mysqli_stmt_error($stmt);
    }

    // Close statement
    mysqli_stmt_close($stmt);
} else {
    echo "error=Invalid request method.";
}

// Fetch events for dropdown (example query)
$eventsQuery = "SELECT id, title FROM events";
$eventsResult = mysqli_query($conn, $eventsQuery);
$events = array();
while ($row = mysqli_fetch_assoc($eventsResult)) {
    $events[] = $row;
}

// Fetch scheduled events for user (example query)
$scheduledEventsQuery = "SELECT e.title, e.description, e.venue, e.start_date, e.end_date 
                         FROM registrations r 
                         JOIN events e ON r.event_id = e.id 
                         WHERE r.user_id = '$user_id'";
$scheduledEventsResult = mysqli_query($conn, $scheduledEventsQuery);
$scheduledEvents = array();
while ($row = mysqli_fetch_assoc($scheduledEventsResult)) {
    $scheduledEvents[] = $row;
}
?>