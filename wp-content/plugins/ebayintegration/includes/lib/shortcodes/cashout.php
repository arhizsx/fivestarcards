
<?php 

    include( plugin_dir_path( __FILE__ ) . "css.php" );			
    $user_id = get_current_user_id();

    global $wpdb;

    $ebay = $this->wpdb->get_results ( "
    SELECT * 
    FROM  ebay
    where status = 'SoldListPaid'
    " 
    );
    
    
    $skus = get_user_meta( get_current_user_id(), "sku", true );		        

?>

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
            <a href="/my-account/cashout" class="5star_btn btn text-left btn-dark mb-3" data-type="psa-value_bulk" data-action="add_card">
                <i class="fa-solid fa-money-bill me-2"></i>
                Cashout
            </a>
        </div>
    </div>
    <div class="row">
        <div class="col-xl-12 col-lg-12">
            <div class="shortcode_tab_box">
                <ul class="clearfix d-none d-lg-block">
                    <li class="active">
                        <a class="" href="/my-account/cashout">Request Cashout</a>
                    </li>
                </ul>
                <div class="content p-3 text-center">
                    <?php 
                        if( $user_id != 1 ){
                    ?>
                    <H1>Please wait we are brewing something cool...</H1>
                    <?php        
                        } else {
                    ?>
                    <div class="table-responsive">
                        <table class="table table-border table-striped table-sm table-hover search_table_auction">
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th class="text-end">Amount</th>
                                    <th class="text-end">Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php 
                                foreach($ebay as $item){ 
                                    $data = json_decode($item->data, true);

                                    if( in_array( $item->sku, $skus ) ){
                                ?>
                                <tr>
                                    <td>
                                        <div class="title">
                                            <a href="<?php echo $data['ListingDetails']['ViewItemURL'] ?>" target="_blank">
                                            <?php echo $data["Title"]; ?>
                                            </a>
                                        </div>
                                        <div class="sku text-small">SKU: <?php echo $item->sku ?></div>
                                        <div class="item_id text-small">Item ID: <?php echo $item->item_id ?></div>
                                    </td>
                                    <td class="text-end">
                                        <?php echo $data["SellingStatus"]["BidCount"] * 1?>
                                    </td>

                                    <td class="text-end">$<?php 
                                        echo number_format(( $data["SellingStatus"]["CurrentPrice"]), 2, '.', ',');
                                    ?></td>
                                </tr>
                                <?php 
                                    }
                                }
                                ?>
                                    <tr>
                                        <td colspan="3" class="text-center p-5">Empty</td>
                                    </tr>
                                <?php                     
                                    }
                                ?>
                            </tbody>
                        </table>
                    </div>                    
                </div>
            </div>
        </div>
    </div>
</div>
