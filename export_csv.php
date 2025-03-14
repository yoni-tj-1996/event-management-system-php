<?php
require 'db.php'; 

header('Content-Type: text/csv');
header('Content-Disposition: attachment; filename="events.csv"');
$output = fopen('php://output', 'w');

fputcsv($output, ['ID', 'Title', 'Description', 'Venue', 'Start Date', 'End Date']);

$query = "SELECT id, title, description, venue, start_date, end_date FROM events"; // Make sure the column names match
$result = mysqli_query($conn, $query);

if ($result) {

    while ($row = mysqli_fetch_assoc($result)) {
        fputcsv($output, $row); // Write data row to CSV
    }
} else {
    echo "Error: " . mysqli_error($conn); 
}

mysqli_close($conn); 
exit(); 
?>
