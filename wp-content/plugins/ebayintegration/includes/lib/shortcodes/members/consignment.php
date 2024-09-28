<style>

    .shortcode_tab_box {
        box-shadow: 0px 2px 18px 0px rgba(0,0,0,0.3);
    }

    .shortcode_tab_box H1 {
        color: black;
    }
    
    .shortcode_tab_box ul {
        list-style-type: none;
        padding: 0 !important;
        line-height: inherit !important;        
        background-color: #f4f4f4;
        height: auto !important;
        word-wrap: break-word;
        box-sizing: border-box;
        margin-top: 0;
        margin-bottom: 1rem;
    }

    .shortcode_tab_box li {
        float: left;
        border-right: none;
        border-bottom: none;
        display: table;
        height: 72px;
        position: relative;
        line-height: 4em;
        font-weight: bold;
    }
    .shortcode_tab_box li a {
        text-decoration: none;
        padding: 4px 30px;
        vertical-align: middle;
        display: table-cell;
        color: #666;
    }

    .shortcode_tab_box li.active {
        background-color: #fff;
        font-weight: bold;
        border-bottom: none;

    }

</style>
<?php 
$hot = $this->wpdb->get_results ( "
SELECT * 
FROM (
    SELECT * 
    FROM view_auction
    WHERE ListingType='Chinese'
    ORDER BY BidCount DESC
    LIMIT 20
) AS top_20
ORDER BY RAND()
LIMIT 6;    
" 
);
?>

<?php 

    function Activate($page){
        if( isset( $_GET['mode']) == false ){
            if( $page == "new" ){
                return "active";
            }
        } 
        else {
            if( $page == $_GET['mode'] ){
                return "active";
            } else {
                return "";
            }
        }
    }


?>

<div class="shortcode_tab_box">
    <ul class="clearfix d-none d-lg-block">
        <li class="<?php echo Activate("new"); ?>">
            <a class="" href="/my-account/consignment">New</a>
        </li>
        <li class="<?php echo Activate("to-ship"); ?>">
            <a class="" href="/my-account/consignment/?mode=to-ship">To Ship</a>
        </li>
        <li class="<?php echo Activate("consigned"); ?>">
            <a class="" href="/my-account/consignment/?mode=consigned">Consigned</a>
        </li>
        <li class="<?php echo Activate("sold"); ?>">
            <a class="" href="/my-account/consignment/?mode=sold">Sold</a>
        </li>
    </ul>
    <div class="d-lg-none p-3">
        <label>Select Consignment Status</label>
        <select class="form-control" id="mobile_tab_select">
            <option value="/my-account/consignment" <?php echo ActivateSelect("new") ?>>New</option>
            <option value="/my-account/consignment?mode=to-ship" <?php echo ActivateSelect("to-ship") ?>>To Ship</option>
            <option value="/my-account/consignment?mode=consigned" <?php echo ActivateSelect("consigned") ?>>Consigned</option>
            <option value="/my-account/consignment?mode=sold" <?php echo ActivateSelect("sold") ?>>Sold</option>
        </select>
    </div>

    <div class="content p-3">
        <?php 
        if(isset( $_GET['mode']) ){
            include( plugin_dir_path( __FILE__ ) . "consignment." . $_GET['mode'] . '.php' );			

        } else {

            include( plugin_dir_path( __FILE__ ) . 'consignment.new.php' );			

        }
        ?>
    </div>

    <div class="row">
    <div class="col">
        <div class="row">
            <div class="col">
                <H1>Hot Auctions</H1>
            </div>
        </div>
        <div class="row">
            <?php 
            foreach($hot as $item){ 
            ?>
            <div class="col-md-4 mb-3 text-center">
                <img style="min-height: 80px;" src="<?php echo $item->GalleryURL ?>">
                <div><?php echo $item->CurrentPrice; ?></div>
                <div style="font-size: 10px;">
                    <a href="">
                    <?php echo $item->Title; ?>
                    </a>
                </div>
            </div>
            <?php 
            }
            ?>
        </div>
    </div>
    <div class="col">
        &nbsp;
    </div>
</div>


</div>



