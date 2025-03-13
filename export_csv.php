<?php
require 'db.php';

header('Content-Type: text/csv');
header('Content-Disposition: attachment; filename="events.csv"');

$output = fopen('php://output', 'w');
fputcsv($output, ['ID', 'Title', 'Description', 'Venue', 'Start Date', 'End Date']);

$query = "SELECT * FROM events";
$result = mysqli_query($conn, $query);

while ($row = mysqli_fetch_assoc($result)) {
    fputcsv($output, $row);
}
?>