<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

include 'db.php';

$conn = new mysqli($db_config['servername'], $db_config['username'], $db_config['password'], $db_config['dbname']);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch messages from the correct table
$sql = "SELECT username, message, timestamp FROM messages ORDER BY timestamp ASC";
$result = $conn->query($sql);

$messages = [];

if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $messages[] = [
            'username' => $row['username'],
            'message' => $row['message'],
            'timestamp' => gmdate('Y-m-d\TH:i:s\Z', strtotime($row['timestamp']))  // Convert to UTC ISO format
        ];
    }
}

echo json_encode($messages);

$conn->close();
?>
