<?php
// run_script.php

if (isset($_POST['folder_id'])) {
    $folder_id = escapeshellarg($_POST['folder_id']);
    $command = "python3 /home/arhizsx/paidout.py {$folder_id} --files 2 2>&1"; // Capture both stdout and stderr

    // Execute the Python script and capture output and errors
    $output = shell_exec($command);
    print_r( json_encode($output) );
}
?>
