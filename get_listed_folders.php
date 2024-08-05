<?php
header('Content-Type: application/json');
$file_path = '/home/arhizsx/listed_folders.json';

if (file_exists($file_path)) {
    $json = file_get_contents($file_path);
    echo $json;
} else {
    http_response_code(404);
    echo json_encode(['error' => 'File not found']);
}
?>
