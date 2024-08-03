<?php
// Access the passed variables
$title = $data['title'] ?? 'Default Title';
$content = $data['content'] ?? 'Default Content';
$date = $data['date'] ?? date('Y-m-d');
?>

<html>
    <body>
    <h1><?php echo esc_html($title); ?></h1>
    <p><?php echo nl2br(esc_html($content)); ?></p>
    <p>Date: <?php echo esc_html($date); ?></p>
    </body>
</html>