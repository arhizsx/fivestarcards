
<?php 

    include( plugin_dir_path( __FILE__ ) . "css.php" );			

?>

<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <H3 style="color: black;">Grading</H3>
        </div>
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
                        <a class="" href="/my-account/grading/">Open</a>
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
                        <option value="/my-account/grading" <?php echo ActivateListingSelect("open") ?>>Open</option>
                        <option value="/my-account/grading/?mode=for_payment" <?php echo ActivateListingSelect("for_payment") ?>>For Payment</option>
                        <option value="/my-account/grading/?mode=consigned" <?php echo ActivateListingSelect("consigned") ?>>Consigned</option>
                        <option value="/my-account/grading/?mode=completed" <?php echo ActivateListingSelect("completed") ?>>Completed</option>
                    </select>
                </div>
                <div class="content p-3">
                    <?php 

                        if( isset( $_GET['mode'] )  == false){

                            $shortcode = "[cards-grading-orders_table table='my_orders']";
                            
                        } 
                        else {

                            switch( $_GET["mode"] ){

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

                        }


                        echo do_shortcode( $shortcode );                    

                    ?>
                </div>
            </div>
        </div>
    </div>
</div>