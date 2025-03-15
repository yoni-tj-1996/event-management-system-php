<?php
$host = 'localhost';
$username = 'root';
$password = '';
$database = 'event_management';

// Create connection
$conn = mysqli_connect($host, $username, $password);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

$sql="create DATABASE if not exists event_management;";

if(!mysqli_query($conn,$sql))
{
    die("database creation failed: " . mysqli_connect_error());
}
mysqli_select_db($conn,$database);
$sql="create TABLE if not exists users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    phone_number VARCHAR(15),
    role ENUM('manager', 'user') NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);";
if(!mysqli_query($conn,$sql))
{
    die("database creation failed: " . mysqli_connect_error());
}
$sql="create TABLE if not exists events (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    description TEXT,
    venue VARCHAR(255),
    start_date DATETIME,
    end_date DATETIME,
    manager_id INT,
     event_organizer varchar(20),
    FOREIGN KEY (manager_id) REFERENCES users(id)
);";

if(!mysqli_query($conn,$sql))
{
    die("database creation failed: " . mysqli_connect_error());
}
$sql="create TABLE if not exists registrations (
 
    id INT AUTO_INCREMENT PRIMARY KEY,
    event_id INT,
    user_id INT,
    email VARCHAR(100),
    phone_number VARCHAR(15),
    registration_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (event_id) REFERENCES events(id),
    FOREIGN KEY (user_id) REFERENCES users(id)

);";

if(!mysqli_query($conn,$sql))
{
    die("database creation failed: " . mysqli_connect_error());
}
$sql="
CREATE TABLE if not exists notifications (
    id INT AUTO_INCREMENT PRIMARY KEY,
    event_id INT NOT NULL,
    message TEXT NOT NULL,
    sent_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (event_id) REFERENCES events(id) ON DELETE CASCADE
);";

if(!mysqli_query($conn,$sql))
{
    die("database creation failed: " . mysqli_connect_error());
}
?>