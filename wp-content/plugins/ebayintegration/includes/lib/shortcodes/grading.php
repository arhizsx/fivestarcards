
<?php 

    include( plugin_dir_path( __FILE__ ) . "css.php" );			

?>

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
            <a href="/my-account/payout" class="5star_btn btn text-left btn-secondary mb-3" data-type="psa-value_bulk" data-action="add_card">
                <i class="fa-solid fa-money-bill me-2"></i>
                Payout
            </a>
        </div>
    </div>
    <div class="row">
        <div class="col-xl-12 col-lg-12">
            <div class="shortcode_tab_box">
                <ul class="clearfix d-none d-lg-block">
                </ul>
                <div class="d-lg-none p-3">
                    <label>Select Grading Order Status</label>
                    <select class="form-control" id="mobile_tab_select">
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
