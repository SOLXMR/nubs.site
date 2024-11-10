<?php
// db.php - Database configuration
$db_config = [
    'servername' => 'localhost', // Usually localhost, but check your hosting
    'username' => 'nubsmrbf_chatappadmin', // Your database username
    'password' => 'Fortnite0019!', // Your database password
    'dbname' => 'nubsmrbf_chat_app_db' // Your database name
];

// Create the database connection
$conn = new mysqli($db_config['servername'], $db_config['username'], $db_config['password'], $db_config['dbname']);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
