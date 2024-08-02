
<?php 

    include( plugin_dir_path( __FILE__ ) . "css.php" );			

    $user_id = get_current_user_id();

    $skus = get_user_meta( $user_id, "sku", true );		
    $array = implode("','",$skus);

    $sql = "
        SELECT * 
        FROM  ebay
        where status = 'SoldListPaid' AND sku IN ('" . $array . "')
        ORDER BY id DESC
    ";

    $cards = $this->wpdb->get_results ( $sql );
    $available = count($cards);

?>

<style>
    h3 {
        margin-bottom: 0px;
        color: black;

    }
</style>

<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <a href="/my-account/grading" class="5star_btn btn text-left  btn-secondary mb-3" data-type="psa-value_bulk" data-action="add_card">
                <i class="fa-solid fa-circle-dot me-2"></i>                
                Grading
            </a>
            <a href="/my-account/consignment" class="5star_btn btn text-left btn-secondary mb-3" data-type="psa-value_bulk" data-action="add_card">
                <i class="fa-solid fa-handshake me-2"></i>
                Consignment
            </a>
            <a href="/my-account/payout" class="5star_btn btn text-left btn-dark mb-3" data-type="psa-value_bulk" data-action="add_card">
                <i class="fa-solid fa-money-bill me-2"></i>
                Payout
            </a>
        </div>
    </div>
    <div class="row">
        <div class="col-xl-12 col-lg-12">
            <div class="shortcode_tab_box">
            <?php 

                if( !isset($_GET["mode"])){
            ?>
                <ul class="clearfix d-none d-lg-block">
                    <li class="active">
                        <a class="" href="/my-account/payout/">Payout Requests</a>
                    </li>
                    <li class="">
                        <a class="" href="/my-account/payout?mode=pending">Pending Payout</a>
                    </li>
                </ul>
                <div class="content p-3 text-center">
                    <div class="table-responsive">
                        <table class="table table-border table-striped table-sm table-hover search_table_paid">
                            <thead>
                                <tr>
                                    <th class="text-start">Request ID</th>
                                    <th class="text-start">Request Date</th>
                                    <th class="text-center">Cards Count</th>
                                    <th class="text-end">Amount Requested</th>
                                    <th class="text-end">Request Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td class="text-start">-</td>
                                    <td class="text-start">-</td>
                                    <td class="text-center">-</td>
                                    <td class="text-end">-</td>
                                    <td class="text-end">-</td>
                                </tr>                                    
                            </tbody>
                        </table>
                    </div>
                </div>

            <?php

                } else {
            ?>

                <ul class="clearfix d-none d-lg-block">
                    <li class="">
                        <a class="" href="/my-account/payout">Payout Requests</a>
                    </li>
                    <li class="active">
                        <a class="" href="/my-account/payout/?mode=pending">Pending Payout</a>
                    </li>
                </ul>
                <div class="content p-3 text-center">
                    <?php 
                        if( $user_id != 1054 ){
                    ?>
                    <H1>Please wait we are brewing something cool...</H1>
                    <?php        
                        } else {

                            $payout_total = 0;
                    ?>
                        <div class="table-responsive">
                            <table class="table table-border table-striped table-sm table-hover search_table_paid">
                                <thead>
                                    <tr>
                                        <th class="text-start" width="60%">Item</th>
                                        <th class="text-end">eBay Pay Date</th>
                                        <th class="text-end">Price Sold</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php 
                                    if( $available > 0 ){
                                        foreach($cards as $item){ 
                                            $ctr++;
                                            $data = json_decode($item->data, true);
                                    ?>
                                    <tr>
                                        <td class="text-start">
                                            <div class="title text-start">
                                                <strong><?php echo $ctr;  ?></strong>&nbsp;
                                                <a href="<?php echo $data["Item"]['ListingDetails']['ViewItemURL'] ?>" target="_blank">
                                                    <?php print_r( $data["Item"]["Title"] ); ?>
                                                </a>
                                            </div> 
                                            <div class="sku text-small">SKU: <?php echo $item->sku ?></div>
                                            <div class="item_id text-small">Item ID: <?php echo $item->item_id ?></div>
                                            <?php 
                                                $listing = $data["Item"]["ListingType"] == "Chinese" ? "Auction" : $data["Item"]["ListingType"]; 
                                            ?>
                                            <div class="item_id text-small">Listing Type: <?php echo $listing; ?></div>                                        
                                        </td>
                                        <td class="text-end">
                                            <?php
                                            $paid_time = explode("T",$data["PaidTime"]); 
                                            echo $paid_time[0];
                                            ?>
                                        </td>
                                        <td class="text-end">
                                            $<?php 
                                            echo number_format(( $data["TransactionPrice"]), 2, '.', ',');
                                            ?>
                                        </td>
                                    </tr>
                                    <?php
                                            $payout_total = $$payout_total + $data["TransactionPrice"];
                                        } 
                                    } 
                                    else {
                                    ?>
                                    <tr>
                                        <td colspan="3" class="text-center p-5">
                                            No Items
                                        </td>
                                    </tr>
                                    <?php 
                                    }
                                    ?>
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th class="text-end" colspan="2">
                                            <H3>Grand Total</H3>
                                        </th>
                                        <th class="text-end">
                                            <H3>$
                                        <?php 
                                        echo number_format(( $payout_total ), 2, '.', ',');
                                        ?>
                                            </H3>
                                        </th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    <?php 
                        }
                    ?>
                    </div>                    
                </div>

            <?php 
                }
            ?>
            </div>
        </div>
    </div>
</div>
