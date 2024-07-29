<?php
// run_script.php

    $progress_file = '/home/arhizsx/progress.txt';
    $progress = file_get_contents($progress_file);
    
    header('Content-type: application/json');
    print_r( ["progress" => $progress] );
    
?>
