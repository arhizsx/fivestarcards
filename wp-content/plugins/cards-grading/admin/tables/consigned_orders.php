<?php

$user_id = get_current_user_id();

$args = array(
    'meta_query' => array(
        array(
            'key' => 'status',
            'value' => array("Active Consignments", "Order Partial Consignment"),
            'compare' => 'IN'
        )
    ),
    'post_type' => 'cards-grading-chk',
    'posts_per_page' => -1
);

$posts = get_posts($args);

?>

<div class="m-0 p-0">
    <div class="row">
        <div class="col-xl-6">
            <H1 style="color: black;">Consigned</H1>            
        </div>
        <div class="col-xl-6 text-end">
            
        </div>
        <div class="col-xl-12">
        <?php if( isset( $_GET["filtered"] ) == "true") { ?>

            Filtered By: 
            <?php if(isset($_GET["user_id"])){ ?>
                <button class="5star_btn btn btn-danger mb-3 py-0 px-2 mt-3 btn-sm" data-action="remove_filter">
                    Customer
                </button>           
            <?php } ?>
            <?php if(isset($_GET["status"])){ ?>
                <button class="5star_btn btn btn-danger mb-3 py-0 px-2 mt-3 btn-sm" data-action="remove_filter">
                    Status
                </button>           
            <?php } ?>
            <?php if(isset($_GET["grading_type"])){ ?>
                <button class="5star_btn btn btn-danger mb-3 py-0 px-2 mt-3 btn-sm" data-action="remove_filter">
                    Grading Type
                </button>           
            <?php } ?>
            <?php if( isset($_GET["submission_number"])){ ?>
                <button class="5star_btn btn btn-danger mb-3 py-0 px-2 mt-3 mr-5  btn-sm" data-action="remove_filter">
                    Submission #
                </button>
            <?php } ?>
            <button class='5star_btn btn btn-secondary mb-3 btn-sm py-0 mt-3 px-2 ml-3' data-action="remove_filter" data-order_number="<?php echo $params['order_number'] ?>">
                Clear
            </button>      
        <?php } ?>
        </div>
    </div>
    <?php 
        if( $posts ){
            
            $customers = [];
            $status = [];
            $grading_types = [];
            $submission_numbers = [];

            foreach($posts as $post)
            {
                $meta = get_post_meta($post->ID);

                $user_id = $meta["user_id"][0];
                $user = get_user_by( "id", $user_id );

                $exists = false;
                foreach($customers as $cx){
                    if( $cx["user_id"] == $user_id){
                        $exists = true;
                        break;
                    }
                }

                if($exists == false){
                    array_push($customers, ["customer"=> ucfirst($user->display_name), "user_id" => $user_id]);
                }


                $exists = false;
                foreach($status as $st){
                    if( $st["status"] == $meta["status"][0]){
                        $exists = true;
                        break;
                    }
                }

                if($exists == false){
                    array_push($status, ["status"=> ucfirst($meta["status"][0])]);
                }

                $exists = false;
                foreach($grading_types as $gd){
                    if( $gd["grading_type"] == $meta["grading_type"][0]){
                        $exists = true;
                        break;
                    }
                }

                if($exists == false){
                    array_push($grading_types, ["grading_type"=> $meta["grading_type"][0]]);
                }

                $exists = false;
                foreach($submission_numbers as $sn){
                    if( $sn["submission_number"] == $meta["submission_number"][0]){
                        $exists = true;
                        break;
                    }
                }

                if($exists == false){
                    array_push($submission_numbers, ["submission_number"=> $meta["submission_number"][0]]);
                }



            }
        }
    ?>
    <div class="table-responsive">    
        <table class='table 5star_my_orders table-bordered table-striped'>
            <thead>
                <tr>
                    <th>Date</th>
                    <th>
                        <select name="select_customer_filter " class="select_filter w-100 px-2 py-0" data-filter="user_id">
                            <option value="">Customer</option>
                            <?php 
                                foreach($customers as $cx){
                            ?>
                            <option value="<?php echo $cx["user_id"]; ?>"><?php echo $cx["customer"]; ?></option>
                            <?php 
                                }
                            ?>
                        </select>
                    </th>
                    <th>Order #</th>
                    <th>
                        <select name="select_grading_filter " class="select_filter w-100 px-2 py-0" data-filter="grading_type" >
                            <option value="">Grading Type</option>
                            <?php 
                                foreach($grading_types as $gd){
                            ?>
                            <option value="<?php echo $gd["grading_type"]; ?>"><?php echo $gd["grading_type"]; ?></option>
                            <?php 
                                }
                            ?>
                        </select>
                    </th>
                    <th>
                        <select name="select_status_filter " class="select_filter w-100 px-2 py-0" data-filter="status" style="" >
                            <option value="">Status</option>
                            <?php 
                                foreach($status as $st){
                            ?>
                            <option value="<?php echo $st["status"]; ?>"><?php echo $st["status"]; ?></option>
                            <?php 
                                }
                            ?>
                        </select>
                    </th>
                    <th class='text-end'>Total Cards</th>
                    <th class='text-end'>Consigned Cards</th>
                    <th class='text-end'>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                    if( $posts ){

                        foreach($posts as $post)
                        {
                            $meta = get_post_meta($post->ID);

                            $date_format = get_option( 'date_format' );
                            $time_format = get_option( 'time_format' );

                            $user_id = $meta["user_id"][0];
                            $user = get_user_by( "id", $user_id );


                            $args = array(
                                'meta_query' => array(
                                    array(
                                        'key' => 'checkout_id',
                                        'value' => $post->ID,
                                    )
                                ),
                                'post_type' => 'cards-grading-card',
                                'posts_per_page' => -1
                            );
                            
                            $cards_list = get_posts($args);
                            $total_grading_charge = 0;
                            $consigned_cards = 0;

                            foreach($cards_list as $card_in_order){
                                $card_meta = get_post_meta($card_in_order->ID);
                                $card = json_decode($card_meta['card'][0], true);

                                if( $card_meta["status"][0] == "To Pay - Grade Only"){
                                    $total_grading_charge  = $total_grading_charge  + $card["per_card"];
                                }
                                if( in_array($card_meta["status"][0], array("Consigned", "Sold - Consigned") )){
                                    $consigned_cards  = $consigned_cards  + 1;
                                }
                            }
                            
                                                        
                ?>
                            <tr class="" data-post_id="<?php echo $post->ID; ?>">
                                <td><?php echo get_the_date( $date_format, $post->ID ) ?><br><span style='font-size:.7em !important;'><?php echo get_the_time( $time_format, $post->ID ); ?></span></td>
                                <td><?php echo $user->display_name; ?></td>
                                <td><?php echo $meta["order_number"][0]; ?></td>
                                <td><?php echo $meta["grading_type"][0]; ?><br><span style='font-size:.7em !important;'><?php echo  $meta["service_type"][0]; ?></span></td>
                                <td><?php echo $meta["status"][0]; ?></td>
                                <td class='text-end'><?php echo $meta["total_cards"][0]; ?></td>
                                <td class='text-end'><?php echo $consigned_cards; ?></td>
                                <td class="text-end">
                                    <button class="5star_btn btn btn-primary mb-3 admin-consigned-row" data-action="admin_table_action" data-post_id="<?php echo $post->ID; ?>">
                                        ...
                                    </button>           
                                </td>
                            </tr>
                <?php          
                        }
                    } else {
                ?>
                <tr>
                    <td class="text-center" colspan="8">Empty</td>
                </tr>
                <?php          
                    }
                ?>
            </tbody>
        </table>
    </div>
</div>