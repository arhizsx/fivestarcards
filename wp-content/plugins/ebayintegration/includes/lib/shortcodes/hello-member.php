<?php 
$current_user = wp_get_current_user();
?>
<div class="d-flex justify-content-between">
    <div>
        <H1 style="color: black;">
            Hello, <?php echo $current_user->display_name; ?>
        </H1>
    </div>
    <div>
        <div class="floating-button-container text-center">
            <button type="button" id="float_btn_add_ticket" class="floating-button btn btn-primary" data-action="add_grading" data-toggle="tooltip" data-placement="left" data-original-title="Add a Grading">
                <i class="fa fa-plus fa-2x fa-w-16 fa-beat" style="margin-top: 10px;"></i>
            </button>
            <small style="border: 1px solid white; background-color: black; color: white; padding-top: 4px; padding-bottom: 4px; padding-left: 10px; padding-right: 10px;">NEW</small>
        </div>
    </div>
</div>