
<?php 

    include( plugin_dir_path( __FILE__ ) . "css.php" );			

?>

<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <a href="/my-account-v2" class="5star_btn btn text-left  btn-secondary mb-3" data-type="psa-value_bulk" data-action="add_card">
                <i class="fa-solid fa-list me-2"></i>
                My Listing
            </a>
            <a href="/my-account/grading-order" class="5star_btn btn text-left  btn-secondary mb-3" data-type="psa-value_bulk" data-action="add_card">
                <i class="fa-solid fa-circle-dot me-2"></i>                
                Grading
            </a>
            <a href="/my-account/consignment" class="5star_btn btn text-left btn-dark mb-3" data-type="psa-value_bulk" data-action="add_card">
                <i class="fa-solid fa-handshake me-2"></i>
                Consignment
            </a>
            <a href="/my-account/request-cashout" class="5star_btn btn text-left btn-secondary mb-3" data-type="psa-value_bulk" data-action="add_card">
                <i class="fa-solid fa-money-bill me-2"></i>
                Cashout
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-xl-12 col-lg-12">
            <div class="shortcode_tab_box">
                <ul class="clearfix d-none d-lg-block">
                    <li class="<?php echo Activate("new"); ?>">
                        <a class="" href="/my-account/consignment">New</a>
                    </li>
                    <li class="<?php echo Activate("to-ship"); ?>">
                        <a class="" href="/my-account/consignment/?mode=to-ship">To Ship</a>
                    </li>
                    <li class="<?php echo Activate("consigned"); ?>">
                        <a class="" href="/my-account/consignment/?mode=consigned">Consigned</a>
                    </li>
                    <li class="<?php echo Activate("sold"); ?>">
                        <a class="" href="/my-account/consignment/?mode=sold">Sold</a>
                    </li>
                </ul>
                <ul class="clearfix d-lg-none">
                    <li class="">
                        <a class="" href="/my-account/consignment">New</a>
                    </li>

                </ul>
                <div class="content p-3">
                    <?php 
                    if(isset( $_GET['mode']) ){
                        include( plugin_dir_path( __FILE__ ) . "consignment." . $_GET['mode'] . '.php' );			

                    } else {
                        include( plugin_dir_path( __FILE__ ) . 'consignment.new.php' );			

                    }
                    ?>
                </div>
            </div>
        </div>
    </div>
</div>
