<?php
// run_script.php

if (isset($_POST['folder_id'])) {
    $folder_id = escapeshellarg($_POST['folder_id']);
    $command = "python3 /home/arhizsx/paidout.py {$folder_id} 2>&1"; // Capture both stdout and stderr
    
    // Execute the Python script and capture output and errors
    $output = shell_exec($command);
    
    // Log the output to a file for debugging
    file_put_contents('/home/arhizsx/debug_output.txt', $output);

    // Check for errors in command execution
    if ($output === null) {
        header('Content-Type: application/json');
        echo json_encode(["error" => true, "message" => "Failed to execute Python script"]);
    } else {
        header('Content-Type: application/json');
        echo $output;
    }
}
?>
