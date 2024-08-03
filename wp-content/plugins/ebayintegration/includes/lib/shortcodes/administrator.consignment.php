
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
            <a href="/administrator/consignment" class="5star_btn btn text-left btn-dark mb-3" data-type="psa-value_bulk" data-action="add_card">
                <i class="fa-solid fa-handshake me-2"></i>
                Consignment
            </a>
            <a href="/administrator/payouts" class="5star_btn btn text-left btn-secondary mb-3" data-type="psa-value_bulk" data-action="add_card">
                <i class="fa-solid fa-money me-2"></i>
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
                <ul class="clearfix d-none d-lg-block">
                    <li class="<?php echo AdministratorConsignment("receiving"); ?>">
                        <a class="" href="/administrator/consignment">Receiving</a>
                    </li>
                    <li class="<?php echo AdministratorConsignment("consigned"); ?>">
                        <a class="" href="/administrator/consignment/?mode=consigned">Consigned</a>
                    </li>
                    <!-- <li class="<?php echo AdministratorConsignment("listed"); ?>">
                        <a class="" href="/administrator/consignment/?mode=listed">Listed</a>
                    </li> -->
                    <li class="<?php echo AdministratorConsignment("ebay"); ?>">
                        <a class="" href="/administrator/consignment/?mode=ebay"><i class="fa-brands fa-ebay fa-2xl"></i></a>
                    </li>
                </ul>
                <div class="d-lg-none p-3">
                    <label>Select Consignment Status</label>
                    <select class="form-control" id="mobile_tab_select">
                        <option value="/administrator/consignment" <?php echo AdministratorConsignmentSelect("receiving") ?>>Receiving</option>
                        <option value="/administrator/consignment?mode=consigned" <?php echo ActivateSelect("consigned") ?>>Consigned</option>
                        <option value="/administrator/consignment?mode=listed" <?php echo ActivateSelect("listed") ?>>Listed</option>
                        <option value="/administrator/consignment?mode=ebay" <?php echo ActivateSelect("ebay") ?>>eBay</option>
                    </select>
                </div>
                <div class="content px-3 py-0">
                    <?php 
                    if(isset( $_GET['mode']) ){
                        
                        include( plugin_dir_path( __FILE__ ) . "administrator/consignment." . $_GET['mode'] . '.php' );			

                    } else {
                        include( plugin_dir_path( __FILE__ ) . 'administrator/consignment.receiving.php' );			

                    }
                    ?>
                </div>
            </div>
        </div>
    </div>
</div>
