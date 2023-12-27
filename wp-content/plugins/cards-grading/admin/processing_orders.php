<?php

$user_id = get_current_user_id();


    $meta_query = array(
        "relaton" => 'AND',
    );

    array_push(
        $meta_query,             
        array(
            'key' => 'status',
            'value' => array("Processing Order", "Shipped to PSA / SGC", "Research", "Grading", "Assembly", "QA1", "QA2", "Cards Graded", "Grading Complete", "Completed - Grades Ready"),
            'compare' => 'IN'
        )
    );


    if(isset( $_GET['filtered']) && $_GET["filtered"] == "true"){

        if(isset( $_GET["submission_number"]) ){        
            $filter_array = array(
                "key" => 'submission_number',
                'value' => $_GET["submission_number"],
            );

            array_push(
                $meta_query,             
                $filter_array,
            );

        }
    
        if(isset( $_GET["user_id"]) ){
            
            $filter_array = array(
                "key" => 'user_id',
                'value' => $_GET["user_id"],
            );

        }

        array_push(
            $meta_query,             
            $filter_array,
        );

    }
    
    $args = array(
        'meta_query' => array(
            $meta_query
        ),
        'post_type' => 'cards-grading-chk',
        'posts_per_page' => -1
    );
    



$posts = get_posts($args);

?>

<div class="m-0 p-0">
    <div class="row">
        <div class="col-xl-6">
            <H1 style="color: black;">Open Orders</H1>
            <?php if( isset( $_GET["filtered"] ) == "true"  && isset($_GET["user_id"])){ ?>
                Filtered By: 
                <button class="5star_btn btn btn-danger mb-3 py-0 px-2 mt-2 btn-sm" data-action="remove_filter">
                    Customer
                </button>           
            <?php } ?>
            <?php if( isset( $_GET["filtered"] ) == "true"  && isset($_GET["submission_number"])){ ?>
                <div class="row">
                    <div class="col">
                        Filtered By: 
                        <button class="5star_btn btn btn-danger mb-3 py-0 px-2 mt-2 mr-5  btn-sm" data-action="remove_filter">
                            Submission #
                        </button>
                    </div>
                    <div class="col">
                        Bulk Action:
                        <button class='5star_btn btn btn-success mb-3 btn-sm py-0 mt-2 px-2 ml-3' data-action="multi_update_status" data-order_number="<?php echo $params['order_number'] ?>">
                            Update Status
                        </button>      
                    </div>
                </div>
            <?php } ?>
        </div>
        <div class="col-xl-6 text-end">
        </div>
    </div>
    <div class="table-responsive">    
        <table class='table 5star_my_orders table-bordered table-striped'>
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Customer</th>
                    <th>Order #</th>
                    <th>Service Type</th>
                    <th>Submission #</th>
                    <th>Status</th>
                    <th class='text-end'>Total Cards</th>
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
                                                        
                ?>
                <tr class="" data-post_id="<?php echo $post->ID; ?>">
                    <td><?php echo get_the_date( $date_format, $post->ID ) ?><br><span style='font-size:.7em !important;'><?php echo get_the_time( $time_format, $post->ID ); ?></span></td>
                    <td><a class="filter-links" href='/admin/?filtered=true&show=open&user_id=<?php echo $user_id; ?>'> <?php echo $user->display_name; ?></a></td>
                    <td><?php echo $meta["order_number"][0]; ?></td>
                    <td><?php echo $meta["grading_type"][0]; ?><br><span style='font-size:.7em !important;'><?php echo  $meta["service_type"][0]; ?></span></td>
                    <td><a class="filter-links" href='/admin/?filtered=true&show=open&submission_number=<?php echo $meta["submission_number"][0]; ?>'><?php echo $meta["submission_number"][0]; ?></td>
                    <td><?php echo $meta["status"][0]; ?></td>
                    <td class='text-end'><?php echo $meta["total_cards"][0]; ?></td>
                    <td class="text-end">
                        <button class="5star_btn btn btn-primary mb-3 admin-order-row" data-action="admin_table_action"  data-post_id="<?php echo $post->ID; ?>">
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