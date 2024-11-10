<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Your Discord public key
$public_key = "feb63653b4ed06cd67658691b9a850eb2ead67fad1db42ce415cca14cfe0cd5b";

// Function to verify the request signature
function verify_signature($public_key, $timestamp, $body, $signature) {
    // Check if the signature has the correct length (128 hex characters, or 64 bytes when converted)
    if (strlen($signature) !== 128) {
        file_put_contents("debug.log", "Signature is not the expected length\n", FILE_APPEND);
        return false;
    }

    // Convert the hex signature to binary
    $signature = hex2bin($signature);
    if ($signature === false) {
        file_put_contents("debug.log", "Failed to convert signature to binary\n", FILE_APPEND);
        return false;
    }

    // Prepare the message (timestamp + body)
    $message = $timestamp . $body;

    // Convert the public key from hex to binary
    $public_key_bin = hex2bin($public_key);
    if ($public_key_bin === false) {
        file_put_contents("debug.log", "Failed to convert public key to binary\n", FILE_APPEND);
        return false;
    }

    // Verify the signature
    return sodium_crypto_sign_verify_detached($signature, $message, $public_key_bin);
}

try {
    // Capture headers and body
    $signature = $_SERVER['HTTP_X_SIGNATURE_ED25519'] ?? '';
    $timestamp = $_SERVER['HTTP_X_SIGNATURE_TIMESTAMP'] ?? '';
    $body = file_get_contents('php://input');

    // Log captured headers and body for debugging
    file_put_contents("debug.log", "Signature: $signature, Timestamp: $timestamp, Body: $body\n", FILE_APPEND);

    // Attempt signature verification
    if (!verify_signature($public_key, $timestamp, $body, $signature)) {
        http_response_code(401);
        echo "Invalid request signature";
        exit();
    }

    // If signature verification passes, log success and proceed
    file_put_contents("debug.log", "Signature verified successfully\n", FILE_APPEND);

    // Parse the request
    $data = json_decode($body, true);

    // Check if JSON decoded successfully
    if ($data === null) {
        file_put_contents("debug.log", "JSON decode error\n", FILE_APPEND);
        throw new Exception("Failed to decode JSON");
    }

    // Respond to ping interaction
    if ($data["type"] == 1) {
        header('Content-Type: application/json');
        echo json_encode(["type" => 1]);
        exit();
    }

    // Example response to a command (if you need this)
    if ($data["type"] == 2) {
        header('Content-Type: application/json');
        echo json_encode([
            "type" => 4,
            "data" => [
                "content" => "Hello! This is your bot's response to the command."
            ]
        ]);
        exit();
    }

} catch (Exception $e) {
    file_put_contents("error.log", $e->getMessage() . "\n", FILE_APPEND);
    http_response_code(500);
    echo "Internal Server Error";
}
?>
