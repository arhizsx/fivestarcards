<?php
// run_script.php

if (isset($_POST['folder_id'])) {
    $folder_id = escapeshellarg($_POST['folder_id']);
    $command = "python3 /home/arhizsx/paidout.py {$folder_id}";
    
    // Execute the Python script and capture output
    $output = shell_exec($command);
    
    // Return the output as JSON
    header('Content-Type: application/json');
    echo $output;
}
?>

