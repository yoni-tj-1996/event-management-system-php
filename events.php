<?php
require 'db.php';

session_start(); // Start session to access user_id

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $action = $_POST['action'];

    if ($action == 'create') {
        // Check if user_id is set in session
        if (!isset($_SESSION['user_id'])) {
            die("error=User not logged in.");
        }

        // Create event
        $title = $_POST['title'];
        $description = $_POST['description'];
        $venue = $_POST['venue'];
        $start_date = $_POST['start_date'];
        $end_date = $_POST['end_date'];
        $manager_id = $_SESSION['user_id']; // Get manager_id from session

        // Insert event into database
        $query = "insert INTO events (title, description, venue, start_date, end_date, manager_id) 
                  VALUES ('$title', '$description', '$venue', '$start_date', '$end_date', '$manager_id')";
        if (mysqli_query($conn, $query)) {
            echo "success=Event created successfully!";
        } else {
            echo "error=Failed to create event: " . mysqli_error($conn);
        }
    } elseif ($action == 'delete') {
        // Delete event
        $event_id = $_POST['event_id'];
        $query = "DELETE FROM events WHERE id = '$event_id'";
        // if (mysqli_query($conn, $query)) {
        //     echo "success=Event deleted successfully!";
        // } else {
        //     echo "error=Failed to delete event: " . mysqli_error($conn);
        // }
    }
}
?>