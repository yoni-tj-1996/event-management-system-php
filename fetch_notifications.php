<?php
require 'db.php';

session_start(); // Start session to access user_id
/*
if (!isset($_SESSION['user_id'])) {
    die("error=User not logged in.");
}

$user_id = $_SESSION['user_id'];

// Fetch events the user has registered for
$query = "SELECT e.* FROM events e
          JOIN registrations r ON e.id = r.event_id
          WHERE r.user_id = '$user_id'";
$result = mysqli_query($conn, $query);

$events = [];
while ($row = mysqli_fetch_assoc($result)) {
    $events[] = $row;
}

echo json_encode($events); // Return events as JSON
*/
?>
<?php


// Ensure the user is logged in
if (!isset($_SESSION['user_id'])) {
    echo "<script>alert('Please log in to view notifications.'); window.location.href='login.php';</script>";
    exit();
}

$user_id = $_SESSION['user_id'];

// Query to retrieve notifications for events the user has registered for
$query = "SELECT n.id, n.message, n.sent_at, e.title AS event_title 
          FROM notifications n
          JOIN events e ON n.event_id = e.id
          JOIN registrations r ON e.id = r.event_id
          WHERE r.user_id = ?
          ORDER BY n.sent_at DESC";

$stmt = mysqli_prepare($conn, $query);
mysqli_stmt_bind_param($stmt, "i", $user_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

$notifications = [];
while ($row = mysqli_fetch_assoc($result)) {
    $notifications[] = $row;
}

mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Notifications</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <h2>My Event Notifications</h2>
    <table styles="border='1'">
        <thead>
            <tr>
                <th>Event</th>
                <th>Message</th>
                <th>Sent At</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($notifications)): ?>
                <?php foreach ($notifications as $notification): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($notification['event_title']); ?></td>
                        <td><?php echo htmlspecialchars($notification['message']); ?></td>
                        <td><?php echo htmlspecialchars($notification['sent_at']); ?></td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="3">No notifications for your registered events.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</body>
</html>

