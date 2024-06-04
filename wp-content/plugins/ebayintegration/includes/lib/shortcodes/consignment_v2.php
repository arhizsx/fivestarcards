
<?php 

    include( plugin_dir_path( __FILE__ ) . "css.php" );			

    function Activate($page){
        if( isset( $_GET['mode']) == false ){
            if( $page == "new" ){
                return "active";
            }
        } 
        else {
            if( $page == $_GET['mode'] ){
                return "active";
            } else {
                return "";
            }
        }
    }


?>

<div class="container-fluid">
    <div class="row">
        <div class="col-xl-2 col-lg-3">
            <a href="/my-account" class="5star_btn btn text-left form-control btn-secondary mb-3" data-type="psa-value_bulk" data-action="add_card">
                <i class="fa-solid fa-list me-2"></i>
                My Listing
            </a>
            <a href="/my-account/grading-order" class="5star_btn btn text-left form-control btn-secondary mb-3" data-type="psa-value_bulk" data-action="add_card">
                <i class="fa-solid fa-circle-dot me-2"></i>                
                Grading Orders
            </a>
            <a href="/my-account/consignment" class="5star_btn btn text-left form-control btn-dark mb-3" data-type="psa-value_bulk" data-action="add_card">
                <i class="fa-solid fa-handshake me-2"></i>
                Consignment
            </a>
            <a href="/my-account/request-cashout" class="5star_btn btn text-left form-control btn-secondary mb-3" data-type="psa-value_bulk" data-action="add_card">
                <i class="fa-solid fa-money-bill me-2"></i>
                Request Cashout
            </a>
        </div>
        <div class="col-xl-10 col-lg-9">
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
