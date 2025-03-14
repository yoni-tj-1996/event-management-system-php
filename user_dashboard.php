<?php
require 'user_dashboard_logic.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
<style>
        .navbar {
            background-color: #333;
            padding: 1em;
            text-align: center;
        }

        .navbar ul {
            list-style-type: none;
            margin: 0;
            padding: 0;
        }

        .navbar li {
            display: inline-block;
            margin-right: 20px;
        }

        .navbar a {
            color: white;
            text-decoration: none;
            padding: 14px 16px;
        }

        .dropdown .dropbtn {
            font-size: 16px;
            border: none;
            outline: none;
            color: white;
            padding: 14px 16px;
            background-color: inherit;
        }

        .dropdown-content {
            display: none;
            position: absolute;
            background-color: #f9f9f9;
            min-width: 160px;
            box-shadow: 0px 8px 16px 0px rgba(0,0,0,0.2);
            z-index: 1;
        }

        .dropdown-content a {
            float: none;
            color: black;
            padding: 12px 16px;
            text-decoration: none;
            display: block;
            text-align: left;
        }

        .dropdown-content a:hover {
            background-color: #f1f1f1;
        }

        .dropdown:hover .dropdown-content {
            display: block;
        }

        .dropdown:hover .dropbtn {
            background-color: #3e8e41;
        }
    </style>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Dashboard</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <nav class="navbar">
        <ul>
            <li><a href="index.php">Home</a></li> 
            <li class="dropdown">
                <a href="#" class="dropbtn">User role</a>
                <div class="dropdown-content">
                    <a href="scheduleEvents.php">View Scheduled Events</a>
                    <a href="./cancelRegistration.php">Cancel Registration</a>
                    <a href="./fetch_notifications.php">view SMS</a>
                </div>
            </li>
            <li style="float:right;"><a href="./loginphp.php">Logout</a></li>
        </ul>
    </nav>

    <div class="container">
        <h2>User Dashboard</h2>

        <!-- Display Input Values in Labels -->
        <?php if ($_SERVER['REQUEST_METHOD'] == 'POST') { ?>
            <div>
                <label>Event ID: <?php echo htmlspecialchars($event_id); ?></label>
                <br>
                <label>Email: <?php echo htmlspecialchars($email); ?></label>
                <br>
                <label>Phone Number: <?php echo htmlspecialchars($phone_number); ?></label>
            </div>
        <?php } ?>

        <!-- Event Registration Form -->
        <form id="registerForm" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post">
            <div class="form-group">
                <label for="event">Select Event:</label>
                <select id="event" name="event" required>
                    <?php foreach ($events as $event) { ?>
                        <option value="<?php echo htmlspecialchars($event['id']); ?>"><?php echo htmlspecialchars($event['title']); ?></option>
                    <?php } ?>
                </select>
            </div>
            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" placeholder="Email" required value="<?php echo htmlspecialchars($email); ?>">
            </div>
            <div class="form-group">
                <label for="phone_number">Phone Number:</label>
                <input type="text" id="phone_number" name="phone_number" placeholder="Phone Number" required value="<?php echo htmlspecialchars($phone_number); ?>">
            </div>
            <button type="submit">Register</button>
        </form>

        <!-- Scheduled Events -->
        <div id="scheduledEvents">
            <h3>Your Scheduled Events</h3>
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
                <tbody id="eventTableBody">
                    <?php foreach ($scheduledEvents as $event) { ?>
                        <tr>
                            <td><?php echo htmlspecialchars($event['title']); ?></td>
                            <td><?php echo htmlspecialchars($event['description']); ?></td>
                            <td><?php echo htmlspecialchars($event['venue']); ?></td>
                            <td><?php echo htmlspecialchars($event['start_date']); ?></td>
                            <td><?php echo htmlspecialchars($event['end_date']); ?></td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
   
 
</body>
</html>

