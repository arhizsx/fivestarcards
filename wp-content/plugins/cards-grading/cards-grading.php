<?php 

/** 
* Plugin Name:       Cards Grading
 * Plugin URI:        https://www.techteam.ph/wordpress/plugins/cards-grading
 * Description:       Card Grading Plugin.
 * Version:           1.0.0
 * Requires at least: 5.2
 * Requires PHP:      7.2
 * Author:            Aris Salvador
 * Author URI:        https://www.techteam.ph/about/aris
 * License:           GPL v2 or later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Update URI:        https://example.com/my-plugin/
 * Text Domain:       cards-grading
 * Domain Path:       /languages
 */

 if( !defined('ABSPATH') )
 {
    echo 'You are not allowed';
    exit;
 }

 class CardsGrading {

    public function __construct() 
    {
        // Create Custom Post Type
        add_action('init', array($this, 'create_custom_post_type') );        

        // Add Assets
        add_action('wp_enqueue_scripts', array( $this, 'load_assets') );

        // Add Shortcodes
        add_shortcode('cards-grading', array( $this, 'cards_grading_shortcodes' ));

        // Add JS
        add_action('wp_footer', array( $this, 'load_scripts' ));

        // Add Endpoint
        add_action("rest_api_init", array($this, 'register_endpoint'));
    }

    public function create_custom_post_type()
    {
        $args = array(
            'public' => true,
            'has_archive' => false,
            'supports' => array('title'),
            'exclude_from_search' => true,
            'publicly_queryable' => false,
            'capability' => 'manage_options',
            'labels' => array(
                'name' => 'Cards Grading',
                'singular_name' => 'Card Grading'
            ),
            'menu_icon' => 'dashicons-media-text'
        );

        register_post_type("cards-grading", $args);

    }

    public function load_assets() {

        wp_enqueue_style(
            'cards-grading',
            plugin_dir_url(__FILE__) . 'css/cards-grading.css',
            array(),
            1,
            'all'
        );

        wp_enqueue_script(
            'cards-grading',
            plugin_dir_url(__FILE__) . 'js/cards-grading.js',
            array('jquery'),
            2,
            true
        );

    }

    public function subscribe_link_att($atts) {
        $default = array(
            'link' => '#',
        );
        $a = shortcode_atts($default, $atts);
        return 'Follow us on '.$a['link'];
    }    

    public function cards_grading_shortcodes($atts) 
    {

        $default = array(
            'title' => 'Grading Title',
            'type' => 'grading-tyoe'
        );
        
        $params = shortcode_atts($default, $atts);

        include( plugin_dir_path( __FILE__ ) . 'admin/modal.php' );

    }


    public function load_scripts()
    { ?>
        <script>
            function addCardToTable(card){

                if( checkIfAddIsStillValid( card ) )
                {
                
                    var card_total_charge = parseFloat(card["quantity"]) * parseFloat(card["per_card"]);
                    var card_total_dv = parseFloat(card["quantity"]) * parseFloat(card["dv"]);
                
                    if( $(document).find(".5star_logged_cards tbody tr td:first-child").text() == "Empty"){
                        $(document).find(".5star_logged_cards tbody").empty();
                    }

                    $(document).find(".5star_logged_cards tbody").append(
                        "<tr>" +
                            "<td>" + card["quantity"] + "</td>" +
                            "<td>" + card["year"] + "</td>" +
                            "<td>" + card["brand"] + "</td>" +
                            "<td>" + card["card_number"] + "</td>" +
                            "<td>" + card["player"] + "</td>" +
                            "<td>" + card["attribute"] + "</td>" +
                            "<td><span class='dollar'>" + parseFloat(card["dv"]).toFixed(2) + "</span></td>" +
                            "<td><span class='dollar'>" + card_total_dv.toFixed(2) + "</span></td>" +
                            "<td><span class='dollar'>" + card_total_charge.toFixed(2) + "</span></td>" +
                            "<td>Remove</td>" +
                        "</tr>"
                    );
                
                    clearModalForm();   
                    setTotals(card_total_dv, card_total_charge)  

                    var nonce = "<?php echo wp_create_nonce("cards_grading"); ?>";
                    var url = "<?php echo get_rest_url(null, "cards-grading/v1/add-card") ?>";

                    $.ajax({
                        method: 'post',
                        url: url,
                        headers: {'X-WP-Nonce': nonce },
                        data: card
                    });


                } else {

                    $(document).find("div#add_card_form_box").addClass("d-none");
                    $(document).find("div#maxed-out").removeClass("d-none");
                    $(document).find("div#maxed-out").find(".message").html(
                        "<strong>Maximum allowed DV is only " + ( parseFloat(card["max_dv"]) - 1)  + "</strong>"
                    );

                    console.log( "Max DV Reached" );

                }
                

            }

            function setTotals( total_dv, total_charge ){

                current_dv = parseFloat( $(document).find("#total_dv").text().replace("$",""));
                current_charge = parseFloat($(document).find("#total_charges").text().replace("$",""));

                new_total_dv = total_dv + current_dv;
                new_total_charge = total_charge + current_charge;
                new_grand_total = new_total_dv + new_total_charge;

                $(document).find("#total_dv").text( "$" + new_total_dv.toFixed(2) );
                $(document).find("#total_charges").text( "$" + new_total_charge.toFixed(2) );
                $(document).find("#grand_total").text( "$" + new_grand_total.toFixed(2) );

            }

            function checkIfAddIsStillValid( card ){

                var card_total_dv = parseFloat(card["quantity"]) * parseFloat(card["dv"]);

                var current_total_dv = parseFloat( $(document).find("#total_dv").text().replace("$","") );
                var max_dv = card["max_dv"];

                if( current_total_dv + card_total_dv < max_dv ) {
                    return true;
                } else {
                    return false;
                }

            }

            function clearModalForm(){
                $(document).find(".dxmodal").find('#add_card_form *').filter(':input:visible:enabled').each(function(k, v){
                    $(v).val("");
                });    
            }

            $(document).on("click", ".5star_btn", function(e){

                switch( $(this).data("action") ){		
                    case "confirm_add":

                        var error_cnt = 0;
                        var card = {};

                        $(document).find(".dxmodal").find('#add_card_form *').filter(':input').each(function(k, v){

                            console.log( $(v).val() );

                            if( $(v).val().length > 0 ){

                                card[ $(v).attr("name") ] = $(v).val();

                            } else {
                                $(v).focus();
                                $(v).val('');
                                error_cnt = error_cnt + 1;
                                return false;
                            }

                        });

                        if(error_cnt === 0){
                            addCardToTable( card );
                        }

                        break;

                    default:
                    
                }
            });

        </script>
    <?php }


    public function register_endpoint(){
        
        register_rest_route(
            "cards-grading/v1",
            "add-card",
            array(
                'methods' => 'POST',
                'callback' => array($this, 'handle_add_card')
            )    
                    
        );
    }

    public function handle_add_card(){
        echo 'Test Endpoint Good';
    }

 }

 new CardsGrading;
