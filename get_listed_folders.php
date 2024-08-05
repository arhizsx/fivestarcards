<?php
function isTimestampOlderThanTwoDays($timestamp) {
    $timestamp = strtotime($timestamp);
    $twoDaysAgo = time() - (2 * 24 * 60 * 60);
    return $timestamp < $twoDaysAgo;
}

$listedFoldersFile = '/home/arhizsx/listed_folders.json';

if (file_exists($listedFoldersFile)) {
    $jsonContent = file_get_contents($listedFoldersFile);
    $data = json_decode($jsonContent, true);

    if (isTimestampOlderThanTwoDays($data['timestamp'])) {
        // Run the Python script
        exec('/usr/bin/python3 /home/arhizsx/listfolders.py');
        
        // Reload the JSON data
        $jsonContent = file_get_contents($listedFoldersFile);
        $data = json_decode($jsonContent, true);
    }

    header('Content-Type: application/json');
    echo json_encode($data);
} else {
    http_response_code(404);
    echo json_encode(["error" => "listed_folders.json not found"]);
}
?>
