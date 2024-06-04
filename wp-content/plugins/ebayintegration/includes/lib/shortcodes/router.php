<?php  

    switch($atts["type"]){

        case "get_item_buttons":

			include( plugin_dir_path( __FILE__ ) . 'getitem.php');			
            break;

        case "members_list":

			include( plugin_dir_path( __FILE__ ) . 'members.php');			
            break;

        case "member_items":

			include( plugin_dir_path( __FILE__ ) . 'member_items.php');			
            break;

        case "ebay_items":

			include( plugin_dir_path( __FILE__ ) . 'ebay_items.php');			
            break;

        case "pending_payout_items":

			include( plugin_dir_path( __FILE__ ) . 'pending_payout_items.php');			
            break;

        case "awaiting_payment_items":

			include( plugin_dir_path( __FILE__ ) . 'awaiting_payment_items.php');			
            break;

        case "paid_out":

			include( plugin_dir_path( __FILE__ ) . 'paid_out.php');			
            break;

        case "auction_items":

			include( plugin_dir_path( __FILE__ ) . 'auction_items.php');			
            break;

        case "fixed_price_items":

			include( plugin_dir_path( __FILE__ ) . 'fixed_price_items.php');			
            break;

        case "pending_payout_items_admin":

			include( plugin_dir_path( __FILE__ ) . 'pending_payout_items_admin.php');			
            break;

        case "awaiting_payment_items_admin":

			include( plugin_dir_path( __FILE__ ) . 'awaiting_payment_items_admin.php');			
            break;

        case "paid_out_admin":

			include( plugin_dir_path( __FILE__ ) . 'paid_out_admin.php');			
            break;

        case "auction_items_admin":

			include( plugin_dir_path( __FILE__ ) . 'auction_items_admin.php');			
            break;

        case "fixed_price_items_admin":

			include( plugin_dir_path( __FILE__ ) . 'fixed_price_items_admin.php');			
            break;

        case "consignment":

			include( plugin_dir_path( __FILE__ ) . 'consignment.php');			
            break;

        case "hello":

			include( plugin_dir_path( __FILE__ ) . 'hello.php');			
            break;

        default:

            echo "Shortcode Not Found";

    }

?>