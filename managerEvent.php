<?php



require 'db.php';
session_start();

// Check if the user is logged in and is a manager
if (!isset($_SESSION['user_id'])) {
    echo '<script>window.location.href = "login.php";</script>';
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

// Function to get all events from database
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
$_SESSION['event_id']='';
$event = "SELECT * FROM events LIMIT 1"; // Fetch at least one event
$res = mysqli_query($conn, $event);
if ($c = mysqli_fetch_assoc($res)) {
    if (isset($c['event_id'])) {  // Ensure event_id exists
        $_SESSION['event_id'] = $c['event_id'];
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['event-name'])) {
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
      
    $sql="INSERT INTO events (title, start_date, end_date,  description, event_organizer,event_location,manager_id) VALUES (?, ?,?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        die("Prepare failed: " . $conn->error);
    }

    $stmt->bind_param("ssssssi", $eventName, $eventDate, $endDate, $eventDescription, $eventOrganizer,$eventLocation, $manager_id );
    if ($stmt->execute()) {
        echo "Event added successfully!";
        $title=$eventName;
       // Send notification to users
       $notificationQuery = "SELECT email FROM users WHERE role = 'user'";
       $notificationResult = mysqli_query($conn, $notificationQuery);
       while ($row = mysqli_fetch_assoc($notificationResult)) {
           $to = $row['email'];
           $subject = "New Event Created: $title";
           $message = "A new event titled '$title' has been created. Please check the event list for details.";
           $headers = "From:mail.mydomain.com";
           mail($to, $subject, $message, $headers);
       }
   } else {
       echo "<script>alert('Failed to create event: " . mysqli_stmt_error($stmt) . "');</script>";
   }

   // Close statement
   mysqli_stmt_close($stmt);
}}
$action = isset($_POST['action']) ? $_POST['action'] : '';


 if ($action == 'send_reminders') {
$event_id = $_POST['reminder-event'];

if (!empty($event_id)) {

   $attendees = getAttendees($conn, $event_id);
   
   $eventQuery = "SELECT title, start_date, venue FROM events WHERE id = ?";
   $stmt = mysqli_prepare($conn, $eventQuery);
   mysqli_stmt_bind_param($stmt, "i", $event_id);
   mysqli_stmt_execute($stmt);
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
       
       mail($to, $subject, $message, $headers);
   }
   
   echo "<script>alert('Reminders sent successfully!');</script>";
} 
else 
{
   echo "<script>alert('Please select an event!');</script>";
}
}

$events = getEvents($conn);
?>


<!DOCTYPE html>
<html lang="en">
<head>
   
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Manager Dashboard</title>
    <link rel="stylesheet" href="styles.css" />
    <script src="scripts.php"></script>
</head>
<body>
    <div class="dashboard">
        <!-- Sidebar Navigation -->
        <div class="sidebar">
            <h2>Dashboard</h2>
            <ul>
                <li>
                    <a href="#event-management" class="nav-link active" >Event Management</a>
                </li>
                <li><a href="#attendee-list" class="nav-link">Attendee List</a></li>
                <li><a href="#reminders" class="nav-link" >Send Reminders</a></li>
            </ul>
        </div>

        <!-- Main Content -->
        <div class="main-content">
            <!-- Event Management Section -->
            <section id="event-management" class="content-section active">
                <h2>Manage Events</h2>
                <form id="event-form" method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>">
                    <input type="hidden" name="action" value="create">
                    <label for="event_name">Event Name:</label>
                    <input
                        type="text"
                        id="event-name"
                        name="event-name"
                        placeholder="Event Name"
                        required
                    />
                    <?php if (!empty($errors['title'])) { ?>
                        <span class="error-message"><?php echo $errors['title']; ?></span>
                    <?php } ?>
                    <label for="start_date">Start Date:</label>
                    <input type="datetime-local" id="event-date" name="start-date" required />
                    <?php if (!empty($errors['start_date'])) { ?>
                        <span class="error-message"><?php echo $errors['start_date']; ?></span>
                    <?php } ?>
                    <label for="end_date">End Date:</label>
                    <input type="datetime-local" id="end-date" name="end-date" required />
                    <?php if (!empty($errors['end_date'])) { ?>
                        <span class="error-message"><?php echo $errors['end_date']; ?></span>
                    <?php } ?>
                    <label for="event_name">Event Location:</label>
                    <input
                        type="text"
                        id="event-location"
                        name="event-location"
                        placeholder="Location"
                        required
                    />
                    <?php if (!empty($errors['venue'])) { ?>
                        <span class="error-message"><?php echo $errors['venue']; ?></span>
                    <?php } ?>
                    <label for="event-description">Event Description:</label>
                    <textarea
                        id="event-description"
                        name="event-description"
                        placeholder="Description"
                        required
                    ></textarea>
                    <?php if (!empty($errors['description'])) { ?>
                        <span class="error-message"><?php echo $errors['description']; ?></span>
                    <?php } ?>
                    <label for="event-organizer">Event Organizer:</label>
                    <input
                        type="text"
                        id="event-organizer"
                        name="event-organizer"
                        placeholder="Organizer"
                        required
                    />
                    <button type="submit">Add Event</button>
                </form>
                </section>


            </section>

            <!-- Attendee List Section -->
            <section id="attendee-list" class="content-section">
            <div id="event-list">
                    <h3>Event List</h3>
                    <table>
                        <thead>
                            <tr>
                                <th>Title</th>
                                <th>Description</th>
                                <th>Venue</th>
                                <th>Start Date</th>
                                <th>End Date</th>
                                <th>Organizer</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach($events as $event): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($event['title']); ?></td>
                                <td><?php echo htmlspecialchars($event['description']); ?></td>
                                <td><?php echo htmlspecialchars($event['venue']); ?></td>
                                <td><?php echo htmlspecialchars($event['start_date']); ?></td>
                                <td><?php echo htmlspecialchars($event['end_date']); ?></td>
                                <td><?php echo htmlspecialchars($event['organizer'] ?? 'N/A'); ?></td>
                                <td>
                                    <button onclick="editEvent(<?php echo $event['id']; ?>)">Edit</button>
                                    <button onclick="deleteEvent(<?php echo $event['id']; ?>)">Delete</button>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                    <button onclick="exportCSV()">Export to CSV</button>
                </div>
              
            </section>

            <!-- Reminders Section -->
            <section id="reminders" class="content-section">
            <h3>Send Notification</h3>

                <form method="POST" action="./send_notification.php">
                    <input type="hidden" name="action" value="send_reminders">
                    <select id="reminder-event" name="reminder-event">
                        <option value="">Select Event</option>
                        <?php foreach($events as $event): ?>
                        <option value="<?php echo $event['id']; ?>"><?php echo htmlspecialchars($event['title']); ?></option>
                        <?php endforeach; ?>
                    </select>
                   
            <div class="form-group">
                <label for="message">Message:</label>
                <textarea id="message" name="message" placeholder="Notification Message" required></textarea>
            </div>
                     <button type="submit">Send Notification</button>
                </form>
            </section>
        </div>
    </div>
<script scr="j.js">document.addEventListener('DOMContentLoaded', function() {
    const navLinks = document.querySelectorAll('.sidebar ul li a');

    const contentSections = document.querySelectorAll('.content-section');

    navLinks.forEach(link => {
        link.addEventListener('click', function(e) {
            e.preventDefault();

            navLinks.forEach(navLink => {
                navLink.classList.remove('active');
            });
            

            this.classList.add('active');

            const targetId = this.getAttribute('href');
        
            contentSections.forEach(section => {
                section.classList.remove('active');
            });
        
            document.querySelector(targetId).classList.add('active');
        });
    });
});
function exportEventsCSV() {
    window.location.href = './export_csv.php';
}
</script>

<?php
require 'footer.php';
?>
</body>
</html>
