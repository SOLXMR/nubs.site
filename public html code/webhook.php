<?php
// Capture the raw POST data
$input = file_get_contents("php://input");

// Log the incoming webhook data to a file for debugging
file_put_contents("webhook.log", $input . "\n", FILE_APPEND);

// Decode the JSON data
$data = json_decode($input, true);

// Handle the webhook event
if (isset($data['event_type'])) {
    switch ($data['event_type']) {
        case 'your_event_type_here':
            // Perform some action
            break;
        default:
            // Log unknown events
            file_put_contents("webhook.log", "Unknown event: " . $data['event_type'] . "\n", FILE_APPEND);
            break;
    }
}

// Send a response back to Discord to confirm receipt
header('Content-Type: application/json');
echo json_encode(['status' => 'received']);
?>
