<?php
// run_script.php

header('Content-Type: application/json'); // Set the content type to JSON

$response = array();

if (isset($_POST['folder_id'])) {
    $folder_id = escapeshellarg($_POST['folder_id']);
    $command = "python3 /home/arhizsx/paidout.py {$folder_id} --files 2 2>&1"; // Capture both stdout and stderr

    // Execute the Python script and capture output and errors
    $output = shell_exec($command);

    // Prepare the response
    $response['status'] = 'success';
    $response['output'] = json_encode($output);
} else {
    $response['status'] = 'error';
    $response['message'] = 'folder_id not set';
}

// Return the JSON response
echo json_encode($response);
?>
