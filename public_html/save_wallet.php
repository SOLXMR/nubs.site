<?php
// Allow CORS for your frontend domain
header("Access-Control-Allow-Origin: https://nubs.site"); // Ensure this is the correct origin
header("Access-Control-Allow-Headers: Content-Type");
header("Access-Control-Allow-Methods: POST, OPTIONS");

// Handle preflight OPTIONS request
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(204);
    exit; 
}

// Database credentials
$dbHost = 'localhost'; 
$dbName = 'nubsmrbf_walletDB'; 
$dbUser = 'nubsmrbf_walletuser'; 
$dbPass = 'password'; 

// Create a new PDO instance for database connection
try {
    $pdo = new PDO("mysql:host=$dbHost;dbname=$dbName", $dbUser, $dbPass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo json_encode(['status' => 'error', 'message' => 'Database connection failed: ' . $e->getMessage()]);
    exit;
}

// Get the raw POST data
$json = file_get_contents('php://input');
$data = json_decode($json, true); 

// Check if public key is provided
if (isset($data['publicKey'])) {
    $publicKey = $data['publicKey'];
    
    // Save the public key into the database
    $stmt = $pdo->prepare("INSERT INTO wallets (public_key) VALUES (:publicKey)");
    $stmt->bindParam(':publicKey', $publicKey);

    if ($stmt->execute()) {
        echo json_encode(['status' => 'success', 'message' => 'Public key saved: ' . $publicKey]);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Failed to save public key.']);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Public key not provided.']);
}
?>
