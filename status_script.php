<?php
// run_script.php

    $progress_file = '/home/arhizsx/progress.txt';
    $progress = file_exists($progress_file) ? file_get_contents($progress_file) : '0';

    return $progress;
?>
