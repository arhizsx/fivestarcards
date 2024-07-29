<?php
// run_script.php

if (isset($_POST['folder_id'])) {
    $folder_id = escapeshellarg($_POST['folder_id']);
    $command = "python3 /home/arhizsx/paidout.py {$folder_id} 2>&1"; // Capture both stdout and stderr

    // Execute the Python script and capture output and errors
    $output = shell_exec($command);

    print_r($output);

    die(  );

    // Log the output to a file for debugging
    file_put_contents('/home/arhizsx/debug_output.txt', $output);

    // Read progress from the progress.txt file
    $progress_file = '/home/arhizsx/progress.txt';
    $progress = file_exists($progress_file) ? file_get_contents($progress_file) : '0';

    // Attempt to decode the JSON output
    $json_output = json_decode($output, true);

    if (json_last_error() === JSON_ERROR_NONE) {
        $json_output['progress'] = trim($progress);
        header('Content-Type: application/json');
        echo json_encode($json_output);
    } else {
        header('Content-Type: application/json');
        echo json_encode(["error" => true, "message" => "Invalid JSON output from Python script"]);
    }
}
?>
