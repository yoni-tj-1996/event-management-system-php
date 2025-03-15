<?php
require 'db.php';
session_start();

// Check if the user is logged in and is a manager
if (!isset($_SESSION['user_id'])) {
    echo '<script>window.location.href = "loginphp.php";</script>';
    exit();
}

// Initialize error messages
$errors = array(
    'title' => '',
    'description' => '',
    'venue' => '',
    'start_date' => '',
    'end_date' => '',
);

// Function to get all events from the database
function getEvents($conn) {
    $query = "SELECT * FROM events ORDER BY start_date DESC";
    $result = mysqli_query($conn, $query);
    $events = [];
    
    if ($result) {
        while ($row = mysqli_fetch_assoc($result)) {
            $events[] = $row;
        }
    }
    
    return $events;
}

// Function to get attendees for a specific event
function getAttendees($conn, $event_id) {
    $query = "SELECT u.name, u.email, a.registration_date 
              FROM attendees a 
              JOIN users u ON a.user_id = u.id 
              WHERE a.event_id = ?";
    
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "i", $event_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    
    $attendees = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $attendees[] = $row;
    }
    
    return $attendees;
}

// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['event-name'])) {
        // Collect form data
        $eventName = $_POST['event-name'];
        $eventDate = $_POST['start-date'];
        $endDate = $_POST['end-date'];
        $eventLocation = $_POST['event-location'];
        $eventDescription = $_POST['event-description'];
        $eventOrganizer = $_POST['event-organizer'];

        if (empty($eventName) || empty($eventDate) || empty($endDate) || empty($eventLocation) || empty($eventDescription) || empty($eventOrganizer)) {
            echo "All fields are required.";
            exit;
        }

        if (empty($errors['title']) && empty($errors['description']) && empty($errors['venue']) && empty($errors['start_date']) && empty($errors['end_date'])) {
            $manager_id = $_SESSION['user_id'];
          
            $sql = "INSERT INTO events (title, start_date, end_date, description, event_organizer, event_location, manager_id) VALUES (?, ?, ?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);
            if (!$stmt) {
                die("Prepare failed: " . $conn->error);
            }

            $stmt->bind_param("ssssssi", $eventName, $eventDate, $endDate, $eventDescription, $eventOrganizer, $eventLocation, $manager_id);
            if ($stmt->execute()) {
                echo "Event added successfully!";
               
                }
            } else {
                echo "<script>alert('Failed to create event: " . mysqli_stmt_error($stmt) . "');</script>";
            }

            // Close statement
            mysqli_stmt_close($stmt);
        }
    } elseif (isset($_POST['action']) && $_POST['action'] == 'send_reminders') {
        $event_id = $_POST['reminder-event'];

        if (!empty($event_id)) {
            $attendees = getAttendees($conn, $event_id);
            
            $eventQuery = "SELECT title, start_date, venue FROM events WHERE id = ?";
            $stmt = mysqli_prepare($conn, $eventQuery);
            mysqli_stmt_bind_param($stmt, "i", $event_id);
            mysqli_stmt_execute($stmt);
            $_SESSION['event_id'] = $event_id;
            $result = mysqli_stmt_get_result($stmt);
            $event = mysqli_fetch_assoc($result);

            foreach ($attendees as $attendee) {
                $to = $attendee['email'];
                $subject = "Reminder: " . $event['title'];
                $message = "Hello " . $attendee['name'] . ",\n\n";
                $message .= "This is a reminder for the upcoming event: " . $event['title'] . "\n";
                $message .= "Date: " . $event['start_date'] . "\n";
                $message .= "Venue: " . $event['venue'] . "\n\n";
                $message .= "We look forward to seeing you there!";
                $headers = "From: your-email@example.com\r\n";
                
               
            }
            
            echo "<script>alert('Reminders sent successfully!');</script>";
        } else {
            echo "<script>alert('Please select an event!');</script>";
        }
    }


$events = getEvents($conn);
?>