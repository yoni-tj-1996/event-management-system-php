<?php
require 'db.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    die("error=User not logged in.");
}

$user_id = $_SESSION['user_id'];

// Fetch user's scheduled events
$scheduledEventsQuery = "SELECT e.id, e.title 
                         FROM registrations r 
                         JOIN events e ON r.event_id = e.id 
                         WHERE r.user_id = '$user_id'";
$scheduledEventsResult = mysqli_query($conn, $scheduledEventsQuery);
$scheduledEvents = array();
while ($row = mysqli_fetch_assoc($scheduledEventsResult)) {
    $scheduledEvents[] = $row;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $event_id = filter_var($_POST['event_id'], FILTER_SANITIZE_NUMBER_INT);

    // Prepare SQL query to cancel registration
    $query = "DELETE FROM registrations WHERE event_id = ? AND user_id = ?";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "ii", $event_id, $user_id);
    if (mysqli_stmt_execute($stmt)) {
        echo "<div class='alert success'>Registration cancelled successfully!</div>";
    } else {
        echo "<div class='alert error'>Failed to cancel registration: " . mysqli_stmt_error($stmt) . "</div>";
    }
    mysqli_stmt_close($stmt);
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cancel Registration</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f0f0f0;
        }
        .container {
            max-width: 600px;
            margin: 40px auto;
            padding: 20px;
            background-color: white;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        .form-group {
            margin-bottom: 20px;
        }
        label {
            display: block;
            margin-bottom: 10px;
        }
        select {
            width: 100%;
            height: 40px;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        button[type="submit"] {
            width: 100%;
            height: 40px;
            background-color: #4CAF50;
            color: white;
            padding: 10px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        button[type="submit"]:hover {
            background-color: #3e8e41;
        }
        .alert {
            padding: 10px;
            border-radius: 5px;
            margin-bottom: 20px;
        }
        .alert.success {
            background-color: #dff0d8;
            border: 1px solid #8bc34a;
            color: #3e8e41;
        }
        .alert.error {
            background-color: #f2dede;
            border: 1px solid #a94442;
            color: #a94442;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Cancel Registration</h2>
        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
            <div class="form-group">
                <label for="event_id">Select Event:</label>
                <select id="event_id" name="event_id" required>
                    <?php foreach ($scheduledEvents as $event) { ?>
                        <option value="<?php echo $event['id']; ?>"><?php echo $event['title']; ?></option>
                    <?php } ?>
                </select>
            </div>
            <button type="submit">Cancel</button>
        </form>
    </div>
</body>
</html>
