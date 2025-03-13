<?php
require 'db.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    die("error=User not logged in.");
}

$user_id = $_SESSION['user_id'];

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

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Scheduled Events</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f0f0f0;
        }
        .container {
            max-width: 800px;
            margin: 40px auto;
            padding: 20px;
            background-color: white;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: left;
        }
        th {
            background-color: #f0f0f0;
        }
        tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        h2 {
            margin-top: 0;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Your Scheduled Events</h2>
        <table>
            <thead>
                <tr>
                    <th>Title</th>
                    <th>Description</th>
                    <th>Venue</th>
                    <th>Start Date</th>
                    <th>End Date</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($scheduledEvents as $event) { ?>
                    <tr>
                        <td><?php echo $event['title']; ?></td>
                        <td><?php echo $event['description']; ?></td>
                        <td><?php echo $event['venue']; ?></td>
                        <td><?php echo $event['start_date']; ?></td>
                        <td><?php echo $event['end_date']; ?></td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
</body>
</html>
