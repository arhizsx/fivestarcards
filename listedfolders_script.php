<?php
// run_script.php

header('Content-Type: application/json'); // Set the content type to JSON

$response = array();

    $command = "python3 /home/arhizsx/listfolders.py  2>&1"; // Capture both stdout and stderr

    // Execute the Python script and capture output and errors
    $output = shell_exec($command);

    // Prepare the response
    $response = $output;

// Return the JSON response
echo $output;
?>
