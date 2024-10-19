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

        case "popular":

			include( plugin_dir_path( __FILE__ ) . 'popular.php');			
            break;

        case "store-auction":

			include( plugin_dir_path( __FILE__ ) . 'store-auction.php');			
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

        case "payout":

			include( plugin_dir_path( __FILE__ ) . 'payout.php');			
            break;

        case "grading":

			include( plugin_dir_path( __FILE__ ) . 'grading.php');			
            break;

        case "grading2":

			include( plugin_dir_path( __FILE__ ) . 'grading2.php');			
            break;

        case "grading-new":

			include( plugin_dir_path( __FILE__ ) . 'grading.new.php');			
            break;

        case "listing":

			include( plugin_dir_path( __FILE__ ) . 'listing.php');			
            break;


        case "consignment_v2":
            
			include( plugin_dir_path( __FILE__ ) . 'consignment_v2.php');			
            break;

        case "consignment-new":
            
			include( plugin_dir_path( __FILE__ ) . 'consignment.new.php');			
            break;

        case "hello":

			include( plugin_dir_path( __FILE__ ) . 'hello.php');			
            break;

        case "hello_admin":

			include( plugin_dir_path( __FILE__ ) . 'hello-admin.php');			
            break;

        case "administrator":

			include( plugin_dir_path( __FILE__ ) . 'administrator.php');			
            break;

        case "administrator_grading":

			include( plugin_dir_path( __FILE__ ) . 'administrator.grading.php');			
            break;

        case "administrator_consignment":

			include( plugin_dir_path( __FILE__ ) . 'administrator.consignment.php');			
            break;

        case "administrator_ebay":

			include( plugin_dir_path( __FILE__ ) . 'administrator.ebay.php');			
            break;

        case "administrator_members":

			include( plugin_dir_path( __FILE__ ) . 'administrator.members.php');			
            break;

        case "administrator_payouts":

			include( plugin_dir_path( __FILE__ ) . 'administrator.payouts.php');			
            break;

        case "refresh":

			include( plugin_dir_path( __FILE__ ) . 'refresh.php');			
            break;

        default:

            echo "Shortcode Not Found";

    }

?>