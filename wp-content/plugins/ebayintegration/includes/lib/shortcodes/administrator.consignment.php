
<?php 

    include( plugin_dir_path( __FILE__ ) . "css.php" );			

?>

<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <H3 style="color: black;">Grading</H3>
        </div>
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
            <a href="/administrator/ebay" class="5star_btn btn text-left btn-secondary mb-3" data-type="psa-value_bulk" data-action="add_card">
                <i class="fa-solid fa-e me-2"></i>
                Listings
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
                    <li class="<?php echo AdministratorGrading("receiving"); ?>">
                        <a class="" href="/administrator/consignment">Receiving</a>
                    </li>
                    <li class="<?php echo AdministratorGrading("consigned"); ?>">
                        <a class="" href="/administrator/consignment/?mode=consigned">Consigned</a>
                    </li>
                    <li class="<?php echo AdministratorGrading("sold"); ?>">
                        <a class="" href="/administrator/consignment/?mode=sold">Sold</a>
                    </li>
                </ul>
                <ul class="clearfix d-lg-none">
                    <li class="">
                        <a class="" href="/administrator/consignment">Receiving</a>
                    </li>

                </ul>
                <div class="content p-3">
                    <?php 

                    ?>
                </div>
            </div>
        </div>
    </div>
</div>
