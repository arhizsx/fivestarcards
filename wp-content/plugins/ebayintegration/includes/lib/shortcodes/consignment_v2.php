
<?php 

    include( plugin_dir_path( __FILE__ ) . "css.php" );			

?>
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <a href="/my-account/grading" class="5star_btn btn text-left  btn-secondary mb-3">
                <i class="fa-solid fa-circle-dot me-2"></i>                
                Grading
            </a>
            <a href="/my-account/consignment" class="5star_btn btn text-left btn-dark mb-3">
                <i class="fa-solid fa-handshake me-2"></i>
                Consignment
            </a>
            <a href="/my-account/payout" class="5star_btn btn text-left btn-secondary mb-3">
                <i class="fa-solid fa-money-bill me-2"></i>
                Cashout
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-xl-12 col-lg-12">
            <div class="shortcode_tab_box">
                <ul class="clearfix d-none d-lg-block">
                    <li class="<?php echo Activate("orders"); ?>">
                        <a class="" href="/my-account/consignment/?mode=orders">Orders</a>
                    </li>
                    <li class="<?php echo Activate("cards"); ?>">
                        <a class="" href="/my-account/consignment/?mode=cards">Cards</a>
                    </li>
                    <li class="<?php echo Activate("listed"); ?>">
                        <a class="" href="/my-account/consignment/?mode=listed">Listed</a>
                    </li>
                </ul>
                <div class="d-lg-none p-3">
                    <label>Select Consignment Status</label>
                    <select class="form-control" id="mobile_tab_select">
                        <option value="/my-account/consignment?mode=orders" <?php echo ActivateSelect("orders") ?>>Orders</option>
                        <option value="/my-account/consignment?mode=orders" <?php echo ActivateSelect("cards") ?>>Cards</option>
                        <option value="/my-account/consignment?mode=listed" <?php echo ActivateSelect("listed") ?>>Listed</option>
                    </select>
                </div>
                <div class="content px-3 py-0">
                    <?php 
                    if(isset( $_GET['mode']) ){
                        
                        include( plugin_dir_path( __FILE__ ) . "members/consignment." . $_GET['mode'] . '.php' );			

                    } else {
                        include( plugin_dir_path( __FILE__ ) . 'members/consignment.listed.php' );			

                    }
                    ?>
                </div>
            </div>
        </div>
    </div>
</div>
