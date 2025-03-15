<?php
require 'manager_dashboard_logic.php';
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
                <li><a href="#event-management" class="nav-link active">Event Management</a></li>
                <li><a href="#attendee-list" class="nav-link">Attendee List</a></li>
                <li><a href="#reminders" class="nav-link">Send Reminders</a></li>
                <li><a href="loginphp.php">logout</a></li>
        
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
                    <input type="text" id="event-name" name="event-name" placeholder="Event Name" required />
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
                    <input type="text" id="event-location" name="event-location" placeholder="Location" required />
                    <?php if (!empty($errors['venue'])) { ?>
                        <span class="error-message"><?php echo $errors['venue']; ?></span>
                    <?php } ?>
                    <label for="event-description">Event Description:</label>
                    <textarea id="event-description" name="event-description" placeholder="Description" required></textarea>
                    <?php if (!empty($errors['description'])) { ?>
                        <span class="error-message"><?php echo $errors['description']; ?></span>
                    <?php } ?>
                    <label for="event-organizer">Event Organizer:</label>
                    <input type="text" id="event-organizer" name="event-organizer" placeholder="Organizer" required />
                    <button type="submit">Add Event</button>
                </form>
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
                            <?php foreach ($events as $event): ?>
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
                <h3>Send SMS</h3>
                <form method="POST" action="send_notification.php">
                    <input type="hidden" name="action" value="send_reminders">
                    <select id="reminder-event" name="reminder-event">
                        <option value="">Select Event</option>
                        <?php foreach ($events as $event): ?>
                            <option value="<?php echo $event['id']; ?>" ><?php echo htmlspecialchars($event['title']); ?></option>
                        <?php endforeach; ?>
                    </select>
                    <div class="form-group">
                        <label for="message">Message:</label>
                        <textarea id="message" name="message" placeholder="Notification Message" required></textarea>
                    </div>
                    <button type="submit">Send SMS</button>
                </form>
            </section>
        </div>
    </div>

    <script scr="j.js">

    </script>

</body>
</html>