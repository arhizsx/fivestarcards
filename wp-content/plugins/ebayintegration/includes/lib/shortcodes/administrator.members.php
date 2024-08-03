
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
            <a href="/administrator/grading" class="5star_btn btn text-left  btn-secondary mb-3" data-type="psa-value_bulk" data-action="add_card">
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
            <a href="/administrator/members" class="5star_btn btn text-left btn-dark mb-3" data-type="psa-value_bulk" data-action="add_card">
                <i class="fa-solid fa-users me-2"></i>
                Members
            </a>
        </div>
    </div>
    <div class="row">
        <div class="col-xl-12 col-lg-12">
            <div class="shortcode_tab_box">
                <ul class="clearfix d-none d-lg-block">
                    <li class="<?php echo AdministratorMembers("users"); ?>">
                        <a class="" href="/administrator/members">Users</a>
                    </li>
                    <li class="<?php echo AdministratorMembers("skus"); ?>">
                        <a class="" href="/administrator/members?mode=skus">SKUs</a>
                    </li>
                    <li class="<?php echo AdministratorMembers("unmatched"); ?>">
                        <a class="" href="/administrator/members?mode=unmatched">Unmatched</a>
                    </li>
                </ul>
                <ul class="clearfix d-lg-none">
                    <li class="">
                        <a class="" href="/administrator/members">Users</a>
                    </li>

                </ul>
                <div class="content p-3">
                    <?php 

                        if(isset( $_GET['mode']) ){
                                                
                            include( plugin_dir_path( __FILE__ ) . "administrator/members." . $_GET['mode'] . '.php' );			

                        } else {
                            include( plugin_dir_path( __FILE__ ) . 'administrator/members.list.php' );			

                        }


                        // if( isset( $_GET['mode'] )  == false){

                        //     $shortcode = "[cards-grading-orders_table table='members']";                            
                        
                        // } else {

                        //     switch( $_GET["mode"] ){

                        //         case "skus":
                                    
                        //             $shortcode = "[ebayintegration-shortcode type='members_list']";                            
                        //             break;

                        //         case "unmatched": 
                        //             $shortcode = "[ebayintegration-shortcode type='get_item_buttons']";                            
                        //             break;

                        //         default:
                        //             $shortcode = "[ebayintegration-shortcode type='members']";                            
                        //             break;
                        //     }


                        // }
 

                        // echo do_shortcode( $shortcode );                    

                    ?>
                </div>
            </div>
        </div>
    </div>
</div>
