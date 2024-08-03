
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
    .payment_request_row {
        cursor: pointer;
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

                $user_id = get_current_user_id();

                $skus = get_user_meta( $user_id, "sku", true );		
                $array = implode("','",$skus);
            
                $sql = "
                    SELECT * 
                    FROM  payouts
                    where user_id = " .  $user_id .  "
                    ORDER BY id DESC
                ";
            
                $cards = $this->wpdb->get_results ( $sql );
            ?>
                <ul class="clearfix d-none d-lg-block">
                    <li class="active">
                        <a class="" href="/my-account/payout/">Payout Requests</a>
                    </li>
                </ul>
                <div class="content p-3 text-center">
                    <div class="table-responsive">
                        <table class="table table-border table-striped table-sm table-hover search_table_paid">
                            <thead>
                                <tr>
                                    <th class="text-start">Payment ID</th>
                                    <th class="text-center">Cards</th>
                                    <th class="text-start">Request Date</th>
                                    <th class="text-end">Amount Requested</th>
                                    <th class="text-end">Status</th>
                                    <th class="text-end">Payout Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php 
                                    if( count( $cards ) > 0 ){
                                        foreach( $cards as $card ){ 
                                            $data = json_decode($card->data, true);
                                ?>
                                <tr class="payment_request_row" data-request_id="<?php echo $card->id ?>">
                                    <td class="text-start"><?php echo $card->id + 1000 ?></td>
                                    <td class="text-center"><?php echo $data["cards_count"] ?></td>
                                    <td class="text-start"><?php echo $card->add_timestamp ?></td>
                                    <td class="text-end"><?php echo $data["requested_amount"] ?></td>
                                    <td class="text-end"><?php echo $card->status ?></td>
                                    <td class="text-end"></td>
                                </tr>  
                                <?php
                                        } 
                                    } else {
                                ?>
                                <tr class="payment_request_row_empty">
                                    <td colspan="8">Empty</td>
                                </tr>
                                <?php
                                    }
                                ?>                                  
                            </tbody>
                        </table>
                    </div>
                </div>

            <?php

                } else {
            ?>


            <?php 
                }
            ?>
            </div>
        </div>
    </div>
</div>
