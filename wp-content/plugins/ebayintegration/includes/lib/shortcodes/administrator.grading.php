
<?php 

    include( plugin_dir_path( __FILE__ ) . "css.php" );			

?>

<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <a href="/administrator" class="5star_btn btn text-left  btn-secondary mb-3" data-type="psa-value_bulk" data-action="add_card">
                <i class="fa-solid fa-list me-2"></i>
                Dashboard
            </a>
            <a href="/administrator/grading" class="5star_btn btn text-left  btn-dark mb-3" data-type="psa-value_bulk" data-action="add_card">
                <i class="fa-solid fa-circle-dot me-2"></i>                
                Grading
            </a>
            <a href="/administrator/consignment" class="5star_btn btn text-left btn-secondary mb-3" data-type="psa-value_bulk" data-action="add_card">
                <i class="fa-solid fa-handshake me-2"></i>
                Consignment
            </a>
            <a href="/administrator/payouts" class="5star_btn btn text-left btn-secondary mb-3" data-type="psa-value_bulk" data-action="add_card">
            <i class="fa-solid fa-money-bill me-2"></i>
            Payouts
            </a>
            <a href="/administrator/members" class="5star_btn btn text-left btn-secondary mb-3" data-type="psa-value_bulk" data-action="add_card">
                <i class="fa-solid fa-users me-2"></i>
                Members
            </a>
        </div>
    </div>
    <div class="row">
        <div class="col-xl-12 col-lg-12">
            <div class="shortcode_tab_box">
                <!-- <ul class="clearfix d-none d-lg-block"> -->
                <ul class="clearfix">
                    <li class="<?php echo AdministratorGrading("new"); ?>">
                        <a class="" href="/administrator/grading/?mode=new">Add Order</a>
                    </li>
                    <li class="<?php echo AdministratorGrading("for_entry"); ?>">
                        <a class="" href="/administrator/grading?mode=for_entry">For Entry</a>
                    </li>
                    <li class="<?php echo AdministratorGrading("order_receiving"); ?>">
                        <a class="" href="/administrator/grading">Receiving</a>
                    </li>
                    <li class="<?php echo AdministratorGrading("open_orders"); ?>">
                        <a class="" href="/administrator/grading?mode=open_orders">Open</a>
                    </li>
                    <li class="<?php echo AdministratorGrading("awaiting_payment"); ?>">
                        <a class="" href="/administrator/grading/?mode=awaiting_payment">Awaiting Payment</a>
                    </li>
                    <li class="<?php echo AdministratorGrading("consignment"); ?>">
                        <a class="" href="/administrator/grading/?mode=consignment">Consignment</a>
                    </li>
                    <li class="<?php echo AdministratorGrading("completed"); ?>">
                        <a class="" href="/administrator/grading/?mode=completed">Completed</a>
                    </li>
                    <li class="<?php echo AdministratorGrading("cancelled"); ?>">
                        <a class="" href="/administrator/grading/?mode=cancelled">Cancelled</a>
                    </li>
                </ul>

                <!-- <ul class="clearfix d-lg-none">
                    <li class="">
                        <a class="" href="/administrator/grading">Receiving</a>
                    </li>

                </ul> -->
                <div class="content p-3">
                    <?php 

                        if( isset( $_GET['mode'] )  == false){

                            $shortcode = "[cards-grading-orders_table table='order_receiving']";
                            
                            
                        } 
                        else {

                            switch( $_GET["mode"] ){

                                case "new": 
                                    $shortcode = "[cards-grading-admin]";                                    
                                    break;

                                case "for_entry": 
                                    $shortcode = "[cards-grading-orders_table table='for_entry']";                                    
                                    break;

                                case "open_orders": 
                                    $shortcode = "[cards-grading-orders_table table='open_orders']";                                    
                                    break;

                                case "awaiting_payment": 
                                    $shortcode = "[cards-grading-orders_table table='awaiting_payment']";                                    
                                    break;

                                case "consignment": 
                                    $shortcode = "[cards-grading-orders_table table='consigned_orders']";
                                    break;

                                case "completed": 
                                    $shortcode = "[cards-grading-orders_table table='completed_orders']";
                                    break;

                                case "cancelled": 
                                    $shortcode = "[cards-grading-orders_table table='cancelled_orders']";
                                    break;

                                default:
                                    $shortcode = "[cards-grading-orders_table table='order_receiving']";
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
