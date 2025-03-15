<?php
session_start();
require 'db.php';





if ($_SERVER['REQUEST_METHOD'] == 'POST') {


    $event_id = $_POST['reminder-event'];
    $message =$_POST['message'];

    
    if (empty($event_id)) {
        die("Error: Invalid event ID.");
    }

    // Check if event exists in the events table
    $checkEventQuery = "SELECT id FROM events WHERE id = ?";
    $stmt = mysqli_prepare($conn, $checkEventQuery);
    mysqli_stmt_bind_param($stmt, "i", $event_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    
    if (mysqli_num_rows($result) == 0) {
        die("Error: Event does not exist.");
    }
    mysqli_stmt_close($stmt);

    // Insert notification into the database
    $insertNotification = "INSERT INTO notifications (event_id, message, sent_at) VALUES (?, ?, NOW())";
    $stmt = mysqli_prepare($conn, $insertNotification);

    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "is", $event_id, $message);
        if (mysqli_stmt_execute($stmt)) {
            echo "Notification added successfully!";
        } else {
            echo "Error inserting notification: " . mysqli_stmt_error($stmt);
        }
        mysqli_stmt_close($stmt);
    } else {
        echo "Error preparing statement: " . mysqli_error($conn);
    }

    mysqli_close($conn); // Close connection

    // Redirect after successful notification
    header("Location: manager_dashboard.php");
    exit();
} else {
    exit("Invalid request.");
}
?>
