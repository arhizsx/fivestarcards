<?php
// Access the passed variables
$title = $pdf_data['title'] ?? 'Default Title';
$content = $pdf_data['content'] ?? 'Default Content';
$date = $pdf_data['date'] ?? date('Y-m-d');
?>

<html>
    <body>
    <h1><?php echo esc_html($title); ?></h1>
    <p><?php echo nl2br(esc_html($content)); ?></p>
    <p>Date: <?php echo esc_html($date); ?></p>
    </body>
</html>