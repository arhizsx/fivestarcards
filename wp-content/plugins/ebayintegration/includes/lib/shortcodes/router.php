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

        default:

            echo "Shortcode Not Found";

    }

?>