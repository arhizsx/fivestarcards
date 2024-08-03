
<html>
    <head>
        <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
        <style>
            @page {
                margin: 0cm;
            }

            body {
                margin: 0cm;
                padding: 0cm;
                font-size: 8pt; /* Set global font size */
            }

            th, td {
                padding: 2px; /* Reduced padding */
            }
            .text-end {
                text-align: right;
            }

        </style>        
    </head>
    <body>
        <div class="container-fluid m-0 p-0">


            <div class="row mb-3 border-bottom">
                <div class="col-12 " style="padding: 20px;">
                    <img src="https://5starcards.com/wp-content/uploads/2023/09/5-star-cards-logo.png" width="120px;" alt="5 Star Cards">
                </div>
            </div>
            <div class="row p-3">
                <div class="col-12" style="padding: 20px;">
                    <H5>Payment Request</H5>
                    <table class="table table-sm table-bordered table-striped table-sm table-hover search_table_paid" style="width: 95%">
                        <thead>
                            <tr>
                                <th class="text-start" width="50%">Item</th>
                                <th class="text-end">Price Sold</th>
                                <th class="text-end">Rate</th>
                                <th class="text-end">Fees</th>
                                <th class="text-end">Final</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                                foreach($data["cards"] as $item){ 
                                    $ctr++;
                                    $data = json_decode($item->data, true);
                            ?>
                            <input type="hidden" name="card[<?php echo $ctr ?>]" value="<?php echo $item->item_id; ?>">
                            <tr>
                                <td class="text-start">
                                    <div class="title text-start">
                                        <a href="<?php echo $data["Item"]['ListingDetails']['ViewItemURL'] ?>" target="_blank">
                                            <?php print_r( $data["Item"]["Title"] ); ?>
                                        </a>
                                    </div> 
                                </td>
                                <?php 
                                    $sold_price = (float) $data["TransactionPrice"];  
                                ?>
                                <td class="text-end">
                                    $<?php 
                                    echo number_format(( $sold_price), 2, '.', ',');
                                    ?>
                                </td>
                                <?php 
                                    if( $sold_price < 10 ){
                                        $rate = 1;
                                        $fees = 3;
                                        $final = ($rate * $sold_price )  + $fees;
                                    }
                                    elseif( $sold_price >= 10 && $sold_price <= 49.99 ){
                                        $rate = .82;
                                        $fees = 0;
                                        $final = ($rate * $sold_price )  + $fees;
                                    }
                                    elseif( $sold_price >= 50 && $sold_price <= 99.99 ){
                                        $rate = .84;
                                        $fees = 0;
                                        $final = ($rate * $sold_price )  + $fees;
                                    }
                                    elseif( $sold_price >= 100 && $sold_price <= 199.99 ){
                                        $rate = .85;
                                        $fees = 0;
                                        $final = ($rate * $sold_price )  + $fees;
                                    }
                                    elseif( $sold_price >= 200 && $sold_price <= 499.99 ){
                                        $rate = .86;
                                        $fees = 0;
                                        $final = ($rate * $sold_price )  + $fees;
                                    }
                                    elseif( $sold_price >= 500 && $sold_price <= 999.99 ){
                                        $rate = .87;
                                        $fees = 0;
                                        $final = ($rate * $sold_price )  + $fees;
                                    }
                                    elseif( $sold_price >= 1000 && $sold_price <= 2999.99 ){
                                        $rate = .88;
                                        $fees = 0;
                                        $final = ($rate * $sold_price )  + $fees;
                                    }
                                    elseif( $sold_price >= 3000 && $sold_price <= 4999.99 ){
                                        $rate = .90;
                                        $fees = 0;
                                        $final = ($rate * $sold_price )  + $fees;
                                    }
                                    elseif( $sold_price >= 5000 && $sold_price <= 8999.99 ){
                                        $rate = .92;
                                        $fees = 0;
                                        $final = ($rate * $sold_price )  + $fees;
                                    }
                                    elseif( $sold_price >= 9000){
                                        $rate = .93;
                                        $fees = 0;
                                        $final = ($rate * $sold_price )  + $fees;
                                        echo $final;
                                    }

                                    $payout_total = $payout_total + $final;
                                ?>
                                <td class="text-end">
                                    <?php echo number_format(( $rate * 100), 2, '.', ','); ?>%                                            
                                </td>
                                <td class="text-end">
                                    $<?php 
                                    echo number_format(( $fees), 2, '.', ',');
                                    ?>
                                </td>
                                <td class="text-end">
                                    $<?php 
                                    echo number_format(( $final), 2, '.', ',');
                                    ?>
                                </td>
                            </tr>
                            <?php
                                } 
                            ?>
                        </tbody>
                        <tfoot>
                            <tr>
                                <th colspan="4" class="text-end">Total Payout</th>
                                <th colspan="1" class="text-end">
                                    $<?php 
                                    echo number_format(( $payout_total), 2, '.', ',');
                                    ?>
                                </th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </body>
</html>