<?php
require 'db.php'; // Include your database connection file
session_start();

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    die("error=User not logged in.");
}

// Initialize variables
$event_id = '';
$email = '';
$phone_number = '';
$events = [];
$scheduledEvents = [];

// Fetch events from the database
$eventsQuery = "SELECT id, title FROM events";
$eventsResult = mysqli_query($conn, $eventsQuery);
if ($eventsResult) {
    while ($row = mysqli_fetch_assoc($eventsResult)) {
        $events[] = $row;
    }
}

// Fetch scheduled events for the logged-in user
$user_id = $_SESSION['user_id'];
$scheduledEventsQuery = "SELECT e.title, e.description, e.venue, e.start_date, e.end_date 
                         FROM registrations r 
                         JOIN events e ON r.event_id = e.id 
                         WHERE r.user_id = '$user_id'";
$scheduledEventsResult = mysqli_query($conn, $scheduledEventsQuery);
if ($scheduledEventsResult) {
    while ($row = mysqli_fetch_assoc($scheduledEventsResult)) {
        $scheduledEvents[] = $row;
    }
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $event_id = filter_var($_POST['event_id'], FILTER_SANITIZE_NUMBER_INT);
    $email = filter_var($_POST['email'], FILTER_VALIDATE_EMAIL);
    $phone_number = filter_var($_POST['phone_number'], FILTER_SANITIZE_STRING);

    if (!$event_id || !$email || !$phone_number) {
        echo "error=Invalid input. Please check your details.";
        exit;
    }

    // Insert registration into the database
    $query = "INSERT INTO registrations (event_id, user_id, email, phone_number) 
              VALUES (?, ?, ?, ?)";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "iiss", $event_id, $user_id, $email, $phone_number);
    if (mysqli_stmt_execute($stmt)) {
        echo "success=Registration successful!";
    } else {
        echo "error=Failed to register: " . mysqli_stmt_error($stmt);
    }
    mysqli_stmt_close($stmt);
}
?>