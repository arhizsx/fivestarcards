
<?php 

    include( plugin_dir_path( __FILE__ ) . "css.php" );			

?>
<div class="container-fluid">
    <div class="row">
        <div class="col-12 text-black">
            <H3 style="color: black;">Consignment</H3>
        </div>
        <div class="col-12">
            <a href="/my-account/grading" class="5star_btn btn text-left  btn-secondary mb-3">
                <i class="fa-solid fa-circle-dot me-2"></i>                
                Grading
            </a>
            <a href="/my-account/consignment" class="5star_btn btn text-left btn-dark mb-3">
                <i class="fa-solid fa-handshake me-2"></i>
                Consignment
            </a>
            <a href="/my-account/cashout" class="5star_btn btn text-left btn-secondary mb-3">
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
                    <li class="<?php echo Activate("listed"); ?>">
                        <a class="" href="/my-account/consignment/?mode=listed">Listed</a>
                    </li>
                    <li class="<?php echo Activate("sold"); ?>">
                        <a class="" href="/my-account/consignment/?mode=sold">Sold</a>
                    </li>
                </ul>
                <div class="d-lg-none p-3">
                    <label>Select Consignment Status</label>
                    <select class="form-control" id="mobile_tab_select">
                        <option value="/my-account/consignment" <?php echo ActivateSelect("new") ?>>New</option>
                        <option value="/my-account/consignment?mode=to-ship" <?php echo ActivateSelect("to-ship") ?>>To Ship</option>
                        <option value="/my-account/consignment?mode=consigned" <?php echo ActivateSelect("consigned") ?>>Consigned</option>
                        <option value="/my-account/consignment?mode=listed" <?php echo ActivateSelect("listed") ?>>Listed</option>
                        <option value="/my-account/consignment?mode=sold" <?php echo ActivateSelect("sold") ?>>Sold</option>
                    </select>
                </div>
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
