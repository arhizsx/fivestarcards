<?php 
$current_user = wp_get_current_user();
?>
<div class="row">
    <div class="col-12">
        <H1 style="color: black;">
            Hello, <?php echo $current_user->display_name; ?>
        </H1>
    </div>
</div>