<?php
// run_script.php

if (isset($_POST['folder_id'])) {
    $folder_id = escapeshellarg($_POST['folder_id']);
    $command = "python3 /path/to/your/paidout.py {$folder_id} 2>&1"; // Capture both stdout and stderr

    // Execute the Python script and capture output and errors
    $output = shell_exec($command);

    print_r($output);
    
    die(  );

    // Log the output to a file for debugging
    file_put_contents('/path/to/debug_output.txt', $output);

    // Check if output is empty
    if (empty($output)) {
        header('Content-Type: application/json');
        echo json_encode(["error" => true, "message" => "No output from Python script"]);
    } else {
        // Try to decode the JSON output
        $json_output = json_decode($output, true);
        if (json_last_error() === JSON_ERROR_NONE) {
            header('Content-Type: application/json');
            echo $output;
        } else {
            header('Content-Type: application/json');
            echo json_encode(["error" => true, "message" => "Invalid JSON output from Python script"]);
        }
    }
}
?>
