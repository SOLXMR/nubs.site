<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

include 'db.php';

$conn = new mysqli($db_config['servername'], $db_config['username'], $db_config['password'], $db_config['dbname']);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get the raw POST data
    $data = json_decode(file_get_contents('php://input'), true);

    // Check if message is set
    if (isset($data['message'])) {
        $message = $data['message'];
    } else {
        echo "No message received.";
        exit;
    }

    // Check if the username is set in the session
    if (isset($_SESSION['username'])) {
        $username = $_SESSION['username'];
        echo "Message being sent by: " . $username; // Debugging output
    } else {
        echo "Username not found in session.";
        exit;
    }

    // Create a timestamp for the message
    $timestamp = date('Y-m-d H:i:s');

    // Prepare and bind the SQL statement
    $stmt = $conn->prepare("INSERT INTO messages (username, message, timestamp) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $username, $message, $timestamp); // "sss" indicates three string parameters

    // Execute the statement
    if ($stmt->execute()) {
        echo "Message inserted successfully.";
    } else {
        echo "Error inserting message: " . $stmt->error; // Use $stmt->error for prepared statement errors
    }

    // Close the statement
    $stmt->close();
} else {
    echo "Invalid request method.";
}

// Close the connection
$conn->close();
?>
