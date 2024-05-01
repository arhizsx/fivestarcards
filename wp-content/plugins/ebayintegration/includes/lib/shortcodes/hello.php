<?php 
$current_user = wp_get_current_user();
?>

<H1 style="color: black;">
    Hello, <?php echo $current_user->display_name; ?>
</H1>