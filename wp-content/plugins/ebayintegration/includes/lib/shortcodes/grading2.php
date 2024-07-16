
<?php 

    include( plugin_dir_path( __FILE__ ) . "css.php" );			

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

<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <a href="/my-account/grading" class="5star_btn btn text-left btn-dark mb-3" data-type="psa-value_bulk" data-action="add_card">
                <i class="fa-solid fa-circle-dot me-2"></i>                
                Grading
            </a>
            <a href="/my-account/consignment" class="5star_btn btn text-left btn-secondary mb-3" data-type="psa-value_bulk" data-action="add_card">
                <i class="fa-solid fa-handshake me-2"></i>
                Consignment
            </a>
            <a href="/my-account/cashout" class="5star_btn btn text-left btn-secondary mb-3" data-type="psa-value_bulk" data-action="add_card">
                <i class="fa-solid fa-money-bill me-2"></i>
                Cashout
            </a>
        </div>
    </div>
    <div class="row">
        <div class="col-xl-12 col-lg-12">
            <div class="shortcode_tab_box">
                <ul class="clearfix d-none d-lg-block">
                    <li class="<?php echo ActivateGrading("open"); ?>">
                        <a class="" href="/my-account/grading/?mode=open">Open</a>
                    </li>
                    <li class="<?php echo ActivateGrading("for_payment"); ?>">
                        <a class="" href="/my-account/grading/?mode=for_payment">For Payment</a>
                    </li>
                    <li class="<?php echo ActivateGrading("consigned"); ?>">
                        <a class="" href="/my-account/grading/?mode=consigned">Consigned</a>
                    </li>
                    <li class="<?php echo ActivateGrading("completed"); ?>">
                        <a class="" href="/my-account/grading/?mode=completed">Completed</a>
                    </li>
                </ul>
                <div class="d-lg-none p-3">
                    <label>Select Grading Order Status</label>
                    <select class="form-control" id="mobile_tab_select">
                        <!-- <option value="/my-account/grading/" <?php echo ActivateGradingSelect("log") ?>>Log Cards</option> -->
                        <option value="/my-account/grading/?mode=open" <?php echo ActivateGradingSelect("open") ?>>Open</option>
                        <option value="/my-account/grading/?mode=for_payment" <?php echo ActivateGradingSelect("for_payment") ?>>For Payment</option>
                        <option value="/my-account/grading/?mode=consigned" <?php echo ActivateGradingSelect("consigned") ?>>Consigned</option>
                        <option value="/my-account/grading/?mode=completed" <?php echo ActivateGradingSelect("completed") ?>>Completed</option>
                    </select>
                </div>
                <div class="content p-3">
                    <?php 

                        if( isset( $_GET['mode'] )  == false){

                            // include( plugin_dir_path( __FILE__ ) . "members/grading.new.php" );			
                            echo do_shortcode( "[cards-grading-orders_table table='my_orders']" );                    

                        } 
                        else {

                            switch( $_GET["mode"] ){

                                case "open": 
                                    $shortcode = "[cards-grading-orders_table table='my_orders']";
                                    break;

                                case "for_payment": 
                                    $shortcode = "[cards-grading-orders_table table='my_for_payment']";
                                    break;

                                case "consigned": 
                                    $shortcode = "[cards-grading-orders_table table='my_consigned']";
                                    break;

                                case "completed": 
                                    $shortcode = "[cards-grading-orders_table table='my_completed']";
                                    break;

                                default:
                                    $shortcode = "[cards-grading-orders_table table='my_orders']";
                                    break;

                            }

                            echo do_shortcode( $shortcode );                    

                        }

                    ?>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="floating-button-container">
    <button type="button" id="float_btn_add_ticket" class="floating-button btn btn-primary" data-action="add_grading" data-toggle="tooltip" data-placement="left" data-original-title="Add a Grading">
        <i class="fa fa-plus fa-2x mt-4 fa-w-16"></i>
    </button>
</div>
