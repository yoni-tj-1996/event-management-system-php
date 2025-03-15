<?php
session_start();
require 'db.php';
/*
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $event_id = $_SESSION['event_id']
    $message = $_POST['message'];

    $query = "select users.email FROM registrations 
              JOIN users ON registrations.user_id = users.id 
              WHERE registrations.event_id = '$event_id'";
    $result = mysqli_query($conn, $query);

    while ($row = mysqli_fetch_assoc($result)) {
        $to = $row['email'];
        $subject = "Event Notification";
        $headers = "From: no-reply@eventmanagement.com";

        // Send email
        mail($to, $subject, $message, $headers);
    }

    header("interface_events.php");
}*/
?>
<?php



if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (!isset($_SESSION['event_id'])) {
        die("Event ID is not set.");
    }

    $event_id = $_SESSION['event_id'];
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
    header("Location: interface_events.php");
    exit();
} else {
    exit("Invalid request.");
}
?>
