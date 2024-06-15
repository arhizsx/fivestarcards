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

			include( plugin_dir_path( __FILE__ ) . 'members/consignment.php');			
            break;

        case "cashout":

			include( plugin_dir_path( __FILE__ ) . 'members/cashout.php');			
            break;

        case "grading":

			include( plugin_dir_path( __FILE__ ) . 'members/grading.php');			
            break;

        case "listing":

			include( plugin_dir_path( __FILE__ ) . 'listing.php');			
            break;


        case "consignment_v2":

            include( plugin_dir_path( __FILE__ ) . 'members/consignment_v2.php');			
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

        default:

            echo "Shortcode Not Found";

    }

?>