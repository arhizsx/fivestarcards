<?php

// Load WordPress functions
require_once __DIR__ . '/wp-load.php';

// Set up logging (optional, useful for debugging)
$log_file = __DIR__ . '/fb-webhook.log';

function log_message($message) {
    global $log_file;
    file_put_contents($log_file, "[" . date("Y-m-d H:i:s") . "] " . $message . "\n", FILE_APPEND);
}

// Facebook Verify Token (Set in ENV or hardcode)
$VERIFY_TOKEN = 'makina_verify';

// Webhook Verification (GET Request)
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    if ($_GET['hub_mode'] === 'subscribe' && $_GET['hub_verify_token'] === $VERIFY_TOKEN) {
        echo $_GET['hub_challenge'];
        log_message("Webhook verified successfully.");
        exit;
    } else {
        http_response_code(403);
        echo "Forbidden: Verification failed.";
        log_message("Webhook verification failed.");
        exit;
    }
}

// Handle Incoming Messages (POST Request)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {


    // Read incoming payload
    $data = file_get_contents("php://input");

    return  $data;

    log_message("Received Payload: " . $data);

    // Decode JSON
    $json = json_decode($data, true);

    if (!$json) {
        log_message("Invalid JSON received.");
        http_response_code(400);
        echo json_encode(['status' => 'error', 'message' => 'Invalid JSON']);
        exit;
    }

    // Insert into Database
    global $wpdb;
    $table_name = $wpdb->prefix . 'fb_webhooks'; // Ensure you create this table

    $result = $wpdb->insert($table_name, [
        'payload' => json_encode($json),
        'created_at' => current_time('mysql')
    ]);

    if ($result === false) {
        log_message("Database insert failed: " . $wpdb->last_error);
        http_response_code(500);
        echo json_encode(['status' => 'error', 'message' => 'Database insert failed']);
        exit;
    }

    log_message("Webhook data stored successfully.");
    echo json_encode(['status' => 'success']);
    exit;
}

// Default Response
http_response_code(405);
echo "Method Not Allowed";
log_message("Invalid request method.");
exit;
