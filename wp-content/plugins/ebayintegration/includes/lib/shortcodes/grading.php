
<?php 

    include( plugin_dir_path( __FILE__ ) . "css.php" );			

?>

<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <H3 style="color: black;">Grading</H3>
        </div>
        <div class="col-12">
            <a href="/my-account" class="5star_btn btn text-left btn-secondary mb-3" data-type="psa-value_bulk" data-action="add_card">
                <i class="fa-solid fa-list me-2"></i>
                My Listings
            </a>
            <a href="/my-account/grading" class="5star_btn btn text-left btn-dark mb-3" data-type="psa-value_bulk" data-action="add_card">
                <i class="fa-solid fa-circle-dot me-2"></i>                
                Grading
            </a>
            <a href="/my-account/consignment" class="5star_btn btn text-left btn-secondary mb-3" data-type="psa-value_bulk" data-action="add_card">
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
                <ul class="clearfix d-lg-none">
                    <li class="">
                        <a class="" href="/my-account/grading/">Open</a>
                    </li>

                </ul>
                <div class="content p-3">
                    <?php 

                    if(isset( $_GET['mode']) ){
                        $shortcode = "";
                    } else {
                        $shorcode = $_GET["mode"];
                    }

                    echo $shortcode;

                    ?>
                </div>
            </div>
        </div>
    </div>
</div>
