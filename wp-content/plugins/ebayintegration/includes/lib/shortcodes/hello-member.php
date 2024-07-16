<?php 
$current_user = wp_get_current_user();
?>
<style>
    .floating-button-container {
        position: fixed;
        right: 15px;
        bottom: 5px;
        width: 54px;
    }
    .floating-button {
        opacity: 100%;
        cursor: pointer;
        margin-bottom: 5px;
        padding: 0;
        border: 1px solid white;
        border-radius: 50px; height: 54px; width: 54px;
        line-height: 54px;
        box-shadow: -0.46875rem 0 2.1875rem rgb(4 9 20 / 3%), -0.9375rem 0 1.40625rem rgb(4 9 20 / 3%), -0.25rem 0 0.53125rem rgb(4 9 20 / 5%), -0.125rem 0 0.1875rem rgb(4 9 20 / 3%);
    }
    .floating-button:hover {
        opacity: 100%;
    }
    .floating-buttons-hide, .floating-buttons-show {
        height: 20px; margin-bottom:30px; margin-top: -15px; cursor: pointer;
    }
</style>
<div class="d-flex justify-content-between">
    <div>
        <H1 style="color: black;">
            Hello, <?php echo $current_user->display_name; ?>
        </H1>
    </div>
    <div>
            <button type="button" id="float_btn_add_ticket" class="floating-button btn btn-primary" data-action="add_grading" data-toggle="tooltip" data-placement="left" data-original-title="Add a Grading">
                <i class="fa fa-plus fa-2x fa-w-16 fa-beat" style="margin-top: 10px;"></i>
            </button>
            <small style="border: 1px solid white; background-color: black; color: white; padding-top: 4px; padding-bottom: 4px; padding-left: 10px; padding-right: 10px;">NEW</small>
    </div>
</div>